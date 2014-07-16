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

	protected static $baseClass = 'linklist\data\category\LinklistCategory';

	protected $unreadLinks = null;
	protected $links = null;
	protected $visits = null;

	public function getUnreadLinks() {
		if ($this->unreadLinks === null) $this->unreadLinks = LinklistCategoryCache::getInstance()->getUnreadLinks($this->categoryID);
		return $this->unreadLinks;
	}

	public function getLinks() {
		if ($this->links === null) $this->links = LinklistCategoryCache::getInstance()->getLinks($this->categoryID);
		return $this->links;
	}

	public function getVisits() {
		if ($this->visits === null) $this->visits = LinklistCategoryCache::getInstance()->getVisits($this->categoryID);
		return $this->visits;
	}

	public function isMainCategory() {
		return isset($this->additionalData['isMainCategory']) ? $this->additionalData['isMainCategory'] : 0;
	}

	public function getIcon() {
		return isset($this->additionalData['icon']) && $this->additionalData['icon'] != '' ? $this->additionalData['icon'] : 'globe';
	}
}
