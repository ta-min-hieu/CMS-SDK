<?php


use \yii\grid\GridView;
use awesome\backend\widgets\AwsBaseHtml;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SongSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Staffs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row staff-index">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-datatable bordered">

            <div class="portlet-body">
                <div class="table-container">
                    <?php
                    Pjax::begin(['timeout' => 0, 'formSelector' => 'form#staff-search-ajax', 'enablePushState' => false, 'id' => 'staffGridPjax']);
                    ?>

                    <?= GridView::widget([
                        'pager' => [
                            'maxButtonCount'=> 5,
                        ],
                        'dataProvider' => $dataProvider,
                        'filterSelector' => 'select[name="per-page"]',
                        'layout' => "{items}\n " . ' <div class="col-md-6">{pager}</div> <div class="pagination col-md-3 text-right total-count">' . Yii::t('backend', 'Tổng số') . ': <b>' . number_format($dataProvider->getTotalCount()) . '</b> ' . Yii::t('backend', 'bản ghi') . '</div>',
                        // 'filterModel' => $searchModel,
                        'columns' => [
                            //['class' => 'yii\grid\SerialColumn'],
//                            [
//                                'attribute' => 'avatar',
//                                'value' => function ($model) {
//                                    return $model->getAvatarUrl();
//                                },
//                                'format' => ['image', ['height' => '80', 'onerror' => "this.src='" . Yii::$app->params['no_image'] . "';"]],
//                                'filter' => false,
//                                'headerOptions' => ['style' => 'width:120px'],
//                            ],
                            [
                                'attribute' => '#',
                                'format' => 'raw',
                                'value' => function ($object) use ($ctype, $ctypeId) {

                                    return Html::button(Yii::t('backend', 'Add'), [
                                        'ahref' => \yii\helpers\Url::to([
                                            'staff/add-to-collection',
                                            'ctype' => $ctype,
                                            'ctype_id' => $ctypeId,
                                            'id_staff' => $object->username,
                                        ]),
                                        'class' => 'btn btn-sm btn-primary btn-staff-add',
                                        'id_staff' => $object->username,
                                    ]);
                                }
                            ],
                            'staff_name',
                            'phone_number',
                            'position',
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
