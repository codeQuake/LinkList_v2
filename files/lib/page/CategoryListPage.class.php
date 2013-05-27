<?php
namespace linklist\page;
use wcf\page\AbstractPage;

use linklist\data\category\LinklistCategoryNodeTree;
use wcf\system\dashboard\DashboardHandler;
use wcf\system\user\collapsible\content\UserCollapsibleContentHandler;
use wcf\system\WCF;
/**
 * Shows the categorylist
 *
 * @author	Jens Krumsieck
 * @copyright	2013 codeQuake
 * @license	GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl-3.0.txt>
 * @package	de.codequake.linklist
 */
class CategoryListPage extends AbstractPage {
    
    public $templateName = 'categoryListIndex';
        /**
     * @see	wcf\page\AbstractPage::$enableTracking
     */
    public $enableTracking = true;
    public $categoryList = null;
    public $objectTypeName = 'de.codequake.linklist.category';
      
    /**
     * @see wcf\page\IPage::readData()
     */
    public function readData() {
        parent::readData();
        $categoryTree = new LinklistCategoryNodeTree($this->objectTypeName);
        $this->categoryList = $categoryTree->getIterator();
        $this->categoryList->setMaxDepth(0);
  }
    /**
     * @see wcf\page\IPage::assignVariables()
     */
    public function assignVariables() {
        parent::assignVariables();

        //dashboard
        DashboardHandler::getInstance()->loadBoxes('de.codequake.linklist.CategoryListPage', $this);

        WCF::getTPL()->assign(array(
            'categoryList' => $this->categoryList,
            'sidebarCollapsed'	=> UserCollapsibleContentHandler::getInstance()->isCollapsed('com.woltlab.wcf.collapsibleSidebar', 'de.codequake.linklist.index'),
            'sidebarName' => 'de.codequake.linklist.index',
            'allowSpidersToIndexThisPage'   =>  true
        ));
    }
}
