<?php
use yii\helpers\Url;
?>
<!-- Signin -->
<section class="pt-5 d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-4">
                <form action="" class="text-center px-md-4 f-base">
                    <h1 class="text-dark fw-bold mb-5 pb-3">Đăng nhập</h1>
                    <!-- <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <p><strong>Có lỗi xảy ra!</strong></p>
                        Email hoặc mật khẩu không hợp lệ!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div> -->
                    <div class="mb-4">
                        <a href="<?= Url::to(['site/sign-in-by-phone']) ?>" class="btn btn-lg btn-control-custom-2 w-100 rounded-2">
                            <i class="ci-u-keypad fs-4"></i>
                            <span class="f-base">Số điện thoại</span>
                        </a>
                    </div>
                    <div class="mb-4">
                        hoặc
                    </div>
                    <div class="mb-4">
                        <input type="email" class="form-control form-control-lg form-control-custom-2 f-base"
                            placeholder="Email">
                    </div>
                    <div class="mb-4">
                        <input type="password" class="form-control form-control-lg form-control-custom-2 f-base"
                            placeholder="Mật khẩu">
                    </div>
                    <div class="mb-4">
                        <button type="button"
                            class="btn btn-lg btn-gradient-custom rounded-2 d-block w-100 f-base">Login</button>
                    </div>
                    <!-- <div class="input-group mb-4">
                        <div class="row">
                            <div class="col">
                                <input type="text"
                                    class="form-control form-control-lg form-control-custom-2 f-base"
                                    placeholder="CAPCHAPT">
                            </div>
                            <div class="col-md-auto">
                                <img src="https://www.pandasecurity.com/en/mediacenter/src/uploads/2014/09/avoid-captcha.jpg"
                                    alt="" srcset="" class="rounded-2" style="height: 47px;">
                            </div>
                        </div>
                    </div> -->
                    <div class="mt-5">
                        <p>Bạn chưa có tài khoản? <a href="" class="text-primary">Đăng ký</a></p>
                        <p><a href="" class="text-primary">Quên mật khẩu?</a></p>
                    </div>
                </form>
                <p class="text-center">
                    <!-- Success solid icon button -->
                    <!-- <a href="#" type="button" class="btn btn-control-custom-2 btn-icon text-primary">
                        <i class="ci-arrow-left"></i>
                    </a> -->
                </p>
            </div>
        </div>
    </div>
</section>