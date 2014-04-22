<?php
namespace linklist\data\link;

use linklist\data\category\LinklistCategory;
use linklist\system\label\object\LinkLabelObjectHandler;

class ViewableLinkList extends LinkList {
	public $decoratorClassName = 'linklist\data\link\ViewableLink';

	public function __construct() {
		parent::__construct();
		$categoryIDs = LinklistCategory::getAccessibleCategoryIDs();
		if (empty($categoryIDs)) return;
		$this->getConditionBuilder()->add('categoryID IN (?)', array(
			$categoryIDs
		));
	}

	public function readObjects() {
		if ($this->objectIDs === null) $this->readObjectIDs();
		parent::readObjects();

		// get assigned labels
		$linkIDs = array();
		foreach ($this->objects as $link) {
			if ($link->hasLabels) {
				$linkIDs[] = $link->linkID;
			}
		}

		if (! empty($linkIDs)) {
			$assignedLabels = LinkLabelObjectHandler::getInstance()->getAssignedLabels($linkIDs);
			foreach ($assignedLabels as $linkID => $labels) {
				foreach ($labels as $label) {
					$this->objects[$linkID]->addLabel($label);
				}
			}
		}
	}
}
