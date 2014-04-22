<?php

namespace linklist\system\event\listener;

use linklist\system\cache\builder\LinklistStatsCacheBuilder;
use wcf\system\event\IEventListener;
use wcf\system\WCF;

class StatsSidebarDashboardBoxListener implements IEventListener {
	public function execute($eventObj, $className, $eventName) {
		WCF::getTPL ()->assign ( array (
				'linklistStats' => LinklistStatsCacheBuilder::getInstance ()->getData () 
		) );
	}
}
