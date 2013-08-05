<?php
namespace linklist\data\link;
use wcf\system\language\LanguageFactory;
use wcf\system\user\activity\event\UserActivityEventHandler;
use wcf\system\user\activity\point\UserActivityPointHandler;
use wcf\system\moderation\queue\ModerationQueueActivationManager;
use linklist\system\cache\builder\CategoryCacheBuilder;
use linklist\system\cache\builder\LinklistStatsCacheBuilder;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\tagging\TagEngine;
use wcf\system\search\SearchIndexManager;
use wcf\system\exception\PermissionDeniedException;
use wcf\data\IClipboardAction;
use wcf\system\clipboard\ClipboardHandler;
use wcf\util\StringUtil;
use wcf\system\WCF;
use linklist\data\link\LinkList;
use linklist\data\link\ViewableLinkList;
use linklist\data\link\LinkEditor;
use linklist\system\log\modification\LinkModificationLogHandler;

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
    protected $permissionsCreate = array('user.linklist.link.canAddLink');
    protected $permissionsDelete = array('mod.linklist.link.canDeleteLink');
    protected $permissionsTrash = array('mod.linklist.link.canTrashLink');    
    protected $permissionsEnable = array('mod.linklist.link.canToggleLink');
    protected $permissionsDisable = array('mod.linklist.link.canToggleLink');
    protected $allowGuestAccess = array('getPostPreview');
    
    public $links = array();
    public $link = null;
    
    public function create(){
        $object = call_user_func(array($this->className, 'create'), $this->parameters['data']);
        if (!empty($this->parameters['tags'])) {
            TagEngine::getInstance()->addObjectTags('de.codequake.linklist.link', $object->linkID, $this->parameters['tags'], $object->languageID);
        }
        $this->handleActivation($object);
       return $object;
    }
    public function update(){
        parent::update();       
        $objectIDs = array();
        foreach ($this->objects as $object) {
           $objectIDs[] = $object->linkID;
            // moderated content
            if (isset($this->parameters['data']['isDisabled'])) {
                    if ($this->parameters['data']['isDisabled']) {
                        $this->addModeratedContent($object->linkID);
                }
                    else {
                        $this->removeModeratedContent($object->linkID);
                    }
                }

                // edit
                if (isset($this->parameters['isEdit'])) {
                    $reason = (isset($this->parameters['data']['editReason'])) ? $this->parameters['data']['editReason'] : '';
                    LinkModificationLogHandler::getInstance()->edit($object->getDecoratedObject(), "");
                }
            // update tags
            $tags = array();
            if (isset($this->parameters['tags'])) {
                $tags = $this->parameters['tags'];
                unset($this->parameters['tags']);
            }
            if (!empty($tags)) {

                $languageID = (!isset($this->parameters['data']['languageID']) || ($this->parameters['data']['languageID'] === null)) ? LanguageFactory::getInstance()->getDefaultLanguageID() : $this->parameters['data']['languageID'];
                TagEngine::getInstance()->addObjectTags('de.codequake.linklist.link', $object->linkID, $tags, $languageID);
            }
        }
        if (!empty($objectIDs)) SearchIndexManager::getInstance()->delete('de.codequake.linklist.link', $objectIDs);
        if (!empty($objectIDs)) SearchIndexManager::getInstance()->add('de.codequake.linklist.link', $object->linkID, $object->message, $object->subject, $object->time, $object->userID, $object->username, $object->languageID);
       
        
    }
    
    //unmark
    public function validateUnmarkAll() { }
    public function unmarkAll() {
        ClipboardHandler::getInstance()->removeItems(ClipboardHandler::getInstance()->getObjectTypeID('de.codequake.linklist.link'));
    }
    protected function unmarkItems() {
        ClipboardHandler::getInstance()->unmark(array_keys($this->links), ClipboardHandler::getInstance()->getObjectTypeID('de.codequake.linklist.link'));
    }
    //trash
    public function trash() {        
        foreach ($this->links as $link) {
            $editor = new LinkEditor($link);
            $editor->update(array(
                'isDeleted' => 1,
                'deleteTime' => TIME_NOW
            ));
            LinkModificationLogHandler::getInstance()->trash($link, "");
        }

        $this->unmarkItems();
    }
     public function validateTrash() {
         $this->loadLinks();
         foreach ($this->links as $link) {
                if ($link->isDeleted) {
                    throw new PermissionDeniedException();
                }
            }
     }
     
     //toggle
     public function validateEnable(){
        $this->loadlinks();
        foreach ($this->links as $link){
            if($link->isActive){
              throw new PermissionDeniedException();
            }
        }
     }
     
     public function enable(){
        foreach ($this->links as $link) {
            $editor = new LinkEditor($link);
            $editor->update(array(
                'isActive' => 1
            ));
            $this->removeModeratedContent($link->linkID);
            $this->publish($link);
        }

        $this->unmarkItems();
     }
     public function validateDisable(){
        $this->loadlinks();
        foreach ($this->links as $link){
            if(!$link->isActive){
              throw new PermissionDeniedException();
            }
        }
     }
     public function disable(){
        foreach ($this->links as $link) {
            $editor = new LinkEditor($link);
            $editor->update(array(
                'isActive' => 0
            ));
            $this->addModeratedContent($link->linkID);
        }

        $this->unmarkItems();
     }
     
     
     //restore
     public function validateRestore(){
        $this->loadLinks();
        foreach($this->links as $link){
            if(!$link->isDeleted){
                throw new PermissionDeniedException();
            }
        }
     }
     public function  restore(){
        foreach($this->links as $link){
            $editor = new LinkEditor($link);
            $editor->update(array(
                'isDeleted' =>  0,
                'deleteTime'    =>  null
            ));
            LinkModificationLogHandler::getInstance()->restore($link);
            
        }
        $this->unmarkItems();
     }
     //delete     
     public function validateDelete(){
        $this->loadLinks();
        foreach($this->links as $link){
            if($link->isDeleted){
                throw new PermissionDeniedException();
            }
        }
     }
     public function delete(){     
        $linkIDs = array();
        foreach($this->links as $link){
            $linkIDs[] = $link->linkID;   
            LinkEditor::updateLinkCounter(array($link->userID => -1));
            $this->removeModeratedContent($link->linkID);
            LinkModificationLogHandler::getInstance()->delete($link, "");
        }
        // remove activity points        
        UserActivityPointHandler::getInstance()->removeEvents('de.codequake.linklist.activityPointEvent.link', $linkIDs);
        
        
        //delete
        parent::delete();
        $linkIDs = array();
        foreach($this->links as $link){
            //clear stats
            $this->refreshStats($link);
            $linkIDs[] = $link->linkID;
            // remove tags
            TagEngine::getInstance()->deleteObjectTags('de.codequake.linklist.link', $link->linkID);
        }
        if (!empty($linkIDs)) SearchIndexManager::getInstance()->delete('de.codequake.linklist.link', $linkIDs);
        
        //reset cache        
        LinklistStatsCacheBuilder::getInstance()->reset();
     }
     
    //getLinks
     protected function loadLinks() {
        if (empty($this->objectIDs)) {
            throw new UserInputException("objectIDs");
        }

        $list = new LinkList();
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
    
    public function getLinkPreview(){
            $list = new ViewableLinkList();
            $list->getConditionBuilder()->add("link.linkID = ?", array($this->link->linkID));
            $list->readObjects();
            $links = $list->getObjects();
            WCF::getTPL()->assign(array('link' => reset($links)));
            return array('template' => WCF::getTPL()->fetch('linkPreview', 'linklist'));
    }
    public function validateGetLinkPreview(){
        $this->link = $this->getSingleObject();
        // check if board may be entered and thread can be read
        $this->link->getCategory()->checkPermission(array('canViewCategory', 'canEnterCategory', 'canViewLink'));
    }
    
    
    protected function refreshStats($link){        
        //update links
         $links = new LinkList();
         $links->sqlConditionJoins = 'WHERE categoryID = '.$link->categoryID;
         $sql = "UPDATE linklist".WCF_N."_category_stats SET  links = ".$links->countObjects()." WHERE categoryID = ".$link->categoryID;
         $statement = WCF::getDB()->prepareStatement($sql);
         $statement->execute();
         CategoryCacheBuilder::getInstance()->reset();
    }
    
    protected function handleActivation($link){
        //handles activation for links
        if($link->getCategory()->getPermission('canAddActiveLink')){
            $editor = new LinkEditor($link);
            $editor->update(array(
                'isActive' => 1
            ));
            $this->publish($link);
        }
        else{
           $this->addModeratedContent($link->linkID);
        }
    }
    protected function removeModeratedContent($linkID){
        ModerationQueueActivationManager::getInstance()->removeModeratedContent('de.codequake.linklist.link', array($linkID));
    }
    protected function addModeratedContent($linkID) {
        ModerationQueueActivationManager::getInstance()->addModeratedContent('de.codequake.linklist.link', $linkID);
    }
    protected function publish($link){
        if($link->userID){
            UserActivityEventHandler::getInstance()->fireEvent('de.codequake.linklist.link.recentActivityEvent', $link->linkID, $link->languageID, $link->userID, $link->time);
            UserActivityPointHandler::getInstance()->fireEvent('de.codequake.linklist.activityPointEvent.link', $link->linkID, $link->userID);
            LinkEditor::updateLinkCounter(array($link->userID => 1));
        }

        $this->refreshStats($link);
        LinklistStatsCacheBuilder::getInstance()->reset();
        SearchIndexManager::getInstance()->add('de.codequake.linklist.link', $link->linkID, $link->message, $link->subject, $link->time, $link->userID, $link->username, $link->languageID);
    }
    
}