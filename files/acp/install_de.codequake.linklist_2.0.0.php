<?php
use wcf\system\dashboard\DashboardHandler;
use wcf\system\WCF;

$package = $this->installation->getPackage ();

// default values
DashboardHandler::setDefaultValues ( 'de.codequake.linklist.CategoryListPage', array (
		'de.codequake.linklist.latestLinks' => 1,
		'de.codequake.linklist.tagCloud' => 2,
		'com.woltlab.wcf.user.recentActivitySidebar' => 3,
		'de.codequake.linklist.randomLink' => 1 
) );
DashboardHandler::setDefaultValues ( 'de.codequake.linklist.CategoryPage', array (
		'de.codequake.linklist.mostVisitedLinks' => 2 
) );

// install date
$sql = "UPDATE	wcf" . WCF_N . "_option
    SET	optionValue = ?
    WHERE	optionName = ?";
$statement = WCF::getDB ()->prepareStatement ( $sql );
$statement->execute ( array (
		TIME_NOW,
		'linklist_install_date' 
) );