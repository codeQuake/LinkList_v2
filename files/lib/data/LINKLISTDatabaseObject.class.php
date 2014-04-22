<?php

namespace linklist\data;

use wcf\data\DatabaseObject;

/**
 *
 * @author Jens Krumsieck
 * @copyright 2013 codeQuake
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package de.codequake.linklist
 *         
 */
abstract class LINKLISTDatabaseObject extends DatabaseObject {
	
	/**
	 *
	 * @see wcf\data\IStorableObject::getDatabaseTableName()
	 *
	 */
	public static function getDatabaseTableName() {
		return 'linklist' . WCF_N . '_' . static::$databaseTableName;
	}
}