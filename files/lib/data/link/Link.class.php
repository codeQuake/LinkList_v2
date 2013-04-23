<?php
namespace linklist\data\link;

use linklist\data\LINKLISTDatabaseObject;
use linklist\data\category\LinklistCategory;

use wcf\system\WCF;
use wcf\data\IUserContent;
use wcf\data\IMessage;
use wcf\data\object\type\ObjectTypeCache;
use wcf\data\category\Category;
use wcf\data\user\User;
use wcf\data\user\UserProfile;
use wcf\system\language\LanguageFactory;
use wcf\system\request\IRouteController;
use wcf\system\bbcode\MessageParser;
use wcf\util\StringUtil;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\request\LinkHandler;
use wcf\system\comment\CommentHandler;

/**
 * @author  Jens Krumsieck
 * @copyright   2013 codeQuake
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package de.codequake.linklist
 */
class Link extends LINKLISTDatabaseObject implements IUserContent, IRouteController, IMessage{
    
    /**
     * @see wcf\data\DatabaseObject::*
     */
    protected static $databaseTableName = 'link';
    protected static $databaseTableIndexName = 'linkID';
    
    public function __construct($id, $row = null, $object = null){
        if ($id !== null) {
             $sql = "SELECT *
                    FROM ".static::getDatabaseTableName()."
                    WHERE (".static::getDatabaseTableIndexName()." = ?)";
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute(array($id));
            $row = $statement->fetchArray();

            if ($row === false) $row = array();
         }       

        parent::__construct(null, $row, $object);
        }
    }
    
}