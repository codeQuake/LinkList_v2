<?php
namespace linklist\system\cache\builder;
use wcf\system\cache\builder\AbstractCacheBuilder;
use wcf\system\WCF;


class LinklistStatsCacheBuilder extends AbstractCacheBuilder {

    protected $maxLifetime = 600;

    protected function rebuild(array $parameters) {
        $data = array();

        $sql = "SELECT  COUNT(*) AS amount
            FROM    linklist".WCF_N."_link";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute();
        $data['links'] = $statement->fetchColumn();


        $days = ceil((TIME_NOW - LINKLIST_INSTALL_DATE) / 86400);
        if ($days <= 0) $days = 1;
        $data['postsPerDay'] = $data['links'] / $days;
        return $data;
    }
}
