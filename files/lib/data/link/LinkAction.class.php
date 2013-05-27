<?php
namespace linklist\data\link;

use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\search\SearchIndexManager;
use wcf\data\IClipboardAction;
use wcf\system\clipboard\ClipboardHandler;
use wcf\util\StringUtil;
use wcf\system\WCF;

/** 
 * @author  Jens Krumsieck
 * @copyright   2013 codeQuake
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package de.codequake.linklist
 */

class LinkAction extends AbstractDatabaseObjectAction implements IClipboardAction{
    /**
     * @see wcf\data\AbstractDatabaseObjectAction::$className
     */
    protected $className = 'linklist\data\link\LinkEditor';
    
    
    public $links = array();
    public $message = null;
    
    public function create(){
        $object = call_user_func(array($this->className, 'create'), $this->parameters);
       return $object;

    }
    
    //unmark
    public function validateUnmarkAll() { }
    public function unmarkAll() {
        ClipboardHandler::getInstance()->removeItems(ClipboardHandler::getInstance()->getObjectTypeID('de.codequake.linklist.link'));
    }
    protected function unmarkItems() {
        ClipboardHandler::getInstance()->unmark(array_keys($this->links), ClipboardHandler::getInstance()->getObjectTypeID('de.codequake.linklist.link'));
    }
    
    //getLinks
     protected function loadLinks() {
        if (empty($this->objectIDs)) {
            throw new UserInputException("objectIDs");
        }

        $list = newLinkList();
        $list->getConditionBuilder()->add("link.linkID IN (?)", array($this->objectIDs));
        $list->sqlLimit = 0;
        $list->readObjects();

        foreach ($list as $link) {
            $this->links[$link->linkID] = $link;
        }

        if (empty($this->links)) {
            throw new UserInputException("objectIDs");
        }
    }
}