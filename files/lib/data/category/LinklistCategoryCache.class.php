<?php
namespace linklist\data\category;

use linklist\system\cache\builder\CategoryCacheBuilder;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\language\LanguageFactory;
use wcf\system\visitTracker\VisitTracker;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

class LinklistCategoryCache extends SingletonFactory {

	public $unreadLinks = array();
	public $data = array();

	protected function init() {
		$this->data = CategoryCacheBuilder::getInstance()->getData();
	}

	public function getLinks($categoryID) {
		return isset($this->data[$categoryID]['links']) ? $this->data[$categoryID]['links'] : 0;
	}

	public function getVisits($categoryID) {
		return isset($this->data[$categoryID]['visits']) ? $this->data[$categoryID]['visits'] : 0;
	}

	protected function initUnreadLinks() {
		$this->unreadLinks = array();

		if (WCF::getUser()->userID) {
			$conditionBuilder = new PreparedStatementConditionBuilder();
			$conditionBuilder->add("link.lastChangeTime > ?", array(
				VisitTracker::getInstance()->getVisitTime('de.codequake.linklist.link')
			));
			$conditionBuilder->add("link.isDeleted = 0 AND link.isDisabled = 0");
			$conditionBuilder->add("tracked_visit.visitTime IS NULL");

			$sql = "SELECT		COUNT(*) AS count, link_to_category.categoryID
				FROM		linklist" . WCF_N . "_link link
				LEFT JOIN	wcf" . WCF_N . "_tracked_visit tracked_visit
				ON		(tracked_visit.objectTypeID = " . VisitTracker::getInstance()->getObjectTypeID('de.codequake.linklist.link') . " AND tracked_visit.objectID = link.linkID AND tracked_visit.userID = " . WCF::getUser()->userID . ")
				LEFT JOIN	linklist" . WCF_N . "_link_to_category link_to_category
				ON		(link_to_category.linkID = link.linkID)
				" . $conditionBuilder . "
				GROUP BY	link_to_category.categoryID";
			$this->sql = $sql;
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute($conditionBuilder->getParameters());
			while ($row = $statement->fetchArray()) {
				$this->unreadLinks[$row['categoryID']] = $row['count'];
			}
		}
	}

	public function getUnreadLinks($categoryID) {
		$this->initUnreadLinks();
		if (isset($this->unreadLinks[$categoryID])) return $this->unreadLinks[$categoryID];
		return 0;
	}
}
