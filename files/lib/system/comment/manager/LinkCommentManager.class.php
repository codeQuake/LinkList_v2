<?php
namespace linklist\system\comment\manager;
use linklist\data\link\Link;

use wcf\data\comment\Comment;
use wcf\data\comment\response\CommentResponse;
use wcf\system\comment\manager\AbstractCommentManager;
use wcf\system\WCF;

class LinkCommentManager extends AbstractCommentManager{
    public $link = null;
    
    
    public function canAdd($objectID){
        if(!$this->isAccessible($objectID)) {
            return false;
        }

        if($this->link === null) {
            $this->link = new Link($objectID);
        }
        
        return $this->link->getCategory()->getPermission('canWriteComment'); 
        
    }
    
    public function canEditComment(Comment $comment){
        //guests are unable to edit comments
        if (!WCF::getUser()->userID) {
            return false;
        }
        if(!$this->isAccessible($comment->objectID)) {
            return false;
        }
        
        if($this->link === null) {
            $this->link = new Link($objectID);
        }
        if($comment->userID == WCF::getUser()->userID) {
                return $this->link->getCategory()->getPermission('canEditOwnComment'); 
            }
        return $this->link->getCategory()->getPermission('canEditComment'); 
    }
    
    public function canEditCommentResponse(CommentResponse $response){
        //guests are unable to edit comment responses
        if (!WCF::getUser()->userID) {
            return false;
        }
        if(!$this->isAccessible($response->objectID)) {
            return false;
        }
        
        if($this->link === null) {
            $this->link = new Link($objectID);
        }
        if($response->getComment()->userID == WCF::getUser()->userID) {
                return $this->link->getCategory()->getPermission('canEditOwnComment'); 
            }
        return $this->link->getCategory()->getPermission('canEditComment'); 
    }
    
    public function canDeleteComment(Comment $comment){
        //guests are unable to delete comments
        if (!WCF::getUser()->userID) {
            return false;
        }
        if(!$this->isAccessible($comment->objectID)) {
            return false;
        }
        
        if($this->link === null) {
            $this->link = new Link($objectID);
        }
        if($comment->userID == WCF::getUser()->userID) {
                return $this->link->getCategory()->getPermission('canDeleteOwnComment'); 
            }
        return $this->link->getCategory()->getPermission('canDeleteComment'); 
    }
    
    public function canDeleteCommentResponse(CommentResponse $respose){
        //guests are unable to delete comment responses
        if (!WCF::getUser()->userID) {
            return false;
        }
        if(!$this->isAccessible($response->objectID)) {
            return false;
        }
        
        if($this->link === null) {
            $this->link = new Link($objectID);
        }
        if($response->getComment()->userID == WCF::getUser()->userID) {
                return $this->link->getCategory()->getPermission('canEditOwnComment'); 
            }
        return $this->link->getCategory()->getPermission('canEditComment'); 
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
    
    //TODO
    public function getLink($objectTypeID, $objectID) {
        return "";
    }
    public function getTitle($objectTypeID, $objectID, $isResponse = false) {
        return "";
    }
    
    public function updateCounter($objectID, $value) {

    }
    
      
      
}