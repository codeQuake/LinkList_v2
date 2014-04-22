<?php
namespace linklist\action;

use wcf\action\AbstractAction;
use linklist\data\link\Link;
use linklist\data\link\LinkList;
use wcf\system\exception\IllegalLinkException;
use linklist\system\cache\builder\CategoryCacheBuilder;
use wcf\util\HeaderUtil;
use wcf\system\WCF;

class LinkVisitAction extends AbstractAction {
	public $link = null;
	public $linkID;

	public function readParameters() {
		parent::readParameters();
		if (isset($_GET['id'])) $this->linkID = intval($_GET['id']);
		$this->link = new Link($this->linkID);
		if ($this->link === null) throw new IllegalLinkException();
	}

	public function execute() {
		parent::execute();
		$this->link->updateVisits();
		// update visits
		$visits = 0;
		$links = new Linklist();
		$links->sqlJoins = 'WHERE categoryID = ' . $this->link->getCategory()->categoryID;
		$links->readObjects();
		$linklist = $links->getObjects();
		foreach ($linklist as $linkitem) {
			$visits = $visits + $linkitem->visits;
		}
		$sql = "UPDATE linklist" . WCF_N . "_category_stats
                    SET  visits = " . $visits . "
                    WHERE categoryID = " . $this->link->getCategory()->categoryID;
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute();
		CategoryCacheBuilder::getInstance()->reset();
		$this->executed();
		HeaderUtil::redirect($this->link->url);
	}
}
