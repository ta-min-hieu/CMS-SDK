
<div class="box-title box-title2 container-fluid">
    <h2 class=""><?= Yii::t('frontend', 'Update your birthday'); ?></h2>
</div>
        <?= Yii::t('frontend', 'Update your birthday and register {service_name} service to know your daily fortune', [
            'service_name' => Yii::$app->params['service_name']
        ]); ?>
        <br/>
        <br/>
        <?php if ($errorMsg): ?>
            <div class="alert alert-warning" role="alert">
                <div class="err-msg"><?= $errorMsg?></div>
            </div>
        <?php endif;?>
        <form method="POST" action="">

            <div class="form-group  row">
                <label for="" class="col-sm-3 col-form-label"><?= Yii::t('frontend', 'Ngày sinh');?></label>
                <div class="col">
                    <!-- <input type="text" class="form-control" name="day" placeholder="<?= Yii::t('frontend', 'Ngày');?>" maxlength="2" value="<?= $day; ?>" /> -->
                    <select name="day" class="form-control"> placeholder="<?= Yii::t('frontend', 'Ngày');?>">
                        <option value=""><?= Yii::t('frontend', 'Ngày') ?></option>
                        <?php for($i = 1; $i < 32; $i++): ?>
                            <option <?= ($i == $day)? 'selected': ''; ?> value="<?= $i ?>"><?= str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col">
                    <!-- <input type="text" class="form-control" name="month" placeholder="<?= Yii::t('frontend', 'Tháng');?>" maxlength="2" value="<?= $month?>" /> -->
                    <select name="month" class="form-control"> placeholder="<?= Yii::t('frontend', 'Tháng');?>">
                        <option value=""><?= Yii::t('frontend', 'Tháng') ?></option>
                        <?php for($i = 1; $i < 13; $i++): ?>
                            <option <?= ($i == $month)? 'selected': ''; ?> value="<?= $i ?>"><?= str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="year" placeholder="<?= Yii::t('frontend', 'Năm');?>" maxlength="4" value="<?= $year?>" />
                </div>
            </div>

            <div class="form-group  row">
                <label for="" class="col-sm-3 col-form-label"><?= Yii::t('frontend', 'Time of birth');?></label>
                <div class="col">
                    <select name="hour" class="form-control"> placeholder="<?= Yii::t('frontend', 'Hour');?>">
                        <option value=""><?= Yii::t('frontend', 'Hour') ?></option>
                        <?php for($i = 0; $i < 24; $i++): ?>
                            <option <?= ($i == $hour)? 'selected': ''; ?> value="<?= $i ?>"><?= str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col">
                    <select name="minute" class="form-control"> placeholder="<?= Yii::t('frontend', 'Minute');?>">
                        <option value=""><?= Yii::t('frontend', 'Minute') ?></option>
                        <?php for($i = 0; $i < 60; $i++): ?>
                            <option <?= ($i == $minute)? 'selected': ''; ?> value="<?= $i ?>"><?= str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col">

                </div>
            </div>

            <div class="form-group">
                <input class="btn btn-lucky" type="submit" value="<?= Yii::t('frontend', 'Update');?>" style="padding: 5px 25px;"/>
                <input type="hidden" name="view" value="1" />
            </div>
        </form>
<br />
<?= $this->render('@app/views/subscriber/_personalLink', [])?>
