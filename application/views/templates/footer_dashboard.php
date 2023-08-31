<!-- Modal cetak pembayaran-->
<div class="modal fade" id="mdllihatterminadm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg w-70">
        <div class="modal-content">
            <div class="modal-header  bg-primary ">
                <h5 class="modal-title text-light mx-auto" id=""><b>Riwayat Pembayaran</b> </h5>
            </div>

            <div class="modal-body">
                <div>
                    <table class="table table-responsive">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Id Penjualan</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Nama Perusahaan</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Jumlah</th>
                                <th scope="col">Bukti</th>
                                <th scope="col">Lihat Invoice</th>
                            </tr>
                        </thead>
                        <tbody id="daftartermin">


                        </tbody>
                    </table>

                </div>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="">Close</button>
            </div>

        </div>
    </div>
</div>
<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; Web Musfiul Chaggi <?= date('Y') ?></span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="<?= base_url('auth/logout') ?>">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('assets/') ?>vendor/jquery/jquery.min.js"></script>
<script src="<?= base_url('assets/') ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


<!-- Core plugin JavaScript-->
<script src="<?= base_url('assets/') ?>vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?= base_url('assets/') ?>js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="<?php echo base_url() ?>assets/vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script>
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    function number_format(number, decimals, dec_point, thousands_sep) {
        // *     example: number_format(1234.56, 2, ',', ' ');
        // *     return: '1 234,56'
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    // Area Chart Example
    var ctx = document.getElementById("myAreaChart");

    if (ctx != null) {
        // ajax mengambil laporan penjualan
        $.ajax({
            type: "POST",
            url: "<?= base_url('admin/penjualan') ?>",
            data: "",
            dataType: "JSON",
            success: function(response) {
                console.log(response);

                let labels = [];
                let data_penjualan = [];
                $.each(response, function(i, item) {
                    labels.push(item['monthname(tanggal_input)']);
                    data_penjualan.push(item['sum(total)']);


                });


                var myLineChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: "Earnings",
                            lineTension: 0.3,
                            backgroundColor: "rgba(78, 115, 223, 0.05)",
                            borderColor: "rgba(78, 115, 223, 1)",
                            pointRadius: 3,
                            pointBackgroundColor: "rgba(78, 115, 223, 1)",
                            pointBorderColor: "rgba(78, 115, 223, 1)",
                            pointHoverRadius: 3,
                            pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                            pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                            pointHitRadius: 10,
                            pointBorderWidth: 2,
                            data: data_penjualan,
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                left: 10,
                                right: 25,
                                top: 25,
                                bottom: 0
                            }
                        },
                        scales: {
                            xAxes: [{
                                time: {
                                    unit: 'date'
                                },
                                gridLines: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    maxTicksLimit: 7
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    maxTicksLimit: 5,
                                    padding: 10,
                                    // Include a dollar sign in the ticks
                                    callback: function(value, index, values) {
                                        return 'Rp.' + number_format(value);
                                    }
                                },
                                gridLines: {
                                    color: "rgb(234, 236, 244)",
                                    zeroLineColor: "rgb(234, 236, 244)",
                                    drawBorder: false,
                                    borderDash: [2],
                                    zeroLineBorderDash: [2]
                                }
                            }],
                        },
                        legend: {
                            display: false
                        },
                        tooltips: {
                            backgroundColor: "rgb(255,255,255)",
                            bodyFontColor: "#858796",
                            titleMarginBottom: 10,
                            titleFontColor: '#6e707e',
                            titleFontSize: 14,
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            intersect: false,
                            mode: 'index',
                            caretPadding: 10,
                            callbacks: {
                                label: function(tooltipItem, chart) {
                                    var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                    return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
                                }
                            }
                        }
                    }
                });
            }
        });
    }
</script>

