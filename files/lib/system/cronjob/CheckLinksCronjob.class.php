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
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $link->url);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:23.0) Gecko/20100101 Firefox/23.0');
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch,CURLOPT_VERBOSE,false);
                curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch,CURLOPT_SSLVERSION,3);
                curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, false);
                $page = curl_exec($ch);
                $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if($code >=200 && $code < 400){
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
        }
}   