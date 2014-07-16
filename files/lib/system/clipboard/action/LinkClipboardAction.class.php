<?php
namespace linklist\system\clipboard\action;

use wcf\data\clipboard\action\ClipboardAction;
use wcf\system\clipboard\action\AbstractClipboardAction;
use wcf\system\WCF;

class LinkClipboardAction extends AbstractClipboardAction {

	protected $links = array();

	protected $actionClassActions = array(
		'delete',
		'enable',
		'disable'
	);

	protected $supportedActions = array(
		'delete',
		'enable',
		'disable'
	);

	public function execute(array $objects, ClipboardAction $action) {
		if (empty($this->links)) {
			$this->links = $objects;
		}

		$item = parent::execute($objects, $action);
		if ($item === null) {
			return null;
		}
		switch ($action->actionName) {
			case 'enable':
				$item->addParameter('objectIDs', array_keys($this->links));
				$item->addInternalData('confirmMessage', WCF::getLanguage()->getDynamicVariable('wcf.clipboard.item.de.codequake.linklist.link.enable.confirmMessage', array(
					'count' => $item->getCount()
				)));
				$item->addParameter('className', $this->getClassName());
				$item->setName('de.codequake.linklist.link.enable');
				break;

			case 'disable':
				$item->addParameter('objectIDs', array_keys($this->links));
				$item->addInternalData('confirmMessage', WCF::getLanguage()->getDynamicVariable('wcf.clipboard.item.de.codequake.linklist.link.disable.confirmMessage', array(
					'count' => $item->getCount()
				)));
				$item->addParameter('className', $this->getClassName());
				$item->setName('de.codequake.linklist.link.disable');
				break;

			case 'delete':
				$item->addParameter('objectIDs', array_keys($this->links));
				$item->addInternalData('confirmMessage', WCF::getLanguage()->getDynamicVariable('wcf.clipboard.item.de.codequake.linklist.link.delete.confirmMessage', array(
					'count' => $item->getCount()
				)));
				$item->addParameter('className', $this->getClassName());
				$item->setName('de.codequake.linklist.link.delete');
				break;
		}
		return $item;
	}

	public function getTypeName() {
		return 'de.codequake.linklist.link';
	}

	public function getClassName() {
		return 'linklist\data\link\LinkAction';
	}

	protected function validateEnable() {
		$linkIDs = array();
		foreach ($this->links as $link) {
			if ($link->isDisabled && $link->canModerate()) {
				$linkIDs[] = $link->linkID;
			}
		}

		return $linkIDs;
	}

	protected function validateDisable() {
		$linkIDs = array();
		foreach ($this->links as $link) {
			if (!$link->isDisabled && $link->canModerate()) {
				$linkIDs[] = $link->linkID;
			}
		}

		return $linkIDs;
	}

	protected function validateDelete() {
		$linkIDs = array();
		foreach ($this->links as $link) {
			if ($link->canDelete()) {
				$linkIDs[] = $link->linkID;
			}
		}

		return $linkIDs;
	}
}
