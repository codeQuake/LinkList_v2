<?php
namespace linklist\system\cache\builder;

use linklist\data\link\LinkList;
use wcf\system\cache\builder\AbstractCacheBuilder;
use wcf\system\WCF;

class CategoryCacheBuilder extends AbstractCacheBuilder {

	protected $maxLifetime = 300;

	protected function rebuild(array $parameters) {
		$data = array(
			'counts' => array()
		);
		
		$sql = "SELECT	categoryID, links, visits
            FROM	linklist" . WCF_N . "_category_stats";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute();
		while ($row = $statement->fetchArray()) {
			$data['counts'][$row['categoryID']] = $row;
		}
		return $data;
	}
}
