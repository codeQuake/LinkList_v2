<?php
namespace linklist\page;
use wcf\page\SortablePage;

use linklist\data\category\LinklistCategoryNodeTree;
use linklist\data\category\LinklistCategoryNode;
use linklist\data\category\LinklistCategory;
use linklist\data\link\CategoryLinkList;
use wcf\system\WCF;
use wcf\system\dashboard\DashboardHandler;
use wcf\system\user\collapsible\content\UserCollapsibleContentHandler;

use wcf\system\category\CategoryHandler;
use wcf\system\request\LinkHandler;
use wcf\system\clipboard\ClipboardHandler;
use wcf\system\breadcrumb\Breadcrumb;

use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;
/**
 * Shows the category page.
 *
 * @author	Jens Krumsieck
 * @copyright	2013 codeQuake
 * @license	GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl-3.0.txt>
 * @package	de.codequake.linklist
 */
class CategoryPage extends SortablePage {
    /**
     * @see	wcf\page\AbstractPage::$enableTracking
     */
    public $enableTracking = true;
    public $categoryList = null;
    public $categoryID;    
    public $objectTypeName = 'de.codequake.linklist.category';
    public $category;
    public $objectListClassName = 'linklist\data\link\CategoryLinkList';
    
    public $validSortFields = array('subject', 'time', 'visits');
    
    
    public $itemsPerPage = LINKLIST_LINKS_PER_PAGE;
    public $defaultSortField = LINKLIST_DEFAULT_SORT_FIELD;
    public $defaultSortOrder = LINKLIST_DEFAULT_SORT_ORDER;
    
    
    protected function initObjectList() {
        $category= CategoryHandler::getInstance()->getCategory($this->categoryID);
        if($category !== null) $this->category = new LinklistCategory($category);
        if($this->category === null) throw new IllegalLinkException();
        if(!$this->category->getPermission('canEnterCategory')) throw new PermissionDeniedException();
        $this->objectList = new CategoryLinkList($this->category, $this->categoryID);
        }
    /**
     * @see wcf\page\IPage::readParameters()
     */
    public function readParameters(){
        parent::readParameters();
        if(isset($_GET['id'])) $this->categoryID = intval($_GET['id']);
    }
    
    /**
     * @see wcf\page\IPage::readData()
     */
    public function readData() {
        parent::readData();
        
        $categoryTree = new LinklistCategoryNodeTree($this->objectTypeName, $this->categoryID);
        $this->categoryList = $categoryTree->getIterator();
        $this->categoryList->setMaxDepth(0);
        
        WCF::getBreadcrumbs()->add(new Breadcrumb(WCF::getLanguage()->get('linklist.index.title'), LinkHandler::getInstance()->getLink('CategoryList',array('application' => 'linklist'))));
        foreach($this->category->getParentCategories()    as $categoryItem) {
                                  WCF::getBreadcrumbs()->add(new Breadcrumb($categoryItem->getTitle(), LinkHandler::getInstance()->getLink('Category', array(
                                      'application' => 'linklist',
                                      'object' => $categoryItem
          ))));
          }
    }
  
    /**
     * @see wcf\page\IPage::assignVariables()
     */
    public function assignVariables() {
        parent::assignVariables();
        //dashboard
        DashboardHandler::getInstance()->loadBoxes('de.codequake.linklist.CategoryPage', $this);

        WCF::getTPL()->assign(array(
            'categoryList' => $this->categoryList,
            'categoryID' => $this->categoryID,
            'category' => $this->category,
            'sidebarCollapsed'	=> UserCollapsibleContentHandler::getInstance()->isCollapsed('com.woltlab.wcf.collapsibleSidebar', 'de.codequake.linklist.CategoryPage'),
            'sidebarName' => 'de.codequake.linklist.CategoryPage',
            'hasMarkedItems' => ClipboardHandler::getInstance()->hasMarkedItems(ClipboardHandler::getInstance()->getObjectTypeID('de.codequake.linklist.link')),
            'allowSpidersToIndexThisPage'   =>  true
        ));
    }
    
    public function getObjectType() {
        return 'de.codequake.linklist.category';
    }

    public function getObjectID() {
        return $this->categoryID;
    }
}
