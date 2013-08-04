<?php
namespace linklist\data\link;

class LatestLinkList extends ViewableLinkList{
    
    public $sqlLimit = LINKLIST_LATEST_LIMIT;
    public $sqlOrderBy = 'link.time DESC';
    
    public function __construct(){
        parent::__construct();
    }
}