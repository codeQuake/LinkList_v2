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

    protected $subCategories = null;
    protected $links = null;
    protected $visits = null;
    public $objectTypeName = 'de.codequake.linklist.category';
    
    protected function fulfillsConditions(DatabaseObject $category) {
        if (parent::fulfillsConditions($category)) {
            $category = new LinklistCategory($category);

            return $category->isAccessible();
          }

        return false;
    }
    
    public function getChildCategories($depth = 0) {
        if($this->subCategories === null) {
            $this->subCategories = new LinklistCategoryNodeTree($this->objectTypeName, $this->categoryID);
            if($depth > 0) $this->subCategories->setMaxDepth($depth);
        }
        return $this->subCategories;
    }
    public function getVisits() {
        return LinklistCategoryCache::getInstance()->getVisits($this->categoryID);
    }
    public function getLinks() {
        return LinklistCategoryCache::getInstance()->getLinks($this->categoryID);
    }


}