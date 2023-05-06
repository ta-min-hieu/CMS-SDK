<?php
use \yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\TransactionAsset;

TransactionAsset::register($this);
?>

<div class="box-title box-title2 container-fluid">
    <h2 class=""><?= Yii::t('frontend', 'Lịch sử giao dịch');?></h2>
</div>

<form id="trans-form" class="trans-form " method="GET" action="<?= Url::to(['subscriber/transaction-history']);?>">
    <div class="form-group row">
         <div class="col-6">
             <input id="start_date" class="datepicker1" name="start_date" value="<?= date('d/m/Y', strtotime($startDate)) ?>"  /> 
         </div>
         <div class="col-6 text-right">
             <input id="end_date" class="datepicker1" name="end_date" value="<?= date('d/m/Y', strtotime($endDate)); ?>"  /> 
         </div>
         <div class="col-12 text-center">
            <br/>
             <input class="btn btn-lucky" type="submit" value="<?= Yii::t('frontend', 'Tìm kiếm'); ?>"  /> 
         </div>
     </div>
</form>

<div id="post-list" class="">
    <?php if (count($listPager)): ?>
        <div class="card-bodyxx table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <th><?= Yii::t('frontend', 'Thời gian');?></th>
                    <th><?= Yii::t('frontend', 'Loại giao dịch'); ?></th>
                    <th><?= Yii::t('frontend', 'Phí (cents)'); ?></th>
                    <th><?= Yii::t('frontend', 'Trạng thái'); ?></th>
                </thead>
                <?php foreach($listPager as $key => $trans): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i:s', strtotime($trans->CHARGE_TIME)) ?></td>
                        <td><?= $trans->getChargeTypeName(); ?></td>
                        <td><?= number_format($trans->MONEY/10000); ?></td>
                        <td><?= ($trans->CHARGE_STATUS == 0)? Yii::t('frontend', 'Thành công'): Yii::t('frontend', 'Thất bại'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class=" col-md-12">
            <?= \yii\widgets\LinkPager::widget([
                'pagination' => $pages,
                'maxButtonCount' => 5,

            ]);
            ?>
        </div>
    <?php else: ?>
        <?= Yii::t('frontend', 'Chưa có giao dịch nào!');?>
    <?php endif;?>
</div>

<br />
<br />
<?= $this->render('@app/views/subscriber/_personalLink', [])?>