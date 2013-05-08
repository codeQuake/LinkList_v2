<?php
namespace linklist\action;
use wcf\action\AbstractAction;
use linklist\data\link\Link;
use wcf\system\exception\IllegalLinkException;
use wcf\util\HeaderUtil;

class LinkVisitAction extends AbstractAction{
    
    public $link = null;
    public $linkID;

    public function readParameters(){
        parent::readParameters();
        if(isset($_GET['id'])) $this->linkID = intval($_GET['id']);
        $this->link = new Link($this->linkID);
        if($this->link === null) throw new IllegalLinkException();
        }
    public function execute(){
        parent::execute();
        $this->link->updateVisits();
        $this->executed();
        HeaderUtil::redirect($this->link->url);
    }
}