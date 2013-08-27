<?php
namespace linklist\system\cronjob;
use wcf\data\cronjob\Cronjob;
use wcf\system\cronjob\AbstractCronjob;
use linklist\data\link\LinkEditor;
use linklist\data\link\Link;
use wcf\system\WCF;

class CheckLinksCronjob extends AbstractCronjob{
    
        public function execute(Cronjob $cronjob){
            parent::execute($cronjob);
            
            $sql = "SELECT linkID
                    FROM linklist".WCF_N."_link
                    WHERE isDeleted = ?";
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute(array(0));
            while($row = $statement->fetchArray()){
                $link = new Link($row['linkID']);
                ini_set('user_agent', 'Mozilla/5.0 (codeQuake Linklist V2.0 +http://codequake.de) Firefox 23.0');
                @$headers = get_headers($link->url);
                if($headers != false){
                $code = substr($headers[0], 9, 3);
                    if(intval($code) < 400){
                        $editor = new LinkEditor($link);
                        $editor->update(array(
                                'isOnline' => 1
                         ));
                    }
                else{
                    $editor = new LinkEditor($link);
                    $editor->update(array(
                            'isOnline' => 0
                     ));
                }
                }
                else{
                    $editor = new LinkEditor($link);
                    $editor->update(array(
                            'isOnline' => 0
                     ));
                }
                
            }
        }
}   