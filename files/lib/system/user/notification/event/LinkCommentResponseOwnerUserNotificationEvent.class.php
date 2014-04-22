<?php
namespace linklist\system\user\notification\event;

use linklist\data\link\Link;
use wcf\data\comment\Comment;
use wcf\data\user\User;
use wcf\system\request\LinkHandler;
use wcf\system\user\notification\event\AbstractUserNotificationEvent;

class LinkCommentResponseOwnerUserNotificationEvent extends AbstractUserNotificationEvent {

	public function getTitle() {
		return $this->getLanguage()->get('linklist.link.commentResponseOwner.notification.title');
	}

	public function getMessage() {
		$comment = new Comment($this->userNotificationObject->commentID);
		$link = new Link($comment->objectID);
		$commentAuthor = new User($comment->userID);
		
		return $this->getLanguage()->getDynamicVariable('linklist.link.commentResponseOwner.notification.message', array(
			'link' => $link,
			'author' => $this->author,
			'commentAuthor' => $commentAuthor
		));
	}

	public function getEmailMessage($notificationType = 'instant') {
		$comment = new Comment($this->userNotificationObject->commentID);
		$link = new Link($comment->objectID);
		$commentAuthor = new User($comment->userID);
		
		return $this->getLanguage()->getDynamicVariable('linklist.link.commentResponseOwner.notification.mail', array(
			'link' => $link,
			'author' => $this->author,
			'commentAuthor' => $commentAuthor
		));
	}

	public function getLink() {
		$comment = new Comment($this->userNotificationObject->commentID);
		$link = new Link($comment->objectID);
		
		return LinkHandler::getInstance()->getLink('Link', array(
			'application' => 'linklist',
			'object' => $link
		), '#comments');
	}
}
