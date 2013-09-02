<?php
namespace linklist\data\link;
use wcf\system\clipboard\ClipboardHandler;

class DeletedLinkList extends ViewableLinkList{

    public $sqlOrderBy = 'link.deleteTime DESC';
    
    public function __construct(){
        parent::__construct();
        $this->getConditionBuilder()->add('isDeleted  = ?', array(1));
    }
    
    public function getMarkedItems() {
        return ClipboardHandler::getInstance()->hasMarkedItems(ClipboardHandler::getInstance()->getObjectTypeID('de.codequake.linklist.link'));
    }
}