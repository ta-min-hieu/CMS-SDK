<?php

namespace backend\models;

use Yii;
use yii\db\Query;

class ReportSDK {
    public static function getAllDepartmentPSC($id_province, $start_date, $end_date) {
        $command = Yii::$app->dbsdk;
        $dbn = substr($command->dsn, (strpos($command->dsn, 'dbname=')) + 7);
        $query = new Query();
        $result = $query->select([
            'COUNT(DISTINCT username) as distinct_user_count',
            'id_province',
            'id_department',
            'type_user',
        ])
        ->from($dbn.'.acc_chat_daily')
        ->where([
            'id_province' => $id_province,
        ])
        ->andWhere(['between', 'DATE(created_at)', $start_date, $end_date])
        ->groupBy(['id_province', 'id_department', 'type_user'])
        ->orderBy(['id_province' => SORT_ASC, 'type_user' => SORT_ASC])
        ->all();
    
        return $result;
    }

    public static function getDepartmentPSC($start_date, $end_date, $id_department) {
        $command = Yii::$app->dbsdk;
        $dbn = substr($command->dsn, (strpos($command->dsn, 'dbname=')) + 7);
        $query = new Query();
        $result = $query->select([
            'COUNT(DISTINCT username) as distinct_user_count',
            'id_province',
            'id_department',
            'type_user',
        ])
        ->from($dbn.'.acc_chat_daily')
        ->where([
            'id_department' => $id_department,
        ])
        ->andWhere(['between', 'DATE(created_at)', $start_date, $end_date])
        ->groupBy(['id_province', 'id_department', 'type_user'])
        ->orderBy(['id_province' => SORT_ASC, 'type_user' => SORT_ASC])
        ->all();
    
        return $result;
    }

    public static function getAllDepartmentNew($id_province, $start_date, $end_date)
    {
        $db = Yii::$app->dbsdk;
        $dbn = substr($db->dsn, (strpos($db->dsn, 'dbname=')) + 7);

        $sql = "SELECT id_province,province,id_department,sum(slm_acc_op_kh),sum(slm_acc_op_bt),sum(slm_acc_op_nvt),sum(slm_acc_op_cskh),sum(slm_acc_op_ccp),
        sum(slm_chat_bc_kh_bt),sum(slm_chat_bc_bt_kh),sum(slm_chat_bc_kh_bct_tl),sum(slm_chat_bc_kh_bct_ktl),
        sum(slm_chat_bc_kh_bcp_tl),sum(slm_chat_bc_kh_bcp_ktl),
        sum(slm_chat_cskh_tl),sum(slm_chat_cskh_ktl),
        sum(slm_chat_ccp_tl),sum(slm_chat_ccp_ktl)
        from {$dbn}.total_stat where date(report_date) between :start_date and :end_date 
        and id_province = :id_province
        group by id_province,province,id_department
        order by province asc;";

        $result = $db->createCommand($sql)
            ->bindValue(':id_province', $id_province)
            ->bindValue(':start_date', $start_date)
            ->bindValue(':end_date', $end_date)
            ->queryAll();

        return $result;
    }

    public static function getDepartmentNew($id_department, $start_date, $end_date)
    {
        $db = Yii::$app->dbsdk;
        $dbn = substr($db->dsn, (strpos($db->dsn, 'dbname=')) + 7);

        $sql = "SELECT id_province,province,id_department,sum(slm_acc_op_kh),sum(slm_acc_op_bt),sum(slm_acc_op_nvt),sum(slm_acc_op_cskh),sum(slm_acc_op_ccp),
        sum(slm_chat_bc_kh_bt),sum(slm_chat_bc_bt_kh),sum(slm_chat_bc_kh_bct_tl),sum(slm_chat_bc_kh_bct_ktl),
        sum(slm_chat_bc_kh_bcp_tl),sum(slm_chat_bc_kh_bcp_ktl),
        sum(slm_chat_cskh_tl),sum(slm_chat_cskh_ktl),
        sum(slm_chat_ccp_tl),sum(slm_chat_ccp_ktl)
        from {$dbn}.total_stat where date(report_date) between :start_date and :end_date 
        and id_department = :id_department
        group by id_province,province
        order by province asc;";

        $result = $db->createCommand($sql)
            ->bindValue(':id_department', $id_department)
            ->bindValue(':start_date', $start_date)
            ->bindValue(':end_date', $end_date)
            ->queryAll();

        return $result;
    }

