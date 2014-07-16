<?php
namespace linklist\action;

use linklist\data\link\Link;
use linklist\data\link\LinkEditor;
use linklist\data\link\LinkList;
use linklist\system\cache\builder\CategoryCacheBuilder;
use linklist\system\cache\builder\LinklistStatsCacheBuilder;
use wcf\action\AbstractAction;
use wcf\system\exception\IllegalLinkException;
use wcf\system\WCF;
use wcf\util\HeaderUtil;

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
		$linkEditor = new LinkEditor($this->link);
		$linkEditor->update(array(
			'visits' => $this->link->visits + 1
		));
		CategoryCacheBuilder::getInstance()->reset();
		LinklistStatsCacheBuilder::getInstance()->reset();
		$this->executed();
		HeaderUtil::redirect($this->link->url);
	}
}
