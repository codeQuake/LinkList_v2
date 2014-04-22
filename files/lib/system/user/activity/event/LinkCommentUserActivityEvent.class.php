<?php
namespace linklist\system\user\activity\event;

use linklist\data\link\LinkList;
use wcf\data\comment\CommentList;
use wcf\system\user\activity\event\IUserActivityEvent;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

class LinkCommentUserActivityEvent extends SingletonFactory implements IUserActivityEvent {

	public function prepare(array $events) {
		$objectIDs = array();
		foreach ($events as $event) {
			$objectIDs[] = $event->objectID;
		}

		// comments
		$commentList = new CommentList();
		$commentList->getConditionBuilder()->add("comment.commentID IN (?)", array(
			$objectIDs
		));
		$commentList->readObjects();
		$comments = $commentList->getObjects();

		// get links
		$linkIDs = array();
		foreach ($comments as $comment) {
			$linkIDs[] = $comment->objectID;
		}

		$linkList = new LinkList();
		$linkList->getConditionBuilder()->add("link.linkID IN (?)", array(
			$linkIDs
		));
		$linkList->readObjects();
		$links = $linkList->getObjects();

		foreach ($events as $event) {
			if (isset($comments[$event->objectID])) {
				$comment = $comments[$event->objectID];
				if (isset($links[$comment->objectID])) {
					$link = $links[$comment->objectID];
					$text = WCF::getLanguage()->getDynamicVariable('wcf.user.profile.recentActivity.linkComment', array(
						'link' => $link
					));
					$event->setTitle($text);
					$event->setDescription($comment->getFormattedMessage());
					$event->setIsAccessible();
				}
			}
			else
				$event->setIsOrphaned();
		}
	}
}
