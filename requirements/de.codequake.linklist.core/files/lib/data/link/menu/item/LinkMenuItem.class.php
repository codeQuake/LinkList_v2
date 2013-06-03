<?php
namespace wcf\data\link\menu\item;
use wcf\data\DatabaseObject;
use wcf\system\exception\SystemException;
use wcf\util\ClassUtil;


class LinkMenuItem extends DatabaseObject {
    protected $contentManager = null;
    protected static $databaseTableName = 'linklist_link_menu_item';
    protected static $databaseTableIndexName = 'menuItemID';
    public function getIdentifier() {
        return str_replace('.', '_', $this->menuItem);
    }

    public function getContentManager() {
        if ($this->contentManager === null) {
            if (!class_exists($this->className)) {
                throw new SystemException("Unable to find class '".$this->className."'");
            }
            
         if (!ClassUtil::isInstanceOf($this->className, 'wcf\system\SingletonFactory')) {
                throw new SystemException("'".$this->className."' does not extend 'wcf\system\SingletonFactory'");
            }
            if (!ClassUtil::isInstanceOf($this->className, 'wcf\system\menu\link\content\ILinkMenuContent')) {
                throw new SystemException("'".$this->className."' does not implement 'wcf\system\menu\link\content\ILinkMenuContent'");
            }
            $this->contentManager = call_user_func(array($this->className, 'getInstance'));
        }

        return $this->contentManager;
    }
}
