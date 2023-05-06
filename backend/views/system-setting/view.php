<?php

use awesome\backend\widgets\AwsBaseHtml;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\SystemSetting */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'System Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row system-setting-view">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-list "></i>
                    <span class="caption-subject  sbold uppercase">
                        <?=  AwsBaseHtml::encode($this->title) ?>
                    </span>
                </div>
                <div class="actions">
                    <?=                     AwsBaseHtml::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id],
                        ['class' => 'btn btn-info  btn-sm'])
                    ?>
                    <?=                     AwsBaseHtml::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-transparent red  btn-sm',
                        'data' => [
                            'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ])
                    ?>
                </div>
            </div>
            <div class="portlet-body">
                <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                            'id',
            'config_key',
            'config_value:ntext',
            'description:ntext',
                ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
