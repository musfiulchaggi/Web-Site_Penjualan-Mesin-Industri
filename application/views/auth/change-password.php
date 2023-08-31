<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-7 col-lg-7">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900">Change Your Password</h1>
                                </div>
                                <?= $message ?>
                                <form class="user" method="POST" action="<?= base_url('auth/changePassword') ?>">
                                    <h5 class="text-center mb-4"><?= $this->session->userdata('reset_password'); ?></h5>
                                    <div class="form-group">
                                        <!-- membuat email  untuk bisa divalidasi oleh ci dengan attribute name dan id type = text-->
                                        <input type="password" class="form-control form-control-user" id="password1" name="password1" aria-describedby="emailHelp" placeholder="Enter New Password...">
                                        <!-- menampilkan pesan eror -->
                                        <?= form_error('password1', '<div><small class="text-danger pl-3">', '</small></div>') ?>

                                    </div>
                                    <div class="form-group">
                                        <!-- membuat email  untuk bisa divalidasi oleh ci dengan attribute name dan id type = text-->
                                        <input type="password" class="form-control form-control-user" id="password2" name="password2" aria-describedby="emailHelp" placeholder="Repeat New Password...">
                                        <!-- menampilkan pesan eror -->
                                        <?= form_error('password2', '<div><small class="text-danger pl-3">', '</small></div>') ?>

                                    </div>


                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Change Password
                                    </button>

                                </form>
                                <hr>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>