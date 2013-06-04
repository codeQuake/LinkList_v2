<?php
namespace linklist\system\menu\link\content;
use linklist\data\link\Link;
use wcf\system\event\EventHandler;
use wcf\system\menu\link\content\ILinkMenuContent;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

class ShowLinkMenuContent extends SingletonFactory implements ILinkMenuContent{
    public $linkID = 0;
    public $link = null;
    
    protected function init() {
        EventHandler::getInstance()->fireAction($this, 'shouldInit');

        EventHandler::getInstance()->fireAction($this, 'didInit');
    }
    
    public function getContent($linkID){
        $this->linkID= $linkID;
        $this->link = new Link($this->linkID);
        WCF::getTPL()->assign(array('link' =>   $this->link));
                                    
       return WCF::getTPL()->fetch('showLink', 'linklist');
    }
}