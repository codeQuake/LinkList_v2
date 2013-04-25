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
    
    public function getFormattedMessage() {
        MessageParser::getInstance()->setOutputType('text/html');
        return MessageParser::getInstance()->parse($this->message, $this->enableSmilies, $this->enableHtml, $this->enableBBCodes);
      }
      
    public function getMessage() {
        return $this->message;
      }
      
   
    public function __toString() {
        return $this->getFormattedMessage();
    }
    
    public function getCategory() {
        if($this->category === null) {
          $category = new Category($this->categoryID);
          $this->category = new LinklistCategory($category);
        }

        return $this->category;
    }
    
    public function getEditor() {
        if ($this->editor === null) {
          $this->editor = new ArticleEditor($this);
        }

        return $this->editor;
    }
    
    public function getID() {
        return $this->articleID;
    }
    
    public function getTitle() {
        return $this->subject;
    }
    
    public function getTime() {
         return $this->time;
    }
    
    public function getUserID() {
        return $this->userID;
    }
    
    public function getUsername() {
        return $this->username;
    }
    
    public function getExcerpt($maxLength = 255, $highlight=false) {
        if(!$highlight) MessageParser::getInstance()->setOutputType('text/plain');
        $message = MessageParser::getInstance()->parse($this->message, false, false, true);
        if(!$highlight) {
          if (StringUtil::length($message) > $maxLength) {
            $message = StringUtil::substring($message, 0, $maxLength).'&hellip;';
          }
        }
        else {
          if(StringUtil::length($message) > $maxLength) {
            $message = StringUtil::substring($message, 0, $maxLength);
          }
        }
    }
    
      public function getLink() {
        return LinkHandler::getInstance()->getLink('Link', array(
                                                    'application'   =>  'linklist',
                                                    'object'    =>  $this
                                                ));
      }
}
