<?php
namespace linklist\system\user\notification\object\type;
use wcf\system\user\notification\object\type\ICommentUserNotificationObjectType;
use wcf\system\user\notification\object\type\AbstractUserNotificationObjectType;
use wcf\system\WCF;

class LinkCommentUserNotificationObjectType extends AbstractUserNotificationObjectType implements ICommentUserNotificationObjectType {
    protected static $decoratorClassName = 'wcf\system\user\notification\object\CommentUserNotificationObject';
    protected static $objectClassName = 'wcf\data\comment\Comment';
    protected static $objectListClassName = 'wcf\data\comment\CommentList';
    public function getOwnerID($objectID) {
		$sql = "SELECT		link.userID
			FROM		wcf".WCF_N."_comment comment
			LEFT JOIN	linklist".WCF_N."_link link
			ON		(link.linkID = comment.objectID)
			WHERE		comment.commentID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array($objectID));
		$row = $statement->fetchArray();
		
		return ($row ? $row['userID'] : 0);
	}
}