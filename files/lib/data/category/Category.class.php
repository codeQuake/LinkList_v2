<?php
namespace linklist\data\page;
use cms\data\LINKLISTDatabaseObject;
use wcf\system\WCF;

/** 
 * Represents a category
 *
 * @author  Jens Krumsieck
 * @copyright 2013 Jens Krumsieck
 * @license GNU Lesser Generel Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package de.codequake.linklist
 */
 
class Category extends LINKLISTDatabaseObject{
    /**
     * @see wcf\data\DatabaseObject::$databaseTableName
     */
     protected static $databaseTableName = 'category';
     
     /**
      * @see wcf\data\DatabaseObject::$databaseTableIndexName
      */
      protected static $databaseTableIndexName = 'categoryID';
      
      /**
       * @see wcf\data\DatabaseObject::$__construct()
       */
      public function __construct($id, $row = null, DatabaseObject $object = null){
        if($id !== null){
            $sql = "SELECT  * 
                    FROM    ".static::getDatabaseTableName()."
                    WHERE   ".static::getDatabaseTableIndexName()." = ?";
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute(array($id));
            $row = $statement->fetchArray();
            
            if($row === false) $row = array();
            
        }
        elseif($object !== null) $row = $object->data;
        
        $this->handleData($row);
      }
}