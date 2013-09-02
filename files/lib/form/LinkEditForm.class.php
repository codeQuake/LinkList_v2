<?php
namespace linklist\form;
use linklist\data\link\LinkAction;
use linklist\data\link\Link;
use wcf\system\tagging\TagEngine;
use wcf\form\MessageForm;
use wcf\util\StringUtil;
use wcf\util\HeaderUtil;
use wcf\util\FileUtil;
use wcf\util\ArrayUtil;
use wcf\system\request\LinkHandler;
use wcf\system\image\ImageHandler;
use wcf\system\language\LanguageFactory;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\UserInputException;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\WCF;

class LinkEditForm extends MessageForm {
	public $enableTracking = true;

	public $templateName = 'linkAdd';
    public $action = 'edit';
    public $linkID;
    public $link;
    public $image = null;
    public $imageType = 'none';
    public $tags = array();
    public $url;

	public $showSignatureSetting = false;
	
    public function readParameters(){
        parent::readParameters();
        if(isset($_GET['id'])) $this->linkID = intval($_GET['id']);
        $this->link = new Link($this->linkID);
        if($this->link->linkID == 0) throw new IllegalLinkException();
        
        //can edit & own
        if($this->link->userID == WCF::getUser()->userID) {
            $this->link->getCategory()->checkPermission(array('canViewCategory', 'canEnterCategory', 'canEditOwnLink'));
        }
        else {            
            $this->link->getCategory()->checkPermission(array('canViewCategory', 'canEnterCategory', 'canEditLink'));
        }
    }
	public function readData() {
		parent::readData();
		$this->subject = $this->link->getTitle();
        $this->url = $this->link->url;
        $this->text = $this->link->message;
        $this->image = $this->link->image;
        if($this->image != null && $this->image != '') $this->imageType = 'link';
        
        WCF::getBreadcrumbs()->add(new Breadcrumb(WCF::getLanguage()->get('linklist.index.title'), LinkHandler::getInstance()->getLink('CategpryList')));
        foreach($this->link->getCategory()->getParentCategories()    AS $categoryItem) {
                                  WCF::getBreadcrumbs()->add(new Breadcrumb($categoryItem->getTitle(), LinkHandler::getInstance()->getLink('Category', array(
                                      'application' => 'linklist',
                                      'object' => $categoryItem
          ))));
          }
        WCF::getBreadcrumbs()->add(new Breadcrumb($this->link->getCategory()->getTitle(), LinkHandler::getInstance()->getLink('Category', array(
          'application' => 'linklist',
          'object' => $this->link->getCategory()
            ))));
        WCF::getBreadcrumbs()->add(new Breadcrumb($this->link->getTitle(), LinkHandler::getInstance()->getLink('Link', array('application'  => 'linklist',
                                                                                                                             'object'   =>  $this->link))));
                                                                                                                             
          // tagging
            if (MODULE_TAGGING) {
            	$tags = TagEngine::getInstance()->getObjectTags('de.codequake.linklist.link', $this->link->linkID, array($this->link->languageID));
                foreach ($tags as $tag) {
                	$this->tags[] = $tag->name;
                }
            }
    }
    

	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			                        'action'    =>  $this->action,
                                    'url'   =>  $this->url,
                                    'link'  =>  $this->link,
                                    'imageType' => $this->imageType,
                                    'image' => $this->image,
                                    'tags'      => $this->tags
		));
	}
	
    public function readFormParameters() {
        parent::readFormParameters();       
        if(isset($_POST['url'])) $this->url = StringUtil::trim($_POST['url']);
        if (isset($_POST['tags']) && is_array($_POST['tags'])) $this->tags = ArrayUtil::trim($_POST['tags']);
        if(isset($_POST['imageType'])) $this->imageType = StringUtil::trim($_POST['imageType']);
         switch ($this->imageType){
            case 'upload':
            if(isset($_FILES['image'])) $this->image = $_FILES['image'];
            
            break;
            case 'link': 
            if(isset($_POST['image'])) $this->image = StringUtil::trim($_POST['image']);
            break;
        }
      }
      
    public function validate(){
        parent::validate();
        
        //image ->link
       if($this->imageType == 'link'){
        if((strpos($this->image, 'png') === false) && (strpos($this->image, 'gif') === false) && (strpos($this->image, 'jpg') === false)) {
            throw new UserInputException('image', 'noimage');
        }
       }
    }
      
	public function save() {
		parent::save();
		if(WCF::getSession()->getPermission('user.linklist.link.canAddOwnPreview') && LINKLIST_ENABLE_OWN_PREVIEW){
            switch ($this->imageType){
                case 'upload':
                    switch($this->image['type']){
                        case 'image/jpeg':
                            $i = 'jpg';
                            break;
                        case 'image/gif':
                            $i = 'gif';
                            break;
                        case 'image/png':
                            $i = 'png';
                            break;
                        default:
                            throw new UserInputException('image', 'notValid');
                            break; 
                    }
                    $imagePath = LINKLIST_DIR.'images/'.$this->image['name'].md5(time()).'.'.$i;
                
                    //shrink if neccessary
                    $image = $this->shrink($this->image['tmp_name'], 150);
                    move_uploaded_file($this->image['tmp_name'], $imagePath);
                    $this->image = RELATIVE_LINKLIST_DIR.'images/'.$this->image['name'].md5(time()).'.'.$i;

                break;
            }
        }
		$this->objectAction = new LinkAction(array($this->linkID), 'update', array(
			'data' => array(
				'message' => $this->text,
				'url' => $this->url,
				'subject' => $this->subject,
				'lastChangeTime' => TIME_NOW,
				'enableSmilies' => $this->enableSmilies,
                'enableHtml'    =>  $this->enableHtml,
                'image' =>$this->image,
                'enableBBCodes' =>  $this->enableBBCodes
			),
           'tags' => $this->tags,
           'isEdit' => 1
		));
		$this->objectAction->executeAction();
		$this->link = new Link($this->linkID);
		$this->saved();
		
		HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Link', array(
                                                                'application' => 'linklist',
                                                                'object' => $this->link
                                                                )));
        exit;
	}
    
    protected function shrink($filename, $size){
        $imageData = getimagesize($filename);
        if ($imageData[0] > $size || $imageData[1] > $size){
            try {
				$obtainDimensions = true;
				if (MAX_AVATAR_WIDTH / $imageData[0] < 150 / $imageData[1]) {
					if (round($imageData[1] * ($size / $imageData[0])) < 48) $obtainDimensions = false;
				}
				else {
					if (round($imageData[0] * ($size / $imageData[1])) < 48) $obtainDimensions = false;
				}
				
				$adapter = ImageHandler::getInstance()->getAdapter();
				$adapter->loadFile($filename);
				$thumbnail = $adapter->createThumbnail($size, $size, $obtainDimensions);
				$adapter->writeImage($thumbnail, $filename);
			}
			catch (SystemException $e) {
				throw new UserInputException('image', 'tooLarge');
			}
        }
        return $filename;
    }
}
