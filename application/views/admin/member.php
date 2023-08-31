<!-- Begin Page Content -->
<div class="container-fluid">



    <?php

    if ($this->session->flashdata('message')) {
        echo '<div class="col-lg-8 alert alert-primary" role="alert">' . $this->session->flashdata('message') . '</div>';
    }

    ?>
    <div class="row">
        <?php foreach ($member_not_active as $nt) : ?>
            <div class="col-lg-4">
                <!-- tambahan member card aja -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Permintaan Member</h5>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-lg-4">
                                Nama
                            </div>
                            <div class="col-lg-8">
                                <?= $nt['name'] ?>
                            </div>
                            <div class="col-lg-4 mt-2">
                                Email
                            </div>
                            <div class="col-lg-8 mt-2">
                                <?= $nt['email'] ?>
                            </div>
                        </div>


                    </div>
                    <div class="card-footer">
                        <div class="row justify-content-center">

                            <div class="col-lg-5 mt-2 text-center">

                                <a href="<?= base_url('admin/hapus/' . $nt['id_user']) ?>" class="btn btn-warning">Hapus</a>
                            </div>
                            <div class="col-lg-7 mt-2 text-center">

                                <a href="<?= base_url('admin/member/' . $nt['id_user']) ?>" class="btn btn-primary">Kirim Token</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="card boder-primary mt-4">

        <div class="card-body ">
            <table id="tblMember" class="table thead-dark table-striped table-bordered " style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Member</th>
                        <th>Email</th>
                        <th>image</th>
                        <th>Role Id</th>
                        <th>Active</th>
                        <th>Action</th>

                    </tr>
                </thead>

                <tbody>
                    <?php $no = 1;
                    foreach ($member_active as $ur) : ?>


                        <tr>
                            <td class="font-weight-bold" style="color: black; font-size:smaller;"> <?= $no ?></td>
                            <td class="font-weight-bold" style="color: black; font-size:smaller;"> <?= $ur['name'] ?></td>
                            <td class="font-weight-bold" style="color: black; font-size:smaller;"> <?= $ur['email'] ?></td>
                            <td class="font-weight-bold" style="color: black; font-size:smaller; text-align:center;"> <a href="<?= base_url('assets/img/profile/' . $ur['image']) ?>" target="blank"><img class="img-fluid" src="<?= base_url('assets/img/profile/' . $ur['image']) ?>" style="width:50px"></a></td>
                            <td class="font-weight-bold" style="color: black; font-size:smaller; text-align:center;"><?php if ($ur['role_id'] == 1) {
                                                                                                                            echo 'Super Admin';
                                                                                                                        } else {
                                                                                                                            echo 'Admin';
                                                                                                                        }  ?></td>
                            <td class="font-weight-bold" style="color: black; font-size:smaller; text-align:center;"> <?php if ($ur['is_active'] == 1) {
                                                                                                                            echo 'Yes';
                                                                                                                        } else {
                                                                                                                            echo 'No';
                                                                                                                        }  ?></td>
                            <td class="font-weight-normal" style="color: black; font-size:smaller; text-align:center;">
                                <div class="col">
                                    <button class="editStatus btn btn-warning btn-sm " data-toggle="modal" data-target="#mdlEditUser" data-idUser="<?= $ur['id_user'] ?>" data-namaUser="<?= $ur['name'] ?>" data-emailUser="<?= $ur['email'] ?>" data-statususer="<?php if ($ur['is_active'] == 1) {
                                                                                                                                                                                                                                                                        echo 'Aktif';
                                                                                                                                                                                                                                                                    } else {
                                                                                                                                                                                                                                                                        echo 'Tidak Aktif';
                                                                                                                                                                                                                                                                    }  ?>">Ubah Status</button>

                                </div>
                            </td>

                        </tr>
                        <?php $no++; ?>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>

    <div class="row justify-content-center">


    </div>
</div>
<!-- /.container-fluid -->


</div>
<!-- End of Main Content -->


<!-- Modal Edit User-->
<div class="modal fade" id="mdlEditUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header justify-content-center bg-warning">
                <h5 class="modal-title text-light" id="">Ubah Status User</h5>
            </div>
            <?= form_open_multipart('Admin/editUser', ['id' => 'formedituser']) ?>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control " id="id_user" name="id_user" value="" hidden>
                </div>
                <div class="form-group row">
                    <div class="col-md-4">

                        <label for="jumlah" style="color: black;"> <b>Nama Member</b> </label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control " id="nama_user" name="nama_user" value="" readonly>

                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-4">

                        <label for="jumlah" style="color: black;"> <b>Email Member</b> </label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control " id="email_user" name="email_user" value="" readonly>

                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-4">

                        <label for="jumlah" style="color: black;"> <b>Status Member</b> </label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control " id="status_user" name="status_user" value="" readonly>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#formedituser')[0].reset();">Close</button>
                    <button type="submit" id="btntambah" class="btn btn-warning" onclick="">Ubah</button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>