<?php
namespace linklist\data\moderation\queue;

use wcf\system\exception\PermissionDeniedException;
use linklist\system\moderation\queue\ModerationQueueOfflineManager;
use wcf\data\moderation\queue\ModerationQueueAction;
use wcf\util\StringUtil;

class ModerationQueueOfflineAction extends ModerationQueueAction {
	public $queue = null;
	protected $allowGuestAccess = array(
		'setOnline',
		'removeContent'
	);

	public function validateSetOnline() {
		$this->queue = $this->getSingleobject();
		if (! $this->queue->canEdit()) {
			throw new PermissionDeniedException();
		}
	}

	public function setOnline() {
		ModerationQueueOfflineManager::getInstance()->setOnline($this->queue->getDecoratedObject());
		$this->queue->markAsDone();
	}

	public function validateRemoveContent() {
		$this->parameters['message'] = (isset($this->parameters['message']) ? StringUtil::trim($this->parameters['message']) : '');
		$this->validateSetOnline();
	}

	public function removeContent() {
		ModerationQueueOfflineManager::getInstance()->removeContent($this->queue->getDecoratedObject(), $this->parameters['message']);
		$this->queue->markAsDone();
	}
}
