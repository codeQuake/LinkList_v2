<?php
namespace linklist\data\link;

class DeletedLinkList extends ViewableLinkList{

    public $sqlOrderBy = 'link.deleteTime DESC';
    
    public function __construct(){
        parent::__construct();
        $this->getConditionBuilder()->add('isDeleted  = ?', array(1));
    }
}