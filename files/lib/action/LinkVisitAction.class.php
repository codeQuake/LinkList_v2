<?php
namespace linklist\action;

use linklist\data\link\Link;
use linklist\data\link\LinkEditor;
use linklist\data\link\LinkList;
use linklist\system\cache\builder\CategoryCacheBuilder;
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
			'clicks' => $this->link->clicks + 1
		));
		$this->executed();
		HeaderUtil::redirect($this->link->url);
	}
}
