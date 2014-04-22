<?php
namespace linklist\system\event\listener;

use wcf\system\event\IEventListener;
use wcf\system\WCF;

class CategoryAddListener implements IEventListener {

	public function execute($eventObj, $className, $eventName) {
		$returnValues = $eventObj->objectAction->getReturnValues();
		$sql = "INSERT INTO linklist" . WCF_N . "_category_stats (categoryID)
                    VALUES(" . $returnValues['returnValues']->categoryID . ")";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute();
	}
}
