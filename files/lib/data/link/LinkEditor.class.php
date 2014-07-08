<?php
namespace linklist\data\link;

use wcf\data\DatabaseObjectEditor;
use wcf\system\WCF;

/**
 *
 * @author Jens Krumsieck
 * @copyright 2013 Jens Krumsieck
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package de.codequake.linklist
 */
class LinkEditor extends DatabaseObjectEditor {

	protected static $baseClass = 'linklist\data\link\Link';

	public static function updateLinkCounter(array $users) {
		$sql = "UPDATE wcf" . WCF_N . "_user
                SET linklistLinks = linklistLinks + ?
                WHERE userID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		foreach ($users as $userID => $links) {
			$statement->execute(array(
				$links,
				$userID
			));
		}
	}
}
