<?php
namespace linklist\data\link;

use linklist\data\link\LinkList;

class ViewableLinkList extends LinkList{
    public $decoratorClassName = 'linklist\data\link\ViewableLink';
    
    public function __construct(){
        parent::__construct();
        $linkIDs = $this->getLinks();
        $this->getConditionBuilder()->add('linkID IN (?)', array($linkIDs));
    }
    
    protected function getLinks(){
        $linkIDs = array();
        $list = new LinkList();
        $list->readObjects();
        $list = $list->getObjects();
        foreach($list as $item){
            if($item->isVisible()) $linkIDs[] = $item->linkID;
        }
        
        return $linkIDs;
     }
}