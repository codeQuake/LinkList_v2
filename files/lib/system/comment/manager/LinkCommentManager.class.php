<?php
namespace linklist\system\comment\manager;
use linklist\data\link\Link;

use wcf\data\comment\Comment;
use wcf\data\comment\response\CommentResponse;
use wcf\system\comment\manager\AbstractCommentManager;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;

class LinkCommentManager extends AbstractCommentManager{

    protected $permissionAdd = 'user.linklist.link.canWriteComment';
    protected $permissionCanModerate = 'mod.linklist.link.canModerateComment';
    protected $permissionDelete = 'user.linklist.link.canDeleteOwnComment';
    protected $permissionEdit = 'user.linklist.link.canEditOwnComment';
    protected $permissionModDelete = 'mod.linklist.link.canDeleteComment';
    protected $permssionModEdit = 'mod.linklist.link.canEditComment';
    
    public $link = null;
    
    
    public function canAdd($objectID){
        if (!$this->isAccessible($objectID, true)) {
            return false;
        }
        if($this->link === null) {
            $this->link = new Link($comment->objectID);
        }
        $aclOption = $this->link->getCategory()->getPermission('canWriteComment');
        $canAdd = $aclOption || parent::canAdd($objectID);
       
        if($canAdd) return true;
        else return false;
    }
    
    public function canEditComment(Comment $comment){
        if (!$this->isAccessible($comment->objectID, true)) {
            return false;
        }
        if($this->link === null) {
            $this->link = new Link($comment->objectID);
        }
        $aclOption = $this->link->getCategory()->getPermission('canEditComment');
        $isOwn =  $this->link->userID && $this->link->userID == WCF::getUser()->userID;
        $canEdit = $aclOption || ($isOwn && $this->link->getCategory()->getPermission('canEditOwnComment')) || parent::canEditComment($comment);
         
        if($canEdit) return true; 
        else return false;
    }
    
    public function canEditCommentResponse(CommentResponse $response){
       if (!$this->isAccessible($response->getComment()->objectID, true)) {
            return false;
        }
        if($this->link === null) {
            $this->link = new Link($response()->getComment()->objectID);
        }
        $aclOption = $this->link->getCategory()->getPermission('canEditComment');
        $isOwn =  $this->link->userID && $this->link->userID == WCF::getUser()->userID;
        $canEdit = $aclOption || ($isOwn && $this->link->getCategory()->getPermission('canEditOwnComment')) || parent::canEditCommentResponse($response);
        
        if($canEdit) return true;
        else return false;
        }
    
    public function canDeleteComment(Comment $comment){
        if(!$this->isAccessible($comment->objectID)) {
            return false;
        }
        
        if($this->link === null) {
            $this->link = new Link($comment->objectID);
        }
        
        $aclOption = $this->link->getCategory()->getPermission('canDeleteComment');
        $isOwn =  $this->link->userID && $this->link->userID == WCF::getUser()->userID;
        $canDelete = $aclOption || ($isOwn && $this->link->getCategory()->getPermission('canDeleteOwnComment')) || parent::canDeleteComment($comment);
        
        if($canDelete) return true;
        else return false;
    }
    
    public function canDeleteCommentResponse(CommentResponse $respose){
       if(!$this->isAccessible($response->objectID)) {
            return false;
        }
        
        if($this->link === null) {
            $this->link = new Link($response->getComment()->objectID);
        }
        $aclOption = $this->link->getCategory()->getPermission('canDeleteComment');
        $isOwn =  $this->link->userID && $this->link->userID == WCF::getUser()->userID;
        $canDelete = $aclOption || ($isOwn && $this->link->getCategory()->getPermission('canDeleteOwnComment')) || parent::canDeleteCommentResponse($response);
        
        if($canDelete) return true;
        else return false; 
    }
    
    
    public function isAccessible($objectID, $validateWritePermission = false){
        if($this->link === null) {
            $this->link = new Link($objectID);
        }

        if($validateWritePermission) {
            return $this->link->getCategory()->getPermission('canWriteComment');
        }

        return $this->link->getCategory()->getPermission('canViewLink');
    }
    
    public function getLink($objectTypeID, $objectID) {
        return LinkHandler::getInstance()->getLink('Link', array('id' => $objectID, 'application' => 'linklist'));
    }
    public function getTitle($objectTypeID, $objectID, $isResponse = false) {
        if ($isResponse) return WCF::getLanguage()->get('linklist.link.commentResponse');
        return WCF::getLanguage()->getDynamicVariable('linklist.link.comment');
    }
    
    public function updateCounter($objectID, $value) { }
    
      
      
}