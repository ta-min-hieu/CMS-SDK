<!-- SESSION -->
<div class="container-fluid pb-4">
    <!-- Link Top -->
    <div class="d-flex flex-nowrap justify-content-between align-items-center  mt-4">
        <div>
            <span class="size-24 fw-bold d-inline-block align-middle me-3 text-light"><?= $this->title ?></span>
            <button type='button' class='btn btn-warning rounded-pill btn-icon me-3 play-toggle'
                data-bs-toggle='button'>
                <i class='ci-u-triangle'></i>
                <i class='ci-u-pause-sign'></i>
            </button>
        </div>
    </div>
    <?= $this->render('@app/views/media/_verticalRankableSongList', ['dataProvider' => $dataProvider]) ?>
</div>