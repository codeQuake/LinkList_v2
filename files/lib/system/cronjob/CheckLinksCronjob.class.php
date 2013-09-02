<?php
namespace linklist\system\cronjob;
use wcf\data\cronjob\Cronjob;
use wcf\system\cronjob\AbstractCronjob;
use linklist\data\link\LinkEditor;
use linklist\data\link\Link;
use linklist\system\moderation\queue\ModerationQueueOfflineManager;
use wcf\system\WCF;
use linklist\system\log\modification\LinkModificationLogHandler;

class CheckLinksCronjob extends AbstractCronjob{
    
        public function execute(Cronjob $cronjob){
            parent::execute($cronjob);
            
            $sql = "SELECT linkID
                    FROM linklist".WCF_N."_link
                    WHERE isDeleted = ?";
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute(array(0));
            $mh = curl_multi_init();
            $handles = array();
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
                $handles[] = array('linkID' => $link->linkID, 'handle' => $ch);
            }
            foreach ($handles as $handle){
                curl_multi_add_handle($mh, $handle['handle']);
            }
            
            $running = null;
            do{
                curl_multi_exec($mh, $running);
            } while ($running);
            foreach($handles as $handle){
                $codes[] = array('linkID' => $handle['linkID'], 'code' => curl_getinfo($handle['handle'], CURLINFO_HTTP_CODE));
                    
                curl_multi_remove_handle($mh, $handle['handle']);
            }
            curl_multi_close($mh);
            
            foreach($codes as $code){
                if($code['code'] >=200 && $code['code'] < 400){
                     $link = new Link($code['linkID']);
                     $editor = new LinkEditor($link);
                     $editor->update(array('isOnline' => 1));
                }
                else{
                    $link = new Link($code['linkID']);
                    $editor = new LinkEditor($link);
                    $editor->update(array('isOnline' => 0));
                    ModerationQueueOfflineManager::getInstance()->addModeratedContent('de.codequake.linklist.link', $link->linkID);
                    LinkModificationLogHandler::getInstance()->setOffline($link);
                }
              }
            
            
        }
}   