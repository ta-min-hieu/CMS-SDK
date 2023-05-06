<?php
use awesome\backend\widgets\AwsBaseHtml;
use backend\models\BoxChat;
use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Major;
/* @var $this yii\web\View */
/* @var $model backend\models\BoxChat */

?>
<div class="box-chat-view">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <?php if (!$isAjax): ?>
            <?php endif; ?>
            <div class="portlet-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id_question',
                        [
                            'attribute' => 'id_box_chat',
                            'format' => 'raw',
                            'value' => function ($object) {
                                $deparment=BoxChat::findOne($object->id_box_chat);
                                return ($deparment)?Yii::$app->params['mission'][$deparment->type_box_chat]:null;
                            }
                        ],
                        'question',
                        'answer',
                        [
                            'attribute' => 'type_question',
                            'format' => 'raw',
                            'value' => function ($object) {
                                return Yii::t('backend',Yii::$app->params['status'][$object->type_question]);
                            }
                        ],
                        'created_at',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>