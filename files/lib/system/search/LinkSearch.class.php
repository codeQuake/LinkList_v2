<?php
namespace linklist\system\search;

use wcf\sytem\search\AbstractSearchableObjectType;
use linklist\data\SearchResultLinkList;

class LinkSearch extends AbstractSearchableObjectType{
    
    public $messageCache = array();
    
    public function cacheObjects(array $objectIDs, array $additionalData = null){
        $linklist = new SearchResultLinkList();
        $linklist->getConditionBuilder()->add('link.linkID IN (?)', array($objectIDs));
        $linklist->readObjects();
        foreach($linklist->getObjects() as $link){
            $this->messageCache[$link->linkID] = $link
        }
    }
}