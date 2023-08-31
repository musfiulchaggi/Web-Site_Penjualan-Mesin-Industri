<!-- Begin Page Content -->
<div class="container-fluid">

    <?php

    if ($this->session->flashdata('message')) {
        echo '<div class="alert alert-primary" role="alert">' . $this->session->flashdata('message') . '</div>';
    }

    ?>
    <div class="row justify-content-end">
        <div class="col">
            <button class="btn" data-toggle="modal" data-target="#mdlTambahMsn"><i class="fas fa-plus">Tambahkan Mesin</i></button>
        </div>
    </div>
    <div class="card boder-primary mt-4">

        <div class="card-body ">
            <table id="tblBarang" class="table thead-dark table-striped table-bordered " style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Mesin</th>
                        <th>Harga</th>
                        <th>Gambar</th>
                        <th>Kapasitas</th>
                        <th>Action</th>

                    </tr>
                </thead>

                <tbody>
                    <?php $no = 1;
                    foreach ($barang as $br) : ?>
                        <tr>
                            <td class="font-weight-normal" style="color: black; font-size:smaller;"> <?= $no ?></td>
                            <td class="font-weight-normal" style="color: black; font-size:smaller;"> <?= $br['nama_mesin'] ?></td>
                            <td class="font-weight-normal text-right" style="color: black; font-size:smaller;"> <?php
                                                                                                                $hasil_rupiah = number_format($br['harga'], 0, ',', '.');
                                                                                                                echo 'Rp. ' . $hasil_rupiah;
                                                                                                                ?></td>
                            <td class="font-weight-normal" style="color: black; font-size:smaller; text-align:center;"> <a href="<?= base_url('assets/img/mesin/' . $br['gambar']) ?>" target="blank"><img class="img-fluid" src="<?= base_url('assets/img/mesin/' . $br['gambar']) ?>" style="width:50px"></a></td>
                            <td class="font-weight-normal" style="color: black; font-size:smaller;"> <?= $br['kapasitas'] ?></td>
                            <td class="font-weight-normal" style="color: black; font-size:smaller; text-align:center;">
                                <div class="col">
                                    <button class="editMsn btn btn-warning btn-sm " data-toggle="modal" data-target="#mdlEditMsn" data-idmesin="<?= $br['id_mesin'] ?>" data-namamesin="<?= $br['nama_mesin'] ?>" data-hargamesin="<?= $br['harga'] ?>" data-kapasitasmesin="<?= $br['kapasitas'] ?>" data-gambarmesin="<?= $br['gambar'] ?>">Edit</button>

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

<!-- modal Tambah Mesin -->
<div class="modal fade" id="mdlTambahMsn" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header justify-content-center bg-success">
                <h3>Tambahkan Mesin</h3>
            </div>
            <!-- bikin form dulu file (action, method, enctype="multipart")-->
            <?= form_open_multipart('transaksi/tambahBarang') ?>
            <div class="modal-body bg-gradient-info">

                <div class="form-group row">
                    <label for="nama_mesin" class="col-sm-4 col-form-label text-light ">Nama Mesin</label>
                    <div class="col-sm-8 ">
                        <input type="text" class="form-control " id="nama_mesin" name="nama_mesin">
                        <!-- menampilkan pesan eror -->
                        <?= form_error('nama_mesin', '<div><small class="text-danger pl-3">', '</small></div>') ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="harga" class="col-sm-4 col-form-label text-light">Harga Mesin</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="harga" name="harga" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                        <!-- menampilkan pesan eror -->
                        <?= form_error('harga', '<div><small class="text-danger pl-3">', '</small></div>') ?>

                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4 text-light">Gambar</div>
                    <div class="col-sm-8">

                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="gambar" name="gambar">
                            <label class="custom-file-label" for="gambar">Choose file</label>
                        </div>


                    </div>
                </div>
                <div class="form-group row">
                    <label for="kapasitas" class="col-sm-4 col-form-label text-light">Kapasitas</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="kapasitas" name="kapasitas">
                        <!-- menampilkan pesan eror -->
                        <?= form_error('kapasitas', '<div><small class="text-danger pl-3">', '</small></div>') ?>

                    </div>
                </div>

                <!-- mangakali bootstrap row rata kanan justify-content-end-->
                <div class="form-group row justify-content-end">
                    <div class="col-sm-8 ">
                        <button type="submit" class="btn btn-success text-light"><b>Tambahkan</b></button>
                    </div>

                </div>

            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<!-- Modal Edit Mesin-->
<div class="modal fade" id="mdlEditMsn" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header justify-content-center bg-warning">
                <h5 class="modal-title text-light" id="">Edit Mesin</h5>
            </div>
            <?= form_open_multipart('transaksi/editMesin', ['id' => 'formeditmesin']) ?>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control " id="id_mesin" name="id_mesin" value="" hidden>
                </div>
                <div class="form-group row">
                    <div class="col-md-4">

                        <label for="jumlah" style="color: black;"> <b>Nama Mesin</b> </label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control " id="nama_mesin" name="nama_mesin">

                    </div>
                </div>
                <div class="form-group row">

                    <div class="col-md-4">

                        <label for="jumlah" style="color: black;"> <b>Harga</b> </label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control " id="harga_mesin" name="harga_mesin" onkeypress='return event.charCode >47 && event.charCode <=57'>

                    </div>

                </div>
                <div class="form-group row">
                    <div class="col-sm-4" style="color: black;"><b>Gambar</b></div>
                    <div class="col-sm-8">

                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="gambar_mesin" name="gambar_mesin">
                            <label id="gambar_mesin" class="custom-file-label" for="gambar_mesin">Choose file</label>
                        </div>


                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-4">

                        <label for="jumlah" style="color: black;"> <b>Kapasitas</b> </label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control " id="kapasitas_mesin" name="kapasitas_mesin">
                    </div>



                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#formeditmesin')[0].reset();">Close</button>
                    <button type="submit" id="btntambah" class="btn btn-warning" onclick="">Edit</button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>