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
		return $this->getLink();
	}

	public function getUserID() {
		return $this->userID;
	}

	public function getObjectID() {
		return $this->linkID;
	}

	public function updateLikeCounter($cumulativeLikes) {
		$editor = new LinkEditor($this->getDecoratedObject());
		$editor->update(array(
			'cumulativeLikes' => $cumulativeLikes
		));
	}
}
