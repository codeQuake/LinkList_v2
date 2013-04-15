<?php
namespace linklist\data\category;
use wcf\data\category\ViewableCategoryNode;
use wcf\data\DatabaseObject;

/**
 * Represents a category node
 *
 * @author  Jens Krumsieck
 * @copyright   2013 codeQuake
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package de.codequake.linklist
 */
 
class LinklistCategoryNode extends ViewableCategoryNode{

    protected $subCategories = null;
    public $objectTypeName = 'de.codequake.linklist.category';
    
    protected function fulfillsConditions(DatabaseObject $category) {
        if (parent::fulfillsConditions($category)) {
            $category = new Category($category);

            return $category->isAccessible();
          }

        return false;
    }
    
    public function getChildCategories($depth = 0) {
        if($this->subCategories === null) {
            $this->subCategories = new CategorynodeList($this->objectTypeName, $this->categoryID);
            if($depth > 0) $this->subCategories->setMaxDepth($depth);
        }
        return $this->subCategories;
    }

}