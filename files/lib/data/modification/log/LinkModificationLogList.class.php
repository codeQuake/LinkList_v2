<?php
namespace linklist\data\modification\log;

use linklist\system\log\modification\LinkModificationLogHandler;
use wcf\data\modification\log\ModificationLogList;
use wcf\system\WCF;

class LinkModificationLogList extends ModificationLogList {
	public $objectTypeID = 0;
	public $link = null;

	public function __construct() {
		parent::__construct();
		$objectType = LinkModificationLogHandler::getInstance()->getObjectType('de.codequake.linklist.link');
		$this->objectTypeID = $objectType->objectTypeID;
	}

	public function setLink($link) {
		$this->link = $link;
	}

	public function countObjects() {
		$sql = "SELECT		COUNT(modification_log.logID) AS count
			    FROM		wcf" . WCF_N . "_modification_log modification_log
			    WHERE		modification_log.objectTypeID = ?
					    AND modification_log.objectID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array(
			$this->objectTypeID,
			$this->link->linkID
		));
		$count = 0;
		while ($row = $statement->fetchArray()) {
			$count += $row['count'];
		}
		
		return $count;
	}

	public function readObjects() {
		$sql = "SELECT		modification_log.*
			FROM		wcf" . WCF_N . "_modification_log modification_log
			WHERE		modification_log.objectTypeID = ?
					AND modification_log.objectID = ?" . (! empty($this->sqlOrderBy) ? "ORDER BY " . $this->sqlOrderBy : '');
		;
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array(
			$this->objectTypeID,
			$this->link->linkID
		));
		$this->objects = $statement->fetchObjects(($this->objectClassName ?  : $this->className));
		$objects = array();
		foreach ($this->objects as $object) {
			$objectID = $object->{$this->getDatabaseTableIndexName()};
			$objects[$objectID] = $object;
			
			$this->indexToObject[] = $objectID;
		}
		$this->objectIDs = $this->indexToObject;
		$this->objects = $objects;
		foreach ($this->objects as &$object) {
			$object = new ViewableLinkModificationLog($object);
		}
		unset($object);
	}
}