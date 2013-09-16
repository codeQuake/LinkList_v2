<?php
namespace linklist\acp\form;
use linklist\system\cache\builder\CategoryCacheBuilder;
use linklist\system\cache\builder\LinklistStatsCacheBuilder;
use linklist\data\link\LinkAction;
use linklist\data\link\LinkList;
use linklist\data\link\Link;
use wcf\system\language\LanguageFactory;
use wcf\form\AbstractForm;
use wcf\util\StringUtil;
use wcf\system\exception\UserInputException;
use wcf\system\exception\SystemException;
use wcf\util\XML;
use wcf\system\io\Tar;
use wcf\system\category\CategoryHandler;
use wcf\data\category\CategoryAction;
use wcf\system\WCF;
use wcf\util\HeaderUtil;
use wcf\data\user\User;
use wcf\system\request\LinkHandler;

class LinklistImportForm extends AbstractForm{

    public $fileUpload ='';
    public $data;
    public $fileName;
    public $neededPermissions = array('admin.linklist.data.canImportData');
    
    public function readFormParameters(){
        parent::readFormParameters();
        
        if (isset($_POST['filename'])) $this->filename = StringUtil::trim($_POST['filename']);
        if (isset($_FILES['fileUpload'])) $this->fileUpload = $_FILES['fileUpload'];
    }
    
    public function validate(){
        if (empty($this->fileUpload)) {
            throw new UserInputException('fileUpload');
        }
    }
    
    public function save(){
        parent::save();
        if (empty($this->fileUpload['tmp_name'])) return;
        $data = self::getLinkListData($this->fileUpload['tmp_name']);
        $oldCategoryIDs = array();
        $categoryID = 0;
        $sql = "SELECT categoryID FROM wcf".WCF_N."_category ORDER BY categoryID DESC";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute(array());
        $row = $statement->fetchArray();
        $categoryID = $row['categoryID'];
        foreach ($data['categoryData'] as $categoryData){
            $categoryID++;
            $oldCategoryIDs[$categoryData['categoryID']] = $categoryID;
        }
        foreach ($data['categoryData'] as $categoryData) {
            // insert categories
           
           $objectType = CategoryHandler::getInstance()->getObjectTypeByName('de.codequake.linklist.category');
           //should never ever happen ;)
           if ($objectType === null) {
                throw new SystemException("Unknown category object type with name '".$objectTypeName."'");
            }
            
            $parent = 0;
            if($categoryData['parentID'] != 0) $parent = $oldCategoryIDs[$categoryData['parentID']];
            
            $create = array('data' => array('description' => $categoryData['description'],
                            'isDisabled' => 0,
                            'objectTypeID' => $objectType->objectTypeID,
                            'parentCategoryID' => $parent,
                            'showOrder' => 1,
                            'title' => $categoryData['title']));
           $objectAction = new CategoryAction(array(), 'create', $create);
           $objectAction->executeAction();
           $returnValues = $objectAction->getReturnValues();
           //stats
           $sql = "INSERT INTO linklist".WCF_N."_category_stats (categoryID)
                    VALUES(".$returnValues['returnValues']->categoryID.")";
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute();
            
            //fill old categoryIDs:
            //$oldCategoryIDs[$categoryData['categoryID']] = $returnValues['returnValues']->categoryID;
           
        }
        
        // import links
            foreach ($data['linkData'] as $linkData) {
                // insert links
                $userID = null;
                $user = User::getUserByUsername($linkData['username']);
                if($user->userID) $userID =  $user->userID;
                
                $data = array(  'url'   =>  $linkData['url'],
                        'subject'   =>  $linkData['subject'],
                        'categoryID'    =>  $oldCategoryIDs[$linkData['categoryID']],
                        'message'   =>  $linkData['message'],
                        'userID' => $userID,
                        'username' => $linkData['username'],
                        'time'  =>  $linkData['time'],
                        'languageID'    =>  LanguageFactory::getInstance()->getDefaultLanguageID(),
                        'enableSmilies' =>  $linkData['enableSmilies'],
                        'enableHtml'    =>  $linkData['enableHtml'],
                        'enableBBCodes' =>  $linkData['enableBBCodes'],
                        'visits'    =>  $linkData['visits'],
                        'isActive' => 1,
                        'ipAddress'  =>  $linkData['ipAddress']);
                $create = array('data' => $data);
                $objectAction = new LinkAction(array(), 'import', $create);
                $returnValues = $objectAction->executeAction();
                
                //count visits
                $visits = 0;
                $links = new Linklist();
                $links->sqlJoins = 'WHERE categoryID = '.$returnValues['returnValues']->categoryID;
                $links->readObjects();
                $linklist = $links->getObjects();
                foreach ($linklist as $linkitem){
                    $visits = $visits + $linkitem->visits;
                }
                $sql = "UPDATE linklist".WCF_N."_category_stats 
                        SET  visits = ".$visits." 
                        WHERE categoryID = ".$returnValues['returnValues']->categoryID;
                $statement = WCF::getDB()->prepareStatement($sql);
                $statement->execute();
            }
        
        
        //clear stats
        LinklistStatsCacheBuilder::getInstance()->reset();
        CategoryCacheBuilder::getInstance()->reset();
        $this->saved();
        HeaderUtil::redirect(LinkHandler::getInstance()->getLink('LinklistCategoryList', array(
                                                                'application' => 'linklist'
                                                                )));
        exit;
        
    }
    
    protected static function getLinklistData($fileUpload){
        //opens tar
        $tar = new Tar($fileUpload);
        $data = self::readArchive($tar);
        $tar->close();
        return $data;
    }
    
    protected static function readArchive($tar){
        $xml = 'linkListData.xml';
        if($tar->getIndexByFileName($xml) === false){
            throw new SystemException("Unable to find required file '".$xml."' in the import archive");
        }
        
        //open XML
        $xmlData = new XML();
        $xmlData->loadXML($xml, $tar->extractToString($tar->getIndexByFileName($xml)));
        $xpath = $xmlData->xpath();
        $root = $xpath->query('/ns:data')->item(0);
        $items = $xpath->query('child::*', $root);
        $data = array();
        $i = 0;
        foreach($items as $item){
            switch($item->tagName){
                case 'linkListCategory':
                    foreach($xpath->query('child::*', $item) as $child){
                        $data['categoryData'][$i][$child->tagName] = $child->nodeValue;
                    }
                break;
                case 'linkListLink':
                    foreach($xpath->query('child::*', $item) as $child){
                        $data['linkData'][$i][$child->tagName] = $child->nodeValue;
                    }
                break;
            }
             $i++;
        }
        return $data;
    }
}