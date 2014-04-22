<?php

namespace linklist\form;

use wcf\form\AbstractModerationForm;
use linklist\system\moderation\queue\ModerationQueueOfflineManager;
use wcf\system\WCF;

class ModerationOfflineForm extends AbstractModerationForm {
	public function assignVariables() {
		parent::assignVariables ();
		
		WCF::getTPL ()->assign ( array (
				'offlineContent' => ModerationQueueOfflineManager::getInstance ()->getOfflineContent ( $this->queue ) 
		) );
	}
}