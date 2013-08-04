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
use wcf\system\language\LanguageFactory;
use wcf\system\exception\IllegalLinkException;
use wcf´\system\exception\UserInputException;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\WCF;

class LinkEditForm extends MessageForm {
	public $enableTracking = true;

	public $templateName = 'linkAdd';
    public $action = 'edit';
    public $linkID;
    public $link;
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
        
        WCF::getBreadcrumbs()->add(new Breadcrumb(WCF::getLanguage()->get('linklist.index.title'), LinkHandler::getInstance()->getLink('Index')));
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
                                    'tags'      => $this->tags
		));
	}
	
    public function readFormParameters() {
        parent::readFormParameters();       
        if(isset($_POST['url'])) $this->url = StringUtil::trim($_POST['url']);
        if (isset($_POST['tags']) && is_array($_POST['tags'])) $this->tags = ArrayUtil::trim($_POST['tags']);
      }
	public function save() {
		parent::save();
		
		$this->objectAction = new LinkAction(array($this->linkID), 'update', array(
			'data' => array(
				'message' => $this->text,
				'url' => $this->url,
				'subject' => $this->subject,
				'lastChangeTime' => TIME_NOW,
				'enableSmilies' => $this->enableSmilies,
                'enableHtml'    =>  $this->enableHtml,
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
}
