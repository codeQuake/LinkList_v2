<?php
namespace linklist\system\moderation\queue\offline;
use linklist\data\link\LinkEditor;
use linklist\data\link\ViewableLink;
use linklist\system\moderation\queue\AbstractLinkModerationQueueHandler;
use wcf\data\moderation\queue\ModerationQueue;
use wcf\data\moderation\queue\ViewableModerationQueue;
use linklist\system\moderation\queue\offline\IModerationQueueOfflineHandler;
use linklist\system\moderation\queue\ModerationQueueOfflineManager;
use wcf\system\WCF;

class LinkModerationQueueOfflineHandler extends AbstractLinkModerationQueueHandler implements IModerationQueueOfflineHandler{
    protected $definitionName = 'de.codequake.linklist.moderation.offline';
    protected $objectType = 'de.codequake.linklist.link';
    
    public function getOfflineContent(ViewableModerationQueue $queue){
        WCF::getTPL()->assign(array(
                'link' => new ViewableLink($queue->getAffectedObject())
            ));
        return WCF::getTPL()->fetch('moderationLink', 'linklist');
    }
}