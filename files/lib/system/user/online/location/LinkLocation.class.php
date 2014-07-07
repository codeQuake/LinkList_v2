<?php
namespace linklist\system\user\online\location;

use linklist\data\link\Link;
use wcf\data\user\online\UserOnline;
use wcf\system\user\online\location\IUserOnlineLocation;
use wcf\system\WCF;

class LinkLocation implements IUserOnlineLocation {

	public function cache(UserOnline $user) {}

	public function get(UserOnline $user, $languageVariable = '') {
		$link = new Link($user->objectID);
		if ($link->linkID != 0) {
			if ($link->isVisible()) {
				return WCF::getLanguage()->getDynamicVariable($languageVariable, array(
					'link' => $link
				));
			}
		}
		return '';
	}
}
