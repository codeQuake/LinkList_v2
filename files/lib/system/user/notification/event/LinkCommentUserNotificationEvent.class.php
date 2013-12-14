<?php
namespace linklist\system\user\notification\event;
use linkist\data\link\Link;
use wcf\system\request\LinkHandler;
use wcf\system\user\notification\event\AbstractUserNotificationEvent;

class LinkCommentUserNotificationEvent extends AbstractUserNotificationEvent {

	public function getTitle() {
		return $this->getLanguage()->get('linklist.link.comment.notification.title');
	}
	
	public function getMessage() {
		$link = new Link($this->userNotificationObject->objectID);
		
		return $this->getLanguage()->getDynamicVariable('linklist.link.comment.notification.message', array(
			'link' => $link,
			'author' => $this->author
		));
	}

	public function getEmailMessage($notificationType = 'instant') {
		$link = new Link($this->userNotificationObject->objectID);
		
		return $this->getLanguage()->getDynamicVariable('linklist.link.comment.notification.mail', array(
			'link' => $link,
			'author' => $this->author
		));
	}

	public function getLink() {
		$link = new Link($this->userNotificationObject->objectID);
		
		return LinkHandler::getInstance()->getLink('Link', array(
			'application' => 'linklist',
			'object' => $link
		), '#comments');
	}
}
