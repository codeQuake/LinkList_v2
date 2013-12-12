<?php
namespace linklist\system\dashboard\box;
use wcf\data\dashboard\box\DashboardBox;
use wcf\page\IPage;
use linklist\data\link\Link;
use linklist\data\link\ViewableLinkList;
use wcf\system\dashboard\box\AbstractContentDashboardBox;
use wcf\system\WCF;

class RandomLinkDashboardBox extends AbstractContentDashboardBox{
    
    public $link = array();
    
    public function init(DashboardBox $box, IPage $page) {
     parent::init($box, $page);
        $linkIDs = $this->getLinks();
        if(!empty($linkIDs)){
            $accessibleLinkIDs = implode(",",$linkIDs);
            $sql = "SELECT linkID FROM linklist".WCF_N."_link WHERE linkID IN (?) ORDER BY RAND() LIMIT 1";
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute(array($accessibleLinkIDs));
            $row = $statement->fetchArray();
        
            $this->link = new Link($row['linkID']);
        }
     }
     
     protected function render(){
        if($this->link->linkID != 0){
            WCF::getTPL()->assign(array(
            'randomLink' => $this->link
            ));
            return WCF::getTPL()->fetch('dashboardBoxRandomLink', 'linklist');
            }
     }
     
     protected function getLinks(){
        $linkIDs = array();
        $list = new ViewableLinkList();
        $list->readObjects();
        $list = $list->getObjects();
        foreach($list as $item){
            if($item->isVisible()) $linkIDs[] = $item->linkID;
        }
        return $linkIDs;
     }
    
}