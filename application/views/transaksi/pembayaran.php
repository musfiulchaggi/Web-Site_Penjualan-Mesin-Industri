<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-dark text-center"><?= $title ?></h1>


    <?= form_error('menu', '<div class="alert alert-danger">', '</div>') ?>
    <?php echo $this->session->flashdata('message'); ?>

    <!-- menampilkan seluruh mesin menggunakan php -->


    <div class="col px-5 text-center" id="cardDaftarPembayaran">

        <?php foreach ($customer_diproses as $cp) : ?>
            <!-- menampilkan card untuk tiap transaksi belum lunas -->
            <div class="card mb-5 ">
                <div class="card-header bg-primary">
                    <h3 class="text-light"><?= $cp['id_penjualan'] . ' _ ' . $cp['nama'] ?></h3>
                </div>
                <div class="card-body">

                    <!-- menampiilkan pesan -->
                    <?php if ($cp['keterangan'] == 'lunas') {
                        echo '<div class="alert alert-success text-dark" role="alert" style="font-size: 30px;">
                                                   <b> Pembayaran Lunas</b>
                                            </div>';
                    } ?>



                    <div class="row">
                        <div class="col-md-6">
                            <!-- table daftar mesin yang dijual -->
                            <table class="table table-dark table-responsive">
                                <thead>
                                    <tr>
                                        <th scope="col">Mesin</th>
                                        <th scope="col">Jumlah</th>
                                        <th scope="col">Subtotal</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($mesin as $dm) : ?>

                                        <?php
                                        $hasil_rupiah = "Rp " . number_format($dm['harga'] * $dm['jumlah_mesin'], 0, ',', '.');

                                        if ($dm['id_penjualan'] == $cp['id_penjualan']) {
                                            echo '<tr>
                                                            <td class="text-left">' . $dm['nama_mesin'] . '</td>
                                                            <td>' . $dm['jumlah_mesin'] . '</td>
                                                            <td>' .  $hasil_rupiah . '</td>
                                                            
                                                                </tr>';
                                        }

                                        ?>

                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                        </div>
                        <div class="col-md-6">
                            <div class="text-right">
                            </div>
                            <table class="table table-striped table-responsive">
                                <thead class="thead-dark">
                                    <tr class="table-borderless">
                                        <th valign="top" colspan="3"><span>Data Transaksi Penjualan </span>
                                        </th>
                                        <th><button class="transEdit btn btn-primary h-50" id="transEdit" data-shipdate="<?php
                                                                                                                            if ($cp['ship_date']) {
                                                                                                                                echo date_format(date_create_from_format('d-M-y', $cp['ship_date']), 'Y-m-d');
                                                                                                                            } ?>" data-shipvia="<?= $cp['ship_via'] ?>" data-fob="<?= $cp['fob'] ?>" data-term="<?= $cp['term'] ?>" data-ongkir="<?= $cp['ongkir'] ?>" data-id="<?= $cp['id_penjualan'] ?>" data-total="<?= "Rp " . number_format($cp['total'], 0, ',', '.'); ?>" data-toggle="modal" data-target="#editTransaksi">Edit</button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="table-bordered">
                                    <tr>
                                        <td>ID_PJL</td>
                                        <td><?= $cp['id_penjualan']  ?></td>
                                        <td>Tanggal</td>
                                        <td><?= $cp['tanggal']  ?></td>
                                    </tr>

                                    <tr>
                                        <td>Total</td>
                                        <td>
                                            <?php
                                            $hasil_rupiah = "Rp " . number_format($cp['total'], 0, ',', '.');
                                            echo $hasil_rupiah;
                                            ?></td>
                                        <td>Ship Date</td>
                                        <td id="tblSdate"><?php if ($cp['ship_date'] == null) {
                                                                echo '-';
                                                            } else {
                                                                echo $cp['ship_date'];
                                                            }   ?></td>
                                    </tr>

                                    <tr>
                                        <td>Ship Via</td>
                                        <td id="tblSvia"><?php if ($cp['ship_via'] == null) {
                                                                echo '-';
                                                            } else {
                                                                echo $cp['ship_via'];
                                                            }   ?></td>
                                        <td>fob</td>
                                        <td id="tblSfob"><?php if ($cp['fob'] == null) {
                                                                echo '-';
                                                            } else {
                                                                echo $cp['fob'];
                                                            }   ?></td>
                                    </tr>

                                    <tr>
                                        <td>Term</td>
                                        <td id="tblTerm"><?php if ($cp['term'] == null) {
                                                                echo '-';
                                                            } else {
                                                                echo $cp['term'];
                                                            }   ?></td>
                                        <td>Ongkos Kirim</td>
                                        <td id="tblOngkir"><?php if ($cp['ongkir'] == null) {
                                                                echo '-';
                                                            } else {
                                                                echo $hasil_rupiah = "Rp " . number_format($cp['ongkir'], 0, ',', '.');
                                                            }   ?></td>
                                    </tr>



                                </tbody>
                            </table>

                        </div>
                    </div>

                    <!-- second half -->
                    <div class=" row">

                        <div class="col">

                            <table class="table table-striped table-responsive">
                                <thead class="thead-dark">
                                    <tr>
                                        <th colspan="4">Data Pembeli</th>
                                    </tr>
                                </thead>
                                <tbody class="table-bordered">
                                    <tr>
                                        <td>Nama</td>
                                        <td><?= $cp['nama']  ?></td>
                                        <td>Perusahaan</td>
                                        <td><?php if ($cp['nama_perusahaan'] == null) {
                                                echo '-';
                                            } else {
                                                echo $cp['nama_perusahaan'];
                                            }   ?></td>

                                    </tr>

                                    <tr>
                                        <td>Npwp</td>
                                        <td><?php if ($cp['npwp'] == null) {
                                                echo '-';
                                            } else {
                                                echo $cp['npwp'];
                                            }   ?></td>
                                        <td>Alamat</td>
                                        <td><?= $cp['alamat'] ?></td>
                                    </tr>

                                    <tr>
                                        <td>Kota</td>
                                        <td><?= $cp['kota'] ?></td>

                                        <td>Kontak</td>
                                        <td><?= $cp['nomor_wa'] ?></td>


                                    </tr>

                                    <tr>
                                        <td>No. Rek</td>
                                        <td><?php if ($cp['nomor_rekening'] == null) {
                                                echo '-';
                                            } else {
                                                echo $cp['nomor_rekening'];
                                            }   ?></td>
                                        <td>Bank</td>
                                        <td><?php if ($cp['bank'] == null) {
                                                echo '-';
                                            } else {
                                                echo $cp['bank'];
                                            }   ?></td>

                                    </tr>




                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-header">
                                    Riwayat Invoice
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-striped table-responsive " style="font-size:small;">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col" style="font-size: 15px;">No.INV</th>
                                                <th scope="col" style="font-size: 15px;">Tanggal</th>
                                                <th scope="col" style="font-size: 15px;">Jumlah</th>
                                                <th scope="col" style="font-size: 15px;">Termin</th>
                                                <th scope="col" style="font-size: 15px;">Bukti</th>
                                                <th scope="col" style="font-size: 15px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody style="color:black;  font-weight: bold;">
                                            <?php
                                            $no = 1;
                                            $jumlahPMB = 0;
                                            foreach ($invoice as $inv) : ?>

                                                <?php
                                                if ($inv['id_penjualan'] == $cp['id_penjualan']) {

                                                    echo '<tr>
                                                                                <td>' . $no . '</td>
                                                                                <td>' . $inv['tanggal'] . '</td>
                                                                                <td>' . $inv['jumlah'] . '</td>
                                                                                <td>' . $inv['termin'] . '</td>
                                                                                <td><a href="' . base_url('assets/img/bukti_pembayaran/' . $inv['gambar_transfer']) . '" target="blank">' . $inv['gambar_transfer'] . '</a></td>
                                                                                <td><a class="edittermin badge badge-pill badge-warning" data-toggle="modal" data-target="#modalEditPembayaran" data-termin="' . $inv['termin'] . '" data-gambar="' . $inv['gambar_transfer'] . '" data-jumlah="' . $inv['jumlah'] . '" data-terbilang="' . $inv['terbilang'] . '" data-keterangan="' . $inv['keterangan'] . '" data-idPJL="' . $inv['id_penjualan'] . '" href="' . base_url('transaksi/editInvoice/' . $inv['id_penjualan'] . '/' . $inv['termin']) . '">Edit</a>
                                                                                    <a class="cetaktermin badge badge-pill badge-success" data-toggle="modal" data-target="#modalCetakTermin" data-idpenjualan="' . $inv['id_penjualan'] . '" data-termin="' . $inv['termin'] . '" role="button" >Cetak</a>
                                                                                </td>
                                                                            </tr>';

                                                    $no += 1;
                                                    $jumlahPMB += $inv['jumlah'];
                                                }


                                                ?>

                                            <?php endforeach; ?>

                                        </tbody>
                                    </table>



                                </div>
                            </div>

                        </div>

                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-header">
                                    Tambahkan Invoice
                                </div>
                                <div class="card-body">
                                    <div class="row text-left">
                                        <div class="col-md-6">
                                            <span>Total Transaksi :</span><br>
                                            <span>Total Pembayaran :</span><br>
                                            <span>Kurang :</span>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <div class="col bg-success"> <span class=" bg-success text-light"><?php $total = "Rp " . number_format($cp['total'], 0, ',', '.');
                                                                                                                echo $total; ?></span><br></div>
                                            <div class="col bg-primary"> <span class="bg-primary text-light"><?php $pembayaran = "Rp " . number_format($jumlahPMB, 0, ',', '.');
                                                                                                                echo $pembayaran; ?></span><br></div>
                                            <div class="col bg-warning"><span class="bg-warning text-light "><?php $kurang = "Rp " . number_format($cp['total'] - $jumlahPMB, 0, ',', '.');
                                                                                                                echo $kurang;; ?></span></div>



                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col">
                                            <button class="tambahbukti btn btn-primary" data-toggle="modal" data-target="#modalTambahPembayaran" data-idpjl="<?= $cp['id_penjualan'] ?>"> Tambahkan Bukti</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>






                </div>



            </div>


        <?php endforeach; ?>


    </div>
    <!-- /.container-fluid -->
