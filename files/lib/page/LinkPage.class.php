<?php
namespace linklist\page;
use linklist\data\link\Link;
use wcf\system\exception\IllegalLinkException;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\request\LinkHandler;
use wcf\page\AbstractPage;
use wcf\system\WCF;

class LinkPage extends AbstractPage{

    public $enableTracking = true; 
    public $linkID;
    public $link = null;
    
    public function readParameters(){
        parent::readParameters();
        if(isset($_GET['id'])) $this->linkID = intval($_GET['id']);
        $this->link = new Link($this->linkID);        
        if($this->link === null) throw new IllegalLinkException();
    }
    
    public function readData(){
        parent::readData();        
        WCF::getBreadcrumbs()->add(new Breadcrumb(WCF::getLanguage()->get('linklist.page.index'), LinkHandler::getInstance()->getLink('Index')));
        //WCF::getBreadCrumbs()->add(new Breadcrumb());
        
    }
    public function assignVariables(){
        parent::assignVariables();
        WCF::getTPL()->assign(array('link'  =>  $this->link,
                                    'allowSpidersToIndexThisPage'   =>  true));
    }
    

}