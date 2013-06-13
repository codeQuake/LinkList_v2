<?php
namespace linklist\system\sitemap;
use linklist\data\category\LinklistCategoryNodeTree;
use wcf\system\sitemap\ISitemapProvider;
use wcf\system\WCF;

class LinklistCategorySitemapProvider implements ISitemapProvider{
    
    public $objectTypeName = 'de.codequake.linklist.category';
    
    public function getTemplate(){
        $nodeTree = new LinklistCategoryNodeTree($this->objectTypeName);
        $nodeList = $nodeTree->getIterator();
        
        WCF::getTPL()->assign(array('nodeList' => $nodeList));
        
        return WCF::getTPL()->fetch('linkSitemap','linklist');
    }
}