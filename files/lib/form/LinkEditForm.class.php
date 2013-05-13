<?php
namespace linklist\form;
use linklist\data\link\LinkAction;
use linklist\data\link\Link;
use wcf\form\MessageForm;
use wcf\util\StringUtil;
use wcf\util\HeaderUtil;
use wcf\util\FileUtil;
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
    public $url;

	public $showSignatureSetting = false;
	
    public function readParameters(){
        parent::readParameters();
        if($_REQUEST['id']) $this->linkID = intval($_REQUEST['id']);
        $this->link = new Link($this->linkID);
        if(!$this->link->linkID) throw new IllegalLinkException();
    }
	public function readData() {
		parent::readData();
		$this->subject = $this->link->getTitle();
        $this->url = $this->link->url;
        $this->text = $this->link->message;
	}

	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			                        'action'    =>  $this->action,
                                    'url'   =>  $this->url,
                                    'link'  =>  $this->link
		));
	}
	
    public function readFormParameters() {
        parent::readFormParameters();       
        if(isset($_POST['url'])) $this->url = StringUtil::trim($_POST['url']);
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
			)
		));
		$this->objectAction->executeAction();
		$this->link = new Link($this->linkID);
		$this->saved();
		
		// show success message
		WCF::getTPL()->assign('success', true);
	}
}
