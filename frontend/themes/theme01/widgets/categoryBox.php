<?php if ($enableBoxLayout === false): ?>
    <?= $this->render('/category/_categoryList2', [
        'cateList' => $cateList,
        'class' => 'level0',
        'level' => 0,
    ]);
    ?>
<?php else: ?>
<div id="cate-box" class="box box7 ">
    <h2 class="t box-title">Sản phẩm</h2>
    <div class="box-content">
        <?= $this->render('/category/_categoryList2', [
            'cateList' => $cateList,
            'class' => 'level0',
            'level' => 0,
        ]);
        ?>
        <div class="clearfix"></div>
    </div>
</div>
<?php endif; ?>