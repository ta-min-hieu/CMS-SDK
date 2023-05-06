<?php

use awesome\backend\widgets\AwsBaseHtml;
use backend\models\User;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Admin'), 'url' => '#'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row user-view">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <?php if (!$isAjax): ?>
            <?php endif; ?>
            <div class="portlet-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'username',
                        'email:email',
                        [
                            'label' => 'Link reset mật khẩu',
                            'format' => 'url',
                            'value' => function($model) {
                                $resetUrl = \yii\helpers\Url::to(['site/reset-password', 'token' => $model->password_reset_token], true);
                                return $resetUrl; //\yii\helpers\Html::a($resetUrl, $resetUrl);
                            }
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => function ($object) {
                                $class = ($object->status == 1) ? 'glyphicon-ok' : 'glyphicon-remove';
                                return '<span class="glyphicon ' . $class . ' icon-is_active"></span>';
                            }
                        ],
                        'id_province',
                        [
                            'label' => 'created_at',
                            'value' => date('H:i:s d/m/Y', $model->created_at),
                        ],
                        [
                            'label' => 'updated_at',
                            'value' => date('H:i:s d/m/Y', $model->updated_at),
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
