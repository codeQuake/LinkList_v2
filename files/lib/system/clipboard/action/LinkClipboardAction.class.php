<?php
namespace linklist\system\clipboard\action;
use wcf\data\clipboard\action\ClipboardAction;
use wcf\system\WCF;
use wcf\system\clipboard\action\AbstractClipboardAction;

class LinkClipboardAction extends AbstractClipboardAction{

    public $links = null;
    protected $actionClassActions = array('trash', 'restore', 'delete', 'enable');
    protected $supportedActions = array('trash', 'delete', 'restore', 'enable');
    
    public function execute(array $objects, ClipboardAction $action) {
        $item = parent::execute($objects, $action);
        if ($item === null) return null;
        
        $this->links = $objects;
        switch ($action->actionName)
        {
            case 'trash':
                $item->addParameter('objectIDs', array_keys($this->links));
                $item->addInternalData('confirmMessage', WCF::getLanguage()->getDynamicVariable('wcf.clipboard.item.de.codequake.linklist.link.trash.confirmMessage', array('count' => $item->getCount())));
                $item->addParameter('className', $this->getClassName());
                $item->setName('de.codequake.linklist.link.trash');
            break;
            
            case 'restore':
                $item->addParameter('objectIDs', array_keys($this->links));
                $item->addInternalData('confirmMessage', WCF::getLanguage()->getDynamicVariable('wcf.clipboard.item.de.codequake.linklist.link.restore.confirmMessage', array('count' => $item->getCount())));
                $item->addParameter('className', $this->getClassName());
                $item->setName('de.codequake.linklist.link.restore');
            break;
            
            case 'enable':
                $item->addParameter('objectIDs', array_keys($this->links));
                $item->addInternalData('confirmMessage', WCF::getLanguage()->getDynamicVariable('wcf.clipboard.item.de.codequake.linklist.link.enable.confirmMessage', array('count' => $item->getCount())));
                $item->addParameter('className', $this->getClassName());
                $item->setName('de.codequake.linklist.link.enable');
            break;
            case 'delete':
                $item->addParameter('objectIDs', array_keys($this->links));
                $item->addInternalData('confirmMessage', WCF::getLanguage()->getDynamicVariable('wcf.clipboard.item.de.codequake.linklist.link.delete.confirmMessage', array('count' => $item->getCount())));
                $item->addParameter('className', $this->getClassName());
                $item->setName('de.codequake.linklist.link.delete');
            break;
            }
            return $item;
        
    }
    
    
    public function getTypeName() {
            return 'de.codequake.linklist.link';
    }
    public function getClassName() {
        return 'linklist\data\link\LinkAction';
    }
   


}
