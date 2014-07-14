<?php
namespace linklist\data\link;

use linklist\data\category\LinklistCategory;
use linklist\system\label\object\LinkLabelObjectHandler;

class ViewableLinkList extends LinkList {

	public $decoratorClassName = 'linklist\data\link\ViewableLink';
	public $sqlOrderBy = 'link.time DESC';

	public function __construct() {
		parent::__construct();
		if (WCF::getUser()->userID != 0) {
			// last visit time
			if (! empty($this->sqlSelects)) $this->sqlSelects .= ',';
			$this->sqlSelects .= 'tracked_visit.visitTime';
			$this->sqlJoins .= " LEFT JOIN wcf" . WCF_N . "_tracked_visit tracked_visit ON (tracked_visit.objectTypeID = " . VisitTracker::getInstance()->getObjectTypeID('de.codequake.linklist.link') . " AND tracked_visit.objectID = link.linkID AND tracked_visit.userID = " . WCF::getUser()->userID . ")";
		}
		if (! WCF::getSession()->getPermission('user.linklist.link.canViewDisabledLinks')) {
			$this->getConditionBuilder()->add('link.isDisabled = ?', array(
				0
			));
		}
		// get like status
		if (! empty($this->sqlSelects)) $this->sqlSelects .= ',';
		$this->sqlSelects .= "like_object.likes, like_object.dislikes";
		$this->sqlJoins .= " LEFT JOIN wcf" . WCF_N . "_like_object like_object ON (like_object.objectTypeID = " . LikeHandler::getInstance()->getObjectType('de.codequake.linklist.likeableLink')->objectTypeID . " AND like_object.objectID = link.linkID)";

		// language Filter
		if (LanguageFactory::getInstance()->multilingualismEnabled() && count(WCF::getUser()->getLanguageIDs())) {
			$this->getConditionBuilder()->add('(link.languageID IN (?) OR link.languageID IS NULL)', array(
				WCF::getUser()->getLanguageIDs()
			));
		}
	}

	public function readObjects() {
		if ($this->objectIDs === null) $this->readObjectIDs();

		parent::readObjects();
	}
}
