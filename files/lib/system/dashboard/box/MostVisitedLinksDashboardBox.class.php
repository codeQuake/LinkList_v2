<?php
namespace linklist\system\dashboard\box;

use linklist\data\link\MostVisitedLinkList;
use linklist\page\CategoryPage;
use wcf\data\dashboard\box\DashboardBox;
use wcf\page\IPage;
use wcf\system\dashboard\box\AbstractSidebarDashboardBox;
use wcf\system\WCF;

class MostVisitedLinksDashboardBox extends AbstractSidebarDashboardBox {

	public $links = null;

	public function init(DashboardBox $box, IPage $page) {
		parent::init($box, $page);

		$categoryArray = ($page instanceof CategoryPage) ? array($page->categoryID) : array();

		$this->links = new MostVisitedLinkList($categoryArray);
		$this->links->readObjects();

		$this->fetched();
	}

	protected function render() {
		if (! count($this->links)) return '';

		WCF::getTPL()->assign(array(
			'mostVisitedLinks' => $this->links
		));

		return WCF::getTPL()->fetch('dashboardBoxMostVisitedLinks', 'linklist');
	}
}
