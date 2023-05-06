<?php

namespace backend\models;

use Yii;
use yii\db\Query;

class ThreadChatView
{
    function getTotal($start_date, $end_date, $queue_name)
    {
        $db = Yii::$app->dbsdk;
        $dbn = substr($db->dsn, (strpos($db->dsn, 'dbname=')) + 7);

        $sql = "SELECT * FROM ((select distinct date(created_at) as date_check FROM {$dbn}.thread_chat_view 
        where date(created_at) >= :start_date and date(created_at) <= :end_date ) dsdate
        left join (select count(*) as count, date_check_1 FROM (select date(created_at) as date_check_1 FROM {$dbn}.thread_chat_view where 
        queue_name = :queue_name and
        date(created_at) >= :start_date and date(created_at) <= :end_date) ds group by date_check_1) 
        dsthread on dsthread.date_check_1 = dsdate.date_check)";

        $result = $db->createCommand($sql)
            ->bindValue(':queue_name', $queue_name)
            ->bindValue(':start_date', $start_date)
            ->bindValue(':end_date', $end_date)
            ->queryAll();

        return $result;
    }

    public function getMissing($start_date, $end_date, $queue_name)
    {
        $db = Yii::$app->dbsdk;
        $dbn = substr($db->dsn, (strpos($db->dsn, 'dbname=')) + 7);

        $sql = "SELECT date(created_at) as date_check,count(thread_id) FROM {$dbn}.missing_thread 
        where queue_name = :queue_name and date(created_at)>=:start_date and date(created_at) <=:end_date
        group by date_check 
        order by date_check asc;";

        $result = $db->createCommand($sql)
            ->bindValue(':queue_name', $queue_name."@queue.vnpost")
            ->bindValue(':start_date', $start_date)
            ->bindValue(':end_date', $end_date)
            ->queryAll();

        return $result;
    }

    public function getDone($start_date, $end_date, $queue_name)
    {
        $db = Yii::$app->dbsdk;
        $dbn = substr($db->dsn, (strpos($db->dsn, 'dbname=')) + 7);

        $sql = "SELECT * from ((select distinct date(created_at) as date_check from {$dbn}.thread_chat_view 
        where date(created_at)>=:start_date and date(created_at) <=:end_date) dsdate
        left join (select count(*) as count, date_check_1 from (select date(created_at) as date_check_1 from {$dbn}.thread_chat_view where 
        queue_name = :queue_name and status = 'done' and
        date(created_at)>=:start_date and date(created_at)<=:end_date ) ds group by date_check_1) 
        dsthread on dsthread.date_check_1 = dsdate.date_check) ;";

        $result = $db->createCommand($sql)
            ->bindValue(':queue_name', $queue_name)
            ->bindValue(':start_date', $start_date)
            ->bindValue(':end_date', $end_date)
            ->queryAll();

        return $result;
    }

    public function getProcessing($start_date, $end_date, $queue_name)
    {
        $db = Yii::$app->dbsdk;
        $dbn = substr($db->dsn, (strpos($db->dsn, 'dbname=')) + 7);

        $sql = "SELECT * from ((select distinct date(created_at) as date_check from {$dbn}.thread_chat_view 
        where date(created_at)>=:start_date and date(created_at) <=:end_date) dsdate
        left join (select count(*) as count, date_check_1 from (select date(created_at) as date_check_1 from {$dbn}.thread_chat_view where 
        queue_name = :queue_name and status = 'processing' and
        date(created_at)>=:start_date and date(created_at)<=:end_date ) ds group by date_check_1) 
        dsthread on dsthread.date_check_1 = dsdate.date_check) ;";

        $result = $db->createCommand($sql)
            ->bindValue(':queue_name', $queue_name)
            ->bindValue(':start_date', $start_date)
            ->bindValue(':end_date', $end_date)
            ->queryAll();

        return $result;
    }

    public function getAgent($start_date, $end_date, $queue_name)
    {
        $db = Yii::$app->dbsdk;
        $dbn = substr($db->dsn, (strpos($db->dsn, 'dbname=')) + 7);

        $sql = "SELECT * from ((select distinct date(created_at) as date_check from {$dbn}.thread_chat_view 
        where date(created_at)>=:start_date and date(created_at) <=:end_date) dsdate
        left join (select count(distinct(agent)) as count, date_check_1 from (select date(created_at) as date_check_1, agent from {$dbn}.thread_chat_view 
        where agent is not null and queue_name =:queue_name
        and date(created_at)>=:start_date and date(created_at)<=:end_date ) dsthread group by date_check_1) 
        dsthread on dsthread.date_check_1 = dsdate.date_check) ;";

        $result = $db->createCommand($sql)
            ->bindValue(':queue_name', $queue_name)
            ->bindValue(':start_date', $start_date)
            ->bindValue(':end_date', $end_date)
            ->queryAll();

        return $result;
    }
}
