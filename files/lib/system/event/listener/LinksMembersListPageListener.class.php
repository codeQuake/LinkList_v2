<?php
namespace linklist\system\event\listener;
use wcf\system\event\IEventListener;


class LinksMembersListPageListener implements IEventListener {

    public function execute($eventObj, $className, $eventName) {
        $eventObj->validSortFields[] = 'linklistLinks';
     }
}
