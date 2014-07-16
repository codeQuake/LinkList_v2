<?php
namespace linklist\form;

use linklist\data\link\Link;
use wcf\system\tagging\TagEngine;
use wcf\system\WCF;

class LinkEditForm extends LinkAddForm {

	public $action = 'edit';

	public $linkID = 0;

	public $link = null;

	public function readParameters() {
		parent::readParameters();
		if (isset($_GET['id'])) $this->linkID = intval($_GET['id']);
		$this->link = new Link($this->linkID);
		// set attachment object id
		$this->attachmentObjectID = $this->linkID;
		if ($this->link->linkID == 0) throw new IllegalLinkException();

		// can edit & own
		if ($this->link->userID == WCF::getUser()->userID) {
			foreach ($this->link->getCategories() as $category) {
				$category->getPermission('canEditOwnLink');
			}
		}
		else {
			foreach ($this->link->getCategories() as $category) {
				$category->getPermission('canEditLink');
			}
		}
	}

	public function readData() {
		parent::readData();
		// read categories
		$this->subject = $this->link->getTitle();
		$this->url = $this->link->url;
		$this->text = $this->link->message;
		$this->teaser = $this->link->teaser;

		foreach ($this->link->getCategories() as $category) {
			$this->categoryIDs[] = $category->categoryID;
		}

		// tagging
		if (MODULE_TAGGING) {
			$tags = TagEngine::getInstance()->getObjectTags('de.codequake.linklist.link', $this->link->linkID, array(
				$this->link->languageID
			));
			foreach ($tags as $tag) {
				$this->tags[] = $tag->name;
			}
		}
	}

	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'link' => $this->link
		));
	}

	public function save() {
		parent::save();
		$this->objectAction = new LinkAction(array(
			$this->linkID
		), 'update', array(
			'data' => array(
				'message' => $this->text,
				'url' => $this->url,
				'subject' => $this->subject,
				'teaser' => $this->teaser,
				'categoryID' => $this->categoryID,
				'lastChangeTime' => TIME_NOW,
				'enableSmilies' => $this->enableSmilies,
				'enableHtml' => $this->enableHtml,
				'enableBBCodes' => $this->enableBBCodes
			),
			'tags' => $this->tags,
			'attachmentHandler' => $this->attachmentHandler
		));
		$this->objectAction->executeAction();
		$this->link = new Link($this->linkID);
		$this->saved();

		HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Link', array(
			'application' => 'linklist',
			'object' => $this->link
		)));
		exit();
	}
}
