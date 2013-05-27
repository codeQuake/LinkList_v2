<?php
namespace linklist\system\clipboard\action;
use wcf\system\clipboard\action\AbstractClipboardAction;
use wcf\system\clipboard\ClipboardEditorItem;
use wcf\system\WCF;

class LinkClipboardAction extends AbstractClipboardAction{
    public $links = null;
    protected $actionClassActions = array('trash', 'restore', 'delete', 'enable');
    protected $supportedActions = array('trash', 'delete', 'restore', 'enable');
    
    
    public function getTypeName() {
            return 'de.codequake.linklist.link';
    }
    public function getClassName() {
        return 'linklist\data\link\LinkAction';
    }
    
    public function execute(array $objects, $actionName, array $typeData = array()) {
        if(empty($this->links)) return;
        
        $item = new ClipboardEditorItem();
        switch ($actionName)
        {
            case 'trash':
                //recycle bin

                $linkIDs = array();
                //get all links in clipboard that aren't deleted ;)
                foreach ($this->links as $link) {
                        if (!$link->isDeleted) {
                            $linkIDs[] = $link->linkID;
                        }
                    }
                if (empty($linkIDs)) return null;
                $item->addParameter('objectIDs', $linkIDs);
                $item->addInternalData('confirmMessage', WCF::getLanguage()->getDynamicVariable('wcf.clipboard.item.link.trash.confirmMessage', array('count' => count($linkIDs))));
                $item->addParameter('actionName', 'trash');
                $item->addParameter('className', 'linklist\data\link\LinkAction');
                $item->setName('link.trash');
            break;
            
            case 'restore':
                //restore
                
                $linkIDs = array();
                //get all links in clipboard that are deleted ;)
                foreach ($this->links as $link) {
                        if ($link->isDeleted) {
                            $linkIDs[] = $link->linkID;
                        }
                    }
                if (empty($linkIDs)) return null;
                $item->addParameter('objectIDs', $linkIDs);
                $item->addInternalData('confirmMessage', WCF::getLanguage()->getDynamicVariable('wcf.clipboard.item.link.restore.confirmMessage', array('count' => count($linkIDs))));
                $item->addParameter('actionName', 'restore');
                $item->addParameter('className', 'linklist\data\link\LinkAction');
                $item->setName('link.restore');
            break;
            
            case 'enable':
                //enable
                
                $linkIDs = array();
                //get all links in clipboard that are disabled ;)
                foreach ($this->links as $link) {
                        if (!$link->isActive) {
                            $linkIDs[] = $link->linkID;
                        }
                    }
                if (empty($linkIDs)) return null;
                $item->addParameter('objectIDs', $linkIDs);
                $item->addParameter('actionName', 'enable');
                $item->addParameter('className', 'linklist\data\link\LinkAction');
                $item->setName('link.enable');
            break;
            case 'delete':
                //del
                
                $linkIDs = array();
                //get all links in clipboard that are deleted ;)
                foreach ($this->links as $link) {
                        if ($link->isDeleted) {
                            $linkIDs[] = $link->linkID;
                        }
                    }
                if (empty($linkIDs)) return null;
                $item->addParameter('objectIDs', $linkIDs);
                $item->addInternalData('confirmMessage', WCF::getLanguage()->getDynamicVariable('wcf.clipboard.item.link.delete.confirmMessage', array('count' => count($linkIDs))));
                $item->addParameter('actionName', 'delete');
                $item->addParameter('className', 'linklist\data\link\LinkAction');
                $item->setName('link.delete');
            break;
        }
    }
    


}
