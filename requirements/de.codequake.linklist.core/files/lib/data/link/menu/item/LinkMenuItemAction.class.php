<?php
namespace wcf\data\link\menu\item;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\UserInputException;
use wcf\system\menu\link\LinkMenu;

class LinkMenuItemAction extends AbstractDatabaseObjectAction {

	protected $allowGuestAccess = array('getContent');

	protected $menuItem = null;

	public function validateGetContent() {
		
		$this->menuItem = LinkMenu::getInstance()->getMenuItem($this->parameters['data']['menuItem']);
		if ($this->menuItem === null) {
			throw new UserInputException('menuItem');
		}
	}
	

	public function getContent() {
		$contentManager = $this->menuItem->getContentManager();
		
		return array(
			'containerID' => $this->parameters['data']['containerID'],
			'template' => $contentManager->getContent($this->parameters['data']['linkID'])
		);
	}
}