    public static function getAllProvinceNew($start_date, $end_date)
    {
        $db = Yii::$app->dbsdk;
        $dbn = substr($db->dsn, (strpos($db->dsn, 'dbname=')) + 7);

        $sql = "SELECT id_province,province,id_department,sum(slm_acc_op_kh),sum(slm_acc_op_bt),sum(slm_acc_op_nvt),sum(slm_acc_op_cskh),sum(slm_acc_op_ccp),
        sum(slm_chat_bc_kh_bt),sum(slm_chat_bc_bt_kh),sum(slm_chat_bc_kh_bct_tl),sum(slm_chat_bc_kh_bct_ktl),
        sum(slm_chat_bc_kh_bcp_tl),sum(slm_chat_bc_kh_bcp_ktl),
        sum(slm_chat_cskh_tl),sum(slm_chat_cskh_ktl),
        sum(slm_chat_ccp_tl),sum(slm_chat_ccp_ktl)
        from {$dbn}.total_stat where date(report_date) between :start_date and :end_date
        and id_department is null 
        group by id_province,province,id_department
        order by province asc;";

        $result = $db->createCommand($sql)
            ->bindValue(':start_date', $start_date)
            ->bindValue(':end_date', $end_date)
            ->queryAll();

        return $result;
    }

    public static function getAllProvince($start_date, $end_date, $dbn) {
        // var_dump($dbn);die();
        $query = new Query();
        $result = $query->select([
            'COUNT(DISTINCT username) as distinct_user_count',
            'id_province',
            'type_user',
        ])
        ->from($dbn.'.acc_chat_daily')
        ->where(['between', 'DATE(created_at)', $start_date, $end_date])
        ->groupBy(['id_province', 'type_user'])
        ->orderBy(['id_province' => SORT_ASC, 'type_user' => SORT_ASC])
        ->all();

        return $result;
    }

    public static function getProvince($start_date, $end_date, $id_province) {
        $command = Yii::$app->dbsdk;
        $dbn = substr($command->dsn, (strpos($command->dsn, 'dbname=')) + 7);
        $query = new Query();
        $result = $query->select([
            'COUNT(DISTINCT username) as distinct_user_count',
            'id_province',
            'type_user',
        ])
        ->from($dbn.'.acc_chat_daily')
        ->where([
            'id_province' => $id_province,
        ])
        ->andWhere(['between', 'DATE(created_at)', $start_date, $end_date])
        ->groupBy(['id_province', 'type_user'])
        ->orderBy(['id_province' => SORT_ASC, 'type_user' => SORT_ASC])
        ->all();
    
        return $result;
    }

    public function searchSldSlc($id_province, $id_department, $date_start, $date_end){
        $result = array();
        if($id_department != null){
            $result = $this->processSldSlc($this->searchSldDepartment($id_department, $date_start),
            $this->searchSlcDepartment($id_department, $date_end));
        }
        elseif($id_province != null && $id_department == null){
            $result = $this->processSldSlc($this->searchSldAllDepartment($id_province, $date_start),
            $this->searchSlcAllDepartment($id_province, $date_end));
        }
        elseif($id_province == null && $id_department == null){
            $result = $this->processSldSlc($this->searchSldAllProvince($date_start),
            $this->searchSlcAllProvince($date_end), 1);
        }
        else
            $result = null;
        return $result;
    }

