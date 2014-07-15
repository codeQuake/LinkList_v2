<?php
namespace linklist\data\link;

use wcf\data\like\object\AbstractLikeObject;
use wcf\system\request\LinkHandler;

class LikeableLink extends AbstractLikeObject {

	protected static $baseClass = 'linklist\data\link\Link';

	public function getTitle() {
		return $this->subject;
	}

	public function getURL() {
		return LinkHandler::getInstance()->getLink('Link', array(
			'application' => 'linklist',
			'object' => $this->getDecoratedObject()
		));
	}

	public function getUserID() {
		return $this->userID;
	}

	public function getObjectID() {
		return $this->linkID;
	}

	public function updateLikeCounter($cumulativeLikes) {
		// update cumulative likes
		$editor = new LinkEditor($this->getDecoratedObject());
		$editor->update(array(
			'cumulativeLikes' => $cumulativeLikes
		));
	}
}
