<?php
namespace linklist\data\link;

class MostVisitedLinkList extends ViewableLinkList{
    
    public $sqlLimit = LINKLIST_MOST_LIMIT;
    public $sqlOrderBy = 'link.visits DESC';
    
    public function __construct(){
        parent::__construct();
    }
}