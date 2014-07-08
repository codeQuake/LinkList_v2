<?php
namespace linklist\system\user\activity\event;

use linklist\data\link\LinkList;
use wcf\system\user\activity\event\IUserActivityEvent;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

class LinkUserActivityEvent extends SingletonFactory implements IUserActivityEvent {

	public function prepare(array $events) {
		$objectIDs = array();
		foreach ($events as $event) {
			$objectIDs[] = $event->objectID;
		}
		$linkList = new LinkList();
		$linkList->getConditionBuilder()->add("link.linkID IN (?)", array(
			$objectIDs
		));
		$linkList->readObjects();
		$links = $linkList->getObjects();
		
		foreach ($events as $event) {
			if (isset($links[$event->objectID])) {
				$link = $links[$event->objectID];
				$text = WCF::getLanguage()->getDynamicVariable('wcf.user.profile.recentActivity.link', array(
					'link' => $link
				));
				$event->setTitle($text);
				$event->setDescription($link->getExcerpt());
				$event->setIsAccessible();
			}
			else {
				$event->setIsOrphaned();
			}
		}
	}
}
