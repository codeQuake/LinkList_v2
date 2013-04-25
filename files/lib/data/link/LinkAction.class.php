<?php
namespace linklist\data\link;

use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\IClipboardAction;
use wcf\system\search\SearchIndexManager;
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
        SearchIndexManager::getInstance()->add('de.codequake.linklist.link', $object->linkID, $object->message, $object->subject, $object->time, $object->userID, $object->username, $object->languageID);
        return $object;

    }
}