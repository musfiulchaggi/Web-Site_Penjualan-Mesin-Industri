<div class="container ">
    <!-- mx-auto adalah atribut bootstrap untuk membuat konten di tengah -->

    <div class="card o-hidden border-0 shadow-lg my-5 col-lg-7 mx-auto">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->

            <div class="row">
                <div class="col">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                        </div>

                        <!-- method form manual html -->
                        <!--  -->
                        <form class="user" method="POST" action="<?= base_url('auth/registration') ?>">

                            <div class="form-group">

                                <!-- untuk mengisi lagi value yang ada dalam form menggunakan set_value() -->
                                <input type="text" class="form-control form-control-user" id="name" placeholder="Full Name " name="name" value="<?= set_value('name') ?>">
                                <!-- menampilkan pesan eror -->
                                <?= form_error('name', '<div><small class="text-danger pl-3">', '</small></div>') ?>

                            </div>
                            <div class="form-group">

                                <!-- untuk mengisi lagi value yang ada dalam form menggunakan set_value() -->
                                <input type="text" class="form-control form-control-user" id="email" name="email" placeholder="Email Address" <?= set_value('email') ?>>
                                <!-- menampilkan pesan eror -->
                                <?= form_error('email', '<div><small class="text-danger pl-3">', '</small></div>') ?>

                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" class="form-control form-control-user" id="password1" name="password1" placeholder="Password">
                                    <!-- menampilkan pesan eror -->
                                    <?= form_error('password1', '<div><small class="text-danger pl-3">', '</small></div>') ?>
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control form-control-user" id="password2" name="password2" placeholder="Repeat Password">
                                </div>
                            </div>
                            <button type="submit" href="login.html" class="btn btn-primary btn-user btn-block">
                                Register Account
                            </button>

                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small" href="<?= base_url('auth/') ?>forgotPassword">Forgot Password?</a>
                        </div>
                        <div class="text-center">
                            <a class="small" href="<?= base_url('auth') ?>">Already have an account? Login!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>