</div>
<!-- End of Main Content -->

<!-- Modal tambah pembayaran-->
<div class="modal fade" id="modalTambahPembayaran" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-light" id="">Masukkan Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?= form_open_multipart('transaksi/addInvoice', ['id' => 'formTambahPMB']) ?>
            <div class="modal-body">
                <input type="text" id="idPJL" name="idPJL" value="" hidden>

                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="jumlahPMB">Jumlah </label>
                    </div>
                    <div class="col-md-8">
                        <input class="form-control" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" name="jumlahPMB" id="jumlahPMB" min="0" placeholder="Nominal Pembayaran">
                    </div>

                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="terbilang">Terbilang </label>
                    </div>
                    <div class="col-md-8">
                        <input class="form-control" type="text" onkeypress="return !(event.charCode >= 48 && event.charCode <= 57)" name="terbilang" id="terbilang" placeholder="-">
                    </div>

                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="customFile">Gambar </label>
                    </div>
                    <div class="col-md-8">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="bukti_pembayaran" name="bukti_pembayaran">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                    </div>

                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="ketTermin">Keterangan Termin </label>
                    </div>
                    <div class="col-md-8">
                        <input class="form-control" type="text" name="ketTermin" id="ketTermin" placeholder="-" value="">
                    </div>

                </div>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#formTambahPMB')[0].reset();">Close</button>
                <button type="submit" id="btnTambahPMB" class="btn btn-primary" onclick="">Tambahkan</button>
            </div>

            <?= form_close() ?>

        </div>
    </div>
