<?php
namespace linklist\system\dashboard\box;

use linklist\page\CategoryPage;
use wcf\system\dashboard\box\AbstractSidebarDashboardBox;
use wcf\system\tagging\TypedTagCloud;
use wcf\system\language\LanguageFactory;
use wcf\system\WCF;
use wcf\page\IPage;
use wcf\data\dashboard\box\DashboardBox;

class LinksTagCloudDashboardBox extends AbstractSidebarDashboardBox {
	public $tagCloud = null;

	public function init(DashboardBox $box, IPage $page) {
		parent::init($box, $page);
		if (MODULE_TAGGING && LINKLIST_ENABLE_TAGS) {
			// multilingualism
			$languageIDs = array();
			if (LanguageFactory::getInstance()->multilingualismEnabled()) {
				$languageIDs = WCF::getUser()->getLanguageIDs();
			}
			$this->tagCloud = new TypedTagCloud('de.codequake.linklist.link', $languageIDs);
		}
		$this->fetched();
	}

	protected function render() {
		if ($this->tagCloud === null) return '';
		WCF::getTPL()->assign(array(
			'tags' => $this->tagCloud->getTags()
		));
		return WCF::getTPL()->fetch('tagCloudBox');
	}
}