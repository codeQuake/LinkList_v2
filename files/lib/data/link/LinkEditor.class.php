<?php
namespace linklist\data\link;

use linklist\system\cache\builder\CategoryCacheBuilder;
use linklist\system\cache\builder\LinklistStatsCacheBuilder;
use wcf\data\DatabaseObjectEditor;
use wcf\data\IEditableCachedObject;
use wcf\system\user\storage\UserStorageHandler;
use wcf\system\WCF;

/**
 *
 * @author Jens Krumsieck
 * @copyright 2013 Jens Krumsieck
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package de.codequake.linklist
 */
class LinkEditor extends DatabaseObjectEditor implements IEditableCachedObject{

	protected static $baseClass = 'linklist\data\link\Link';

	public static function resetCache() {
		CategoryCacheBuilder::getInstance()->reset();
		LinklistStatsCacheBuilder::getInstance()->reset();

		// reset storage
		UserStorageHandler::getInstance()->resetAll('linklistUnreadLinks');
	}

	public function updateCategoryIDs(array $categoryIDs = array()) {
		// remove old assigns
		$sql = "DELETE FROM	linklist" . WCF_N . "_link_to_category
			WHERE		linkID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array(
			$this->linkID
		));

		// assign new categories
		if (! empty($categoryIDs)) {
			WCF::getDB()->beginTransaction();

			$sql = "INSERT INTO	linklist" . WCF_N . "_link_to_category
						(categoryID, linkID)
				VALUES		(?, ?)";
			$statement = WCF::getDB()->prepareStatement($sql);
			foreach ($categoryIDs as $categoryID) {
				$statement->execute(array(
					$categoryID,
					$this->linkID
				));
			}

			WCF::getDB()->commitTransaction();
		}
	}
}
