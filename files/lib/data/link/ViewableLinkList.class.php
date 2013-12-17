<?php
namespace linklist\data\link;

use linklist\system\label\object\LinkLabelObjectHandler;
use linklist\data\link\LinkList;

class ViewableLinkList extends LinkList{
    public $decoratorClassName = 'linklist\data\link\ViewableLink';
    
    public function __construct(){
        parent::__construct();
        $linkIDs = $this->getLinks();
        if(!empty($linkIDs)) $this->getConditionBuilder()->add('linkID IN (?)', array($linkIDs));
        else return;
    }
    
    protected function getLinks(){
        $linkIDs = array();
        $list = new LinkList();
        $list->readObjects();
        $list = $list->getObjects();
        foreach($list as $item){
            if($item->isVisible()) $linkIDs[] = $item->linkID;
        }
        
        return $linkIDs;
     }
     
     public function readObjects() {
		if ($this->objectIDs === null) $this->readObjectIDs();
		parent::readObjects();
		
		// get assigned labels
		$linkIDs = array();
		foreach ($this->objects as $link) {
			if ($link->hasLabels) {
				$linkIDs[] = $link->linkID;
			}
		}
		
		if (!empty($linkIDs)) {
			$assignedLabels =LinkLabelObjectHandler::getInstance()->getAssignedLabels($linkIDs);
			foreach ($assignedLabels as $linkID => $labels) {
				foreach ($labels as $label) {
					$this->objects[$linkID]->addLabel($label);
				}
			}
		}
	}
}