<?php
namespace linklist\page;

use linklist\data\link\Link;
use linklist\data\link\LinkAction;
use linklist\data\link\LinkEditor;
use linklist\data\link\ViewableLink;
use wcf\page\AbstractPage;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\comment\CommentHandler;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\like\LikeHandler;
use wcf\system\request\LinkHandler;
use wcf\system\tagging\TagEngine;
use wcf\system\user\collapsible\content\UserCollapsibleContentHandler;
use wcf\system\MetaTagHandler;
use wcf\system\WCF;
use wcf\util\StringUtil;

class LinkPage extends AbstractPage {

	public $activeMenuItem = 'linklist.pageMenu.index';

	public $enableTracking = true;

	public $linkID;

	public $link = null;

	public $commentObjectTypeID = 0;

	public $commentManager = null;

	public $commentList = null;

	public $likeData = array();

	public $tags = array();

	public function readParameters() {
		parent::readParameters();

		if (isset($_REQUEST['id'])) $this->linkID = intval($_REQUEST['id']);
		else throw new IllegalLinkException();
		if (! isset($this->linkID) || $this->linkID == 0) throw new IllegalLinkException();
		$this->link = ViewableLink::getLink($this->linkID);
		if ($this->link === null) throw new IllegalLinkException();
		foreach ($this->link->getCategories() as $category) {
			if (! $category->getPermission('canViewLink')) throw new PermissionDeniedException();
		}
	}

	public function readData() {
		parent::readData();
		WCF::getBreadcrumbs()->add(new Breadcrumb(WCF::getLanguage()->get('linklist.pageMenu.index'), LinkHandler::getInstance()->getLink('CategoryList', array(
			'application' => 'linklist'
		))));

		$this->commentObjectTypeID = CommentHandler::getInstance()->getObjectTypeID('de.codequake.linklist.linkComment');
		$this->commentManager = CommentHandler::getInstance()->getObjectType($this->commentObjectTypeID)->getProcessor();
		$this->commentList = CommentHandler::getInstance()->getCommentList($this->commentManager, $this->commentObjectTypeID, $this->linkID);

		$linkEditor = new LinkEditor($this->link->getDecoratedObject());
		$linkEditor->update(array(
			'clicks' => $this->link->clicks + 1
		));

		// get Tags
		if (MODULE_TAGGING) {
			$this->tags = $this->link->getTags();
		}
		if ($this->link->teaser != '') MetaTagHandler::getInstance()->addTag('description', 'description', $this->link->teaser);
		else MetaTagHandler::getInstance()->addTag('description', 'description', StringUtil::decodeHTML(StringUtil::stripHTML($this->link->getExcerpt())));

		if (! empty($this->tags)) MetaTagHandler::getInstance()->addTag('keywords', 'keywords', implode(',', $this->tags));
		MetaTagHandler::getInstance()->addTag('og:title', 'og:title', $this->link->subject . ' - ' . WCF::getLanguage()->get(PAGE_TITLE), true);
		MetaTagHandler::getInstance()->addTag('og:url', 'og:url', LinkHandler::getInstance()->getLink('Link', array(
			'application' => 'linklist',
			'object' => $this->link->getDecoratedObject()
		)), true);
		MetaTagHandler::getInstance()->addTag('og:type', 'og:type', 'article', true);
		if ($this->link->getUserProfile()->facebook != '') MetaTagHandler::getInstance()->addTag('article:author', 'article:author', 'https://facebook.com/' . $this->link->getUserProfile()->facebook, true);
		if (FACEBOOK_PUBLIC_KEY != '') MetaTagHandler::getInstance()->addTag('fb:app_id', 'fb:app_id', FACEBOOK_PUBLIC_KEY, true);
		MetaTagHandler::getInstance()->addTag('og:description', 'og:description', StringUtil::decodeHTML(StringUtil::stripHTML($this->link->getExcerpt())), true);

		if ($this->link->isNew()) {
			$action = new LinkAction(array(
				$this->link->getDecoratedObject()
			), 'markAsRead', array(
				'viewableLink' => $this->link
			));
			$action->executeAction();
		}

		// fetch likes
		if (MODULE_LIKE) {
			$objectType = LikeHandler::getInstance()->getObjectType('de.codequake.linklist.likeableLink');
			LikeHandler::getInstance()->loadLikeObjects($objectType, array(
				$this->linkID
			));
			$this->likeData = LikeHandler::getInstance()->getLikeObjects($objectType);
		}
	}

	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'linkID' => $this->linkID,
			'link' => $this->link,
			'sidebarCollapsed' => UserCollapsibleContentHandler::getInstance()->isCollapsed('com.woltlab.wcf.collapsibleSidebar', 'de.codequake.linklist.link'),
			'sidebarName' => 'de.codequake.linklist.link',
			'likeData' => ((MODULE_LIKE && $this->commentList) ? $this->commentList->getLikeData() : array()),
			'linkLikeData' => $this->likeData,
			'commentCanAdd' => (WCF::getUser()->userID && WCF::getSession()->getPermission('user.linklist.link.canWriteComment')),
			'commentList' => $this->commentList,
			'commentObjectTypeID' => $this->commentObjectTypeID,
			'tags' => $this->tags,
			'lastCommentTime' => ($this->commentList ? $this->commentList->getMinCommentTime() : 0),
			'attachmentList' => $this->link->getAttachments(),
			'allowSpidersToIndexThisPage' => true,
		));
	}

	public function getObjectType() {
		return 'de.codequake.linklist.link';
	}

	public function getObjectID() {
		return $this->linkID;
	}
}
