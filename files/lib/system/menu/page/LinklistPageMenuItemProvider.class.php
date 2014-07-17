<?php
namespace linklist\system\menu\page;

use linklist\data\category\LinklistCategory;
use wcf\system\category\CategoryHandler;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\language\LanguageFactory;
use wcf\system\menu\page\DefaultPageMenuItemProvider;
use wcf\system\user\storage\UserStorageHandler;
use wcf\system\visitTracker\VisitTracker;
use wcf\system\WCF;

/**
 * @author	Jens Krumsieck
 * @copyright	2014 codeQuake
 * @license	GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl-3.0.txt>
 * @package	de.codequake.linklist
 */
class LinklistPageMenuItemProvider extends DefaultPageMenuItemProvider {

	protected $notifications = null;

	public function getNotifications() {
		if ($this->notifications === null) {
			$this->notifications = 0;

			if (WCF::getUser()->userID) {
				// load storage data
				UserStorageHandler::getInstance()->loadStorage(array(
					WCF::getUser()->userID
				));

				// get ids
				$data = UserStorageHandler::getInstance()->getStorage(array(
					WCF::getUser()->userID
				), 'linklistUnreadLinks');

				// cache does not exist or is outdated
				if ($data[WCF::getUser()->userID] === null) {
					$categoryIDs = LinklistCategory::getAccessibleCategoryIDs();
					// removed ignored boards
					foreach ($categoryIDs as $key => $categoryID) {
						$category = CategoryHandler::getInstance()->getCategory($categoryID);
					}

					if (! empty($categoryIDs)) {
						$conditionBuilder = new PreparedStatementConditionBuilder();
						$conditionBuilder->add("link.lastChangeTime > ?", array(
							VisitTracker::getInstance()->getVisitTime('de.codequake.linklist.link')
						));
						$conditionBuilder->add("link.linkID IN (SELECT linkID FROM linklist" . WCF_N . "_link_to_category WHERE categoryID IN (?))", array(
							$categoryIDs
						));
						$conditionBuilder->add("link.isDeleted = 0 AND link.isDisabled = 0");
						$conditionBuilder->add("tracked_visit.visitTime IS NULL");
						// apply language filter
						if (LanguageFactory::getInstance()->multilingualismEnabled() && count(WCF::getUser()->getLanguageIDs())) {
							$conditionBuilder->add('(link.languageID IN (?) OR link.languageID IS NULL)', array(
								WCF::getUser()->getLanguageIDs()
							));
						}

						$sql = "SELECT		COUNT(*) AS count
							FROM		linklist" . WCF_N . "_link link
							LEFT JOIN	wcf" . WCF_N . "_tracked_visit tracked_visit
							ON		(tracked_visit.objectTypeID = " . VisitTracker::getInstance()->getObjectTypeID('de.codequake.linklist.link') . " AND tracked_visit.objectID = link.linkID AND tracked_visit.userID = " . WCF::getUser()->userID . ")
							" . $conditionBuilder;
						$statement = WCF::getDB()->prepareStatement($sql);
						$statement->execute($conditionBuilder->getParameters());
						$row = $statement->fetchArray();
						$this->notifications = $row['count'];
					}

					// update storage data
					UserStorageHandler::getInstance()->update(WCF::getUser()->userID, 'linklistUnreadLinks', $this->notifications);
				} else {
					$this->notifications = $data[WCF::getUser()->userID];
				}
			}
		}

		return $this->notifications;
	}
}
