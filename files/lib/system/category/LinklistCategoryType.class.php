<?php
namespace linklist\system\category;

use wcf\system\category\AbstractCategoryType;

/**
 * category type
 *
 * @author Jens Krumsieck
 * @copyright 2013 codeQuake
 * @license GNU Lesser General Public License
 * @package de.codequake.linklist
 */
class LinklistCategoryType extends AbstractCategoryType {

	/**
	 *
	 * @see AbstractCategoryType::$*
	 */
	protected $permissionPrefix = 'admin.linklist.category';

	protected $langVarPrefix = 'linklist.category';

	protected $objectTypes = array(
		'com.woltlab.wcf.acl' => 'de.codequake.linklist.category'
	);

	protected $forceDescription = false;

	/**
	 *
	 * @see wcf\system\category\ICategoryType::getApplication()
	 */
	public function getApplication() {
		return 'linklist';
	}
}
