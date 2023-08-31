<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

</div>
<!-- /.container-fluid -->
<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <?= $this->session->flashdata('message'); ?>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-6">
            <form action="<?= base_url('user/changepassword'); ?>" method="POST">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" class="form-control" id="current_password" name="current_password">
                    <!-- menampilkan pesan eror -->
                    <?= form_error('current_password', '<div><small class="text-danger pl-3">', '</small></div>') ?>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password">
                    <!-- menampilkan pesan eror -->
                    <?= form_error('new_password', '<div><small class="text-danger pl-3">', '</small></div>') ?>
                </div>
                <div class="form-group">
                    <label for="repeat_password">Repeat Password</label>
                    <input type="password" class="form-control" id="repeat_password" name="repeat_password">
                    <!-- menampilkan pesan eror -->
                    <?= form_error('repeat_password', '<div><small class="text-danger pl-3">', '</small></div>') ?>
                </div>
                <div class="form-group">

                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>

</div>

</div>
<!-- End of Main Content -->