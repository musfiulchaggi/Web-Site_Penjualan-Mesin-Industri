<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        td {
            font-size: 8px;
        }
    </style>

</head>

<body>
    <table cellpadding="2" width="100%" style="border-collapse: collapse; border: 1px solid black;">
        <tr>
            <td colspan="3"><img src="<?php
                                        //Use this code to convert your image to base64
                                        // Apply this in a view 

                                        $path = base_url('assets/img/profile/Logoinagi.png'); // Modify this part (your_img.png
                                        $type = pathinfo($path, PATHINFO_EXTENSION);
                                        $data = file_get_contents($path);
                                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                        echo $base64;
                                        ?>" alt="" style="height: 75px;"> </td>
            <td colspan="3" valign="top" style="text-align: left; color:cornflowerblue; font-size:large;">INVOICE</td>
        </tr>
        <tr>
            <td colspan="3"> PT. INOVASI ANAK NEGERI</td>
            <td><b>Date</b> </td>
            <td colspan="2" style="text-align: center;"><?= $termin['tanggal'] ?></td>
        </tr>
        <tr>
            <td colspan="3">Jalan Raya Sekarpuro No.01, Pakis</td>
            <td><b>INVOICE</b> </td>
            <td colspan="2" style="text-align: left;"><?= $penjualan_cust['id_penjualan'] . '/' . $termin['termin'] ?>/INV/IAN/VI/2022</td>
        </tr>

        <tr>
            <td colspan="6">Malang, Jawa Timur</td>
        </tr>
        <tr>
            <td colspan="6">Office : (0341) 3024389</td>
        </tr>
        <tr>
            <td colspan="6"> <a href="http://www.jualmesin.co.id">www.jualmesin.co.id,</a> <a href="http://www.susulistrik.com">www.susulistrik.com</a> </td>
        </tr>
        <tr>
            <td width="14%"><b>
                    &nbsp;
                </b></td>
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="24%"><b>
                    &nbsp;
                </b></td>
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="19%"><b>
                    &nbsp;
                </b></td>
            <td width="19%"><b>
                    &nbsp;
                </b></td>
        </tr>

        <tr>
            <td style="background-color: #6495ED;"><b>RECIEVED FROM</b></td>
            <td colspan="5"><b>
                    &nbsp;
                </b></td>
        </tr>
        <tr>
            <td><b>Name</b></td>
            <td colspan="5"><b><?= $penjualan_cust['nama'] ?></b></td>
        </tr>
        <tr>
            <td><b>Company Name</b></td>
            <td colspan="5"><b><?= $penjualan_cust['nama_perusahaan'] ?></b></td>
        </tr>
        <tr>
            <td><b>NPWP</b></td>
            <td colspan="5"><b><?= $penjualan_cust['npwp'] ?></b></td>
        </tr>
        <tr height="60px" valign="top" style="text-align: left; ">
            <td><b>Address</b></td>
            <td colspan="5"><b><?= $penjualan_cust['alamat'] ?></b></td>
        </tr>
        <tr>
            <td><b>City</b></td>
            <td colspan="5"><b><?= $penjualan_cust['kota'] . "   " . $penjualan_cust['provinsi'] ?></b></td>
        </tr>
        <tr>
            <td><b>Phone</b></td>
            <td colspan="5"><b><?= $penjualan_cust['nomor_wa'] ?></b></td>
        </tr>
        <tr>
            <td width="14%"><b>
                    &nbsp;
                </b></td>
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="24%"><b>
                    &nbsp;
                </b></td>
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="19%"><b>
                    &nbsp;
                </b></td>
            <td width="19%"><b>
                    &nbsp;
                </b></td>
        </tr>
        <tr style="text-align: center;">
            <td width="12%" style="background-color: #6495ED; border: 1px solid black; "><b>Sales Person</b></td>
            <td width="10%" style="background-color: #6495ED; border: 1px solid black;"><b>P.O.#</b></td>
            <td width="28%" style="background-color: #6495ED; border: 1px solid black;"><b>SHIP DATE</b></td>
            <td width="12%" style="background-color: #6495ED; border: 1px solid black;"><b>SHIP VIA</b></td>
            <td width="19%" style="background-color: #6495ED; border: 1px solid black;"><b>FOB</b></td>
            <td width="19%" style="background-color: #6495ED; border: 1px solid black;"><b>TERM</b></td>
        </tr>
        <tr style="text-align: center;">
            <td width="12%" style="border: 1px solid black;"><?= $mesin_dijual[0]['sales'] ?></td>
            <td width="10%" style="border: 1px solid black;"></td>
            <td width="28%" style="border: 1px solid black;"><?= $mesin_dijual[0]['ship_date'] ?></td>
            <td width="12%" style="border: 1px solid black;"><?= $mesin_dijual[0]['ship_via'] ?></td>
            <td width="19%" style="border: 1px solid black;"><?= $mesin_dijual[0]['fob'] ?></td>
            <td width="19%" style="border: 1px solid black;"><?= $mesin_dijual[0]['term'] ?></td>
        </tr>
        <tr style="text-align: center;">
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="10%"><b>
                    &nbsp;
                </b></td>
            <td width="28%"><b>
                    &nbsp;
                </b></td>
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="19%"><b>
                    &nbsp;
                </b></td>
            <td width="19%"><b>
                    &nbsp;
                </b></td>
        </tr>
        <tr style="text-align: center;">
            <td width="12%" style="background-color: #6495ED; border: 1px solid black; "><b>No</b></td>
            <td width="10%" style="background-color: #6495ED; border: 1px solid black;"><b>ITEM#</b></td>
            <td width="28%" style="background-color: #6495ED; border: 1px solid black;"><b>DESCRIPTIONS</b></td>
            <td width="12%" style="background-color: #6495ED; border: 1px solid black;"><b>QTY</b></td>
            <td width="19%" style="background-color: #6495ED; border: 1px solid black;"><b>UNIT PRICE</b></td>
            <td width="19%" style="background-color: #6495ED; border: 1px solid black;"><b>TOTAL</b></td>
        </tr>
        <?php
        $no = 1;
        for ($i = 0; $i < 10; $i++) {

            if ($i < count($mesin_dijual)) {
                echo    '<tr style="text-align: center;">
                            <td width="12%" style="border: 1px solid black;">' . $no . '</td>
                            <td width="10%" style="border: 1px solid black;"></td>
                            <td width="28%" style="border: 1px solid black;">' . $mesin_dijual[$i]['nama_mesin'] . '</td>
                            <td width="12%" style="border: 1px solid black;">' . $mesin_dijual[$i]['jumlah_mesin'] . '</td>
                            <td width="19%" style="border: 1px solid black;"><span>' . "Rp " . number_format($mesin_dijual[$i]['harga'], 0, ',', '.') . '</span></td>
                            <td width="19%" style="border: 1px solid black;">' . "Rp " . number_format($mesin_dijual[$i]['jumlah_mesin'] * $mesin_dijual[$i]['harga'], 0, ',', '.')  . '</td>
                        </tr>';
                $no++;
            } else {
                echo    '<tr style="text-align: center;">
                            <td width="12%" style="border: 1px solid black;"> &nbsp;</td>
                            <td width="10%" style="border: 1px solid black;"> &nbsp;</td>
                            <td width="28%" style="border: 1px solid black;"> &nbsp;</td>
                            <td width="12%" style="border: 1px solid black;"> &nbsp;</td>
                            <td width="19%" style="border: 1px solid black;"> &nbsp;</td>
                            <td width="19%" style="border: 1px solid black;"> &nbsp;</td>
                        </tr>';
            }
        } ?>



        <!-- end loop -->
        <tr>
            <td width="14%"><b>
                    &nbsp;
                </b></td>
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="24%"><b>
                    &nbsp;
                </b></td>
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="19%"><b>Jumlah Total</b></td>
            <td width="19%"><span><?= "Rp " . number_format($penjualan_cust['total'], 0, ',', '.')  ?></span></td>
        </tr>
        <tr>
            <td width="14%"><b>
                    &nbsp;
                </b></td>
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="24%"><b>
                    &nbsp;
                </b></td>
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="19%"><b>Biaya Pengiriman</b></td>
            <td width="19%"><span><?php
                                    if ($penjualan_cust['ongkir']) {
                                        echo  "Rp " . number_format($penjualan_cust['ongkir'], 0, ',', '.');
                                    } ?></span></td>
        </tr>
        <tr>
            <td width="14%"><b>
                    &nbsp;
                </b></td>
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="24%"><b>
                    &nbsp;
                </b></td>
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="19%"><b>DP 50%</b></td>
            <td width="19%"><span><?php
                                    echo  "Rp " . number_format($penjualan_cust['total'] / 2, 0, ',', '.');
                                    ?></span></td>
        </tr>
        <tr>
            <td width="14%"><b>
                    &nbsp;
                </b></td>
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="24%"><b>
                    &nbsp;
                </b></td>
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="19%"><b>Total</b></td>
            <td width="19%"><span><?php
                                    if ($termin['jumlah']) {
                                        echo  "Rp " . number_format($termin['jumlah'], 0, ',', '.');
                                    } ?></span></td>
        </tr>
        <tr style="text-align: center;">
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="10%"><b>
                    &nbsp;
                </b></td>
            <td width="28%"><b>
                    &nbsp;
                </b></td>
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="19%"><b>
                    &nbsp;
                </b></td>
            <td width="19%"><b>
                    &nbsp;
                </b></td>
        </tr>
        <tr style="text-align: center;">
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="10%"><b>
                    &nbsp;
                </b></td>
            <td width="28%"><b>
                    &nbsp;
                </b></td>
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="19%"><b>
                    &nbsp;
                </b></td>
            <td width="19%"><b>
                    &nbsp;
                </b></td>
        </tr>
        <tr style="text-align: center;">
            <td width="50%" colspan="3" style="text-align:left; border: 1px solid black; background-color: #6495ED; ">Other Comment & Special instructions</td>
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="19%"><b>
                    &nbsp;
                </b></td>
            <td width="19%"><b>
                    &nbsp;
                </b></td>
        </tr>
        <tr style="text-align: center;">
            <td width="50%" colspan="3" style="text-align:left; border: 1px solid black; "><?= '1. ' . $komentar[0] ?></td>
            <td width="31%" colspan="2"><b>
                    Terbilang
                </b></td>

            <td width="19%" style="font-style: italic;">
                <?php echo  $termin['terbilang']; ?>
            </td>
        </tr>
        <tr style="text-align: center;">
            <td width="50%" colspan="3" style="text-align:left; border: 1px solid black; "><?php if ($komentar[1] != "") {
                                                                                                echo '2. ' . $komentar[1];
                                                                                            }  ?></td>
            <td width="31%" colspan="2"><b>
                    &nbsp;
                </b></td>

            <td width="19%" style="font-style: italic;">
                &nbsp;
            </td>
        </tr>
        <tr style="text-align: center;">
            <td width="50%" colspan="3" style="text-align:left; border: 1px solid black; "><?php if ($komentar[2] != "") {
                                                                                                echo '3. ' . $komentar[2];
                                                                                            }  ?></td>
            <td width="31%" colspan="2"><b>
                    Keterangan
                </b></td>

            <td width="19%">
                <?= $termin['keterangan'] ?>
            </td>
        </tr>
        <tr style="text-align: center;">
            <td width="50%" colspan="3" style="text-align:left; border: 1px solid black; "></td>
            <td width="31%" colspan="2"><b>
                    &nbsp;
                </b></td>

            <td width="19%" style="font-style: italic;">
                &nbsp;
            </td>
        </tr>
        <tr style="text-align: center;">
            <td width="50%" colspan="3" style="text-align:left; border: 1px solid black; "></td>

            <td width="50%" colspan="3" style="border: 1px solid black;  background-color: #6495ED; ">
                Make all checks payble to

            </td>
        </tr>
        <tr style="text-align: center;">
            <td width="50%" colspan="3" style="text-align:left;  "> &nbsp;</td>

            <td width="50%" colspan="3" style="border: 1px solid black;  ">
                Mandiri Number : 1440016536499
            </td>
        </tr>
        <tr style="text-align: center;">
            <td width="50%" colspan="3" style="text-align:left;  "> &nbsp;</td>

            <td width="50%" colspan="3" style="border: 1px solid black;  ">
                atas nama : INOVASI ANAK NEGERI
            </td>
        </tr>
        <tr style="text-align: center;">
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="10%"><b>
                    &nbsp;
                </b></td>
            <td width="28%"><b>
                    &nbsp;
                </b></td>
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="19%"><b>
                    &nbsp;
                </b></td>
            <td width="19%"><b>
                    &nbsp;
                </b></td>
        </tr>
        <tr style="text-align: center;">
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="10%"><b>
                    &nbsp;
                </b></td>
            <td width="28%"><b>
                    &nbsp;
                </b></td>
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="19%"><b>
                    &nbsp;
                </b></td>
            <td width="19%"><b>
                    &nbsp;
                </b></td>
        </tr>

        <tr style="text-align: center;">
            <td width="50%" colspan="3"> &nbsp;</td>

            <td width="50%" colspan="3" style="text-align:left;  ">
                Director
            </td>
        </tr>
        <tr style="text-align: center;">
            <td width="50%" colspan="3"> &nbsp;</td>

            <td width="50%" colspan="3" style="text-align:left;  ">
                PT Inovasi Anak Negeri
            </td>
        </tr>
        <tr style="text-align: left;">
            <td width="40%" colspan="3"> &nbsp;</td>

            <td width="60%" colspan="3">
                <img src="<?php
                            //Use this code to convert your image to base64
                            // Apply this in a view 

                            $path = base_url('assets/img/profile/Signature.png'); // Modify this part (your_img.png
                            $type = pathinfo($path, PATHINFO_EXTENSION);
                            $data = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            echo $base64;
                            ?>" alt="" style="height: 100px;">
            </td>
        </tr>
        <tr style="text-align: center;">
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="10%"><b>
                    &nbsp;
                </b></td>
            <td width="28%"><b>
                    &nbsp;
                </b></td>
            <td width="12%"><b>
                    &nbsp;
                </b></td>
            <td width="19%"><b>
                    &nbsp;
                </b></td>
            <td width="19%"><b>
                    &nbsp;
                </b></td>
        </tr>
        <tr style="text-align: center;">
            <td width="100%" colspan="6"> If you have any question about this invoice, please contact</td>


        </tr>
        <tr style="text-align: center;">
            <td width="100%" colspan="6"> Fa Rizky BP, 085-257-688-855, <a href="mailto:farizqibp@gmail.com">farizqibp@gmail.com</a> </td>


        </tr>
        <tr style="text-align: center;">
            <td width="100%" colspan="6">Thank you for your Bussiness </td>


        </tr>
        <tr style="text-align: center;">
            <td width="100%" colspan="6">&nbsp;</td>


        </tr>

    </table>
    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('assets/') ?>vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets/') ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- <script>
        $(document).ready(function() {

            $.ajax({
                type: "post",
                url: "<?= base_url('transaksi/praCetakInvoice') ?>",
                data: {
                    'idpjl': 42,
                    'termin': 1
                },
                dataType: "JSON",
                success: function(response) {
                    console.log(response)

                }
            });
        });
    </script> -->
</body>

</html>