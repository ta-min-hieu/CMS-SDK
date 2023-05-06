<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\UserLoginFailed */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'User Login Faileds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row user-login-failed-view">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <?php if (!$isAjax): ?>            <div class="portlet-title">

                <div class="">
                    <?=                     Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id],
                        ['class' => 'btn btn-info  btn-sm'])
                    ?>
                    <?=                     Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-transparent red  btn-sm',
                        'data' => [
                            'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ])
                    ?>
                </div>
            </div>
            <?php endif; ?>            <div class="portlet-body">
                <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                            'id',
            'username',
            'user_id',
            'ip',
            'created_at',
                ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