    public function processSldSlc($sld, $slc, $check = null){
        $result = array();
        //SLD
        for ($i = 0; $i < count($sld); $i++) {
            $result[$i]['id_province'] = $sld[$i]['id_province'];
            $result[$i]['province'] = $sld[$i]['province'];
            $result[$i]['id_department'] = $sld[$i]['id_department'];
            $result[$i]['sld_acc_op_kh'] = $sld[$i]['sld_acc_op_kh'];
            $result[$i]['sld_acc_op_bt'] = $sld[$i]['sld_acc_op_bt'];
            $result[$i]['sld_acc_op_nvt'] = $sld[$i]['sld_acc_op_nvt'];
            $result[$i]['sld_acc_op_cskh'] = $sld[$i]['sld_acc_op_cskh'];
            $result[$i]['sld_acc_op_ccp'] = $sld[$i]['sld_acc_op_ccp'];
            $result[$i]['sld_ts_dm'] = $sld[$i]['sld_acc_op_kh'] + $sld[$i]['sld_acc_op_bt'] + $sld[$i]['sld_acc_op_nvt'] + $sld[$i]['sld_acc_op_cskh'] + $sld[$i]['sld_acc_op_ccp'];
            $result[$i]['sld_acc_chat_kh'] = $sld[$i]['sld_acc_chat_kh'];
            $result[$i]['sld_acc_chat_bt'] = $sld[$i]['sld_acc_chat_bt'];
            $result[$i]['sld_acc_chat_nvt'] = $sld[$i]['sld_acc_chat_nvt'];
            $result[$i]['sld_acc_chat_cskh'] = $sld[$i]['sld_acc_chat_cskh'];
            $result[$i]['sld_acc_chat_ccp'] = $sld[$i]['sld_acc_chat_ccp'];
            $result[$i]['sld_ts_psc_acc'] = $sld[$i]['sld_acc_chat_kh'] + $sld[$i]['sld_acc_chat_bt'] + $sld[$i]['sld_acc_chat_nvt'] + $sld[$i]['sld_acc_chat_cskh'] + $sld[$i]['sld_acc_chat_ccp'];
            $result[$i]['sld_ts_psc_tl'] = ($result[$i]['sld_ts_dm'] == 0) ? "0.00" : number_format(($result[$i]['sld_ts_psc_acc'] / $result[$i]['sld_ts_dm']) * 100, 2);
            $result[$i]['sld_chat_bc_kh_bt'] = $sld[$i]['sld_chat_bc_kh_bt'];
            $result[$i]['sld_chat_bc_bt_kh'] = $sld[$i]['sld_chat_bc_bt_kh'];
            $result[$i]['sld_chat_bc_kh_bct_tl'] = $sld[$i]['sld_chat_bc_kh_bct_tl'];
            $result[$i]['sld_chat_bc_kh_bct_ktl'] = $sld[$i]['sld_chat_bc_kh_bct_ktl'];
            $result[$i]['sld_ts_pc_kh_bct'] = ($sld[$i]['sld_chat_bc_kh_bct_tl'] + $sld[$i]['sld_chat_bc_kh_bct_ktl'] == 0) ? "0.00" : number_format($sld[$i]['sld_chat_bc_kh_bct_tl'] / ($sld[$i]['sld_chat_bc_kh_bct_tl'] + $sld[$i]['sld_chat_bc_kh_bct_ktl']) * 100, 2);
            $result[$i]['sld_chat_bc_kh_bcp_tl'] = $sld[$i]['sld_chat_bc_kh_bcp_tl'];
            $result[$i]['sld_chat_bc_kh_bcp_ktl'] = $sld[$i]['sld_chat_bc_kh_bcp_ktl'];
            $result[$i]['sld_ts_pc_kh_bcp'] = ($sld[$i]['sld_chat_bc_kh_bcp_tl'] + $sld[$i]['sld_chat_bc_kh_bcp_ktl'] == 0) ? "0.00" : number_format($sld[$i]['sld_chat_bc_kh_bcp_tl'] / ($sld[$i]['sld_chat_bc_kh_bcp_tl'] + $sld[$i]['sld_chat_bc_kh_bcp_ktl']) * 100, 2);
            $result[$i]['sld_chat_cskh_tl'] = $sld[$i]['sld_chat_cskh_tl'];
            $result[$i]['sld_chat_cskh_ktl'] = $sld[$i]['sld_chat_cskh_ktl'];
            $result[$i]['sld_ts_pc_kh_cskh'] = ($sld[$i]['sld_chat_cskh_tl'] + $sld[$i]['sld_chat_cskh_ktl'] == 0) ? "0.00" : number_format($sld[$i]['sld_chat_cskh_tl'] / ($sld[$i]['sld_chat_cskh_tl'] + $sld[$i]['sld_chat_cskh_ktl']) * 100, 2);
            $result[$i]['sld_chat_ccp_tl'] = $sld[$i]['sld_chat_ccp_tl'];
            $result[$i]['sld_chat_ccp_ktl'] = $sld[$i]['sld_chat_ccp_ktl'];
            $result[$i]['sld_ts_pc_kh_bh'] = ($sld[$i]['sld_chat_ccp_tl'] + $sld[$i]['sld_chat_ccp_ktl'] == 0) ? "0.00" : number_format($sld[$i]['sld_chat_ccp_tl'] / ($sld[$i]['sld_chat_ccp_tl'] + $sld[$i]['sld_chat_ccp_ktl']) * 100, 2);
        }

        //SLC
        if($check == 1){
            for ($i = 0; $i < count($result); $i++) {
                for ($j = 0; $j < count($slc); $j++) {
                    if ($result[$i]['id_province'] == $slc[$j]['id_province']) {
                        $result[$i]['slc_acc_op_kh'] = $slc[$j]['slc_acc_op_kh'];
                        $result[$i]['slc_acc_op_bt'] = $slc[$j]['slc_acc_op_bt'];
                        $result[$i]['slc_acc_op_nvt'] = $slc[$j]['slc_acc_op_nvt'];
                        $result[$i]['slc_acc_op_cskh'] = $slc[$j]['slc_acc_op_cskh'];
                        $result[$i]['slc_acc_op_ccp'] = $slc[$j]['slc_acc_op_ccp'];
                        $result[$i]['slc_ts_dm'] = $slc[$j]['slc_acc_op_kh'] + $slc[$j]['slc_acc_op_bt'] + $slc[$j]['slc_acc_op_nvt'] + $slc[$j]['slc_acc_op_cskh'] + $slc[$j]['slc_acc_op_ccp'];
                        $result[$i]['slc_acc_chat_kh'] = $slc[$j]['slc_acc_chat_kh'];
                        $result[$i]['slc_acc_chat_bt'] = $slc[$j]['slc_acc_chat_bt'];
                        $result[$i]['slc_acc_chat_nvt'] = $slc[$j]['slc_acc_chat_nvt'];
                        $result[$i]['slc_acc_chat_cskh'] = $slc[$j]['slc_acc_chat_cskh'];
                        $result[$i]['slc_acc_chat_ccp'] = $slc[$j]['slc_acc_chat_ccp'];
                        $result[$i]['slc_ts_psc_acc'] = $slc[$j]['slc_acc_chat_kh'] + $slc[$j]['slc_acc_chat_bt'] + $slc[$j]['slc_acc_chat_nvt'] + $slc[$j]['slc_acc_chat_cskh'] + $slc[$j]['slc_acc_chat_ccp'];
                        $result[$i]['slc_ts_psc_tl'] = ($result[$i]['slc_ts_dm'] == 0) ? "0.00" : number_format(($result[$i]['slc_ts_psc_acc'] / $result[$i]['slc_ts_dm']) * 100, 2);
                        $result[$i]['slc_chat_bc_kh_bt'] = $slc[$j]['slc_chat_bc_kh_bt'];
                        $result[$i]['slc_chat_bc_bt_kh'] = $slc[$j]['slc_chat_bc_bt_kh'];
                        $result[$i]['slc_chat_bc_kh_bct_tl'] = $slc[$j]['slc_chat_bc_kh_bct_tl'];
                        $result[$i]['slc_chat_bc_kh_bct_ktl'] = $slc[$j]['slc_chat_bc_kh_bct_ktl'];
                        $result[$i]['slc_ts_pc_kh_bct'] = ($slc[$j]['slc_chat_bc_kh_bct_tl'] + $slc[$j]['slc_chat_bc_kh_bct_ktl'] == 0) ? "0.00" : number_format($slc[$j]['slc_chat_bc_kh_bct_tl'] / ($slc[$j]['slc_chat_bc_kh_bct_tl'] + $slc[$j]['slc_chat_bc_kh_bct_ktl']) * 100, 2);
                        $result[$i]['slc_chat_bc_kh_bcp_tl'] = $slc[$j]['slc_chat_bc_kh_bcp_tl'];
                        $result[$i]['slc_chat_bc_kh_bcp_ktl'] = $slc[$j]['slc_chat_bc_kh_bcp_ktl'];
                        $result[$i]['slc_ts_pc_kh_bcp'] = ($slc[$j]['slc_chat_bc_kh_bcp_tl'] + $slc[$j]['slc_chat_bc_kh_bcp_ktl'] == 0) ? "0.00" : number_format($slc[$j]['slc_chat_bc_kh_bcp_tl'] / ($slc[$j]['slc_chat_bc_kh_bcp_tl'] + $slc[$j]['slc_chat_bc_kh_bcp_ktl']) * 100, 2);
                        $result[$i]['slc_chat_cskh_tl'] = $slc[$j]['slc_chat_cskh_tl'];
                        $result[$i]['slc_chat_cskh_ktl'] = $slc[$j]['slc_chat_cskh_ktl'];
                        $result[$i]['slc_ts_pc_kh_cskh'] = ($slc[$j]['slc_chat_cskh_tl'] + $slc[$j]['slc_chat_cskh_ktl'] == 0) ? "0.00" : number_format($slc[$j]['slc_chat_cskh_tl'] / ($slc[$j]['slc_chat_cskh_tl'] + $slc[$j]['slc_chat_cskh_ktl']) * 100, 2);
                        $result[$i]['slc_chat_ccp_tl'] = $slc[$j]['slc_chat_ccp_tl'];
                        $result[$i]['slc_chat_ccp_ktl'] = $slc[$j]['slc_chat_ccp_ktl'];
                        $result[$i]['slc_ts_pc_kh_bh'] = ($slc[$j]['slc_chat_ccp_tl'] + $slc[$j]['slc_chat_ccp_ktl'] == 0) ? "0.00" : number_format($slc[$j]['slc_chat_ccp_tl'] / ($slc[$j]['slc_chat_ccp_tl'] + $slc[$j]['slc_chat_ccp_ktl']) * 100, 2);
                        break;
                    }
                }
            }
        } else {
            for ($i = 0; $i < count($result); $i++) {
                for ($j = 0; $j < count($slc); $j++) {
                    if ($result[$i]['id_department'] == $slc[$j]['id_department']) {
                        $result[$i]['slc_acc_op_kh'] = $slc[$j]['slc_acc_op_kh'];
                        $result[$i]['slc_acc_op_bt'] = $slc[$j]['slc_acc_op_bt'];
                        $result[$i]['slc_acc_op_nvt'] = $slc[$j]['slc_acc_op_nvt'];
                        $result[$i]['slc_acc_op_cskh'] = $slc[$j]['slc_acc_op_cskh'];
                        $result[$i]['slc_acc_op_ccp'] = $slc[$j]['slc_acc_op_ccp'];
                        $result[$i]['slc_ts_dm'] = $slc[$j]['slc_acc_op_kh'] + $slc[$j]['slc_acc_op_bt'] + $slc[$j]['slc_acc_op_nvt'] + $slc[$j]['slc_acc_op_cskh'] + $slc[$j]['slc_acc_op_ccp'];
                        $result[$i]['slc_acc_chat_kh'] = $slc[$j]['slc_acc_chat_kh'];
                        $result[$i]['slc_acc_chat_bt'] = $slc[$j]['slc_acc_chat_bt'];
                        $result[$i]['slc_acc_chat_nvt'] = $slc[$j]['slc_acc_chat_nvt'];
                        $result[$i]['slc_acc_chat_cskh'] = $slc[$j]['slc_acc_chat_cskh'];
                        $result[$i]['slc_acc_chat_ccp'] = $slc[$j]['slc_acc_chat_ccp'];
                        $result[$i]['slc_ts_psc_acc'] = $slc[$j]['slc_acc_chat_kh'] + $slc[$j]['slc_acc_chat_bt'] + $slc[$j]['slc_acc_chat_nvt'] + $slc[$j]['slc_acc_chat_cskh'] + $slc[$j]['slc_acc_chat_ccp'];
                        $result[$i]['slc_ts_psc_tl'] = ($result[$i]['slc_ts_dm'] == 0) ? "0.00" : number_format(($result[$i]['slc_ts_psc_acc'] / $result[$i]['slc_ts_dm']) * 100, 2);
                        $result[$i]['slc_chat_bc_kh_bt'] = $slc[$j]['slc_chat_bc_kh_bt'];
                        $result[$i]['slc_chat_bc_bt_kh'] = $slc[$j]['slc_chat_bc_bt_kh'];
                        $result[$i]['slc_chat_bc_kh_bct_tl'] = $slc[$j]['slc_chat_bc_kh_bct_tl'];
                        $result[$i]['slc_chat_bc_kh_bct_ktl'] = $slc[$j]['slc_chat_bc_kh_bct_ktl'];
                        $result[$i]['slc_ts_pc_kh_bct'] = ($slc[$j]['slc_chat_bc_kh_bct_tl'] + $slc[$j]['slc_chat_bc_kh_bct_ktl'] == 0) ? "0.00" : number_format($slc[$j]['slc_chat_bc_kh_bct_tl'] / ($slc[$j]['slc_chat_bc_kh_bct_tl'] + $slc[$j]['slc_chat_bc_kh_bct_ktl']) * 100, 2);
                        $result[$i]['slc_chat_bc_kh_bcp_tl'] = $slc[$j]['slc_chat_bc_kh_bcp_tl'];
                        $result[$i]['slc_chat_bc_kh_bcp_ktl'] = $slc[$j]['slc_chat_bc_kh_bcp_ktl'];
                        $result[$i]['slc_ts_pc_kh_bcp'] = ($slc[$j]['slc_chat_bc_kh_bcp_tl'] + $slc[$j]['slc_chat_bc_kh_bcp_ktl'] == 0) ? "0.00" : number_format($slc[$j]['slc_chat_bc_kh_bcp_tl'] / ($slc[$j]['slc_chat_bc_kh_bcp_tl'] + $slc[$j]['slc_chat_bc_kh_bcp_ktl']) * 100, 2);
                        $result[$i]['slc_chat_cskh_tl'] = $slc[$j]['slc_chat_cskh_tl'];
                        $result[$i]['slc_chat_cskh_ktl'] = $slc[$j]['slc_chat_cskh_ktl'];
                        $result[$i]['slc_ts_pc_kh_cskh'] = ($slc[$j]['slc_chat_cskh_tl'] + $slc[$j]['slc_chat_cskh_ktl'] == 0) ? "0.00" : number_format($slc[$j]['slc_chat_cskh_tl'] / ($slc[$j]['slc_chat_cskh_tl'] + $slc[$j]['slc_chat_cskh_ktl']) * 100, 2);
                        $result[$i]['slc_chat_ccp_tl'] = $slc[$j]['slc_chat_ccp_tl'];
                        $result[$i]['slc_chat_ccp_ktl'] = $slc[$j]['slc_chat_ccp_ktl'];
                        $result[$i]['slc_ts_pc_kh_bh'] = ($slc[$j]['slc_chat_ccp_tl'] + $slc[$j]['slc_chat_ccp_ktl'] == 0) ? "0.00" : number_format($slc[$j]['slc_chat_ccp_tl'] / ($slc[$j]['slc_chat_ccp_tl'] + $slc[$j]['slc_chat_ccp_ktl']) * 100, 2);
                        break;
                    }
                }
            }
        }
        return $result;
    }

