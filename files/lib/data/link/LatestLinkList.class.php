<?php
namespace linklist\data\link;

class LatestLinkList extends ViewableLinkList{
    
    public $sqlLimit = 10;
    public $sqlOrderBy = 'link.time DESC';
    
    public function __construct(){
        parent::__construct();
    }
}