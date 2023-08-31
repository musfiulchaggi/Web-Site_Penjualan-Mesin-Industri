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
                                    <h1 class="h4 text-gray-900 mb-4">Do you forgot your password?</h1>
                                </div>
                                <?= $message ?>
                                <form class="user" method="POST" action="<?= base_url('auth/forgotPassword') ?>">
                                    <div class="form-group">
                                        <!-- membuat email  untuk bisa divalidasi oleh ci dengan attribute name dan id type = text-->
                                        <input type="text" class="form-control form-control-user" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter Email Address..." value="<?= set_value('email') ?>">
                                        <!-- menampilkan pesan eror -->
                                        <?= form_error('email', '<div><small class="text-danger pl-3">', '</small></div>') ?>

                                    </div>


                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Reset
                                    </button>

                                </form>
                                <hr>

                                <div class="text-center">
                                    <a class="small" href="<?= base_url('auth') ?>">Back to Log in</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>