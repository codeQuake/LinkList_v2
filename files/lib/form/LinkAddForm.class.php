<?php
namespace linklist\form;

use linklist\data\category\LinklistCategoryNodeTree;
use linklist\data\category\LinklistCategory;
use linklist\data\link\LinkAction;
use linklist\data\link\LinkList;
use linklist\system\cache\builder\CategoryCacheBuilder;
use wcf\system\WCF;
use wcf\system\category\CategoryHandler;
use wcf\form\MessageForm;
use wcf\util\StringUtil;
use wcf\util\HeaderUtil;
use wcf\util\UserUtil;
use wcf\util\FileUtil;
use wcf\util\ArrayUtil;
use wcf\system\request\LinkHandler;
use wcf\system\language\LanguageFactory;
use wcf\system\exception\IllegalLinkException;
use wcf´\system\exception\UserInputException;
use wcf\system\breadcrumb\Breadcrumb;

/**
 * @author  Jens Krumsieck
 * @copyright   2013 codeQuake
 * @license GNU Lesser General Public License
 * @pakage  de.codequake.linklist
 */
 
class LinkAddForm extends MessageForm{

    public $action = 'add';
    public $templateName = 'linkAdd';
    public $username ='';
    public $categoryID = 0;
    public $category = null;
    public $categoryNodeList = null;
    public $url;
    public $tags = array();
    public $enableMultilingualism = true;
    
    protected $link = null;
    
    public $objectTypeName = 'de.codequake.linklist.category';
    
    public function readParameters(){
        parent::readParameters();
        if(isset($_GET['id']))        {
            $this->categoryID = intval($_GET['id']);
            $category = CategoryHandler::getInstance()->getCategory($this->categoryID);

            if($category !== null) $this->category = new LinklistCategory($category);

            if ($this->category === null || !$this->category->categoryID) {
                throw new IllegalLinkException();
            }
            $this->category->checkPermission(array('canViewCategory', 'canEnterCategory', 'canAddLink'));
        }
    }
    
    public function readData(){
        parent::readData();
        WCF::getBreadcrumbs()->add(new Breadcrumb(
            WCF::getLanguage()->get('linklist.index.title'), 
            LinkHandler::getInstance()->getLink('Index', array(
                'application' => 'linklist'
           ))
        ));
        // read categories
        $categoryTree = new LinklistCategoryNodeTree($this->objectTypeName);
        $this->categoryNodeList = $categoryTree->getIterator();
        
       // default values
        if (!count($_POST)) {
            $this->username = WCF::getSession()->getVar('username');

            // multilingualism
            if (!empty($this->availableContentLanguages)) {
                if (!$this->languageID) {
                    $language = LanguageFactory::getInstance()->getUserLanguage();
                    $this->languageID = $language->languageID;
                }

                if (!isset($this->availableContentLanguages[$this->languageID])) {
                    $languageIDs = array_keys($this->availableContentLanguages);
                    $this->languageID = array_shift($languageIDs);
                }
             }
        }
    }
    
    public function readFormParameters() {
        parent::readFormParameters();

        if(isset($_POST['username'])) $this->username = StringUtil::trim($_POST['username']);
        if(isset($_POST['category'])) $this->categoryID = intval($_POST['category']);        
        if(isset($_POST['url'])) $this->url = StringUtil::trim($_POST['url']);
        if (isset($_POST['tags']) && is_array($_POST['tags'])) $this->tags = ArrayUtil::trim($_POST['tags']);
      }
      
    
    public function assignVariables(){
        parent::assignVariables();
        WCF::getTPL()->assign(array('categoryNodeList'  =>  $this->categoryNodeList,
                                    'categoryID'    =>  $this->categoryID,
                                    'username'  =>  $this->username,
                                    'action'    =>  $this->action,
                                    'tags'      => $this->tags,
                                    'url'   =>  $this->url));
        

    }
    
    public function validate(){
        parent::validate();
        
        //user
        if (WCF::getUser()->userID == 0) {
            if (empty($this->username)) {
                throw new UserInputException('username');
            }
            if (!UserUtil::isValidUsername($this->username)) {
                throw new UserInputException('username', 'notValid');
            }
            if (!UserUtil::isAvailableUsername($this->username)) {
                throw new UserInputException('username', 'notAvailable');
            }

            WCF::getSession()->register('username', $this->username);
        }
        
        //url
        /**if (!FileUtil::isURL($this->url)) {
                throw new UserInputException('url', 'illegalURL');
        }**/
    }
    public function save(){
        parent::save();
         if($this->languageID === null) {
            $this->languageID = LanguageFactory::getInstance()->getDefaultLanguageID();
        }
        
        $data = array(  'url'   =>  $this->url,
                        'subject'   =>  $this->subject,
                        'categoryID'    =>  $this->categoryID,
                        'message'   =>  $this->text,
                        'userID' => (WCF::getUser()->userID ?: null),
                        'username' => (WCF::getUser()->userID ? WCF::getUser()->username : $this->username),
                        'time'  =>  TIME_NOW,
                        'languageID'    =>  $this->languageID,
                        'enableSmilies' =>  $this->enableSmilies,
                        'enableHtml'    =>  $this->enableHtml,
                        'enableBBCodes' =>  $this->enableBBCodes,
                        'visits'    =>  0,
                        'ipAddress'  =>  $_SERVER['REMOTE_ADDR']);
        $linkData = array('data' => $data,
                          'tags' => array());
        if (MODULE_TAGGING) {
			$linkData['tags'] = $this->tags;
		}
        $this->objectAction = new LinkAction(array(), 'create', $linkData);
        $resultvalues = $this->objectAction->executeAction();
        
        $this->link = $resultvalues['returnValues'];
            
            
            
        $this->saved();
        
        HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Link', array(
                                                                'application' => 'linklist',
                                                                'object' => $this->link
                                                                )));
        exit;
    }
    
    
}