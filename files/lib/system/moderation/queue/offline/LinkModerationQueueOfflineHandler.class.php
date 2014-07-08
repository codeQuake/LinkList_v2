<?php
namespace linklist\system\moderation\queue\offline;

use linklist\data\link\LinkEditor;
use linklist\data\link\ViewableLink;
use linklist\system\moderation\queue\offline\IModerationQueueOfflineHandler;
use linklist\system\moderation\queue\AbstractLinkModerationQueueHandler;
use linklist\system\moderation\queue\ModerationQueueOfflineManager;
use wcf\data\moderation\queue\ModerationQueue;
use wcf\data\moderation\queue\ViewableModerationQueue;
use wcf\system\WCF;

class LinkModerationQueueOfflineHandler extends AbstractLinkModerationQueueHandler implements IModerationQueueOfflineHandler {

	protected $definitionName = 'de.codequake.linklist.moderation.offline';

	protected $objectType = 'de.codequake.linklist.link';

	public function getOfflineContent(ViewableModerationQueue $queue) {
		WCF::getTPL()->assign(array(
			'link' => new ViewableLink($queue->getAffectedObject())
		));
		return WCF::getTPL()->fetch('moderationLink', 'linklist');
	}

	public function setOnline(ModerationQueue $queue) {
		if ($this->isValid($queue->objectID)) {
			$editor = new LinkEditor($this->getLink($queue->objectID));
			$editor->update(array(
				'isOnline' => 1
			));
			ModerationQueueOfflineManager::getInstance()->removeModeratedContent('de.codequake.linklist.link', array(
				$queue->objectID
			));
		}
	}
}
