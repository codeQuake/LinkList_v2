<?php
namespace linklist\system\cache\builder;

use wcf\data\object\type\ObjectTypeCache;
use wcf\data\tag\Tag;
use wcf\data\tag\TagCloudTag;
use wcf\system\cache\builder\TagCloudCacheBuilder;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\WCF;
use wcf\util\StringUtil;

class LinksTagCloudCacheBuilder extends TagCloudCacheBuilder {

	protected $maxLifetime = 3600;

	protected function rebuild(array $parameters) {
		$objectType = ObjectTypeCache::getInstance()->getObjectTypeByName('com.woltlab.wcf.tagging.taggableObject', 'de.codequake.linklist.link');
		
		$conditions = new PreparedStatementConditionBuilder();
		if (isset($parameters['languageIDs']) && ! empty($parameters['languageIDs'])) {
			$conditions->add("tag_to_object.languageID IN (?)", array(
				$parameters['languageIDs']
			));
		}
		$conditions->add("tag_to_object.objectTypeID = ?", array(
			$objectType->objectTypeID
		));
		$conditions->add("tag_to_object.objectID = thread.threadID");
		$conditions->add("link.categoryID = ?", array(
			$parameters['categoryID']
		));
		
		$sql = "SELECT		COUNT(tag_to_object.tagID) AS counter, tag_to_object.tagID
			FROM		wcf" . WCF_N . "_tag_to_object tag_to_object,
					linklist" . WCF_N . "_link link
			" . $conditions . "
			GROUP BY	tag_to_object.tagID
			ORDER BY	counter DESC";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute($conditions->getParameters());
		
		$tagIDs = array();
		while ($row = $statement->fetchArray()) {
			$tagIDs[$row['tagID']] = $row['counter'];
		}
		
		if (! empty($tagIDs)) {
			$conditions = new PreparedStatementConditionBuilder();
			$conditions->add("tagID IN (?)", array(
				array_keys($tagIDs)
			));
			
			$sql = "SELECT	*
				FROM	wcf" . WCF_N . "_tag
				" . $conditions;
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute($conditions->getParameters());
			while ($row = $statement->fetchArray()) {
				$row['counter'] = $tagIDs[$row['tagID']];
				$this->tags[StringUtil::toLowerCase($row['name'])] = new TagCloudTag(new Tag(null, $row));
			}
			
			uasort($this->tags, array(
				'self',
				'compareTags'
			));
		}
		
		return $this->tags;
	}
}
