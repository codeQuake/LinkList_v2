<?php
namespace linklist\page;
use wcf\page\AbstractPage;

use linklist\data\category\LinklistCategoryNodeList;
use linklist\data\category\LinklistCategoryNode;
use linklist\data\category\LinklistCategory;
use wcf\system\dashboard\DashboardHandler;
use wcf\system\user\collapsible\content\UserCollapsibleContentHandler;
use wcf\system\WCF;
/**
 * Shows the category page.
 *
 * @author	Jens Krumsieck
 * @copyright	2013 codeQuake
 * @license	GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl-3.0.txt>
 * @package	de.codequake.linklist
 */
class CategoryPage extends AbstractPage {
    /**
     * @see	wcf\page\AbstractPage::$enableTracking
     */
    public $enableTracking = true;
    public $categoryList = null;
    public $categoryID;
    public $category;
    public $objectTypeName = 'de.codequake.linklist.category';
      
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
        $this->categoryList = new LinklistCategoryNodeList($this->objectTypeName, $this->categoryID);
        $this->categoryList->setMaxDepth(0);
  }
    /**
     * @see wcf\page\IPage::assignVariables()
     */
    public function assignVariables() {
        parent::assignVariables();


        WCF::getTPL()->assign(array(
            'categoryList' => $this->categoryList,
            'categoryID' => $this->categoryID,
            'category' => $this->category
        ));
    }
}
