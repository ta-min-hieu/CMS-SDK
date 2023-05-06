<?php

use yii\grid\GridView;
use awesome\backend\widgets\AwsBaseHtml;
use backend\models\Department;
use backend\models\Major;
use backend\models\Queue;
use yii\helpers\Html;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'View the details of the list of invalid employees');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row user-index">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <div class="portlet-body">
                <?php
                // echo "<pre>";
                // var_dump(Yii::$app->session['tippIds']);
                // die();
                ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Staff name</th>
                            <th>Phone number</th>
                            <th>Position</th>
                            <th>Department name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $session = Yii::$app->session['tippIds'];
                        for ($i = 1; $i <= Yii::$app->session['row']; $i++) {
                            echo "<tr>";
                            for ($j = 1; $j <= \Yii::$app->session['col']; $j++) {
                                if (isset($session[0][$j][$i])) {
                                    echo "<td>";
                                    echo $session[0][$j][$i];
                                    echo "</td>";
                                }
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="portlet-title">
    <a href="<?= \yii\helpers\Url::to(['/queue/staff?id=' . \Yii::$app->session['id']]); ?>" class="btn btn-default btn-sm">
        <i class="fa fa-angle-left"></i> <?= Yii::t('backend', 'Back') ?> </a>
    &nbsp;&nbsp;&nbsp;
</div>