    public function searchSldAllDepartment($id_province, $date_start){
        $db = Yii::$app->dbsdk;
        $dbn = substr($db->dsn, (strpos($db->dsn, 'dbname=')) + 7);

        $sql = "SELECT id_province, province, id_department,
        sld_acc_op_kh, sld_acc_op_bt, sld_acc_op_nvt, sld_acc_op_cskh, sld_acc_op_ccp,
        sld_acc_chat_kh, sld_acc_chat_bt, sld_acc_chat_nvt, sld_acc_chat_cskh, sld_acc_chat_ccp,
        sld_chat_bc_kh_bt, sld_chat_bc_bt_kh, sld_chat_bc_kh_bct_tl, sld_chat_bc_kh_bct_ktl,
        sld_chat_bc_kh_bcp_tl, sld_chat_bc_kh_bcp_ktl, sld_chat_cskh_tl, sld_chat_cskh_ktl,
        sld_chat_ccp_tl, sld_chat_ccp_ktl
        FROM {$dbn}.total_stat
        where date(report_date) = :date_start
        and id_province = :id_province";

        $result = $db->createCommand($sql)
            ->bindValue(':id_province', $id_province)
            ->bindValue(':date_start', $date_start)
            ->queryAll();

        return $result;
    }

    public function searchSldDepartment($id_department, $date_start){
        $db = Yii::$app->dbsdk;
        $dbn = substr($db->dsn, (strpos($db->dsn, 'dbname=')) + 7);

        $sql = "SELECT id_province, province, id_department,
        sld_acc_op_kh, sld_acc_op_bt, sld_acc_op_nvt, sld_acc_op_cskh, sld_acc_op_ccp,
        sld_acc_chat_kh, sld_acc_chat_bt, sld_acc_chat_nvt, sld_acc_chat_cskh, sld_acc_chat_ccp,
        sld_chat_bc_kh_bt, sld_chat_bc_bt_kh, sld_chat_bc_kh_bct_tl, sld_chat_bc_kh_bct_ktl,
        sld_chat_bc_kh_bcp_tl, sld_chat_bc_kh_bcp_ktl, sld_chat_cskh_tl, sld_chat_cskh_ktl,
        sld_chat_ccp_tl, sld_chat_ccp_ktl
        FROM {$dbn}.total_stat
        where date(report_date) = :date_start
        and id_department = :id_department";

        $result = $db->createCommand($sql)
            ->bindValue(':id_department', $id_department)
            ->bindValue(':date_start', $date_start)
            ->queryAll();

        return $result;
    }

