<?php
namespace linklist\system\dashboard\box;
use wcf\data\dashboard\box\DashboardBox;
use wcf\page\IPage;
use linklist\data\link\ViewableLinkList;
use wcf\system\dashboard\box\AbstractContentDashboardBox;
use wcf\system\WCF;

class RandomLinkDashboardBox extends AbstractContentDashboardBox{
    
    public $link = null;
    
    public function init(DashboardBox $box, IPage $page) {
     parent::init($box, $page);
            $list = new ViewableLinkList();
            $list->sqlOrderBy = 'RAND()';
            $list->sqlLimit = 1;
            $list->readObjects();
            foreach($list->getObjects() as $item){
                $this->link = $item;
            }
    }

     protected function render(){
        if(isset($this->link) && $this->link->linkID != 0){
            WCF::getTPL()->assign(array(
            'randomLink' => $this->link
            ));
            return WCF::getTPL()->fetch('dashboardBoxRandomLink', 'linklist');
            }
     }
    
    
}