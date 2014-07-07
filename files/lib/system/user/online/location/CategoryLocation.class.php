<?php
namespace linklist\system\user\online\location;

use linklist\data\category\LinklistCategory;
use wcf\data\user\online\UserOnline;
use wcf\system\category\CategoryHandler;
use wcf\system\user\online\location\IUserOnlineLocation;
use wcf\system\WCF;

class CategoryLocation implements IUserOnlineLocation {

	public function cache(UserOnline $user) {}

	public function get(UserOnline $user, $languageVariable = '') {
		if ($category = CategoryHandler::getInstance()->getCategory($user->objectID)) {
			$category = new LinklistCategory($category);
			if ($category->getPermission()) {
				return WCF::getLanguage()->getDynamicVariable($languageVariable, array(
					'category' => $category
				));
			}
		}
		return '';
	}
}
