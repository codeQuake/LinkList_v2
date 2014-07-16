<?php
namespace linklist\data\category;

use wcf\data\category\AbstractDecoratedCategory;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\breadcrumb\IBreadcrumbProvider;
use wcf\system\category\CategoryHandler;
use wcf\system\category\CategoryPermissionHandler;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;

/**
 * Represents a category
 *
 * @author Jens Krumsieck
 * @copyright 2013 codeQuake
 * @license GNU Lesser Generel Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package de.codequake.linklist
 */
class LinklistCategory extends AbstractDecoratedCategory implements IBreadcrumbProvider{

	public static $objectTypeName = 'de.codequake.linklist.category';

	public $permissions = null;

	public function isAccessible() {
		if ($this->getObjectType()->objectType != self::$objectTypeName) return false;
		return $this->getPermission('canViewCategory');
	}

	public function getPermission($permission = 'canViewCategory') {
		if ($this->permissions === null) {
			$this->permissions = CategoryPermissionHandler::getInstance()->getPermissions($this->getDecoratedObject());
		}

		if (isset($this->permissions[$permission])) {
			return $this->permissions[$permission];
		}

		return (WCF::getSession()->getPermission('user.linklist.link.' . $permission) || WCF::getSession()->getPermission('mod.linklist.link.' . $permission) || WCF::getSession()->getPermission('admin.linklist.link.' . $permission));
	}

	public function getBreadcrumb() {
		return new Breadcrumb(WCF::getLanguage()->get($this->title), LinkHandler::getInstance()->getLink('NewsCategory', array(
			'application' => 'cms',
			'object' => $this->getDecoratedObject()
		)));
	}

	public static function getAccessibleCategoryIDs($permissions = array('canViewCategory')) {
		$categoryIDs = array();
		foreach (CategoryHandler::getInstance()->getCategories(self::$objectTypeName) as $category) {
			$result = true;
			$category = new LinklistCategory($category);
			foreach ($permissions as $permission) {
				$result = $result && $category->getPermission($permission);
			}

			if ($result) {
				$categoryIDs[] = $category->categoryID;
			}
		}
		return $categoryIDs;
	}

	public function isMainCategory() {
		return isset($this->additionalData['isMainCategory']) ? $this->additionalData['isMainCategory'] : 0;
	}

	public function getIcon() {
		return isset($this->additionalData['icon']) && $this->additionalData['icon'] !='' ? $this->additionalData['icon'] : 'globe';
	}

}
