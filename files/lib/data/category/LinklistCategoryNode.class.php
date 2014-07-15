<?php
namespace linklist\data\category;

use wcf\data\category\CategoryNode;

/**
 * Represents a category node
 *
 * @author Jens Krumsieck
 * @copyright 2013 codeQuake
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package de.codequake.linklist
 */
class LinklistCategoryNode extends CategoryNode {

	protected static $baseClass = 'cms\data\category\NewsCategory';

	protected $unreadLinks = null;

	public function getUnreadLinks() {
		if ($this->unreadLinks === null) $this->unreadLinks = LinklistCategoryCache::getInstance()->getUnreadNews($this->categoryID);
		return $this->unreadLinks;
	}

	public function isMainCategory() {
		return isset($this->additionalData['isMainCategory']) ? $this->additionalData['isMainCategory'] : 0;
	}
}
