<?php
namespace linklist\data\link;

class MostVisitedLinkList extends CategoryLinkList {

	public $sqlLimit = LINKLIST_MOST_LIMIT;

	public $sqlOrderBy = 'link.visits DESC';
}
