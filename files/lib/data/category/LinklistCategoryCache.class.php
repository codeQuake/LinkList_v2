<?php
namespace linklist\data\category;
use linklist\system\cache\builder\CategoryCacheBuilder;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

class LinklistCategoryCache extends SingletonFactory{
    public $counts = array();
    
    protected function init(){
        $this->counts = CategoryCacheBuilder::getInstance()->getData(array(), 'counts');
        
    }
    
    public function getVisits($categoryID) {
        if (isset($this->counts[$categoryID])) {
        return $this->counts[$categoryID]['visits'];
        }
         return 0;
    }
    public function getLinks($categoryID) {
        if (isset($this->counts[$categoryID])) {
        return $this->counts[$categoryID]['links'];
        }
         return 0;
    }
}