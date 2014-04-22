<?php
namespace linklist\system\moderation\queue\report;

use linklist\data\link\ViewableLink;
use linklist\system\moderation\queue\AbstractLinkModerationQueueHandler;
use wcf\data\moderation\queue\ViewableModerationQueue;
use wcf\system\moderation\queue\report\IModerationQueueReportHandler;
use wcf\system\WCF;

class LinkModerationQueueReportHandler extends AbstractLinkModerationQueueHandler implements IModerationQueueReportHandler {
	protected $definitionName = 'com.woltlab.wcf.moderation.report';
	protected $objectType = 'de.codequake.linklist.link';

	public function canReport($objectID) {
		if (! $this->isValid($objectID)) {
			return false;
		}

		if (! $this->getLink($objectID)->isVisible()) {
			return false;
		}

		return true;
	}

	public function getReportedContent(ViewableModerationQueue $queue) {
		WCF::getTPL()->assign(array(
			'link' => new ViewableLink($queue->getAffectedObject())
		));

		return WCF::getTPL()->fetch('moderationLink', 'linklist');
	}

	public function getReportedObject($objectID) {
		if ($this->isValid($objectID)) {
			return $this->getLink($objectID);
		}

		return null;
	}
}
