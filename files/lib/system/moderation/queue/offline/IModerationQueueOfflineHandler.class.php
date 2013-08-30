<?php
namespace linklist\system\moderation\queue\offline;
use wcf\data\moderation\queue\ModerationQueue;
use wcf\data\moderation\queue\ViewableModerationQueue;
use wcf\system\moderation\queue\IModerationQueueHandler;

interface IModerationQueueOfflineHandler extends IModerationQueueHandler{

    public function getOfflineContent(ViewableModerationQueue $queue);
    
    public function setOnline(ModerationQueue $queue);
}