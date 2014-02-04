<?php
namespace linklist\data\category;
use wcf\data\category\CategoryNode;
use wcf\data\DatabaseObject;
use linklist\data\category\LinklistCategoryCache;
/**
 * Represents a category node
 *
 * @author  Jens Krumsieck
 * @copyright   2013 codeQuake
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package de.codequake.linklist
 */
 
class LinklistCategoryNode extends CategoryNode{

    protected $links = null;
    protected $visits = null;
    public $parentNode = null;
    
    protected static $baseClass = 'linklist\data\category\LinklistCategory';
    public $objectTypeName = 'de.codequake.linklist.category';
    
    protected function fulfillsConditions(DatabaseObject $category) {
        if (parent::fulfillsConditions($category)) {
            $category = new LinklistCategory($category);

            return $category->isAccessible();
          }

        return false;
    }
    
    public function getVisits() {
        $visits = LinklistCategoryCache::getInstance()->getVisits($this->categoryID);
        foreach($this->getChildCategories() as $subCategory) {
            $visits = $visits + LinklistCategoryCache::getInstance()->getVisits($subCategory->categoryID);
        }
        return $visits;
    }
    public function getLinks() {
        $links = LinklistCategoryCache::getInstance()->getLinks($this->categoryID);
        foreach($this->getChildCategories() as $subCategory) {
            $links = $links + LinklistCategoryCache::getInstance()->getLinks($subCategory->categoryID);
        }
        return  $links;
    }
    
    public function isMainCategory(){
        return isset($this->additionalData['isMainCategory']) ? $this->additionalData['isMainCategory'] : 0;
    }
    
    

}