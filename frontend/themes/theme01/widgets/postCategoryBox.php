<div id="<?= ($boxId)? $boxId: 'post-cate-box'; ?>" class="box box7">
    <h2 class="t box-title">Chuyên san</h2>
    <div class="box-content">
        <?= $this->render('/category/_postCategoryList2', [
            'cateList' => $cateList,
            'class' => 'level0',
            'level' => 0,
        ]);
        ?>
        <div class="clearfix"></div>
    </div>
</div>
