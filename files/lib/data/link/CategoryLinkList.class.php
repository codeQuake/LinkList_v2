<?php
namespace linklist\data\link;
use linklist\data\category\LinklistCategory;

class CategoryLinkList extends ViewableLinkList{
    public $category = null;
    public $languageID = 0;
    public $categoryIDs = array();
    
    public function __construct(LinklistCategory $category, $categoryIDs = '', $languageID = 0){
        $this->category = $category;
        $this->languageID = $languageID;
        $this->categoryIDs = $categoryIDs;
        
        parent::__construct();
        
        $this->getConditionBuilder()->add('link.categoryID IN (?)', array($this->categoryIDs));
        
    }
}