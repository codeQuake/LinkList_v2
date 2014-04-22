<?php
namespace linklist\action;

use wcf\action\AbstractAction;
use linklist\data\link\LinkEditor;
use linklist\data\link\Link;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;
use wcf\util\HeaderUtil;
use wcf\system\request\LinkHandler;

class LinkMoveAction extends AbstractAction {
	public $link = null;
	public $moveID = 0;

	public function readParameters() {
		parent::readParameters();
		if (isset($_GET['id'])) $this->linkID = intval($_GET['id']);
		$this->link = new Link($this->linkID);
		if ($this->link === null) throw new IllegalLinkException();
		if (! $this->link->getCategory()->getPermission('canEditLink')) throw new PermissionDeniedException();
		if (isset($_POST['move'])) $this->moveID = intval($_POST['move']);
	}

	public function execute() {
		parent::execute();
		$editor = new LinkEditor($this->link);
		$editor->update(array(
			'categoryID' => $this->moveID
		));
		
		$this->executed();
		HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Link', array(
			'application' => 'linklist',
			'object' => $this->link
		)));
	}
}