<?php
namespace linklist\page;
use linklist\data\link\Link;
use wcf\system\exception\IllegalLinkException;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\request\LinkHandler;
use wcf\page\AbstractPage;
use wcf\system\user\collapsible\content\UserCollapsibleContentHandler;
use wcf\system\WCF;

class LinkPage extends AbstractPage{

    public $enableTracking = true; 
    public $linkID;
    public $link = null;
    
    public function readParameters(){
        parent::readParameters();
        if(isset($_GET['id'])) $this->linkID = intval($_GET['id']);
    }
    
    public function readData(){
        parent::readData();        
        $this->link = new Link($this->linkID);        
        if($this->link === null | $this->link->linkID == 0) throw new IllegalLinkException();
        WCF::getBreadcrumbs()->add(new Breadcrumb(WCF::getLanguage()->get('linklist.index.title'), LinkHandler::getInstance()->getLink('CategoryList',array('application' => 'linklist'))));
        foreach($this->link->getCategory()->getParentCategories()    AS $categoryItem) {
                                  WCF::getBreadcrumbs()->add(new Breadcrumb($categoryItem->getTitle(), LinkHandler::getInstance()->getLink('Category', array(
                                      'application' => 'linklist',
                                      'object' => $categoryItem
          ))));
          }
          WCF::getBreadcrumbs()->add(new Breadcrumb($this->link->getCategory()->getTitle(), LinkHandler::getInstance()->getLink('Category', array(
          'application' => 'linklist',
          'object' => $this->link->getCategory()
            ))));
        
    }
    public function assignVariables(){
        parent::assignVariables();
        WCF::getTPL()->assign(array('link'  =>  $this->link,
                                    'allowSpidersToIndexThisPage'   =>  true,
                                    'sidebarCollapsed'	=> UserCollapsibleContentHandler::getInstance()->isCollapsed('com.woltlab.wcf.collapsibleSidebar', 'de.codequake.linklist.link'),
                                    'sidebarName' => 'de.codequake.linklist.link',));
    }
    

}