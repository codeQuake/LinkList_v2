<?php
namespace linklist\system\menu\link\content;
use linklist\data\link\Link;
use wcf\system\comment\CommentHandler;
use wcf\system\event\EventHandler;
use wcf\system\menu\link\content\ILinkMenuContent;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

class CommentLinkMenuContent extends SingletonFactory implements ILinkMenuContent{
    public $linkID = 0;
    public $link = null;
    public $commentManager = null;
    public $commentList = null;
    public $objectType = 0;
    
    protected function init() {
        EventHandler::getInstance()->fireAction($this, 'shouldInit');

        EventHandler::getInstance()->fireAction($this, 'didInit');
    }
    
    public function readData(){
        $this->objectTypeID = CommentHandler::getInstance()->getObjectTypeID('de.codequake.linklist.linkComment');
        $objectType = CommentHandler::getInstance()->getObjectType($this->objectTypeID);
        $this->commentManager = $objectType->getProcessor();

        $this->commentList = CommentHandler::getInstance()->getCommentList($this->commentManager, $this->objectTypeID, $this->linkID);
    }
    
    public function getContent($linkID){
        $this->linkID = $linkID;
        $this->link = new Link($this->linkID);
        $this->readData();
        WCF::getTPL()->assign(array('link' =>   $this->link,
                                    'commentList' => $this->commentList,
                                    'commentObjectTypeID'=> $this->objectTypeID,
                                    'commentCanAdd' => $this->commentManager->canAdd($this->linkID),
                                    'lastCommentTime' => $this->commentList->getMinCommentTime(),
                                    'commentsPerPage' => $this->commentManager->getCommentsPerPage()));
       return WCF::getTPL()->fetch('linkCommentList', 'linklist');
    }
}