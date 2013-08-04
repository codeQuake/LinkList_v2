<?php
namespace linklist\data\link;

class MostVisitedLinkList extends ViewableLinkList{
    
    public $sqlLimit = 10;
    public $sqlOrderBy = 'link.visits DESC';
    
    public function __construct(){
        parent::__construct();
    }
}