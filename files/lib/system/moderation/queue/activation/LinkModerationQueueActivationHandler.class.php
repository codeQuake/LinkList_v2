<?php
namespace linklist\system\moderation\queue\activation;
use linklist\data\link\LinkEditor;
use linklist\data\link\ViewableLink;
use linklist\system\moderation\queue\AbstractLinkModerationQueueHandler;
use wcf\data\moderation\queue\ModerationQueue;
use wcf\data\moderation\queue\ViewableModerationQueue;
use wcf\system\moderation\queue\activation\IModerationQueueActivationHandler;
use wcf\system\moderation\queue\ModerationQueueActivationManager;
use wcf\system\WCF;


class LinkModerationQueueActivationHandler extends AbstractLinkModerationQueueHandler implements IModerationQueueActivationHandler {

	protected $definitionName = 'com.woltlab.wcf.moderation.activation';

	protected $objectType = 'de.codequake.linklist.link';
	

	public function enableContent(ModerationQueue $queue) {
		    $editor = new LinkEditor($this->getLink($queue->objectID));
            $editor->update(array(
                'isActive' => 1
            ));
            ModerationQueueActivationManager::getInstance()->removeModeratedContent('de.codequake.linklist.link', array($queue->objectID));
	}
	
	public function getDisabledContent(ViewableModerationQueue $queue) {
		WCF::getTPL()->assign(array(
			'link' => new ViewableLink($queue->getAffectedObject())
		));
		
		return WCF::getTPL()->fetch('moderationLink', 'linklist');
	}
}
