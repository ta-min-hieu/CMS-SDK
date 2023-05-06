<!-- Signin by phonenumber -->
<section class="pt-5 d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-4">
                <form action="" class="text-center px-md-4 f-base">
                    <h1 class="text-dark fw-bold mb-5 pb-3">Số điện thoại</h1>
                    <div class="mb-4">
                        <input type="tel" class="form-control form-control-lg form-control-custom-2 f-base"
                            placeholder="Số điện thoại">
                    </div>
                    <div class="mb-4">
                        <button type="button"
                            class="btn btn-lg btn-gradient-custom rounded-2 d-block w-100 f-base"
                            data-bs-toggle="modal" data-bs-target="#modalOTP">Xác nhận</button>
                    </div>
                </form>
                <?php if(($referUrl = Yii::$app->request->getReferrer())) : ?>
                <p class="text-center">
                    <!-- Success solid icon button -->
                    <a href="<?= $referUrl ?>" type="button" class="btn btn-control-custom-2 btn-icon text-primary mt-5">
                        <i class="ci-arrow-left"></i>
                    </a>
                </p>
                <?php endif ?>
            </div>
        </div>
    </div>
</section>