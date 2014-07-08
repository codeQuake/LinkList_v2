<?php
namespace linklist\system\user\notification\event;

use linklist\data\link\Link;
use wcf\data\comment\Comment;
use wcf\system\request\LinkHandler;
use wcf\system\user\notification\event\AbstractUserNotificationEvent;

class LinkCommentResponseUserNotificationEvent extends AbstractUserNotificationEvent {

	public function getTitle() {
		return $this->getLanguage()->get('linklist.link.commentResponse.notification.title');
	}

	public function getMessage() {
		$comment = new Comment($this->userNotificationObject->commentID);
		$link = new Link($comment->objectID);
		
		return $this->getLanguage()->getDynamicVariable('linklist.link.commentResponse.notification.message', array(
			'link' => $link,
			'author' => $this->author
		));
	}

	public function getEmailMessage($notificationType = 'instant') {
		$comment = new Comment($this->userNotificationObject->commentID);
		$link = new Link($comment->objectID);
		
		return $this->getLanguage()->getDynamicVariable('linklist.link.commentResponse.notification.mail', array(
			'link' => $link,
			'author' => $this->author
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