    public function searchSldAllProvince($date_start){
        $db = Yii::$app->dbsdk;
        $dbn = substr($db->dsn, (strpos($db->dsn, 'dbname=')) + 7);

        $sql = "SELECT id_province, province, id_department,
        sld_acc_op_kh, sld_acc_op_bt, sld_acc_op_nvt, sld_acc_op_cskh, sld_acc_op_ccp,
        sld_acc_chat_kh, sld_acc_chat_bt, sld_acc_chat_nvt, sld_acc_chat_cskh, sld_acc_chat_ccp,
        sld_chat_bc_kh_bt, sld_chat_bc_bt_kh, sld_chat_bc_kh_bct_tl, sld_chat_bc_kh_bct_ktl,
        sld_chat_bc_kh_bcp_tl, sld_chat_bc_kh_bcp_ktl, sld_chat_cskh_tl, sld_chat_cskh_ktl,
        sld_chat_ccp_tl, sld_chat_ccp_ktl
        FROM {$dbn}.total_stat
        where date(report_date) = :date_start
        and id_department is null";

        $result = $db->createCommand($sql)
            ->bindValue(':date_start', $date_start)
            ->queryAll();

        return $result;
    }

    public function searchSlcAllDepartment($id_province, $date_end){
        $db = Yii::$app->dbsdk;
        $dbn = substr($db->dsn, (strpos($db->dsn, 'dbname=')) + 7);

        $sql = "SELECT id_province, id_department,
        slc_acc_op_kh, slc_acc_op_bt, slc_acc_op_nvt, slc_acc_op_cskh, slc_acc_op_ccp,
        slc_acc_chat_kh, slc_acc_chat_bt, slc_acc_chat_nvt, slc_acc_chat_cskh, slc_acc_chat_ccp,
        slc_chat_bc_kh_bt, slc_chat_bc_bt_kh, slc_chat_bc_kh_bct_tl, slc_chat_bc_kh_bct_ktl,
        slc_chat_bc_kh_bcp_tl, slc_chat_bc_kh_bcp_ktl, slc_chat_cskh_tl, slc_chat_cskh_ktl,
        slc_chat_ccp_tl, slc_chat_ccp_ktl
        FROM {$dbn}.total_stat
        where date(report_date) = :date_end
        and id_province = :id_province";

        $result = $db->createCommand($sql)
            ->bindValue(':id_province', $id_province)
            ->bindValue(':date_end', $date_end)
            ->queryAll();

        return $result;
    }

