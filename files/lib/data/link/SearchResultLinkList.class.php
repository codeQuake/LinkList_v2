<?php
namespace linklist\data\link;

class SearchResultLinkList extends ViewableLinkList{

    public $decoratorClassName = 'linklist\data\link\SearchResultLink';
    
    public function __construct(){
        parent::__construct();
        
        if(!empty($this->sqlSelects)) $this->sqlSelects .= ',';
        $this->sqlSelects .= 'category.categoryID, category.title';
        $this->sqlJoins .= " LEFT JOIN wcf".WCF_N."_category category ON (category.categoryID = link.categoryID)";
    }
}