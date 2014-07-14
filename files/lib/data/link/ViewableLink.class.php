<?php
namespace linklist\data\link;

use wcf\data\user\User;
use wcf\data\user\UserProfile;
use wcf\data\DatabaseObjectDecorator;
use wcf\system\visitTracker\VisitTracker;
use wcf\system\WCF;

class ViewableLink extends DatabaseObjectDecorator {

	protected static $baseClass = 'linklist\data\link\Link';
	protected $effectiveVisitTime = null;

	public $userProfile = null;

	public function getVisitTime() {
		if ($this->effectiveVisitTime === null) {
			if (WCF::getUser()->userID) {
				$this->effectiveVisitTime = max($this->visitTime, VisitTracker::getInstance()->getVisitTime('de.codequake.linklist.link'));
			} else {
				$this->effectiveVisitTime = max(VisitTracker::getInstance()->getObjectVisitTime('de.codequake.linklist.link', $this->linkID), VisitTracker::getInstance()->getVisitTime('de.codequake.linklist.link'));
			}
			if ($this->effectiveVisitTime === null) {
				$this->effectiveVisitTime = 0;
			}
		}

		return $this->effectiveVisitTime;
	}

	public function isNew() {
		if ($this->lastChangeTime > $this->getVisitTime()) {
			return true;
		}

		return false;
	}

	public static function getLink($linkID) {
		$list = new ViewableLinkList();
		$list->setObjectIDs(array(
			$linkID
		));
		$list->readObjects();

		return $list->search($linkID);
	}

	public function getUserProfile() {
		if ($this->userProfile === null) {
			$this->userProfile = new UserProfile(new User($this->getDecoratedObject()->userID));
		}

		return $this->userProfile;
	}
}
