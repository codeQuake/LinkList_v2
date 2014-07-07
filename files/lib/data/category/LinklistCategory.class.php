<?php
namespace linklist\data\category;

use linklist\data\category\LinklistCategoryCache;
use wcf\data\category\AbstractDecoratedCategory;
use wcf\data\label\group\LabelGroupList;
use wcf\data\label\group\ViewableLabelGroup;
use wcf\data\object\type\ObjectTypeCache;
use wcf\system\category\CategoryHandler;
use wcf\system\category\CategoryPermissionHandler;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\WCF;

/**
 * Represents a category
 *
 * @author Jens Krumsieck
 * @copyright 2013 codeQuake
 * @license GNU Lesser Generel Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package de.codequake.linklist
 */
class LinklistCategory extends AbstractDecoratedCategory {

	public static $objectTypeName = 'de.codequake.linklist.category';

	public $availableLabelGroups = null;

	public function getPermission($permission = 'canViewCategory') {
		if ($this->permissions === null) {
			$this->permissions = CategoryPermissionHandler::getInstance()->getPermissions($this->getDecoratedObject());
		}
		
		if (isset($this->permissions[$permission])) {
			return $this->permissions[$permission];
		}
		
		if (strpos($permission, 'Category') !== false) {
			return (WCF::getSession()->getPermission('user.linklist.category.' . $permission) || WCF::getSession()->getPermission('mod.linklist.category.' . $permission));
		}
		
		return (WCF::getSession()->getPermission('user.linklist.link.' . $permission) || WCF::getSession()->getPermission('mod.linklist.link.' . $permission));
	}

	public function checkPermission(array $permissions = array('canViewCategory')) {
		foreach ($permissions as $permission) {
			if (! $this->getPermission($permission)) {
				throw new PermissionDeniedException();
			}
		}
	}

	public static function getAccessibleCategoryIDs($permissions = array('canViewCategory', 'canEnterCategory')) {
		$categoryIDs = array();
		$categories = CategoryHandler::getInstance()->getCategories('de.codequake.linklist.category');
		foreach ($categories as $category) {
			$result = true;
			$category = new LinklistCategory($category);
			;
			foreach ($permissions as $permission) {
				$result = $result && $category->getPermission($permission);
			}
			
			if ($result) {
				$categoryIDs[] = $category->categoryID;
			}
		}
		return $categoryIDs;
	}

	public function isAccessible() {
		return $this->getPermission('canViewCategory') && $this->getPermission('canEnterCategory');
	}

	public function getAvailableLabelGroups() {
		if ($this->availableLabelGroups === null) {
			// get object type
			$objectType = ObjectTypeCache::getInstance()->getObjectTypeByName('com.woltlab.wcf.label.objectType', 'de.codequake.linklist.category');
			
			if ($objectType === null) {
				return null;
			}
			$availableLabelGroups = new LabelGroupList();
			$availableLabelGroups->sqlJoins .= "LEFT JOIN wcf" . WCF_N . "_label_group_to_object label_group_to_object ON (label_group.groupID = label_group_to_object.groupID)";
			
			$availableLabelGroups->getConditionBuilder()->add("label_group_to_object.objectTypeID = ?", array(
				$objectType->objectTypeID
			));
			$availableLabelGroups->getConditionBuilder()->add("label_group_to_object.objectID = ?", array(
				$this->categoryID
			));
			
			$availableLabelGroups->readObjects();
			
			$this->availableLabelGroups = $availableLabelGroups->getObjects();
			
			foreach ($this->availableLabelGroups as $key => $labelGroup) {
				$this->availableLabelGroups[$key] = new ViewableLabelGroup($labelGroup);
			}
		}
		
		return $this->availableLabelGroups;
	}

	public function getVisits() {
		$visits = LinklistCategoryCache::getInstance()->getVisits($this->categoryID);
		foreach ($this->getChildCategories() as $subCategory) {
			$visits = $visits + LinklistCategoryCache::getInstance()->getVisits($subCategory->categoryID);
		}
		return $visits;
	}

	public function getLinks() {
		$links = LinklistCategoryCache::getInstance()->getLinks($this->categoryID);
		foreach ($this->getChildCategories() as $subCategory) {
			$links = $links + LinklistCategoryCache::getInstance()->getLinks($subCategory->categoryID);
		}
		return $links;
	}

	public function isMainCategory() {
		return isset($this->additionalData['isMainCategory']) ? $this->additionalData['isMainCategory'] : 0;
	}
}
