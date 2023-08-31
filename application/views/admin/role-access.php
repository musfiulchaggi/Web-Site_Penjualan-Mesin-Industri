<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>


    <h5>Role : <?= $role['role'] ?></h5>
    <div class="row">
        <div class="col-lg-6">
            <?php echo $this->session->flashdata('message'); ?>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Menu</th>
                        <th scope="col">Access</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1 ?>
                    <?php foreach ($menu as $mn) : ?>
                        <tr>
                            <th scope="row"><?= $i ?></th>
                            <td><?= $mn['menu'] ?></td>
                            <td>
                                <div class="form-check">
                                    <!-- mengecek status checked dengan mengguanakan fungsi cek_akses di helper -->
                                    <!-- membuat jquery dengan data-role dan data-menu -->
                                    <!-- jquery ada di footer_dashboard -->
                                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" <?= cek_akses($role['id_role'], $mn['id']); ?> data-role="<?= $role['id_role'] ?>" data-menu="<?= $mn['id'] ?>">
                                </div>
                            </td>
                        </tr>
                        <?php $i++ ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<!-- /.container-fluid -->


</div>
<!-- End of Main Content -->