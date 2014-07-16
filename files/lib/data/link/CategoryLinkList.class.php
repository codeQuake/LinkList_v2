<?php
namespace linklist\data\link;

use linklist\data\category\LinklistCategory;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\category\CategoryHandler;
use wcf\system\WCF;


class CategoryLinkList extends ViewableLinkList {

public function __construct(array $categoryIDs) {
		parent::__construct();
		if (! empty($categoryIDs)) {
			$this->getConditionBuilder()->add('link_to_category.categoryID IN (?)', array(
				$categoryIDs
			));
			$this->getConditionBuilder()->add('link.linkID = link_to_category.linkID');
		} else
			$this->getConditionBuilder()->add('1=0');
		foreach ($categoryIDs as $categoryID) {
			$category = new LinklistCategory(CategoryHandler::getInstance()->getCategory($categoryID));
			if (!$category->getPermission('canSeeDeactivatedLink')) $this->getConditionBuilder()->add('link.isDisabled = ?', array(
				0
			));
		}
	}

	public function readObjectIDs() {
		$this->objectIDs = array();
		$sql = "SELECT	link_to_category.linkID AS objectID
				FROM	linklist" . WCF_N . "_link_to_category link_to_category,
						linklist" . WCF_N . "_link link
						" . $this->sqlConditionJoins . "
						" . $this->getConditionBuilder() . "
						" . (! empty($this->sqlOrderBy) ? "ORDER BY " . $this->sqlOrderBy : '');
		$statement = WCF::getDB()->prepareStatement($sql, $this->sqlLimit, $this->sqlOffset);
		$statement->execute($this->getConditionBuilder()
			->getParameters());
		while ($row = $statement->fetchArray()) {
			$this->objectIDs[] = $row['objectID'];
		}
	}

	public function countObjects() {
		$sql = "SELECT	COUNT(*) AS count
			FROM	linklist" . WCF_N . "_link_to_category link_to_category,
				linklist" . WCF_N . "_link link
			" . $this->sqlConditionJoins . "
			" . $this->getConditionBuilder();
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute($this->getConditionBuilder()
			->getParameters());
		$row = $statement->fetchArray();
		return $row['count'];
	}
}
