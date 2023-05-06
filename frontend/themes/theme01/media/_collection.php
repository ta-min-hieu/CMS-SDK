<?php

use yii\helpers\Url;

?>
<!-- SESSION -->
<div class="container-fluid pb-4">
	<!-- Heading-->
	<div class="d-flex flex-wrap justify-content-between align-items-center pt-1">
		<h2 class="size-24 fw-bold mb-0 pt-3 me-2"><?= $collection->collection_name ?></h2>
		<div class="pt-3">
			<a href="<?= Url::to(['collection/detail', 'id' => $collection->id]) ?>" class="l-more f-base"><?= Yii::t('frontend', 'Tất cả') ?></a>
		</div>
	</div>
	<!-- Grid-->
	<div class="row pt-3 mx-n2">
		<?php foreach ($collection->contents as $content) : ?>
			<!-- song -->
			<div class="col-lg-2 col-md-3 col-sm-6 px-2">
				<div class="card product-card-alt active-card">
					<div class="product-thumb">
						<div class="position-relative w-100">
							<div class="product-card-actions">
								<a class="d-inline-block align-middle like-toggle" data-bs-toggle="button" href="#">
									<i class="ci-u-like-outline size-24">
									</i>
									<i class="ci-u-like-solid size-24">
									</i>
								</a>
								<button class="btn btn-warning rounded-pill btn-icon mx-3 play-toggle" data-bs-toggle="button" type="button">
									<i class="ci-u-triangle">
									</i>
									<img src="/img/u-pause-sign.svg" width="15px"/>
								</button>
								<a class="d-inline-block align-middle" href="#">
									<i class="ci-u-more size-24">
									</i>
								</a>
							</div>
							<a class="product-thumb-overlay rounded" href="<?= $content->getFrontendDetailUrl() ?>">
							</a>
							<img alt="<?= $content->getContentName() ?>" class="rounded w-100" src="<?= $content->getThumbnailUrl() ?>"/>
						</div>
					</div>
					<div class="card-body">
						<h6 class="product-title mb-0 text-truncate-multi h6">
							<a href="<?= $content->getFrontendDetailUrl() ?>"><?= $content->getContentName() ?></a>
						</h6>
					</div>
				</div>
			</div>
			<!-- /song -->
		<?php endforeach ?>
	</div>
</div>