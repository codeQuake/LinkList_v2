<?php

namespace linklist\system\label\object\type;

use linklist\data\category\LinklistCategoryNodeTree;
use wcf\system\label\object\type\AbstractLabelObjectTypeHandler;
use wcf\system\label\object\type\LabelObjectType;
use wcf\system\label\object\type\LabelObjectTypeContainer;

class CategoryLabelObjectTypeHandler extends AbstractLabelObjectTypeHandler {
	public $categoryList = null;
	public $objectTypeID = 0;
	public $objectTypeName = 'de.codequake.linklist.category';
	protected function init() {
		// get category list
		$categoryTree = new LinklistCategoryNodeTree ( $this->objectTypeName );
		$this->categoryList = $categoryTree->getIterator ();
	}
	public function setObjectTypeID($objectTypeID) {
		parent::setObjectTypeID ( $objectTypeID );
		
		// build label object type container
		$this->container = new LabelObjectTypeContainer ( $this->objectTypeID );
		
		foreach ( $this->categoryList as $category ) {
			$objectType = new LabelObjectType ( $category->getTitle (), $category->categoryID, $this->categoryList->getDepth () );
			$this->container->add ( $objectType );
		}
	}
	public function save() {
	}
}