<?php

namespace linklist\system\dashboard\box;

use linklist\data\link\LatestLinkList;
use wcf\data\dashboard\box\DashboardBox;
use wcf\page\IPage;
use wcf\system\dashboard\box\AbstractSidebarDashboardBox;
use wcf\system\WCF;

class LatestLinksDashboardBox extends AbstractSidebarDashboardBox {
	public $latestLinks = null;
	public function init(DashboardBox $box, IPage $page) {
		parent::init ( $box, $page );
		
		$this->latestLinks = new LatestLinkList ();
		$this->latestLinks->readObjects ();
	}
	protected function render() {
		if (! count ( $this->latestLinks ))
			return '';
		
		WCF::getTPL ()->assign ( array (
				'latestLinks' => $this->latestLinks 
		) );
		
		return WCF::getTPL ()->fetch ( 'dashboardBoxLatestLinks', 'linklist' );
	}
}
