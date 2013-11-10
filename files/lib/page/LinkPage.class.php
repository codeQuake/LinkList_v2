<?php
namespace linklist\page;
use linklist\data\link\Link;
use linklist\data\category\LinklistCategoryNodeTree;
use wcf\system\exception\IllegalLinkException;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\request\LinkHandler;
use wcf\system\comment\CommentHandler;

use wcf\system\tagging\TagEngine;
use wcf\system\like\LikeHandler;
use wcf\page\AbstractPage;
use wcf\system\user\collapsible\content\UserCollapsibleContentHandler;
use wcf\system\WCF;


class LinkPage extends AbstractPage{

    public $enableTracking = true; 
    public $linkID;
    public $link = null;
    //comments
    public $commentManager = null;
    public $commentList = null;
    public $objectType = 0;
    public $likeData = array();
    public $tags = array();
    public $categoryList = array();
    
    
    public function readParameters(){
        parent::readParameters();
        if(isset($_GET['id'])) $this->linkID = intval($_GET['id']);
    }
    
    public function readData(){
        parent::readData();       
        $this->link = new Link($this->linkID);        
        if($this->link === null | $this->link->linkID == 0) throw new IllegalLinkException();
        WCF::getBreadcrumbs()->add(new Breadcrumb(WCF::getLanguage()->get('linklist.index.title'), LinkHandler::getInstance()->getLink('CategoryList',array('application' => 'linklist'))));
        foreach($this->link->getCategory()->getParentCategories()    as $categoryItem) {
                                  WCF::getBreadcrumbs()->add(new Breadcrumb($categoryItem->getTitle(), LinkHandler::getInstance()->getLink('Category', array(
                                      'application' => 'linklist',
                                      'object' => $categoryItem
          ))));
          }
          WCF::getBreadcrumbs()->add(new Breadcrumb($this->link->getCategory()->getTitle(), LinkHandler::getInstance()->getLink('Category', array(
          'application' => 'linklist',
          'object' => $this->link->getCategory()
            ))));
            
            
            //comments
            $this->objectTypeID = CommentHandler::getInstance()->getObjectTypeID('de.codequake.linklist.linkComment');
            $objectType = CommentHandler::getInstance()->getObjectType($this->objectTypeID);
            $this->commentManager = $objectType->getProcessor();

            $this->commentList = CommentHandler::getInstance()->getCommentList($this->commentManager, $this->objectTypeID, $this->linkID);
            
            
            // fetch likes
		    if (MODULE_LIKE && LINKLIST_ENABLE_LIKES) {
                $linkIDs = array();
                $linkIDs[] = $this->link->linkID;
			    $objectType = LikeHandler::getInstance()->getObjectType('de.codequake.linklist.likeableLink');
			    LikeHandler::getInstance()->loadLikeObjects($objectType, $linkIDs);
			    $this->likeData = LikeHandler::getInstance()->getLikeObjects($objectType);
		    }
            
            // get tags
		if (MODULE_TAGGING && LINKLIST_ENABLE_TAGS) {
			$this->tags = $this->link->getTags();
		}
        
        if ($this->link->getCategory()->getPermission('canEditLink')){
            $categoryTree = new LinklistCategoryNodeTree('de.codequake.linklist.category');
            $this->categoryList = $categoryTree->getIterator();
            $this->categoryList->setMaxDepth();
        }
    }
    public function assignVariables(){
        parent::assignVariables();
        WCF::getTPL()->assign(array('link'  =>  $this->link,
                                    'allowSpidersToIndexThisPage'   =>  true,
                                    'sidebarCollapsed'=> UserCollapsibleContentHandler::getInstance()->isCollapsed('com.woltlab.wcf.collapsibleSidebar', 'de.codequake.linklist.link'),
                                    'sidebarName' => 'de.codequake.linklist.link',
                                    'commentList' => $this->commentList,
                                    'categoryList' => $this->categoryList,
                                    'likeData' => ((MODULE_LIKE && $this->commentList) ? $this->commentList->getLikeData() : array()),
                                    'commentObjectTypeID'=> $this->objectTypeID,
                                    'linkLikeData' => $this->likeData,
                                    'tags' => $this->tags,
                                    'commentCanAdd' => $this->commentManager->canAdd($this->linkID),
                                    'lastCommentTime' => $this->commentList->getMinCommentTime(),
                                    'commentsPerPage' => $this->commentManager->getCommentsPerPage()));
    }
    
    
    public function getParentObjectType() {
       return 'de.codequake.linklist.category';
    }
    public function getParentObjectID() {
        if ($this->link) return $this->link->categoryID;
        return 0;
    }
    public function getObjectType() {
        return 'de.codequake.linklist.link';
    }
    public function getObjectID() {
        return $this->link->linkID;
    }

}