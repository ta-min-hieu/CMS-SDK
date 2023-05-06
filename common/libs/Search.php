<?php

/**
 * All Rights Reserved.
 * Date: 25-Oct-15 4:02 PM
 */

namespace common\libs;

use Solarium\Core\Client\Client;

class Search {

    protected static $config = array(
        'endpoint' => array(
            'localhost' => array(
                'host' => '192.168.146.252',
                'port' => 9583,
                'path' => '/solr/imz4g_wap',
            )
        )
    );
    protected static $configRbt = array(
        'endpoint' => array(
            'localhost' => array(
                'host' => '192.168.146.252',
                'port' => 9583,
                'path' => '/solr/imz4g_wap',
            )
        )
    );

//    protected static $config = array(
//        'endpoint' => array(
//            'localhost' => array(
//                'host' => '10.58.50.19',
//                'port' => 8083,
//                'path' => '/solr/imz4g_wap',
//            )
//        )
//    );
//
//    protected static $configRbt = array(
//        'endpoint' => array(
//            'localhost' => array(
//                'host' => '10.58.50.19',
//                'port' => 8083,
//                'path' => '/solr/bn',
//            )
//        )
//    );

    public function setConfig($conf) {
        $this->config = $conf;
    }

    /**
     *
     * @param $query
     * @param $limit
     */
    public static function searchAll($query, $limit) {
//        var_dump(self::$config);die;
        $client = new Client(self::$config);
        $exec = $client->createQuery($client::QUERY_SELECT);
        $exec->setQuery($query);
        $exec->setRows($limit);
        $resultset = $client->execute($exec);
        foreach ($resultset as $document) {
            $data['full_items'][] = $document;
        }
        $data['total'] = $resultset->getNumFound();
        return $data;
    }

    /**
     *
     * @param $query
     * @param $limit
     * song: query = song_name:"keyword"
     * video: query = video_name:"keyword"
     * playlist: query = album_name:"keyword"
     * singer: query = alias:"keyword"
     */
    public static function searchObject($query, $limit = 0, $offset = 0) {
        $client = new Client(self::$config);
        $exec = $client->createQuery($client::QUERY_SELECT);
        $exec->setQuery($query);
        if ($limit) {
            $exec->setRows($limit);
        }
        if ($offset) {
            $exec->setStart($offset);
        }
        $resultset = $client->execute($exec);
        $data['full_items'] = array();
        foreach ($resultset as $document) {
            $data['full_items'][] = $document;
        }
        $data['total'] = $resultset->getNumFound();
        return $data;
    }

    /**
     *
     * Make a dismax query
     * @param type $filterQueries
     * @param type $pageLimit
     * @param type $pageNumber
     * @return type
     */
    
    public static function searchDismax($filterQueries, $pageLimit = 1, $pageNumber = 1) {

        $client = new Client(self::$configRbt);
        $query = $client->createSelect();
        $dismax = $query->getDisMax();
        $dismax->setQueryFields("huawei_tone_name^3 huawei_singer_name");
        $dismax->setPhraseFields("huawei_tone_name^6 huawei_singer_name^3");
        $dismax->setTie(0.1);

        $query->setQuery($filterQueries);
//        $query->setRows($pageLimit*$pageNumber);
        if ($limit) {
            $query->setRows($limit);
        }
        if ($offset) {
            $query->setStart($offset);
        }

        $resultset = $client->select($query);
        $data = array();
        foreach ($resultset as $document) {
            $data['full_items'][] = $document;
        }
        $data['total'] = $resultset->getNumFound();
        return $data;
    }

    /**
     *
     * Make a dismax query
     * @param type $filterQueries
     * @param type $pageLimit
     * @param type $pageNumber
     * @return type
     */
    public static function searchDismaxSong($filterQueries, $limit = 1, $offset = 0) {

        $client = new Client(self::$config);
        $query = $client->createSelect();
        $dismax = $query->getDisMax();
        $dismax->setQueryFields("song_name^3 singer_alias_song");
        $dismax->setPhraseFields("song_name^6 singer_alias_song^3");
        $dismax->setTie(0.1);

        $query->setQuery($filterQueries);
        if ($limit) {
            $query->setRows($limit);
        }
        if ($offset) {
            $query->setStart($offset);
        }

        $resultset = $client->select($query);
        $data = array();
        foreach ($resultset as $document) {
            $data['full_items'][] = $document;
        }
        $data['total'] = $resultset->getNumFound();
        return $data;
    }

    /**
     *
     * Make a dismax query
     * @param type $filterQueries
     * @param type $pageLimit
     * @param type $pageNumber
     * @return type
     */
    public static function searchDismaxVideo($filterQueries, $limit = 1, $pageNumber = 0) {

        $client = new Client(self::$config);
        $query = $client->createSelect();
        $dismax = $query->getDisMax();
        $dismax->setQueryFields("video_name^3 singer_alias_video");
        $dismax->setPhraseFields("video_name^6 singer_alias_video^3");
        $dismax->setTie(0.1);

        $query->setQuery($filterQueries);
//        $query->setRows($pageLimit*$pageNumber);
        if ($limit) {
            $query->setRows($limit);
        }
        if ($pageNumber) {
            $query->setStart($limit*$pageNumber);
        }

        $resultset = $client->select($query);
        $data = array();
        foreach ($resultset as $document) {
            $data['full_items'][] = $document;
        }
        $data['total'] = $resultset->getNumFound();
        return $data;
    }

    /**
     *
     * Make a dismax query
     * @param type $filterQueries
     * @param type $pageLimit
     * @param type $pageNumber
     * @return type
     */
    public static function searchDismaxAlbum($filterQueries, $limit = 1, $pageNumber = 0) {

        $client = new Client(self::$config);
        $query = $client->createSelect();
        $dismax = $query->getDisMax();
        $dismax->setQueryFields("album_name^3 singer_alias_album");
        $dismax->setPhraseFields("album_name^6 singer_alias_album^3");
        $dismax->setTie(0.1);

        $query->setQuery($filterQueries);
//        $query->setRows($pageLimit*$pageNumber);
        if ($limit) {
            $query->setRows($limit);
        }
         if ($pageNumber) {
            $query->setStart($limit*$pageNumber);
        }

        $resultset = $client->select($query);
        $data = array();
        foreach ($resultset as $document) {
            $data['full_items'][] = $document;
        }
        $data['total'] = $resultset->getNumFound();
        return $data;
    }
    
    
    
    /**
     *
     * Make a dismax query
     * @param type $filterQueries
     * @param type $pageLimit
     * @param type $pageNumber
     * @return type
     */
    public static function searchDismaxSinger($filterQueries, $limit = 1, $pageNumber = 0) {

        $client = new Client(self::$config);
        $query = $client->createSelect();
        $dismax = $query->getDisMax();
        $dismax->setQueryFields("alias");
        $dismax->setTie(0.1);

        $query->setQuery($filterQueries);
//        $query->setRows($pageLimit*$pageNumber);
        if ($limit) {
            $query->setRows($limit);
        }
         if ($pageNumber) {
            $query->setStart($limit*$pageNumber);
        }

        $resultset = $client->select($query);
        $data = array();
        foreach ($resultset as $document) {
            $data['full_items'][] = $document;
        }
        $data['total'] = $resultset->getNumFound();
        return $data;
    }

}
