<?php
namespace linklist\page;
use wcf\page\AbstractPage;
use wcf\system\cache\builder\UserStatsCacheBuilder;
use wcf\data\user\online\UsersOnlineList;
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
    public $usersOnlineList = null;
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
                                    
        // users online
		if (MODULE_USERS_ONLINE && LINKLIST_INDEX_WIO) {
			$this->usersOnlineList = new UsersOnlineList();
			$this->usersOnlineList->readStats();
			$this->usersOnlineList->getConditionBuilder()->add('session.userID IS NOT NULL');
			$this->usersOnlineList->readObjects();
			
			// check users online record
			$usersOnlineTotal = (LINKLIST_INDEX_WIO_NOGUESTS ? $this->usersOnlineList->stats['members'] : $this->usersOnlineList->stats['total']);
			if ($usersOnlineTotal > LINKLIST_USERS_ONLINE_RECORD) {
				// save new record
				$action = new OptionAction(array(), 'import', array('data' => array(
					'linklist_users_online_record' => $usersOnlineTotal,
					'linklist_users_online_record_time' => TIME_NOW
				)));
				$action->executeAction();
			}
		}
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
            'usersOnlineList' => $this->usersOnlineList,
            'stats' => $this->stats
        ));
    }
}