<!-- datatables -->
<script src="<?= base_url('assets/') ?>vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets/') ?>vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- pusher -->
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('8459db692d2df931dcd7', {
        cluster: 'ap1'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(pesan) {

        // alert(pesan.message);

        xhr = $.ajax({
            type: "POST",
            url: "<?= base_url('admin/tampil_notif') ?>",
            success: function(response) {
                $('.list-notifikasi').html(response);
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        loadKeranjang();
        $('#tblRiwayatTrans').DataTable();
        $('#tblMember').DataTable();


        var table = $('#tblBarang').DataTable({
            pageLength: 3,
            lengthMenu: [
                [3, 5, 10, 20, -1],
                [3, 5, 10, 20, 'Todos']
            ]
        })

        var table2 = $('#transsu').DataTable({
            pageLength: 3,
            lengthMenu: [
                [3, 5, 10, 20, -1],
                [3, 5, 10, 20, 'Todos']
            ]
        })


    });




    //untuk mangakali upload file edit profile
    $('.custom-file-input').on('change', function() {
        let filename = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(filename);
    })


    // unuk mengakali saat terjadi perubahan di form check role
    $('.form-check-input').on('click', function() {
        const menuId = $(this).data('menu');
        const roleId = $(this).data('role');

        $.ajax({
            url: "<?= base_url('admin/changeAccess') ?>",
            type: 'POST',
            data: {
                menuId: menuId,
                roleId: roleId
            },
            success: function() {
                // meredirect halaman ke roleaccess
                document.location.href = "<?= base_url('admin/roleAccess/') ?>" +
                    roleId;
            }
        });
    })


    // menambahkan pilihan mesin kekeranjang
    // agar jquery bisa mencari multiple atribut menggunakan class.
    // tidak menggunakan # (id) yang bisa hanya satu atribut
    $('.memilih').on('click', function() {

        let nama = $(this).data('nama');
        let harga = $(this).data('harga');
        let gambar = $(this).data('gambar');
        let id = $(this).data('id');

        $('#pilihMesin #gambar').attr('src', gambar);
        $('#pilihMesin #namamesin').html(nama);
        $('#pilihMesin #harga').html(harga);

        $('#btntambah').attr('onclick', 'tambahKeranjang(' + id + ')')



    })

    function tambahKeranjang($id) {

        let id = $id;
        let jumlah = $('#jumlah').val();


        $.ajax({
            type: "POST",
            url: "<?= base_url('transaksi/simpanKeranjang') ?>",
            data: {
                "id": id,
                "jumlah": jumlah
            },
            dataType: "JSON",
            success: function(response) {

                $('#pilihMesin').modal('hide');
                // alert(response.message);
                $('#daftarKeranjang').html('');
                loadKeranjang();

            }
        });
    }

    function loadKeranjang() {

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Transaksi/loaddaftar') ?>",
            data: "",
            dataType: "JSON",
            success: function(response) {

                // console.log(response);

                if (response.message == 'Data Kosong') {

                    $('#daftarKeranjang').html(`<h5 class="card-title text-center">Daftar Kosong</h5>`);
                } else {
                    $('#daftarKeranjang').html('');


                    //looping ke daftar keranjang 
                    $.each(response, function(i, item) {
                        $('#daftarKeranjang').append(`
                                                        <div class="row">
                                                            <h5 class="card-title col-lg-8">` + item['nama_mesin'] + `</h5>
                                                            <div class="col-lg-4">
                                                                <b> X ` + item['jumlah_mesin'] + `</b>
                                                            </div>
                                                           
                                                        </div>
                                                       
                                                        <div class="row ">
                                                            <div class="col-lg-9"
                                                             <small>   <b>` + formatRupiah(item['subtotal']) + `</b></small>
                                                            </div>
                                                            <button class="btn btn-sm btn-danger mr-1" onclick="hapusDaftar(` + item['id_penjualan'] + `,` + item['id_mesin'] + `)"><b>X</b></button>
                                                        </div> 
                                                        <hr>`);

                    });


                    $('#daftarKeranjang').append(`
                                            <div class="row justify-content-center">
                                                <button class="btn btn-warning" id="btnLanjutPembayaran" data-toggle="modal" data-target="#modallanjutPembayaran" data-idjual="` + response[0]['id_penjualan'] + `">Lanjutkan Pembayaran</button>
                                            </div>
                    `);


                }







            }
        });



    }

    function hapusDaftar($id_penjualan, $id_mesin) {
        let id_penjualan = $id_penjualan;
        let id_mesin = $id_mesin;

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Transaksi/hapusDaftar') ?>",
            data: {
                'id_penjualan': id_penjualan,
                'id_mesin': id_mesin
            },
            dataType: "JSON",
            success: function(response) {

                console.log(response)
                loadKeranjang();

            }
        });
    }

    function formatRupiah(money) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(money);
    }

    $('#daftarKeranjang').on('click', '#btnLanjutPembayaran', function() {
        let idPenjualan = $(this).data('idjual');
        let total = 0;

        $.ajax({
            type: "POST",
            url: "<?= base_url('transaksi/lanjutPembayaran') ?>",
            data: {
                'idJual': idPenjualan,
                'proses': 'tampil'
            },
            dataType: "JSON",
            success: function(response) {
                $('#cardLanjutPembayaran').html('');

                console.log(response);

                // menampilkan mesin di form pembeli di modal lanjutpembayaran
                $.each(response, function(i, item) {
                    total += parseInt(item['harga'] * item['jumlah_mesin']);

                    $('#cardLanjutPembayaran').append(`<div class="row mb-3">
                                                        <div class="col-lg-4">
                                                            <img src="<?= base_url('assets/img/mesin/') ?>` + item['gambar'] + `" class="img img-thumbnail">
                                                                </div>
                                                                    <div class="col-lg-8" style="font-size: 30px;">
                                                                        <div class="row ">
                                                                            <p><b>` + item['nama_mesin'] + `</b></p>
                                                                            
                                                                        </div>
                                                                        <span style="font-size: 15px;" class="text-left">( x : ` + item['jumlah_mesin'] + `) </span>
                                                                        <span style="font-size: 20px;" class="text-right font-weight-bold"> ` + formatRupiah(item['harga'] * item['jumlah_mesin']) + `</span>
    
                                                                         </div>
                                                        </div>
    
                                                        `);

                });

                $('#cardLanjutPembayaran').append(`<div class="col text-center bg-warning">
                                                    <span style="font-size: 30px; color:black;" > Total = ` + formatRupiah(total) + `</span>
                </div>`)

                $('#idJual').attr('value', response[0]['id_penjualan']);
                $('#totalPembayaran').attr('value', total);


            }
        });
    })

    $('#formIsiPembeli').submit(function() {
        var nama = $('#customername').val().length;
        var address = $('#address').val().length;
        var city = $('#city').val().length;
        var province = $('#province').val().length;
        var phone = $('#phone').val().length;

        if (nama == 0) {
            $('#validationname').addClass('text-danger');
            return false;
        } else if (address == 0) {
            $('#validationaddress').addClass('text-danger');
            return false;

        } else if (city == 0) {
            $('#validationcity').addClass('text-danger');
            return false;

        } else if (phone == 0) {
            $('#validationphone').addClass('text-danger');
            return false;

        } else if (province == 0) {
            $('#validationprovince').addClass('text-danger');
            return false;

        }
    })
