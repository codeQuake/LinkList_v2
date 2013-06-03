<?php
namespace wcf\system\cache\builder;
use wcf\data\link\menu\item\LinkMenuItemList;

class LinkMenuCacheBuilder extends AbstractCacheBuilder {

    protected function rebuild(array $parameters) {
        $itemList = new LinkMenuItemList();
        $itemList->sqlOrderBy = "linklist_link_menu_item.showOrder ASC";
        $itemList->readObjects();
        return $itemList->getObjects();
    }
}
