<?php
namespace linklist\system\dashboard\box;

use wcf\data\dashboard\box\DashboardBox;
use wcf\page\IPage;
use linklist\data\link\ViewableLinkList;
use wcf\system\dashboard\box\AbstractContentDashboardBox;
use wcf\system\WCF;

class RandomLinkDashboardBox extends AbstractContentDashboardBox {
	public $links = null;

	public function init(DashboardBox $box, IPage $page) {
		parent::init($box, $page);
		$list = new ViewableLinkList();
		$list->sqlOrderBy = 'RAND()';
		$list->sqlLimit = 5;
		$list->readObjects();
		foreach ($list->getObjects() as $item) {
			$this->links[] = $item;
		}
	}

	protected function render() {
		if (isset($this->links)) {
			WCF::getTPL()->assign(array(
				'randomLinks' => $this->links
			));
			return WCF::getTPL()->fetch('dashboardBoxRandomLink', 'linklist');
		}
	}
}
