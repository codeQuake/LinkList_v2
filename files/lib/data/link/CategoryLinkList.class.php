<?php
namespace linklist\data\link;

use linklist\data\category\LinklistCategory;

class CategoryLinkList extends ViewableLinkList {
	public $category = null;
	public $languageID = 0;
	public $categoryIDs = array();

	public function __construct(LinklistCategory $category, $categoryIDs = '', $languageID = 0) {
		$this->category = $category;
		$this->languageID = $languageID;
		$this->categoryIDs = $categoryIDs;

		parent::__construct();
		if (! $category->getPermission('canSeeDeactivatedLink')) $this->getConditionBuilder()->add('isActive = 1');
		if (! $category->getPermission('canTrashLink')) $this->getConditionBuilder()->add('isDeleted = 0');

		$this->getConditionBuilder()->add('link.categoryID IN (?)', array(
			$this->categoryIDs
		));
	}
}
