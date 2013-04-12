<?php
namespace linklist\system;
use wcf\system\cache\CacheHandler;
use wcf\system\menu\page\PageMenu;
use wcf\system\application\AbstractApplication;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;

/**
 * Linklist core.
 *
 * @author	Jens Krumsieck
 * @copyright	2013 Jens Krumsieck
 * @license	GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl-3.0.txt>
 * @package	de.codequake.linklist
 */
class LINKLISTCore extends AbstractApplication {
	/**
	 * @see	AbstractApplication::$abbreviation
	 */
	protected $abbreviation = 'linklist';
	/**
	 * @see wcf\system\application\AbstractApplication
	 */
	public function __run() {
		if (!$this->isActiveApplication()) {
			return;
		}
		
		PageMenu::getInstance()->setActiveMenuItem('linklist.pageMenu.index');
		WCF::getBreadcrumbs()->add(new Breadcrumb(
			WCF::getLanguage()->get('linklist.breadCrumbs.index'), 
			LinkHandler::getInstance()->getLink('Index', array(
				'application' => 'linklist'
			))
		));
	}
}