</script>

<script>
    $('.transEdit').on('click', function() {
        $id = $(this).data('id');
        $total = $(this).data('total');
        $Sdate = $(this).data('shipdate');
        $Svia = $(this).data('shipvia');
        $Sfob = $(this).data('fob');
        $term = $(this).data('term');
        $ongkir = $(this).data('ongkir');


        console.log($id + $total);

        $('#editTransaksi #idPenjualan').attr('value', $id);
        $('#editTransaksi #totalTrans').attr('value', $total);
        $('#editTransaksi #shipVia').attr('value', $Svia);
        $('#editTransaksi #ongkir').attr('value', $ongkir);
        $('#editTransaksi #shipDate').val($Sdate);
        $('#editTransaksi #fob').attr('value', $Sfob);
        $('#editTransaksi #term').attr('value', $term);
    })
    // menambahkan idPJL pada form input modal tambah invoice
    $('.tambahbukti').on('click', function() {
        let id = $(this).data('idpjl');
        console.log(id);

        $('#modalTambahPembayaran #idPJL').attr('value', id);
    })

    $('#formTambahPMB').submit(function() {
        let jumlah = $('#modalTambahPembayaran #jumlahPMB').val().length;
        let terbilang = $('#modalTambahPembayaran #terbilang').val();



        if (jumlah == 0) {
            $('#modalTambahPembayaran #jumlahPMB').val(0);
            return false;

        } else if (terbilang.length == 0 || terbilang == 'Inputan Salah!') {
            $('#modalTambahPembayaran #terbilang').val('Inputan Salah!');
            return false;
        }
    })

    $('.edittermin').on('click', function() {
        let termin = $(this).data('termin');
        let id = $(this).data('idpjl');
        let gambar = $(this).data('gambar');
        let jumlah = $(this).data('jumlah');
        let terbilang = $(this).data('terbilang');
        let ket = $(this).data('keterangan');


        $('#modalEditPembayaran #idPJL').attr('value', id);
        $('#modalEditPembayaran #termin').attr('value', termin);
        $('#modalEditPembayaran #jumlahPMB').attr('value', jumlah);
        $('#modalEditPembayaran #terbilang').attr('value', terbilang);
        $('#modalEditPembayaran #ketTermin').attr('value', ket);
        $('#modalEditPembayaran .edit').html(gambar);
    })

    $('.cetaktermin').on('click', function() {

        let idPJL = $(this).data('idpenjualan');
        let termin = $(this).data('termin');

        $.ajax({
            type: "post",
            url: "<?= base_url('transaksi/praCetakInvoice') ?>",
            data: {
                'idpjl': idPJL,
                'termin': termin
            },
            dataType: "JSON",
            success: function(response) {
                console.log(response);

                $('#modalCetakTermin #idPJL').attr('value', idPJL)
                $('#modalCetakTermin #termin').attr('value', termin);

                $('#modalCetakTermin #namaCust').html(response.penjualan_cust.nama);
                $('#modalCetakTermin #TglCust').html(response.penjualan_cust.tanggal);
                $('#modalCetakTermin #BuktiCust').html('<img src="<?= base_url('assets/img/bukti_pembayaran/') ?>' + response.termin.gambar_transfer + '" class="img-thumbnail" style="height:150px; object-fit: cover;">');

                $('#modalCetakTermin #jumlahPMBCTK').val(formatRupiah(response.termin.jumlah));
                $('#modalCetakTermin #terbilangCTK').val(response.termin.terbilang);
                $('#modalCetakTermin #ketTermin').val(response.termin.keterangan);
                $('#modalCetakTermin #komentar1').val('Pembayaran dengan giro atau check dianggap sah setelah diuangkan');
            }
        });
    })
