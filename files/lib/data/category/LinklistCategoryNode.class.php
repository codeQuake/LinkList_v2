<?php
namespace linklist\data\category;
use wcf\data\category\CategoryNode;
use wcf\data\DatabaseObject;
use linklist\data\link\LinkList;

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
    
    public function getLinks(){
        if($this->links === null){
            $links = new LinkList();
            $links->sqlConditionJoins = 'WHERE categoryID = '.$this->categoryID;
            $this->links = $links->countObjects();
        }
        return $this->links;
    }
    
    public function countVisits(){
        if($this->visits === null){
            $this->visits = 0;
            $links = new Linklist();
            $links->sqlJoins = 'WHERE categoryID = '.$this->categoryID;
            $links->readObjects();
            $linklist = $links->getObjects();
            foreach ($linklist as $linkitem){
                $this->visits = $this->visits + $linkitem->visits;
            }
        }
        return $this->visits;
    }

}