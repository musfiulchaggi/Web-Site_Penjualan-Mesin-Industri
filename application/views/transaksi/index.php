<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800 text-center"><?= $title ?></h1>



    <div class="row">
        <div class="col-lg-9">
            <?= form_error('menu', '<div class="alert alert-danger">', '</div>') ?>
            <?php echo $this->session->flashdata('message'); ?>
            <div class="row">
                <!-- menampilkan seluruh mesin menggunakan php -->
                <?php foreach ($mesin as $ms) : ?>


                    <div class="col-md-4 col-lg-4 px-2 mb-3">

                        <div class="card">
                            <div class="col p-4">

                                <img src="<?= base_url('assets/img/mesin/') . $ms['gambar'] ?>" class="card-img-top rounded border border-primary">
                            </div>
                            <div class="card-body">
                                <h4 class="card-title text-center" style="color: black;"><?= $ms['nama_mesin'] ?></h4>

                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item text-center" style="color: black;"><?= "Rp " . number_format($ms['harga'], 0, ",", "."); ?></li>

                            </ul>
                            <div class="card-body text-center mb-1 ">
                                <a class="memilih btn btn-primary text-center" id="buttonpilihMesin" data-toggle="modal" data-target="#pilihMesin" data-id="<?= $ms['id_mesin'] ?>" data-harga="<?= "Rp " . number_format($ms['harga'], 2, ",", "."); ?>" data-nama="<?= $ms['nama_mesin'] ?>" data-gambar="<?= base_url('assets/img/mesin/') . $ms['gambar'] ?>">Pilih</a>
                            </div>
                        </div>
                    </div>



                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header text-light bg-secondary">
                    <i class="fas fa-shopping-cart"></i>
                    <span style="margin-left: 20px;">Keranjang Penjualan</span>
                </div>
                <div class="card-body" id="daftarKeranjang">


                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->


</div>
<!-- End of Main Content -->

<!-- Modal Add New Menu-->
<div class="modal fade" id="pilihMesin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Pilih Mesin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="row">
                <div class="col-lg-5">
                    <div class="modal-body">
                        <div class="form-group">
                            <img src="" id="gambar" class="img-thumbnail">
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="modal-body">
                        <div class="form-group">
                            <h5 id="namamesin"></h5>
                        </div>
                        <div class="form-group">
                            <h5 id="harga"></h5>
                        </div>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="jumlah" style="color: black;"> <b>Masukkan Jumlah Mesin</b> </label>
                            <input type="text" class="form-control col-lg-3" id="jumlah" name="menu" value="1">
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" id="btntambah" class="btn btn-primary" onclick="">Tambah</button>
            </div>

        </div>
    </div>
</div>

<!-- Modal Add Lnjut Pembayaran -->
<div class="modal fade" id="modallanjutPembayaran" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md w-50">
        <div class="modal-content overflow-auto " style="max-height: 600px;">
            <div class="row">
                <div class="col-lg ">
                    <div class="modal-header bg-dark ">
                        <h4 class="mx-auto" style="color: white;  color:light;">Isi Identitas Pembeli</h4>
                    </div>
                    <form action="<?= base_url('transaksi/lanjutPembayaran') ?> " method="POST" id="formIsiPembeli">
                        <div class="modal-body">

                            <div class="card">
                                <div class="card-body" id="cardLanjutPembayaran">
                                    <!-- diisi daftar gambar penjualan -->


                                </div>
                            </div>
                            <div class="form-group">
                                <input type="text" id="idJual" name="idJual" value="" hidden>
                                <input type="text" id="totalPembayaran" name="total" value="" hidden>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">

                                    <label for="customername">Name :</label>
                                </div>
                                <div class="col-lg-8">

                                    <div class="row">
                                        <input type="text" class="form-control col-md-8" id="customername" name="customername" value="">
                                        <span id="validationname" style="font-size: 10px; color:black;">*harus diisi</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">

                                    <label for="companyname">Company Name :</label>
                                </div>
                                <div class="col-lg-8">

                                    <div class="row">
                                        <input type="text" class="form-control col-md-8" id="companyname" name="companyname" value="">

                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">

                                    <label for="npwp">NPWP :</label>
                                </div>
                                <div class="col-lg-8">

                                    <div class="row">
                                        <input type="text" class="form-control col-md-8" id="npwp" name="npwp" value="">

                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">

                                    <label for="address">Address :</label>
                                </div>
                                <div class="col-lg-8">

                                    <div class="row">
                                        <input type="text" class="form-control col-md-8" id="address" name="address" value="">
                                        <span id="validationaddress" style="font-size: 10px; color:black;">*harus diisi</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-4">

                                    <label for="city">City :</label>
                                </div>
                                <div class="col-lg-8">

                                    <div class="row">
                                        <input type="text" class="form-control col-md-8" id="city" name="city" value="">
                                        <span id="validationcity" style="font-size: 10px; color:black;">*harus diisi</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label for="city">Province :</label>

                                </div>
                                <div class="col-lg-8">

                                    <div class="row">
                                        <input type="text" class="form-control col-md-8" id="province" name="province" value="">
                                        <span id="validationprovince" style="font-size: 10px; color:black;">*harus diisi</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">

                                    <label for="phone">Phone :</label>
                                </div>
                                <div class="col-lg-8">

                                    <div class="row">
                                        <input type="text" class="form-control col-md-8" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="phone" name="phone" value="">
                                        <span id="validationphone" style="font-size: 10px; color:black;">*harus diisi</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">

                                    <label for="sales">Sales Person :</label>
                                </div>
                                <div class="col-lg-8">
                                    <div class="row">
                                        <input type="text" class="form-control col-md-8" id="sales" name="sales" value="">

                                    </div>

                                </div>
                            </div>
                        </div>
                </div>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" id="linkLanjut" class="btn btn-primary">Lanjutkan</button>
            </div>
            </form>
        </div>
    </div>
</div>