</script>

<script>
    $(document).ready(function() {


    });


    $('.editMsn').on('click', function() {

        $('#mdlEditMsn #id_mesin').attr('value', $(this).data('idmesin'));
        $('#mdlEditMsn #nama_mesin').attr('value', $(this).data('namamesin'));
        $('#mdlEditMsn #harga_mesin').attr('value', $(this).data('hargamesin'));
        $('#mdlEditMsn #gambar_mesin').html($(this).data('gambarmesin'));
        $('#mdlEditMsn #kapasitas_mesin').attr('value', $(this).data('kapasitasmesin'));
    })

    $('.editStatus').on('click', function() {
        console.log($(this).data('iduser'));
        $('#mdlEditUser #id_user').attr('value', $(this).data('iduser'));
        $('#mdlEditUser #nama_user').attr('value', $(this).data('namauser'));
        $('#mdlEditUser #email_user').attr('value', $(this).data('emailuser'));
        $('#mdlEditUser #status_user').attr('value', $(this).data('statususer'));
    })

    $('.list-notifikasi').on('click', '#alertsDropdown', function() {
        $.ajax({
            type: "POST",
            url: "<?= base_url('user/bukanotif') ?>",
            data: {
                'id_user': $(this).data('id')
            },
            dataType: "JSON",
            success: function(response) {
                $('.badge').html('');

            }
        });
    })
    let url = $('.exportlap').attr('href');
    $('#selecttrans').on('change', function() {
        $('#tabeltrans').html('');

        let pilihan = $(this).val();

        if (pilihan == 1) {
            $.ajax({
                type: "POST",
                url: "<?= base_url('admin/pilihanTrans/1') ?>",
                dataType: "JSON",
                success: function(response) {
                    let no = 1;
                    $.each(response, function(i, item) {
                        let dibayarkan = 0;

                        if (item['dibayarkan']) {
                            dibayarkan = item['dibayarkan'];
                        }

                        $('#tabeltrans').append(`
                            <tr>
                                    <td class="font-weight-normal" style="color: black; ">` + no + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['id_penjualan'] + `</td>
                                    <td class="font-weight-normal text-right" style="color: black; "> ` + formatRupiah(item['total']) + `</td>
                                    <td class="font-weight-normal text-right" style="color: black; "> ` + formatRupiah(dibayarkan) + `</td>
                                    <td class="font-weight-normal" style="color: black; ">` + item['name'] + ` </td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['nama_mesin'] + `</td>
                                    <td class="font-weight-normal text-right" style="color: black; "> ` + formatRupiah(item['harga']) + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['jumlah_mesin'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['nama'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['nama_perusahaan'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['nomor_wa'] + `</td>
                                    <td class="font-weight-normal text-right" style="color: black; "> ` + formatRupiah(item['ongkir']) + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['ship_date'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['ship_via'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['fob'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['term'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['sales'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['keterangan'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> <button class="lihatterminadm btn btn-primary btn-sm" data-toggle="modal" data-target="#mdllihatterminadm" data-idpjl="` + item['id_penjualan'] + `"><small>Lihat</small></button> </td>



                                </tr>
                        `)
                        no++;
                    })

                }
            });
            let url2 = url;
            url2 = url2 + pilihan;
            $('.exportlap').attr('href', url2);

        } else if (pilihan == 2) {
            $.ajax({
                type: "POST",
                url: "<?= base_url('admin/pilihanTrans/2') ?>",
                dataType: "JSON",
                success: function(response) {
                    let no = 1;
                    $.each(response, function(i, item) {
                        let dibayarkan = 0;

                        if (item['dibayarkan']) {
                            dibayarkan = item['dibayarkan'];
                        }

                        $('#tabeltrans').append(`
                            <tr>
                                    <td class="font-weight-normal" style="color: black; ">` + no + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['id_penjualan'] + `</td>
                                    <td class="font-weight-normal text-right" style="color: black; "> ` + formatRupiah(item['total']) + `</td>
                                    <td class="font-weight-normal text-right" style="color: black; "> ` + formatRupiah(dibayarkan) + `</td>
                                    <td class="font-weight-normal" style="color: black; ">` + item['name'] + ` </td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['nama_mesin'] + `</td>
                                    <td class="font-weight-normal text-right" style="color: black; "> ` + formatRupiah(item['harga']) + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['jumlah_mesin'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['nama'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['nama_perusahaan'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['nomor_wa'] + `</td>
                                    <td class="font-weight-normal text-right" style="color: black; "> ` + formatRupiah(item['ongkir']) + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['ship_date'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['ship_via'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['fob'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['term'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['sales'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['keterangan'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> <button class="lihatterminadm btn btn-primary btn-sm" data-toggle="modal" data-target="#mdllihatterminadm" data-idpjl="` + item['id_penjualan'] + `"><small>Lihat</small></button> </td>



                                </tr>
                        `)
                        no++;
                    })

                }
            });
            let url2 = url;
            url2 = url2 + pilihan;
            $('.exportlap').attr('href', url2);

        } else {

            $.ajax({
                type: "POST",
                url: "<?= base_url('admin/pilihanTrans/') ?>",
                dataType: "JSON",
                success: function(response) {
                    let no = 1;
                    $.each(response, function(i, item) {
                        let dibayarkan = 0;

                        if (item['dibayarkan']) {
                            dibayarkan = item['dibayarkan'];
                        }

                        $('#tabeltrans').append(`
                            <tr>
                                    <td class="font-weight-normal" style="color: black; ">` + no + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['id_penjualan'] + `</td>
                                    <td class="font-weight-normal text-right" style="color: black; "> ` + formatRupiah(item['total']) + `</td>
                                    <td class="font-weight-normal text-right" style="color: black; "> ` + formatRupiah(dibayarkan) + `</td>
                                    <td class="font-weight-normal" style="color: black; ">` + item['name'] + ` </td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['nama_mesin'] + `</td>
                                    <td class="font-weight-normal text-right" style="color: black; "> ` + formatRupiah(item['harga']) + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['jumlah_mesin'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['nama'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['nama_perusahaan'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['nomor_wa'] + `</td>
                                    <td class="font-weight-normal text-right" style="color: black; "> ` + formatRupiah(item['ongkir']) + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['ship_date'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['ship_via'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['fob'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['term'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['sales'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> ` + item['keterangan'] + `</td>
                                    <td class="font-weight-normal" style="color: black; "> <button class="lihatterminadm btn btn-primary btn-sm" data-toggle="modal" data-target="#mdllihatterminadm" data-idpjl="` + item['id_penjualan'] + `"><small>Lihat</small></button> </td>


                                </tr>
                        `)
                        no++;
                    })

                }
            });
            $('.exportlap').attr('href', url);

        }


    })
