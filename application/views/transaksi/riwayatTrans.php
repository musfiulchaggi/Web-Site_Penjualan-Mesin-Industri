<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800 text-center"><?= $title ?></h1>



    <div class="row">

        <div class="col">
            <div class="col-md-12">
                <div class="row">

                    <div class="col-auto ml-auto">
                        <a href="<?= base_url('transaksi/export/' . $user['id_user']) ?>" class="btn btn-success"><i class="fas fa-file-export"> Sheet</i></a>

                    </div>
                </div>
            </div>

            <div class="col mt-3">
                <table id="tblRiwayatTrans" class="table thead-dark table-striped table-bordered table-responsive" style="font-size:small;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Customer</th>
                            <th>Company</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>Phone</th>
                            <th>Total</th>
                            <th>Transaction Date</th>
                            <th>Ship Date</th>
                            <th>Shipping Cost</th>
                            <th>Ship Via</th>
                            <th>Sales</th>
                            <th>Desc</th>

                        </tr>
                    </thead>

                    <tbody>
                        <?php $no = 1;
                        foreach ($transaksi as $ts) : ?>
                            <tr>
                                <td class="font-weight-normal" style="color: black; font-size:smaller;"> <?= $no ?></td>
                                <td class="font-weight-normal" style="color: black; font-size:smaller;"> <?= $ts['nama'] ?></td>
                                <td class="font-weight-normal" style="color: black; font-size:smaller;"> <?= $ts['nama_perusahaan'] ?></td>
                                <td class="font-weight-normal" style="color: black; font-size:smaller;"> <?= $ts['alamat'] ?></td>
                                <td class="font-weight-normal" style="color: black; font-size:smaller;"> <?= $ts['kota'] ?></td>
                                <td class="font-weight-normal" style="color: black; font-size:smaller;"> <?= $ts['nomor_wa'] ?></td>
                                <td class="font-weight-normal" style="color: black; font-size:smaller;"> <?= $ts['total'] ?></td>
                                <td class="font-weight-normal" style="color: black; font-size:smaller;"> <?= $ts['tanggal'] ?></td>
                                <td class="font-weight-normal" style="color: black; font-size:smaller;"> <?= $ts['ship_date'] ?></td>
                                <td class="font-weight-normal" style="color: black; font-size:smaller;"> <?= $ts['ongkir'] ?></td>
                                <td class="font-weight-normal" style="color: black; font-size:smaller;"> <?= $ts['ship_via'] ?></td>
                                <td class="font-weight-normal" style="color: black; font-size:smaller;"> <?= $ts['sales'] ?></td>
                                <td class="font-weight-normal" style="color: black; font-size:smaller;"> <?= $ts['keterangan'] ?></td>


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