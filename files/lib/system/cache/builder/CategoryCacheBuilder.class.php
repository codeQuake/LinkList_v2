<?php
namespace linklist\system\cache\builder;

use linklist\data\link\Link;
use wcf\system\cache\builder\AbstractCacheBuilder;
use wcf\system\category\CategoryHandler;
use wcf\system\WCF;

class CategoryCacheBuilder extends AbstractCacheBuilder {
	protected $maxLifetime = 600;

	protected function rebuild(array $parameters) {
		$data = array();

		foreach (CategoryHandler::getInstance()->getCategories('de.codequake.linklist.category') as $category) {
			$sql = "SELECT * FROM linklist".WCF_N."_link_to_category WHERE categoryID = ?";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute(array($category->categoryID));
			$data[$category->categoryID]['links'] = 0;
			$data[$category->categoryID]['visits'] = 0;
			while ($row = $statement->fetchArray()) {
				$data[$category->categoryID]['links']++;
				$link = new Link($row['linkID']);
				$data[$category->categoryID]['visits'] = $data[$category->categoryID]['visits'] + $link->visits;
			}

		}

		return $data;
	}
}
