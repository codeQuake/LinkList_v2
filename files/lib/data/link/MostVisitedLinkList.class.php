<?php

namespace linklist\data\link;

class MostVisitedLinkList extends ViewableLinkList {
	public $sqlLimit = LINKLIST_MOST_LIMIT;
	public $sqlOrderBy = 'link.visits DESC';
	public function __construct($categoryID = 0) {
		parent::__construct ();
		
		if ($categoryID != 0) {
			$this->getConditionBuilder ()->add ( 'categoryID IN (?)', array (
					$categoryID 
			) );
		}
	}
}