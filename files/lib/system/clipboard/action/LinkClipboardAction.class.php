<?php
namespace linklist\system\clipboard\action;

use wcf\data\clipboard\action\ClipboardAction;
use wcf\system\WCF;
use wcf\system\clipboard\action\AbstractClipboardAction;

class LinkClipboardAction extends AbstractClipboardAction {
	protected $links = array();
	protected $actionClassActions = array(
		'trash',
		'restore',
		'delete',
		'enable',
		'disable'
	);
	protected $supportedActions = array(
		'trash',
		'delete',
		'restore',
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
			case 'trash':
				$item->addParameter('objectIDs', array_keys($this->links));
				$item->addInternalData('confirmMessage', WCF::getLanguage()->getDynamicVariable('wcf.clipboard.item.de.codequake.linklist.link.trash.confirmMessage', array(
					'count' => $item->getCount()
				)));
				$item->addParameter('className', $this->getClassName());
				$item->setName('de.codequake.linklist.link.trash');
				break;

			case 'restore':
				$item->addParameter('objectIDs', array_keys($this->links));
				$item->addInternalData('confirmMessage', WCF::getLanguage()->getDynamicVariable('wcf.clipboard.item.de.codequake.linklist.link.restore.confirmMessage', array(
					'count' => $item->getCount()
				)));
				$item->addParameter('className', $this->getClassName());
				$item->setName('de.codequake.linklist.link.restore');
				break;

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

	protected function validateTrash() {
		$linkIDs = array();
		foreach ($this->links as $link) {
			if (! $link->isDeleted && $link->canTrash()) {
				$linkIDs[] = $link->linkID;
			}
		}

		return $linkIDs;
	}

	protected function validateEnable() {
		$linkIDs = array();
		foreach ($this->links as $link) {
			if (! $link->isActive && $link->canToggle()) {
				$linkIDs[] = $link->linkID;
			}
		}

		return $linkIDs;
	}

	protected function validateDisable() {
		$linkIDs = array();
		foreach ($this->links as $link) {
			if ($link->isActive && $link->canToggle()) {
				$linkIDs[] = $link->linkID;
			}
		}

		return $linkIDs;
	}

	protected function validateRestore() {
		$linkIDs = array();
		foreach ($this->links as $link) {
			// if you can trash, you can restore
			if ($link->isDeleted && $link->canTrash()) {
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
