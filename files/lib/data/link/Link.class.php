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
use wcf\system\tagging\TagEngine;
use wcf\data\user\UserProfile;
use wcf\system\language\LanguageFactory;
use wcf\system\request\IRouteController;
use wcf\system\bbcode\MessageParser;
use wcf\util\StringUtil;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\request\LinkHandler;
use wcf\system\like\LikeHandler;
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
    public $commentList;
    
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
      
     public function getCommentList() {
        if($this->commentList === null) {
            $objectTypeID = CommentHandler::getInstance()->getObjectTypeID('de.codequake.linklist.linkComment');
            $objectType = CommentHandler::getInstance()->getObjectType($objectTypeID);
            $commentManager = $objectType->getProcessor();

            $this->commentList = CommentHandler::getInstance()->getCommentList($commentManager, $objectTypeID, $this-linkID);
        }
        return $this->commentList;
    }
      
    public function getMessage() {
        return $this->message;
      }
      
    public function getImage($size = 150){
        if($this->image !== null && $this->image != '')
        {
            return '<img src="'.$this->image.'" alt="'.$this->getTitle().'" style="max-width: 100%; max-height: 100%;" />';
        }
        return '<img src="http://api.webthumbnail.org?width='.$size.'&height='.$size.'&screen=1280&format=png&url='.$this->url.'" alt="Captured by webthumbnail.org" />';
        
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
          $this->editor = new LinkEditor($this);
        }

        return $this->editor;
    }
    
    public function getID() {
        return $this->linkID;
    }
    
    public function getTitle() {
        return $this->subject;
    }
    
    public function getTime() {
         return $this->time;
    }
    
    public function getUserProfile(){
        return new UserProfile(new User($this->userID));
    }
    public function getUserID() {
        return $this->userID;
    }
    
    public function getUsername() {
        return $this->username;
    }
    public function updateVisits(){
        $visits = $this->visits + 1;
        $sql = "UPDATE ".static::getDatabaseTableName()."
                SET visits = ".$visits."
                WHERE ".static::getDatabaseTableIndexName()." = ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute(array($this->linkID));
        
    }
    
    public function getExcerpt($maxLength = 255) {
        MessageParser::getInstance()->setOutputType('text/simplified-html');
        return StringUtil::truncateHTML(MessageParser::getInstance()->parse($this->message, $this->enableSmilies, $this->enableHtml, $this->enableBBCodes), $maxLength);
    }
    
      public function getLink() {
        return LinkHandler::getInstance()->getLink('Link', array(
                                                    'application'   =>  'linklist',
                                                    'object'    =>  $this
                                                ));
      }
      
       public function isVisible() {
            if($this->isActive == 0 && $this->isDeleted == 0) {
                return $this->getCategory()->getPermission('canSeeDeactivatedLink');
            }
            elseif($this->isDeleted == 1 && $this->isActive == 1) {
                return $this->getCategory()->getPermission('canTrashLink');
            }
            elseif($this->isDeleted === 1 && $this->isActive == 0){
                $trash = $this->getCategory()->getPermission('canSeeTrashLink');
                $deactive = $this->getCategory()->getPermission('canSeeDeactivatedLink');
                if($trash && $deactive) return true;
                else return false;
            }
            else{
                return $this->getCategory()->getPermission('canViewLink');
            }
        }
        
        public function canTrash(){
            $aclOption = $this->getCategory()->getPermission('canTrashLink');
            $isOwn =  $this->userID && $this->userID == WCF::getUser()->userID;
            $canTrash = $aclOption || ($isOwn && $this->getCategory()->getPermission('canDeleteOwnLink'));
            if($canTrash) return true;
            else return false;
        }
        
        public function canDelete(){
            $aclOption = $this->getCategory()->getPermission('canDeleteLink');
            $isOwn =  $this->userID && $this->userID == WCF::getUser()->userID;
            $canDelete = $aclOption || ($isOwn && $this->getCategory()->getPermission('canDeleteOwnLink'));
            if($canDelete) return true;
            else return false;
        }
        
        public function canToggle(){
            $aclOption = $this->getCategory()->getPermission('canToggleLink');
            if($aclOption) return true;
            else return false;
        }
        
        public function countLikes(){
            if (MODULE_LIKE) {
                $linkIDs = array();
                $linkIDs[] = $this->linkID;
                $objectType = LikeHandler::getInstance()->getObjectType('de.codequake.linklist.likeableLink');
                LikeHandler::getInstance()->loadLikeObjects($objectType, $linkIDs);
                return LikeHandler::getInstance()->getLikeObject($objectType, $this->linkID);
            }
        }
        public function hasLikes(){
            if (MODULE_LIKE) {
                if ($this->cumulativeLikes == 0){
                    return false;
                }
                else return true;
            }
            return false;
        }
        
        public function getTags(){
            $tags = TagEngine::getInstance()->getObjectTags(
				'de.codequake.linklist.link',
				$this->linkID,
				array(($this->languageID === null ? LanguageFactory::getInstance()->getDefaultLanguageID() : ""))
			);
            return $tags;
        }
}