</script>

<script>
    $('#tabeltrans').on('click', '.lihatterminadm', function() {
        $idpjl = $(this).data('idpjl');
        $.ajax({
            type: "POST",
            url: "<?= base_url('admin/dataTermin/') ?>",
            data: {
                'idpjl': $idpjl
            },
            dataType: "JSON",
            success: function(response) {
                console.log(response);
                let no = 1;
                let gambar = "";

                $('#daftartermin').html('');
                $.each(response, function(i, item) {
                    if (item['gambar_transfer'] == '-') {
                        gambar = "pointer-events: none; cursor: default;"
                    }
                    $('#daftartermin').append(`
                            <tr>
                                <td>` + no + `</td>
                                <td>` + item['id_penjualan'] + `</td>
                                <td>` + item['nama'] + `</td>
                                <td>` + item['nama_perusahaan'] + `</td>
                                <td>` + item['tanggal'] + `</td>
                                <td>` + formatRupiah(item['jumlah']) + `</td>
                                <td><a href="<?= base_url('assets/img/bukti_pembayaran/') ?>` + item['gambar_transfer'] + `" target="_blank" style="` + gambar + `">` + item['gambar_transfer'] + `</a></td>
                                <td><a  class="btn btn-primary btn-sm" href="<?= base_url('admin/lihatTermin/') ?>` + item['id_penjualan'] + `/` + item['termin'] + `" target="_blank" >Lihat Termin</a></td>
                            </tr>
                    `);
                    no++;
                })

            }
        });
    })