</div>

<!-- Modal edit pembayaran-->
<div class="modal fade" id="modalEditPembayaran" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header  bg-warning ">
                <h5 class="modal-title mx-auto" id="" style="color: black;">Edit Pembayaran</h5>

            </div>

            <?= form_open_multipart('transaksi/addInvoice', ['id' => 'formEditPMB']) ?>
            <div class="modal-body">
                <input type="text" id="idPJL" name="idPJL" value="" hidden>
                <input type="text" id="termin" name="termin" value="" hidden>

                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="jumlahPMB">Jumlah </label>
                    </div>
                    <div class="col-md-8">
                        <input class="form-control" type="text" name="jumlahPMB" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="jumlahPMB" min="0" placeholder="Nominal Pembayaran" value="">
                    </div>

                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="terbilang">Terbilang </label>
                    </div>
                    <div class="col-md-8">
                        <input class="form-control" type="text" onkeypress="return !(event.charCode >= 48 && event.charCode <= 57)" name="terbilang" id="terbilang" placeholder="-" value="">
                    </div>

                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="customFile">Gambar </label>
                    </div>
                    <div class="col-md-8">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="bukti_pembayaran" name="bukti_pembayaran" value="">
                            <label class="edit custom-file-label" for="customFile">Choose file</label>
                        </div>
                    </div>

                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="ketTermin">Keterangan Termin </label>
                    </div>
                    <div class="col-md-8">
                        <input class="form-control" type="text" name="ketTermin" id="ketTermin" placeholder="-" value="">
                    </div>

                </div>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#formEditPMB')[0].reset();">Close</button>
                <button type="submit" id="btnEditPMB" class="btn btn-primary" onclick="">Ubah</button>
            </div>

        </div>
        <?= form_close() ?>
    </div>
