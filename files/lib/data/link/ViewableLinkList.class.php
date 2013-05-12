<?php
namespace linklist\data\link;

use linklist\data\link\LinkList;

class ViewableLinkList extends LinkList{
    public $decoratorClassName = 'linklist\data\link\ViewableLink';

}