</script>

<script>
    $('.list-notifikasi').on('click', '.lihatterminadm', function() {
        $idpjl = $(this).data('idpjl');
        $.ajax({
            type: "POST",
            url: "<?= base_url('admin/dataTermin/') ?>",
            data: {
                'idpjl': $idpjl
            },
            dataType: "JSON",
            success: function(response) {
                console.log(response);
                let no = 1;
                let gambar = "";

                $('#daftartermin').html('');
                $.each(response, function(i, item) {
                    if (item['gambar_transfer'] == '-') {
                        gambar = "pointer-events: none; cursor: default;"
                    }
                    $('#daftartermin').append(`
                            <tr>
                                <td>` + no + `</td>
                                <td>` + item['id_penjualan'] + `</td>
                                <td>` + item['nama'] + `</td>
                                <td>` + item['nama_perusahaan'] + `</td>
                                <td>` + item['tanggal'] + `</td>
                                <td>` + formatRupiah(item['jumlah']) + `</td>
                                <td><a href="<?= base_url('assets/img/bukti_pembayaran/') ?>` + item['gambar_transfer'] + `" target="_blank" style="` + gambar + `">` + item['gambar_transfer'] + `</a></td>
                                <td><a  class="btn btn-primary btn-sm" href="<?= base_url('admin/lihatTermin/') ?>` + item['id_penjualan'] + `/` + item['termin'] + `" target="_blank" >Lihat Termin</a></td>
                            </tr>
                    `);
                    no++;
                })

            }
        });
    })
</script>
</body>

</html>