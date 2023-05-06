<?php


use yii\grid\GridView;
use awesome\backend\widgets\AwsBaseHtml;
use backend\models\User;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row user-index">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <div class="portlet-title">
                <?php echo $this->render('_search-agent', ['model' => $searchModel]); ?>

            </div>

            <div class="portlet-body">
                <div class="table-container">
                    <?php
                    Pjax::begin(['formSelector' => 'form', 'enablePushState' => false, 'id' => 'mainGridPjax']);
                    ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        //'filterModel' => $searchModel,
                        'filterSelector' => 'select[name="per-page"]',
                        'layout' => "{items}\n <div class='form-inline pagination page-size'>" . awesome\backend\grid\AwsPageSize::widget([
                            'options' => [
                                'class' => 'form-control  form-control-sm',
                            ]
                        ]) . '</div> <div class="col-md-6">{pager}</div> <div class="pagination col-md-3 text-right total-count">' . Yii::t('backend', 'Tổng số') . ': <b>' . number_format($dataProvider->getTotalCount()) . '</b> ' . Yii::t('backend', 'bản ghi') . '</div>',

                        'columns' => [
                            'id',

                            'username',
                            // [
                            //     'attribute' => 'user_type',
                            //     'content' => function($model) {
                            //         return $model->getUserTypeName();
                            //     }
                            // ],
                            // 'msisdn',
                            // 'email:email',

                            [
                                'attribute' => 'support_status',
                                'format' => 'raw',
                                'value' => function($model) {
                                    return $model->getDisplaySupportStatus();
                                },
                            ],
                            'last_time_login',
                            [
                                'attribute' => 'Consulting',
                                'format' => 'raw',
                                'contentOptions' => ['id' => 'inprocess'],
                                'value' => function ($object) {

                                    return '<span id="inprocess-' . $object->id . '">0<span>';
                                }
                            ],
                            [
                                'attribute' => 'Processing of the day',
                                'format' => 'raw',
                                'value' => function ($object) {

                                    return '<span id="process-' . $object->id . '">0<span>';
                                }
                            ],

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'headerOptions' => ['style' => 'width: 200px;', 'class' => 'head-actions'],
                                'contentOptions' => ['class' => 'row-actions'],
                                'template' => '{add}{delete}{check}',
                                'buttons' => [
                                    'add' => function ($url, $model) {
                                        return Html::a('Add chat', ['support-customer/add-chat', 'id' => $model->id], [
                                            'class' => 'text-danger',
                                            'data-pjax' => 0,
                                        ]);
                                    },
                                    'delete' => function ($url, $model) {
                                        return Html::a(
                                            '<span class="glyphicon glyphicon-trash"></span>',
                                            ['manage-delete', 'id' => $model->id],
                                            [
                                                'data-pjax' => 0,
                                                'data' => [
                                                    'confirm' => Yii::t('backend', 'Bạn muốn xóa agent ?'),

                                                ],
                                            ]
                                        );
                                    },
                                    'check' => function ($url, $model) {
                                        if ($model->auto_close_sc == 1) {
                                            return Html::a(
                                                '<span class="glyphicon glyphicon-ok icon-is_active"></span>',
                                                ['update-auto-close', 'id' => $model->id, 'auto' => 0],
                                                [
                                                    'class' => 'text-success',
                                                    'data-pjax' => 0,
                                                    'data' => [
                                                        'confirm' => Yii::t('backend', 'Bạn muốn thay đổi trạng thái ?'),

                                                    ],
                                                ]
                                            );
                                        } else {
                                            return Html::a(
                                                '<span class="glyphicon glyphicon-remove icon-is_active"></span>',
                                                ['update-auto-close', 'id' => $model->id, 'auto' => 1],
                                                [
                                                    'class' => 'text-danger',
                                                    'data-pjax' => 0,
                                                    'data' => [
                                                        'confirm' => Yii::t('backend', 'Bạn muốn thay đổi trạng thái ?'),
                                                    ],
                                                ]
                                            );
                                        }
                                    },
                                ]
                            ],
                        ],
                    ]); ?>

                    <?php
                    Pjax::end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(
    <<< EOT_JS_CODE
    $(document).ready(function() {
        $(document).keypress(
            function(event){
              if (event.which == '13') {
                event.preventDefault();
              }
        });

        supportInfo();
        
        

            function supportInfo(){
            
            var data = { agentIds: [$list_id] };  
         
            $.ajax({
                url: 'https://cmscamid.ringme.vn:8443/helpdesk-camid-service/api/v1/cms-dashboard/get-info-sc-agent-today',
                headers: {
                    'Content-Type':'application/json'
                },
                method: 'POST',
                dataType: 'json',
                data: JSON.stringify(data),
                success: function(res){

                for (const [key, value] of Object.entries(res.data.assigning)) {
                    $('#inprocess-'+key).html(value);
                }

                for (const [key, value] of Object.entries(res.data.supportToday)) {
                    $('#process-'+key).html(value);
                }
                  

                }
              });
            }

            setInterval(function(){
                supportInfo();
            }, 30000);
    });

EOT_JS_CODE
);
?>