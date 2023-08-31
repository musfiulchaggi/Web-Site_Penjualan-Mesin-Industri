<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800 text-center"><?= $title ?></h1>



    <div class="row">

        <div class="col">
            <div class="col-md-4">
                <div class="input-group">
                    <select class="custom-select" id="selecttrans" aria-label="Example select with button addon">
                        <option selected>Semua</option>
                        <option value="1">Lunas</option>
                        <option value="2">Belum Lunas</option>
                    </select>
                    <div class="input-group-append">
                        <a href="<?= base_url('admin/export/') ?>" class="btn btn-success exportlap"> Export Excel <i class="fas fa-file-export"></i></a>


                    </div>
                </div>
            </div>


            <div class="col" style="margin-top: 4rem;">
                <table id="transsu" class="table thead-dark table-striped table-bordered table-responsive" style="font-size:small;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Transaksi</th>
                            <th>Total Trans</th>
                            <th>Total Dibayarkan</th>
                            <th>Admin</th>
                            <th>Nama Mesin</th>
                            <th>Harga Mesin</th>
                            <th>Jumlah Mesin</th>
                            <th>Nama Pelanggan</th>
                            <th>Perusahaan</th>
                            <th>Nomor WA</th>
                            <th>Ongkir</th>
                            <th>Ship Date</th>
                            <th>Ship Via</th>
                            <th>FOB</th>
                            <th>Term</th>
                            <th>Sales</th>
                            <th>Keterangan</th>
                            <th>Lihat Invoice</th>

                        </tr>
                    </thead>

                    <tbody id="tabeltrans">
                        <?php $no = 1;
                        foreach ($transaksi as $ts) : ?>
                            <tr>
                                <td class="font-weight-normal" style="color: black; "> <?= $no ?></td>
                                <td class="font-weight-normal" style="color: black; "> <?= $ts['id_penjualan'] ?></td>
                                <td class="font-weight-normal text-right" style="color: black; "> <?php
                                                                                                    $hasil_rupiah = number_format($ts['total'], 0, ',', '.');
                                                                                                    echo $hasil_rupiah;
                                                                                                    ?></td>
                                <td class="font-weight-normal text-right" style="color: black; "> <?php
                                                                                                    $hasil_rupiah2 = null;
                                                                                                    foreach ($dibayarkan as $db) {
                                                                                                        if ($ts['id_penjualan'] == $db['id_penjualan']) {
                                                                                                            $hasil_rupiah2 = number_format($db['dibayarkan'], 0, ',', '.');
                                                                                                            echo $hasil_rupiah2;
                                                                                                        }
                                                                                                    }
                                                                                                    if ($hasil_rupiah2 == null) {
                                                                                                        echo '0';
                                                                                                    }


                                                                                                    ?></td>
                                <td class="font-weight-normal" style="color: black; "> <?= $ts['name'] ?></td>
                                <td class="font-weight-normal" style="color: black; "> <?= $ts['nama_mesin'] ?></td>
                                <td class="font-weight-normal text-right" style="color: black; "> <?php
                                                                                                    $hasil_rupiah = number_format($ts['harga'], 0, ',', '.');
                                                                                                    echo $hasil_rupiah;
                                                                                                    ?></td>
                                <td class="font-weight-normal" style="color: black; "> <?= $ts['jumlah_mesin'] ?></td>
                                <td class="font-weight-normal" style="color: black; "> <?= $ts['nama'] ?></td>
                                <td class="font-weight-normal" style="color: black; "> <?= $ts['nama_perusahaan'] ?></td>
                                <td class="font-weight-normal" style="color: black; "> <?= $ts['nomor_wa'] ?></td>
                                <td class="font-weight-normal text-right" style="color: black; "> <?php
                                                                                                    $hasil_rupiah = number_format($ts['ongkir'], 0, ',', '.');
                                                                                                    echo $hasil_rupiah;
                                                                                                    ?></td>
                                <td class="font-weight-normal" style="color: black; "> <?= $ts['ship_date'] ?></td>
                                <td class="font-weight-normal" style="color: black; "> <?= $ts['ship_via'] ?></td>
                                <td class="font-weight-normal" style="color: black; "> <?= $ts['fob'] ?></td>
                                <td class="font-weight-normal" style="color: black; "> <?= $ts['term'] ?></td>
                                <td class="font-weight-normal" style="color: black; "> <?= $ts['sales'] ?></td>
                                <td class="font-weight-normal" style="color: black; "> <?= $ts['keterangan'] ?></td>
                                <td class="font-weight-normal" style="color: black; "> <button class="lihatterminadm btn btn-primary btn-sm" data-toggle="modal" data-target="#mdllihatterminadm" data-idpjl="<?= $ts['id_penjualan'] ?>"><small>Lihat</small></button> </td>


                            </tr>
                            <?php $no++; ?>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>


        </div>
    </div>

</div>
<!-- /.container-fluid -->


</div>
<!-- End of Main Content -->