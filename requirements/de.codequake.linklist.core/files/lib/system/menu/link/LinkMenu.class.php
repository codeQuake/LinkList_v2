<?php
namespace wcf\system\menu\link;
use wcf\data\link\menu\item\LinkMenuItem;
use wcf\system\cache\builder\LinkMenuCacheBuilder;
use wcf\system\event\EventHandler;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

class LinkMenu extends SingletonFactory {

    public $menuItems = null;
    public $activeMenuItem = null;
    
    protected function init() {
        // get menu items from cache
        $this->loadCache();
        // check menu items
        $this->checkMenuItems();
        // call init event
        EventHandler::getInstance()->fireAction($this, 'init');
    }
    protected function loadCache() {
        // call loadCache event
        EventHandler::getInstance()->fireAction($this, 'loadCache');
        $this->menuItems = LinkMenuCacheBuilder::getInstance()->getData();
    }
    
    protected function checkMenuItems() {
        foreach ($this->menuItems as $key => $item) {
            if (!$this->checkMenuItem($item)) {
                // remove this item
                unset($this->menuItems[$key]);
            }
        }
    }
    
    protected function checkMenuItem(LinkMenuItem $item) {
        // check the options of this item
        $hasEnabledOption = true;
        if (!empty($item->options)) {
			$hasEnabledOption = false;
			$options = explode(',', strtoupper($item->options));
			foreach ($options as $option) {
				if (defined($option) && constant($option)) {
					$hasEnabledOption = true;
					break;
				}
			}
		}
		if (!$hasEnabledOption) return false;
		
		// check the permission of this item for the active user
		$hasPermission = true;
		if (!empty($item->permissions)) {
			$hasPermission = false;
			$permissions = explode(',', $item->permissions);
			foreach ($permissions as $permission) {
				if (WCF::getSession()->getPermission($permission)) {
					$hasPermission = true;
					break;
				}
			}
		}
		if (!$hasPermission) return false;
		
		return true;
	}
    
    public function getMenuItems() {
		return $this->menuItems;
	}
    
    public function setActiveMenuItem($menuItem) {
		foreach ($this->menuItems as $item) {
			if ($item->menuItem == $menuItem) {
				$this->activeMenuItem = $item;
				return true;
			}
		}
		
		return false;
	}
    
    public function getActiveMenuItem() {
		if (empty($this->menuItems)) {
			return null;
		}
		
		if ($this->activeMenuItem === null) {
			reset($this->menuItems);
			$this->activeMenuItem = current($this->menuItems);
		}
		
		return $this->activeMenuItem;
	}
    
    public function getMenuItem($menuItem) {
		foreach ($this->menuItems as $item) {
			if ($item->menuItem == $menuItem) {
				return $item;
			}
		}
		
		return null;
	}
}