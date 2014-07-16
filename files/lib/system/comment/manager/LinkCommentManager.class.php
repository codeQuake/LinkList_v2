<?php
namespace linklist\system\comment\manager;

use linklist\data\link\Link;
use linklist\data\link\LinkEditor;
use wcf\data\comment\response\CommentResponse;
use wcf\data\comment\Comment;
use wcf\system\comment\manager\AbstractCommentManager;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;

class LinkCommentManager extends AbstractCommentManager {

	protected $permissionAdd = 'user.linklist.link.canWriteComment';

	protected $permissionCanModerate = 'mod.linklist.link.canModerateComment';

	protected $permissionDelete = 'user.linklist.link.canDeleteOwnComment';

	protected $permissionEdit = 'user.linklist.link.canEditOwnComment';

	protected $permissionModDelete = 'mod.linklist.link.canDeleteComment';

	protected $permssionModEdit = 'mod.linklist.link.canEditComment';

	public $link = null;

	public function isAccessible($objectID, $validateWritePermission = false) {
		if ($this->link === null) {
			$this->link = new Link($objectID);
		}

		return $this->link->canRead();
	}

	public function getLink($objectTypeID, $objectID) {
		return LinkHandler::getInstance()->getLink('Link', array(
			'id' => $objectID,
			'application' => 'linklist'
		));
	}

	public function getTitle($objectTypeID, $objectID, $isResponse = false) {
		if ($isResponse) return WCF::getLanguage()->get('linklist.link.commentResponse');
		return WCF::getLanguage()->getDynamicVariable('linklist.link.comment');
	}

	public function updateCounter($objectID, $value) {
		$link = new Link($objectID);
		$editor = new LinkEditor($link);
		$editor->update(array(
			'comments' => ($link->comments + $value)
		));
	}
}
