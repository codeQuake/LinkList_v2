<?php
namespace linklist\system\moderation;
use linklist\data\link\DeletedLinkList;
use wcf\system\moderation\IDeletedContentProvider;

class DeletedLinkProvider implements IDeletedContentProvider{
    
    public function getObjectList(){
        return new DeletedLinkList();
    }
    
    public function getApplication(){
        return 'linklist';
    }
    
    public function getTemplateName(){
        return 'linksList';
    }
}