    public function searchSlcDepartment($id_department, $date_end){
        $db = Yii::$app->dbsdk;
        $dbn = substr($db->dsn, (strpos($db->dsn, 'dbname=')) + 7);

        $sql = "SELECT id_province, id_department,
        slc_acc_op_kh, slc_acc_op_bt, slc_acc_op_nvt, slc_acc_op_cskh, slc_acc_op_ccp,
        slc_acc_chat_kh, slc_acc_chat_bt, slc_acc_chat_nvt, slc_acc_chat_cskh, slc_acc_chat_ccp,
        slc_chat_bc_kh_bt, slc_chat_bc_bt_kh, slc_chat_bc_kh_bct_tl, slc_chat_bc_kh_bct_ktl,
        slc_chat_bc_kh_bcp_tl, slc_chat_bc_kh_bcp_ktl, slc_chat_cskh_tl, slc_chat_cskh_ktl,
        slc_chat_ccp_tl, slc_chat_ccp_ktl
        FROM {$dbn}.total_stat
        where date(report_date) = :date_end
        and id_department = :id_department";

        $result = $db->createCommand($sql)
            ->bindValue(':id_department', $id_department)
            ->bindValue(':date_end', $date_end)
            ->queryAll();

        return $result;
    }

    public function searchSlcAllProvince($date_end){
        $db = Yii::$app->dbsdk;
        $dbn = substr($db->dsn, (strpos($db->dsn, 'dbname=')) + 7);

        $sql = "SELECT id_province, id_department,
        slc_acc_op_kh, slc_acc_op_bt, slc_acc_op_nvt, slc_acc_op_cskh, slc_acc_op_ccp,
        slc_acc_chat_kh, slc_acc_chat_bt, slc_acc_chat_nvt, slc_acc_chat_cskh, slc_acc_chat_ccp,
        slc_chat_bc_kh_bt, slc_chat_bc_bt_kh, slc_chat_bc_kh_bct_tl, slc_chat_bc_kh_bct_ktl,
        slc_chat_bc_kh_bcp_tl, slc_chat_bc_kh_bcp_ktl, slc_chat_cskh_tl, slc_chat_cskh_ktl,
        slc_chat_ccp_tl, slc_chat_ccp_ktl
        FROM {$dbn}.total_stat
        where date(report_date) = :date_end
        and id_department is null";

        $result = $db->createCommand($sql)
            ->bindValue(':date_end', $date_end)
            ->queryAll();

        return $result;
    }
}
