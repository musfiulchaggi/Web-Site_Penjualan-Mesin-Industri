<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Include librari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


use phpDocumentor\Reflection\Types\This;

class Transaksi extends CI_Controller
{
    public function __construct()
    {
        // cek masuk terlebih dahulu
        parent::__construct();

        // cek masuk terlebih dahulu
        // memanggil fungsi yang ada di helper musfiul_helper.php
        sudah_masuk();
    }
    public function index()
    {
        // data yang dikirim adalah arrray dan yg digunakan mulai index 1 bukan 0
        $data['title'] = 'Jual Barang';
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email'),
        ])->row_array();
        $data['notifikasi'] = $this->db->query('select * from notifikasi where id_user=' . $data['user']['id_user'] . ' order by tanggal desc limit 6;')->result_array();

        // mengambil daftar mesin
        $data['mesin'] = $this->db->get('daftar_mesin')->result_array();


        $this->load->view('templates/header_dashboard', $data);
        $this->load->view('templates/sidebar_dashboard', $data);
        $this->load->view('templates/topbar_dashboard', $data);
        $this->load->view('transaksi/index.php', $data);
        $this->load->view('templates/footer_dashboard', $data);
    }

    public function daftarPenjualan()
    {
        // data yang dikirim adalah arrray dan yg digunakan mulai index 1 bukan 0
        $data['title'] = 'Edit Profile';
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email'),
        ])->row_array();
        $data['notifikasi'] = $this->db->query('select * from notifikasi where id_user=' . $data['user']['id_user'] . ' order by tanggal desc limit 6;')->result_array();


        $this->form_validation->set_rules('name', 'Name', 'required|trim');

        if ($this->form_validation->run() == false) {


            $this->load->view('templates/header_dashboard', $data);
            $this->load->view('templates/sidebar_dashboard', $data);
            $this->load->view('templates/topbar_dashboard', $data);
            $this->load->view('user/edit', $data);
            $this->load->view('templates/footer_dashboard', $data);
        } else {
            $name = $this->input->post('name');
            $this->db->where('email', $this->session->userdata('email'));
            $this->db->update('user', ['name' => $name]);

            // cek jika ada gambar yang diupload
            // maka akan ada $_FILES
            $upload_image = $_FILES['image'];
            $nama_image = $upload_image['name'];

            if ($nama_image) {
                $config['upload_path'] = './assets/img/profile/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size']     = '2048';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {

                    // mengecek terlebih dahulu nama gambar untuk hapus filw di folder
                    if ($data['user']['image'] != 'default.jpg') {
                        // untuk menghapus pakai unlink
                        // tidak bisa pakai base_url();
                        unlink(FCPATH . 'assets/img/profile/' . $data['user']['image']);
                    }

                    // data['file_name'] merupakan fungsi yang ada di ci untuk mengambila data
                    $new_image = $this->upload->data('file_name');

                    // menyimpan di database
                    $this->db->where('email', $this->session->userdata('email'));
                    $this->db->update('user', ['image' => $new_image]);
                } else {
                    echo $this->upload->display_errors('<p class="alert alert-danger">', '</p>');
                }
            }


            // membuat flashdata untuk menampilkan pesan
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            your profile has been edited!
            </div><br>');

            redirect('user');
        }
    }

    public function loaddaftar()
    {
        $data['transaksi'] = $this->db->query("select data_penjualan_mesin.id_penjualan,daftar_mesin.id_mesin,daftar_mesin.nama_mesin,daftar_mesin_dijual.jumlah_mesin, (daftar_mesin.harga)*daftar_mesin_dijual.jumlah_mesin as
        subtotal from daftar_mesin join daftar_mesin_dijual on daftar_mesin.id_mesin = daftar_mesin_dijual.id_mesin join data_penjualan_mesin on data_penjualan_mesin.id_penjualan=daftar_mesin_dijual.id_penjualan
        and data_penjualan_mesin.status_transaksi = 'Belum Selesai';
         ")->result_array();

        if ($data['transaksi']) {
            echo json_encode($data['transaksi']);
        } else {
            echo json_encode(['message' => 'Data Kosong']);
        }
    }

    public function simpanKeranjang()
    {
        $user = $this->db->get_where('user', [
            'email' => $this->session->userdata('email')
        ])->row_array();
        $id_user = $user['id_user'];

        $id = $this->input->post('id');
        $jumlah = $this->input->post('jumlah');

        $data['penjualan'] = $this->db->query("select * from data_penjualan_mesin where status_transaksi = 'Belum Selesai'")->row_array();
        $id_penjualan = $data['penjualan']['id_penjualan'];

        if ($data['penjualan']) {

            // mengakali apabila memilih mesin yang sama
            $mesin_sama = $this->db->get_where('daftar_mesin_dijual', [
                'id_penjualan' => $id_penjualan,
                'id_mesin' => $id,
            ])->row_array();

            if ($mesin_sama) {
                $this->db->where('id_mesin', $id);
                $update = $this->db->update('daftar_mesin_dijual', [
                    'jumlah_mesin' => $jumlah
                ]);

                if ($update) {
                    echo json_encode(['message' => 'Data Berhasil Diubah']);
                } else {
                    echo json_encode(['message' => 'Data Gagal Diubah']);
                }
            } else {
                $insert = $this->db->insert('daftar_mesin_dijual', [
                    'id_penjualan' => $id_penjualan,
                    'id_mesin' => $id,
                    'jumlah_mesin' => $jumlah

                ]);

                if ($insert) {
                    echo json_encode(['message' => 'Data Berhasil Ditambahkan']);
                } else {
                    echo json_encode(['message' => 'Data Gagal Ditambahkan']);
                }
            }
        } else {
            $insert = $this->db->insert('data_penjualan_mesin', [
                'id_user' => $id_user,
                'tanggal' => date("d-F-y")

            ]);

            if ($insert) {
                // mengambil data penjualan 
                $data['penjualan'] = $this->db->query("select * from data_penjualan_mesin where status_transaksi = 'Belum Selesai'")->row_array();



                $insert = $this->db->insert('daftar_mesin_dijual', [
                    'id_penjualan' => $data['penjualan']['id_penjualan'],
                    'id_mesin' => $id,
                    'jumlah_mesin' => $jumlah

                ]);
                if ($insert) {
                    echo json_encode(['message' => 'Data Berhasil Ditambahkan']);
                } else {
                    echo json_encode(['message' => 'Data Gagal Ditambahkan']);
                }
            } else {
                echo json_encode(['message' => 'Data Gagal Ditambahkan']);
            }
        }
    }

    public function hapusDaftar()
    {
        $id_penjualan = $this->input->post('id_penjualan');
        $id_mesin = $this->input->post('id_mesin');

        $hapus = $this->db->delete('daftar_mesin_dijual', [
            'id_penjualan' => $id_penjualan,
            'id_mesin' => $id_mesin
        ]);

        if ($hapus) {
            echo json_encode(['message' => 'Delete Success']);
        } else {

            echo json_encode(['message' => 'Delete failed']);
        }
    }



    public function lanjutPembayaran()
    {

        // menyimpan transaksi penjualan
        // mengubah status transaksi 'Belum Selesai'=>'Diproses'
        $idJual = $this->input->post('idJual');
        $proses = $this->input->post('proses');
        if ($proses != 'tampil') {

            $customername = $this->input->post('customername');
            $company = $this->input->post('companyname');
            $npwp = $this->input->post('npwp');
            $address = $this->input->post('address');
            $city = $this->input->post('city');
            $province = $this->input->post('province');
            $phone = $this->input->post('phone');
            $sales = $this->input->post('sales');
            $total = $this->input->post('total');



            // input data menuju table customer dengan
            // dan mengembalikan nilai id_customer 
            $this->db->insert('customer', [
                'nama' => $customername,
                'nama_perusahaan' => $company,
                'npwp' => $npwp,
                'alamat' => $address,
                'kota' => $city,
                'provinsi' => $province,
                'nomor_wa' => $phone
            ]);

            $id_cust = $this->db->get_where('customer', [
                'nomor_wa' => $phone
            ])->row_array();

            // update data_penjualan_mesin
            $this->db->where('id_penjualan', $idJual);
            $this->db->update('data_penjualan_mesin', [
                'status_transaksi' => 'Diproses',
                'total' => $total,
                'sales' => $sales,
                'id_customer' => $id_cust['id_customer']
            ]);

            //membuat notifikasi 
            $this->db->insert('notifikasi', [
                'id_user' => '1',
                'judul' => 'Transaksi Penjualan Baru',
                'url' => 'admin/transaksi',
                'keterangan' => 'Id Penjualan = ' . $idJual
            ]);

            $options = array(
                'cluster' => 'ap1',
                'useTLS' => true
            );

            $pusher = new Pusher\Pusher(
                '8459db692d2df931dcd7',
                'ddd443ba03d8e782dde8',
                '1444015',
                $options
            );

            $pesan['message'] = 'Transaksi Penjualan Baru';
            $pusher->trigger('my-channel', 'my-event', $pesan);

            redirect('transaksi/menuPembayaran');
        } elseif ($proses == 'tampil') {

            $data['penjualan'] = $this->db->query("select * from data_penjualan_mesin, daftar_mesin_dijual, daftar_mesin where
            data_penjualan_mesin.id_penjualan = daftar_mesin_dijual.id_penjualan and daftar_mesin_dijual.id_mesin = daftar_mesin.id_mesin
            and data_penjualan_mesin.id_penjualan = '" . $idJual . "';")->result_array();

            echo json_encode($data['penjualan']);
        }
    }

    public function menuPembayaran()
    {
        // data yang dikirim adalah arrray dan yg digunakan mulai index 1 bukan 0
        $data['title'] = 'Pembayaran';
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email'),
        ])->row_array();
        $data['notifikasi'] = $this->db->query('select * from notifikasi where id_user=' . $data['user']['id_user'] . ' order by tanggal desc limit 6;')->result_array();


        // mengambil daftar penjualan diproses
        $data['customer_diproses'] = $this->db->query("select * from data_penjualan_mesin,customer 
        where data_penjualan_mesin.id_customer=customer.id_customer and data_penjualan_mesin.status_transaksi= 'Diproses' and data_penjualan_mesin.id_user = " . $data['user']['id_user'] . " order by id_penjualan desc; ")->result_array();

        $data['mesin'] = $this->db->query("select * from data_penjualan_mesin, daftar_mesin_dijual, daftar_mesin 
        where data_penjualan_mesin.id_penjualan = daftar_mesin_dijual.id_penjualan and daftar_mesin.id_mesin = daftar_mesin_dijual.id_mesin 
        and data_penjualan_mesin.status_transaksi = 'Diproses' and data_penjualan_mesin.id_user = " . $data['user']['id_user'] . ";")->result_array();

        $data['invoice'] = $this->db->query('select * from invoice order by termin desc;')->result_array();


        $this->load->view('templates/header_dashboard', $data);
        $this->load->view('templates/sidebar_dashboard', $data);
        $this->load->view('templates/topbar_dashboard', $data);
        $this->load->view('transaksi/pembayaran.php', $data);
        $this->load->view('templates/footer_dashboard', $data);
    }

    public function addInvoice()
    {
        $idPJL = $this->input->post('idPJL');
        $jumlah = $this->input->post('jumlahPMB');
        $terbilang = $this->input->post('terbilang');
        $termin = $this->input->post('termin');
        $keterangan = $this->input->post('ketTermin');


        // cek jika ada gambar_bukti yang diupload
        // maka akan ada $_FILES[name="bukti_pembayaran"] 
        // attr name="bukti_pembayaran" merupakan sebuah kumpulan informasi dari form input file

        $nama_image = null;

        if ($_FILES['bukti_pembayaran']) {
            $upload_image = $_FILES['bukti_pembayaran'];
            $nama_image = $upload_image['name'];
        }


        // menyiapkan di database invoice
        $invoice = $this->db->query("select max(termin) from invoice where id_penjualan='" . $idPJL . "';")->row_array();
        $gambar = $this->db->query("select gambar_transfer from invoice where id_penjualan='" . $idPJL . "' and termin='" . $termin . "';")->row_array();

        if ($nama_image) {
            $config['upload_path'] = './assets/img/bukti_pembayaran/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size']     = '2048';


            // mengecek terlebih dahulu nama gambar untuk hapus filw di folder
            if ($nama_image && $termin) {
                // untuk menghapus pakai unlink
                // tidak bisa pakai base_url();
                unlink(FCPATH . 'assets/img/bukti_pembayaran/' . $gambar['gambar_transfer']);
            }


            $this->load->library('upload', $config);

            // do_upload('atrr_name_form_input') akan mengupload file yang telah dikirimkan 
            // dan bisa kita lihat di $_FILES['atrr_name_form_input']
            if ($this->upload->do_upload('bukti_pembayaran')) {




                // data['file_name'] merupakan fungsi yang ada di ci untuk mengambila data
                $new_image = $this->upload->data('file_name');


                if ($invoice == null) {
                    $invoice['max(termin)'] = 1;
                } else {
                    $invoice['max(termin)'] = $invoice['max(termin)'] + 1;
                }

                if ($termin) {
                    $this->db->where('id_penjualan', $idPJL);
                    $this->db->where('termin', $termin);
                    $this->db->update('invoice', [
                        'jumlah' => $jumlah,
                        'terbilang' => $terbilang,
                        'gambar_transfer' => $new_image,
                        'keterangan' => $keterangan
                    ]);

                    // merubah status keterangan lunas
                    $total_bayar = $this->db->query('select sum(jumlah) from invoice where id_penjualan=' . $idPJL . ';')->row_array();
                    $total = $this->db->query('select total from data_penjualan_mesin where id_penjualan=' . $idPJL . ';')->row_array();
                    $this->db->where('id_penjualan', $idPJL);
                    if ($total_bayar['sum(jumlah)'] == $total['total']) {
                        $this->db->update('data_penjualan_mesin', [
                            'keterangan' => 'lunas'
                        ]);
                    } else {
                        $this->db->update('data_penjualan_mesin', [
                            'keterangan' => 'Belum Lunas'
                        ]);
                    }
                } else {
                    $this->db->insert('invoice', [
                        'id_penjualan' => $idPJL,
                        'tanggal' => date('d-M-y'),
                        'jumlah' => $jumlah,
                        'terbilang' => $terbilang,
                        'gambar_transfer' => $new_image,
                        'termin' => $invoice['max(termin)'],
                        'keterangan' => $keterangan
                    ]);

                    // merubah status keterangan lunas
                    $total_bayar = $this->db->query('select sum(jumlah) from invoice where id_penjualan=' . $idPJL . ';')->row_array();
                    $total = $this->db->query('select total from data_penjualan_mesin where id_penjualan=' . $idPJL . ';')->row_array();
                    $this->db->where('id_penjualan', $idPJL);
                    if ($total_bayar['sum(jumlah)'] == $total['total']) {
                        $this->db->update('data_penjualan_mesin', [
                            'keterangan' => 'lunas'
                        ]);
                    } else {
                        $this->db->update('data_penjualan_mesin', [
                            'keterangan' => 'Belum Lunas'
                        ]);
                    }
                }

                //membuat notifikasi 
                $this->db->insert('notifikasi', [
                    'id_user' => '1',
                    'judul' => 'Transaksi Pembayaran',
                    'url' => 'admin/transaksi',
                    'keterangan' => 'Id Penjualan = ' . $idPJL
                ]);

                $options = array(
                    'cluster' => 'ap1',
                    'useTLS' => true
                );

                $pusher = new Pusher\Pusher(
                    '8459db692d2df931dcd7',
                    'ddd443ba03d8e782dde8',
                    '1444015',
                    $options
                );

                $pesan['message'] = 'Transaksi Penjualan Baru';
                $pusher->trigger('my-channel', 'my-event', $pesan);


                redirect('transaksi/menuPembayaran');
            } else {
                echo $this->upload->display_errors('<p class="alert alert-danger">', '</p>');
            }
        } else {


            if ($invoice == null) {
                $invoice['max(termin)'] = 1;
            } else {
                $invoice['max(termin)'] = $invoice['max(termin)'] + 1;
            }

            if ($termin) {
                $this->db->where('id_penjualan', $idPJL);
                $this->db->where('termin', $termin);
                $this->db->update('invoice', [
                    'jumlah' => $jumlah,
                    'terbilang' => $terbilang,
                    'keterangan' => $keterangan
                ]);
                // merubah status keterangan lunas
                $total_bayar = $this->db->query('select sum(jumlah) from invoice where id_penjualan=' . $idPJL . ';')->row_array();
                $total = $this->db->query('select total from data_penjualan_mesin where id_penjualan=' . $idPJL . ';')->row_array();

                $this->db->where('id_penjualan', $idPJL);
                if ($total_bayar['sum(jumlah)'] == $total['total']) {
                    $this->db->update('data_penjualan_mesin', [
                        'keterangan' => 'lunas'
                    ]);
                } else {
                    $this->db->update('data_penjualan_mesin', [
                        'keterangan' => 'Belum Lunas'
                    ]);
                }
            } else {
                $this->db->insert('invoice', [
                    'id_penjualan' => $idPJL,
                    'tanggal' => date('d-M-y'),
                    'jumlah' => $jumlah,
                    'terbilang' => $terbilang,
                    'gambar_transfer' => '-',
                    'termin' => $invoice['max(termin)'],
                    'keterangan' => $keterangan
                ]);
                // merubah status keterangan lunas
                $total_bayar = $this->db->query('select sum(jumlah) from invoice where id_penjualan=' . $idPJL . ';')->row_array();
                $total = $this->db->query('select total from data_penjualan_mesin where id_penjualan=' . $idPJL . ';')->row_array();


                $this->db->where('id_penjualan', $idPJL);
                if ($total_bayar['sum(jumlah)'] == $total['total']) {
                    $this->db->update('data_penjualan_mesin', [
                        'keterangan' => 'lunas'
                    ]);
                } else {
                    $this->db->update('data_penjualan_mesin', [
                        'keterangan' => 'Belum Lunas'
                    ]);
                }
            }
            //membuat notifikasi 
            $this->db->insert('notifikasi', [
                'id_user' => '1',
                'judul' => 'Transaksi Pembayaran',
                'url' => 'admin/transaksi',
                'keterangan' => 'Id Penjualan = ' . $idPJL
            ]);

            $options = array(
                'cluster' => 'ap1',
                'useTLS' => true
            );

            $pusher = new Pusher\Pusher(
                '8459db692d2df931dcd7',
                'ddd443ba03d8e782dde8',
                '1444015',
                $options
            );

            $pesan['message'] = 'Transaksi Penjualan Baru';
            $pusher->trigger('my-channel', 'my-event', $pesan);


            redirect('transaksi/menuPembayaran');
        }
    }

    public function getdataTermin($idPJL = null, $termin = null)
    {

        $this->db->where('id_penjualan', $idPJL);
        $this->db->where('termin', $termin);

        $data['termin'] = $this->db->get_where('invoice', ['id_penjualan' => $idPJL, 'termin' => $termin])->row_array();
        $data['penjualan_cust'] = $this->db->query("select * from data_penjualan_mesin,customer where id_penjualan=" . $idPJL . " and data_penjualan_mesin.id_customer=customer.id_customer")->row_array();
        $data['mesin_dijual'] = $this->db->query("select * from data_penjualan_mesin,daftar_mesin_dijual,daftar_mesin where data_penjualan_mesin.id_penjualan=daftar_mesin_dijual.id_penjualan and daftar_mesin_dijual.id_mesin=daftar_mesin.id_mesin and data_penjualan_mesin.id_penjualan='" . $idPJL . "';")->result_array();
        return $data;

        // $this->load->view('termin', $data);

        // $this->load->library('pdf');
        // $this->pdf->setPaper('A4', 'potrait');
        // $this->pdf->filename = "laporan-data-siswa.pdf";
        // $this->pdf->load_view('termin');
    }

    public function cetakInvoice()
    {
        $idPJL = $this->input->post('idPJL');;
        $termin = $this->input->post('termin');;
        $komentar1 = $this->input->post('komentar1');
        $komentar2 = $this->input->post('komentar2');
        $komentar3 = $this->input->post('komentar3');

        $data['termin'] = $this->db->get_where('invoice', ['id_penjualan' => $idPJL, 'termin' => $termin])->row_array();
        $data['penjualan_cust'] = $this->db->query("select * from data_penjualan_mesin,customer where id_penjualan=" . $idPJL . " and data_penjualan_mesin.id_customer=customer.id_customer")->row_array();
        $data['mesin_dijual'] = $this->db->query("select * from data_penjualan_mesin,daftar_mesin_dijual,daftar_mesin where data_penjualan_mesin.id_penjualan=daftar_mesin_dijual.id_penjualan and daftar_mesin_dijual.id_mesin=daftar_mesin.id_mesin and data_penjualan_mesin.id_penjualan='" . $idPJL . "';")->result_array();
        $data['komentar'] = [$komentar1, $komentar2, $komentar3];

        $this->load->library('pdf');
        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->filename = "laporan-termin" . $idPJL . "-" . $termin . ".pdf";
        $this->pdf->load_view('termin', $data);
    }

    public function praCetakInvoice()
    {
        $idPJL = $this->input->post('idpjl');
        $termin = $this->input->post('termin');
        $data = $this->getdataTermin($idPJL, $termin);
        echo json_encode($data);
    }

    public function editTransaksi()
    {
        $idPJL = $this->input->post('idPenjualan');
        $Svia = $this->input->post('shipVia');
        $Sdate = $this->input->post('shipDate');
        $fob = $this->input->post('fob');
        $term = $this->input->post('term');
        $ongkir = $this->input->post('ongkir');

        // date 07/09/2022
        $newDateString = date_format(date_create_from_format('Y-m-d', $Sdate), 'd-M-y');


        $this->db->where('id_penjualan', $idPJL);
        $this->db->update('data_penjualan_mesin', [
            'ship_date' => $newDateString,
            'ship_via' => $Svia,
            'fob' => $fob,
            'term' => $term,
            'ongkir' => $ongkir
        ]);

        redirect('transaksi/menuPembayaran');
    }

    // menu riwayat transaksi
    public function riwayatTransaksi()
    {
        // data yang dikirim adalah arrray dan yg digunakan mulai index 1 bukan 0
        $data['title'] = 'Riwayat Transaksi';
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email'),
        ])->row_array();
        $data['notifikasi'] = $this->db->query('select * from notifikasi where id_user=' . $data['user']['id_user'] . ' order by tanggal desc limit 6;')->result_array();


        $data['transaksi'] = $this->db->query("select * from data_penjualan_mesin join customer on customer.id_customer = data_penjualan_mesin.id_customer WHERE data_penjualan_mesin.id_user=" . $data['user']['id_user'] . ";")->result_array();

        $this->load->view('templates/header_dashboard', $data);
        $this->load->view('templates/sidebar_dashboard', $data);
        $this->load->view('templates/topbar_dashboard', $data);
        $this->load->view('transaksi/riwayatTrans.php', $data);
        $this->load->view('templates/footer_dashboard', $data);
    }

    public function tambahBarang()
    {
        // data yang dikirim adalah arrray dan yg digunakan mulai index 1 bukan 0
        $data['title'] = 'Tambah Barang';
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email')
        ])->row_array();
        $data['notifikasi'] = $this->db->query('select * from notifikasi where id_user=' . $data['user']['id_user'] . ' order by tanggal desc limit 6;')->result_array();



        $data['barang'] = $this->db->query('select * from daftar_mesin order by id_mesin DESC;')->result_array();

        $this->form_validation->set_rules('nama_mesin', 'Name', 'required');
        $this->form_validation->set_rules('harga', 'Cost', 'required');
        $this->form_validation->set_rules('kapasitas', 'Capacity', 'required');

        if ($this->form_validation->run() == false) {


            $this->load->view('templates/header_dashboard', $data);
            $this->load->view('templates/sidebar_dashboard', $data);
            $this->load->view('templates/topbar_dashboard', $data);
            $this->load->view('transaksi/tambah_barang.php', $data);
            $this->load->view('templates/footer_dashboard', $data);;
        } else {


            $nama_mesin = $this->input->post('nama_mesin');
            $harga = $this->input->post('harga');
            $kapasitas = $this->input->post('kapasitas');

            // cek jika ada gambar yang diupload
            // maka akan ada $_FILES
            $upload_image = $_FILES['gambar'];
            $nama_image = $upload_image['name'];



            if ($nama_image) {
                $config['upload_path'] = './assets/img/mesin/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size']     = '2048';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('gambar')) {



                    // data['file_name'] merupakan fungsi yang ada di ci untuk mengambila data
                    $gbr = $this->upload->data();

                    if ($gbr['file_size'] >= 100) {

                        $config['image_library'] = 'gd2';
                        $config['source_image'] = './assets/img/mesin/' . $gbr['file_name'];

                        $config['create_thumb'] = FALSE;
                        $config['maintain_ratio'] = FALSE;
                        $config['quality'] = '80%';
                        $config['width'] = 400;
                        $config['height'] = 400;
                        $config['new_image'] = './assets/img/mesin/' . 'new_' . $gbr['file_name'];

                        $old_gbr = $gbr['file_name'];
                        $gbr['file_name'] = 'new_' . $gbr['file_name'];

                        $this->load->library('image_lib', $config);
                        $this->image_lib->resize();

                        unlink(FCPATH . 'assets/img/mesin/' . $old_gbr);
                    }

                    $this->db->insert('daftar_mesin', [
                        'nama_mesin' => $nama_mesin,
                        'harga' => $harga,
                        'gambar' =>  $gbr['file_name'],
                        'kapasitas' => $kapasitas,
                    ]);
                } else {
                    echo $this->upload->display_errors('<p class="alert alert-danger">', '</p>');
                }
            } else {
                $this->db->insert('daftar_mesin', [
                    'nama_mesin' => $nama_mesin,
                    'harga' => $harga,
                    'kapasitas' => $kapasitas,
                ]);
            }


            // membuat flashdata untuk menampilkan pesan
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            your data has been added!
            </div><br>');

            redirect('transaksi/tambahBarang');
        }
    }


    public function editMesin()
    {
        $id = $this->input->post('id_mesin');
        $nama = $this->input->post('nama_mesin');
        $harga = $this->input->post('harga_mesin');
        $kapasitas = $this->input->post('kapasitas_mesin');

        $gambar = $_FILES['gambar_mesin'];
        $nama_gambar = $gambar['name'];

        if ($nama_gambar) {
            // melakukan upload
            $config['upload_path'] = './assets/img/mesin/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size']     = '2048';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('gambar_mesin')) {
                // data['file_name'] merupakan fungsi yang ada di ci untuk mengambila data
                $gbr = $this->upload->data();

                if ($gbr['file_size'] >= 100) {

                    $config['image_library'] = 'gd2';
                    $config['source_image'] = './assets/img/mesin/' . $gbr['file_name'];

                    $config['create_thumb'] = FALSE;
                    $config['maintain_ratio'] = FALSE;
                    $config['quality'] = '80%';
                    $config['width'] = 400;
                    $config['height'] = 400;
                    $config['new_image'] = './assets/img/mesin/' . 'new_' . $gbr['file_name'];

                    $old_gbr = $gbr['file_name'];
                    $gbr['file_name'] = 'new_' . $gbr['file_name'];

                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();

                    unlink(FCPATH . 'assets/img/mesin/' . $old_gbr);
                }
                $this->db->update('daftar_mesin', [
                    'nama_mesin' => $nama,
                    'harga' => $harga,
                    'gambar' =>  $gbr['file_name'],
                    'kapasitas' => $kapasitas,
                ], ['id_mesin' => $id]);
            } else {
                echo $this->upload->display_errors('<p class="alert alert-danger">', '</p>');
            }
        } else {
            $this->db->update('daftar_mesin', [
                'nama_mesin' => $nama,
                'harga' => $harga,
                'kapasitas' => $kapasitas,
            ], ['id_mesin' => $id]);
        }
        // membuat flashdata untuk menampilkan pesan
        $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">
         your data has been edited!
         </div><br>');

        redirect('transaksi/tambahBarang');
    }

    public function export($id_user)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = [
            'font' => ['bold' => true], // Set font nya jadi bold
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];
        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];
        $sheet->setCellValue('A1', "Data Transaksi"); // Set kolom A1 dengan tulisan "DATA SISWA"
        $sheet->mergeCells('A1:M1'); // Set Merge Cell pada kolom A1 sampai E1
        $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
        // Buat header tabel nya pada baris ke 3
        $sheet->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
        $sheet->setCellValue('B3', "Customer"); // Set kolom B3 dengan tulisan "NIS"
        $sheet->setCellValue('C3', "Company"); // Set kolom C3 dengan tulisan "NAMA"
        $sheet->setCellValue('D3', "Address"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
        $sheet->setCellValue('E3', "City"); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('F3', "Phone"); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('G3', "Total"); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('H3', "Trans Date"); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('I3', "Ship Date"); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('J3', "Shipping Cost"); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('K3', "Ship Via"); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('L3', "Sales"); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('M3', "Desc"); // Set kolom E3 dengan tulisan "ALAMAT"
        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $sheet->getStyle('A3')->applyFromArray($style_col);
        $sheet->getStyle('B3')->applyFromArray($style_col);
        $sheet->getStyle('C3')->applyFromArray($style_col);
        $sheet->getStyle('D3')->applyFromArray($style_col);
        $sheet->getStyle('E3')->applyFromArray($style_col);
        $sheet->getStyle('F3')->applyFromArray($style_col);
        $sheet->getStyle('G3')->applyFromArray($style_col);
        $sheet->getStyle('H3')->applyFromArray($style_col);
        $sheet->getStyle('I3')->applyFromArray($style_col);
        $sheet->getStyle('J3')->applyFromArray($style_col);
        $sheet->getStyle('K3')->applyFromArray($style_col);
        $sheet->getStyle('L3')->applyFromArray($style_col);
        $sheet->getStyle('M3')->applyFromArray($style_col);
        // Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya
        $data = $this->db->query("select * from data_penjualan_mesin join customer on customer.id_customer = data_penjualan_mesin.id_customer WHERE data_penjualan_mesin.id_user=" . $id_user . ";")->result_array();

        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach ($data as $data) { // Lakukan looping pada variabel siswa
            $sheet->setCellValue('A' . $numrow, $no);
            $sheet->setCellValue('B' . $numrow, $data['nama']);
            $sheet->setCellValue('C' . $numrow, $data['nama_perusahaan']);
            $sheet->setCellValue('D' . $numrow, $data['alamat']);
            $sheet->setCellValue('E' . $numrow, $data['kota']);
            $sheet->setCellValue('F' . $numrow, $data['nomor_wa']);
            $sheet->setCellValue('G' . $numrow, $data['total']);
            $sheet->setCellValue('H' . $numrow, $data['tanggal']);
            $sheet->setCellValue('I' . $numrow, $data['ship_date']);
            $sheet->setCellValue('J' . $numrow, $data['ongkir']);
            $sheet->setCellValue('K' . $numrow, $data['ship_via']);
            $sheet->setCellValue('L' . $numrow, $data['sales']);
            $sheet->setCellValue('M' . $numrow, $data['keterangan']);

            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $sheet->getStyle('A' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('B' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('C' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('D' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('E' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('F' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('G' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('H' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('I' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('J' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('K' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('L' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('M' . $numrow)->applyFromArray($style_row);

            $no++; // Tambah 1 setiap kali looping
            $numrow++; // Tambah 1 setiap kali looping
        }
        // Set width kolom
        $sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
        $sheet->getColumnDimension('B')->setWidth(15); // Set width kolom B
        $sheet->getColumnDimension('C')->setWidth(25); // Set width kolom C
        $sheet->getColumnDimension('D')->setWidth(20); // Set width kolom D
        $sheet->getColumnDimension('E')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('F')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('G')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('H')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('I')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('J')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('K')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('L')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('M')->setWidth(30); // Set width kolom E

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $sheet->setTitle("Laporan Data Transaksi");
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Data Transaksi.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
