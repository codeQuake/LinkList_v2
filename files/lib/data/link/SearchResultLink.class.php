<?php
namespace linklist\data\link;

use wcf\data\search\ISearchResultObject;
use wcf\system\request\LinkHandler;
use wcf\system\search\SearchResultTextParser;

class SearchResultLink extends ViewableLink implements ISearchResultObject {

	public $link = null;

	public function getFormattedMessage() {
		return SearchResultTextParser::getInstance()->parse($this->getDecoratedObject()
			->getFormattedMessage());
	}

	public function getSubject() {
		return $this->getLinklistLink()->subject;
	}

	public function getLink($query = '') {
		if ($query) {
			return LinkHandler::getInstance()->getLink('Link', array(
				'application' => 'linklist',
				'object' => $this->link,
				'highlight' => urlencode($query)
			));
		}
		return $this->getDecoratedObject()->getLink();
	}

	public function getTime() {
		return $this->getLinklistLink()->time;
	}

	public function getObjectTypeName() {
		return 'de.codequake.linklist.link';
	}

	public function getContainerTitle() {
		return $this->getLinklistLink()
			->getCategory()
			->getTitle();
	}

	public function getContainerLink() {
		return LinkHandler::getInstance()->getLink('Category', array(
			'application' => 'linklist',
			'object' => $this->getLinklistLink()
				->getCategory()
		));
	}

	public function getUserProfile() {
		return $this->getLinklistLink()->getUserProfile();
	}

	public function getLinklistLink() {
		if ($this->link === null) {
			$this->link = new Link($this->linkID);
		}
		return $this->link;
	}
}
