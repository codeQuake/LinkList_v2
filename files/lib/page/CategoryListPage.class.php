<?php
namespace linklist\page;
use wcf\page\AbstractPage;
use wcf\system\cache\builder\UserStatsCacheBuilder;
use linklist\system\cache\builder\LinklistStatsCacheBuilder;
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
    public $stats = array();
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
        $this->stats = array_merge(LinklistStatsCacheBuilder::getInstance()->getData(),
                                    UserStatsCacheBuilder::getInstance()->getData());
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
            'sidebarCollapsed'	=> UserCollapsibleContentHandler::getInstance()->isCollapsed('com.woltlab.wcf.collapsibleSidebar', 'de.codequake.linklist.CategoryListPage'),
            'sidebarName' => 'de.codequake.linklist.CategoryListPage',
            'allowSpidersToIndexThisPage'   =>  true,
            'stats' => $this->stats
        ));
    }
}
