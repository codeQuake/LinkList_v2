<?php
namespace linklist\system\user\activity\point;
use wcf\data\object\type\ObjectType;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\user\activity\point\IUserActivityPointObjectProcessor;
use wcf\system\WCF;

class LinkUserActivityPointObjectProcessor implements IUserActivityPointObjectProcessor{
    public $objectType = null;
    public $limit = 5000;
    
    public function __construct(ObjectType $objectType) {
        $this->objectType = $objectType;
    }

    public function countRequests() {

        $sql = "SELECT  COUNT(*) AS count
            FROM    linklist".WCF_N."_link";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute();
        $row = $statement->fetchArray();
        return ceil($row['count'] / $this->limit) + 1;
    }
    
    public function updateActivityPointEvents($request){
        if ($request == 0) {
            // first request
            $sql = "DELETE FROM	wcf".WCF_N."_user_activity_point_event 
            WHERE   objectTypeID = ?";
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute(array($this->objectType->objectTypeID));
        }
        else{
            //others
            
            //get linkIDs
            $sql = "SELECT link.linkID
                FROM    linklist".WCF_N."_link link
                    AND link.userID IS NOT NULL   
                ORDER BY link.linkID ASC";
            $statement = WCF::getDB()->prepareStatement($sql, $this->limit, ($this->limit * ($request - 1)));
            $statement->execute();
            $linkIDs = array();
            while ($row = $statement->fetchArray()) {
                $linkIDs[] = $row['linkID'];
            }
            
            //if there's no link
            if(empty($linkIDs)) return;
            
            $conditionBuilder = new PreparedStatementConditionBuilder();
            $conditionBuilder->add("objectTypeID = ?", array($this->objectType->objectTypeID));
            $conditionBuilder->add("objectID IN (?)", array($linkIDs));
            
            //kill old values
            $sql = "DELETE FROM	wcf".WCF_N."_user_activity_point_event 
                ".$conditionBuilder;
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute($conditionBuilder->getParameters());
            
            //prepare Uranus
            $conditionBuilder = new PreparedStatementConditionBuilder();
            $conditionBuilder->add("linkID IN (?)", array($linkIDs));
            //as in ReceivedLikesUserActivtityPointObjectProcessor
            $sql = "INSERT INTO 
                    wcf".WCF_N."_user_activity_point_event (userID, objectTypeID, objectID, additionalData)
                    SELECT	userID,
                        ?, 
                        linkID AS objectID,
                        ?
                    FROM	linklist".WCF_N."_link
                    ".$conditionBuilder;
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute(array_merge((array) $this->objectType->objectTypeID, (array) serialize(array()), $conditionBuilder->getParameters()));
        }
    }
}