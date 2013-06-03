<?php
namespace wcf\data\link\menu\item;
use wcf\data\DatabaseObjectEditor;
use wcf\data\IEditableCachedObject;
use wcf\system\cache\builder\LinkMenuCacheBuilder;
use wcf\system\WCF;


class LinkMenuItemEditor extends DatabaseObjectEditor implements IEditableCachedObject {
    protected static $baseClass = 'wcf\data\link\menu\item\LinkMenuItem';

    public static function create(array $parameters = array()) {
        // calculate show order
        $parameters['showOrder'] = self::getShowOrder($parameters['showOrder']);
        
        return parent::create($parameters);
    }

    
    public function update(array $parameters = array()) {
        if (isset($parameters['showOrder'])) {
          $this->updateShowOrder($parameters['showOrder']);
        }
        parent::update($parameters);
    }


    public function delete() {
        // update show order
        $sql = "UPDATE	wcf".WCF_N."_linklist_link_menu_item
            SET	showOrder = showOrder - 1
            WHERE	showOrder >= ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute(array($this->showOrder));
        
        parent::delete();
    }

    
    protected function updateShowOrder($showOrder) {
        if ($this->showOrder != $showOrder) {
            if ($showOrder < $this->showOrder) {
                $sql = "UPDATE	wcf".WCF_N."_linklist_link_menu_item
                    SET	showOrder = showOrder + 1
                    WHERE	showOrder >= ?
                        AND showOrder < ?";
                $statement = WCF::getDB()->prepareStatement($sql);
                $statement->execute(array(
                    $showOrder,
                    $this->showOrder
                ));
            }
            else if ($showOrder > $this->showOrder) {
                $sql = "UPDATE	wcf".WCF_N."_linklist_link_menu_item
                    SET	showOrder = showOrder - 1
                    WHERE	showOrder <= ?
                        AND showOrder > ?";
                $statement = WCF::getDB()->prepareStatement($sql);
                $statement->execute(array(
                $showOrder,
                $this->showOrder
                ));
            }
        }
    }

    
    protected static function getShowOrder($showOrder = 0) {
        if ($showOrder == 0) {
            // get next number in row
            $sql = "SELECT	MAX(showOrder) AS showOrder
                FROM	wcf".WCF_N."_linklist_link_menu_item";
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute();
            $row = $statement->fetchArray();
            if (!empty($row)) $showOrder = intval($row['showOrder']) + 1;
            else $showOrder = 1;
        }
        else {
            $sql = "UPDATE	wcf".WCF_N."_linklist_link_menu_item
                SET	showOrder = showOrder + 1
                WHERE	showOrder >= ?";
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute(array($showOrder));
        }
        return $showOrder;
    }

    public static function resetCache() {
        LinkMenuCacheBuilder::getInstance()->reset();
    }
}
