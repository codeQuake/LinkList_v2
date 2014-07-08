<?php
namespace linklist\system\moderation\queue;

use wcf\data\moderation\queue\ModerationQueue;
use wcf\data\moderation\queue\ViewableModerationQueue;
use wcf\system\exception\SystemException;
use wcf\system\moderation\queue\AbstractModerationQueueManager;
use wcf\system\request\LinkHandler;

class ModerationQueueOfflineManager extends AbstractModerationQueueManager {

	protected $definitionName = 'de.codequake.linklist.moderation.offline';

	public function getOfflineContent(ViewableModerationQueue $queue) {
		return $this->getProcessor(null, $queue->objectTypeID)->getOfflineContent($queue);
	}

	public function getLink($queueID) {
		return LinkHandler::getInstance()->getLink('ModerationOffline', array(
			'id' => $queueID,
			'application' => 'linklist'
		));
	}

	public function addModeratedContent($objectType, $objectID, array $additionalData = array()) {
		if (! $this->isValid($objectType)) throw new SystemException("Object type '" . $objectType . "' is not valid for definition '" . $this->definitionName . "'");
		$this->addEntry($this->getObjectTypeID($objectType), $objectID, $this->getProcessor($objectType)
			->getContainerID($objectID), $additionalData);
	}

	public function removeModeratedContent($objectType, array $objectIDs) {
		if (! $this->isValid($objectType)) throw new SystemException("Object type '" . $objectType . "' is not valid for definition '" . $this->definitionName . "'");
		
		$this->removeEntries($this->getObjectTypeID($objectType), $objectIDs);
	}

	public function setOnline(ModerationQueue $queue) {
		return $this->getProcessor(null, $queue->objectTypeID)->setOnline($queue);
	}
}
