<?php
use wcf\system\dashboard\DashboardHandler;
use wcf\system\WCF;

$package = $this->installation->getPackage();

//default values
DashboardHandler::setDefaultValues('de.codequake.linklist.CategoryListPage', array(
    'com.woltlab.wcf.user.recentActivitySidebar' => 1
));

//install date
$sql = "UPDATE	wcf".WCF_N."_option
    SET	optionValue = ?
    WHERE	optionName = ?";
$statement = WCF::getDB()->prepareStatement($sql);
$statement->execute(array(TIME_NOW, 'linklist_install_date'));