</div>

<!-- Modal cetak pembayaran-->
<div class="modal fade" id="modalCetakTermin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg w-50">
        <div class="modal-content">
            <div class="modal-header  bg-primary ">
                <h5 class="modal-title text-light mx-auto" id=""><b>Cetak Pembayaran</b> </h5>
            </div>

            <form action="<?= base_url('transaksi/cetakInvoice') ?>" method="post" id="formcetakTrm">
                <div class="modal-body">
                    <input type="text" id="idPJL" name="idPJL" value="" hidden>
                    <input type="text" id="termin" name="termin" value="" hidden>

                    <table>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Bukti</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="namaCust"></td>
                                    <td id="TglCust"></td>
                                    <td id="BuktiCust" width="40%"></td>
                                </tr>

                            </tbody>
                        </table>
                    </table>

                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="jumlahPMBCTK">Jumlah </label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control text-left" type="text" name="jumlahPMBCTK" id="jumlahPMBCTK" min="0" placeholder="Nominal Pembayaran" value="" readonly>
                        </div>

                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="terbilangCTK">Terbilang </label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control text-left" type="text" name="terbilangCTK" id="terbilangCTK" placeholder="-" value="" readonly>
                        </div>

                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="terbilang">Keterangan Termin </label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control" type="text" name="ketTermin" id="ketTermin" placeholder="-" value="" readonly>
                        </div>

                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="Komentar">Komentar</label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control" type="text" name="komentar1" id="komentar1" placeholder="-" value="" style="font-size: 15px; font-weight:bold; ">
                            <input class="form-control mt-1" type="text" name="komentar2" id="komentar2" placeholder="komentar_2" value="" style="font-size: 15px; font-weight:bold; ">
                            <input class="form-control mt-1" type="text" name="komentar3" id="komentar3" placeholder="komentar_3" value="" style="font-size: 15px; font-weight:bold; ">
                        </div>

                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#formcetakTrm')[0].reset();">Close</button>
                    <button type="submit" id="btnCetakPMB" class="btn btn-primary" onclick="">Cetak</button>
                </div>

        </div>
        <?= form_close() ?>
    </div>
</div>

<!-- Modal edit transaksi pembayaran-->
<div class="modal fade" id="editTransaksi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg w-50">
        <div class="modal-content">
            <div class="modal-header  bg-primary ">
                <h5 class="modal-title text-light mx-auto" id=""><b>Edit Transaksi Pembayaran</b> </h5>
            </div>

            <form action="<?= base_url('transaksi/editTransaksi') ?>" method="POST" id="formeditTrans">
                <div class="modal-body">


                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="idPenjualan">Id Penjualan </label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control text-left" type="text" name="idPenjualan" id="idPenjualan" value="" readonly>
                        </div>

                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="totalTrans">Total </label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control text-left" type="text" name="totalTrans" id="totalTrans" placeholder="-" value="" readonly>
                        </div>

                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="shipVia">Ship Via </label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control" type="text" name="shipVia" id="shipVia" placeholder="-" value="">
                        </div>

                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="ongkir">Ongkos Kirim </label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control" onkeypress="return event.charCode >= 48 && event.charCode<=57" type="text" name="ongkir" id="ongkir" placeholder="-" value="">
                        </div>

                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="shipDate">Ship Date </label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control" data-date="09/07/2022" value="" type="date" name="shipDate" id="shipDate" placeholder="-">
                        </div>

                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="fob">FOB </label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control" type="text" name="fob" id="fob" placeholder="-" value="">
                        </div>

                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="term">Term </label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control" type="text" name="term" id="term" placeholder="-" value="">
                        </div>

                    </div>

                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#formeditTrans')[0].reset();">Close</button>
                    <button type="submit" id="" class="btn btn-primary" role="button" target="blank">Simpan</button>
                </div>

        </div>
        <?= form_close() ?>
    </div>
</div>