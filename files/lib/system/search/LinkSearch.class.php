<?php
namespace linklist\system\search;

use wcf\system\search\AbstractSearchableObjectType;
use linklist\data\link\SearchResultLinkList;
use linklist\data\category\LinklistCategoryNodeTree;
use wcf\form\IForm;
use wcf\util\ArrayUtil;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\exception\UserInputException;
use wcf\system\category\CategoryHandler;
use wcf\system\WCF;

class LinkSearch extends AbstractSearchableObjectType{
    
    public $messageCache = array();
    public $categoryIDs =array();
    public $categories = array();
    public $selectedCategories = array();
    public $findLinks = 1;
    public $objectTypeName = 'de.codequake.linklist';
    
    public function cacheObjects(array $objectIDs, array $additionalData = null){
        $linklist = new SearchResultLinkList();
        $linklist->getConditionBuilder()->add('link.linkID IN (?)', array($objectIDs));
        $linklist->readObjects();
        foreach($linklist->getObjects() as $link){
            $this->messageCache[$link->linkID] = $link;
        }
    }
    
    public function getObject($objectID){
        if(isset($this->messageCache[$objectID])) return $this->messageCache[$objectID];
        return null;
    }
    
    public function getAdditionalData(){
        return array('categoryIDs' => $this->categoryIDs,
                    'findLinks' => $this->findLinks);
    }
    
    public function getTableName(){
        return 'linklist'.WCF_N."_link";
    }
    
    public function getIDFieldName(){
        return $this->getTableName().'.linkID';
    }
    
    public function getSubjectFieldName(){
        return $this->getTableName().'.subject';
    }   
    
    public function getUsernameFieldName(){
        return $this->getTableName().'.username';
    }
    
    public function getTimeFieldName(){
        return $this->getTableName().'.time';
    }
    
    public function getFormTemplateName(){
        return 'linkSearch';
    }
    
    public function getApplication(){
        return 'linklist';
    }
    
    public function show(IForm $form = null){
        $nodeTree = new LinklistCategoryNodeTree($this->objectTypeName);
        $nodeList = $nodeTree->getIterator();
        
        if($form !== null && isset($form->searchData['additionalData']['link'])){
            $this->linkIDs = $form->searchData['additionalData']['link']['categoryIDs'];
            
        }
        
        WCF::getTPL()->assign(array('linkIDS' => $his->linkIDs,
                                    'selectAllCategories' => count($this->categoryIDs) == 0 || $this->categoryIDs[0] == '*',
                                    'findLinks' => $this->findLinks(),
                                    'nodeList' => $nodeList));
    }
    
    public function readFormParameters(IForm $form = null){
        //current
        if($form !== null && isset($form->searchData['additionalData']['link'])) {
            $this->categoryIDs = $form->searchData['additionalData']['link']['categoryIDs'];
        }
        
        //new pewpew
        if(isset($_POST['categoryIDs']) && is_array($_POST['categoryIDs'])) $this->categoryIDs = ArrayUtil::toIntegerArray($_POST['categoryIDs']);
        
        if(isset($_POST['findLinks'])) $this->findLinks = intval($_POST['findLinks']);
        
        
    }
    
    public function getConditions(IForm $form = null){
        $conditionBuilder = new PreparedStatementConditionBuilder();
        $this->readFormParameters($form);
        
        $categoryIDs = $this->categoryIDs;
        if(count($categoryIDs) && $categoryIDs[0] == '*') $categoryIDs = array();
        
        foreach($categoryIDs as $key => $categoryID){
            if($categoryID == '-') unset($categoryIDs[$key]);
        }
        
        $this->categories = CategoryHandler::getInstance()->getCategories($this->objectTypeName);
        $this->selectedCategories = array();
        
        foreach($categoryIDs as $key => $categoryID){
            if(! isset($this->categories[$categoryID])) {
                throw new UserInputException('categoryIDs', 'notValid');
            }
            if(! isset($this->selectedCategories[$categoryID])) {
                $this->selectedCategories[$categoryID] = $this->categories[$categoryID];
            }
        }
        
        $categoryIDs = array();
        if(count($this->selectedCategories) != $this->categories){
            foreach($this->selectedCategories as $category){
                $categoryIDs[] = $category->categoryID;
            }
        }
        
        if(count($categoryIDs)) {
            $conditionBuilder->add($this->getTableName().'.categoryID IN (?)', array (
            $categoryIDs
            ));
        }
        
        if(count(WCF::getUser()->getLanguageIDs())){
            $conditionBuilder->add('('.$this->getTableName().'.languageID IN (?) OR '.$this->getTableName().'.languageID IS NULL)', array(WCF::getUser()->getLanguageIDs()));
            
        }
        return $conditionBuilder;
    }   
}