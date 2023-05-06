<?php
use awesome\backend\widgets\AwsBaseHtml;
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
                        'id_box_chat',
                        [
                            'attribute' => 'type_box_chat',
                            'format' => 'raw',
                            'value' => function ($object) {
                                $deparment=Major::findOne($object->type_box_chat);
                                return ($deparment)?$deparment->mission_name:null;
                            }
                        ],
                        'description',
                        'title_question',
                        'greeting',
                        'created_at',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>