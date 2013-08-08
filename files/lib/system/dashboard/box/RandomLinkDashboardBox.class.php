<?php
namespace linklist\system\dashboard\box;
use wcf\data\dashboard\box\DashboardBox;
use wcf\page\IPage;
use linklist\data\link\Link;
use wcf\system\dashboard\box\AbstractSidebarDashboardBox;
use wcf\system\WCF;

class RandomLinkDashboardBox extends AbstractSidebarDashboardBox{
    
    public $link = array();
    
    public function init(DashboardBox $box, IPage $page) {
     parent::init($box, $page);
        $sql = "SELECT linkID FROM linklist".WCF_N."_link ORDER BY RAND() LIMIT 1";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute();
        $row = $statement->fetchArray();
        
        $this->link = new Link($row['linkID']);
     }
     
     protected function render(){
            WCF::getTPL()->assign(array(
            'randomLink' => $this->link
            ));
            return WCF::getTPL()->fetch('dashboardBoxRandomLink', 'linklist');
     }
    
}