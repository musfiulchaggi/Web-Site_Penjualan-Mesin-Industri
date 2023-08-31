<?php
defined('BASEPATH') or exit('No direct script access allowed');
// Include librari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Pusher\Pusher;

class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // cek masuk terlebih dahulu
        // memanggil fungsi yang ada di helper musfiul_helper.php
        sudah_masuk();
    }
    public function index()
    {
        // data yang dikirim adalah arrray dan yg digunakan mulai index 1 bukan 0
        $data['title'] = 'Dashboard';
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email'),
        ])->row_array();
        $data['notifikasi'] = $this->db->query('select * from notifikasi where id_user=' . $data['user']['id_user'] . ' order by tanggal desc limit 6;')->result_array();

        $data['daftar_penjualan'] = $this->db->query('SELECT sum(total), monthname(tanggal_input) FROM data_penjualan_mesin GROUP by month(tanggal_input)')->result_array();


        $this->load->view('templates/header_dashboard', $data);
        $this->load->view('templates/sidebar_dashboard', $data);
        $this->load->view('templates/topbar_dashboard', $data);
        $this->load->view('admin/index.php', $data);
        $this->load->view('templates/footer_dashboard', $data);
    }
    public function role()
    {
        // data yang dikirim adalah arrray dan yg digunakan mulai index 1 bukan 0
        $data['title'] = 'Role';
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email'),
        ])->row_array();
        $data['notifikasi'] = $this->db->query('select * from notifikasi where id_user=' . $data['user']['id_user'] . ' order by tanggal desc limit 6;')->result_array();


        $data['role'] = $this->db->get('user_role')->result_array();

        $this->load->view('templates/header_dashboard', $data);
        $this->load->view('templates/sidebar_dashboard', $data);
        $this->load->view('templates/topbar_dashboard', $data);
        $this->load->view('admin/role', $data);
        $this->load->view('templates/footer_dashboard', $data);
    }
    public function roleAccess($role_id)
    {
        // data yang dikirim adalah arrray dan yg digunakan mulai index 1 bukan 0
        $data['title'] = 'Role';
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email'),
        ])->row_array();
        $data['notifikasi'] = $this->db->query('select * from notifikasi where id_user=' . $data['user']['id_user'] . ' order by tanggal desc limit 6;')->result_array();


        $data['role'] = $this->db->get_where('user_role', ['id_role' => $role_id])->row_array();

        $this->db->where('id !=', 1);
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->load->view('templates/header_dashboard', $data);
        $this->load->view('templates/sidebar_dashboard', $data);
        $this->load->view('templates/topbar_dashboard', $data);
        $this->load->view('admin/role-access', $data);
        $this->load->view('templates/footer_dashboard', $data);
    }
    public function changeAccess()
    {
        $menuId = $this->input->post('menuId');
        $roleId = $this->input->post('roleId');

        $data = [
            'role_id' => $roleId,
            'menu_id' => $menuId
        ];
        $result = $this->db->get_where('user_access_menu', $data);

        if ($result->num_rows() < 1) {
            $this->db->insert('user_access_menu', $data);
        } else {
            $this->db->where('role_id', $roleId);
            $this->db->where('menu_id', $menuId);
            $this->db->delete('user_access_menu');
        }
        // membuat flashdata untuk menampilkan pesan
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
             Access, changed!
             </div><br>');
    }
    public function penjualan()
    {
        $daftar_penjualan = $this->db->query('SELECT sum(total), monthname(tanggal_input) FROM data_penjualan_mesin GROUP by month(tanggal_input)')->result_array();

        echo json_encode($daftar_penjualan);
    }

    public function member($user = null)
    {
        if ($user == null) {
            // data yang dikirim adalah arrray dan yg digunakan mulai index 1 bukan 0
            $data['title'] = 'Member';
            $data['user'] = $this->db->get_where('user', [
                'email' => $this->session->userdata('email'),
            ])->row_array();
            $data['notifikasi'] = $this->db->query('select * from notifikasi where id_user=' . $data['user']['id_user'] . ' order by tanggal desc limit 6;')->result_array();


            $data['member_active'] = $this->db->get_where('user', ['token_sent' => 1])->result_array();
            $data['member_not_active'] = $this->db->get_where('user', ['token_sent' => 0])->result_array();

            $this->load->view('templates/header_dashboard', $data);
            $this->load->view('templates/sidebar_dashboard', $data);
            $this->load->view('templates/topbar_dashboard', $data);
            $this->load->view('admin/member.php', $data);
            $this->load->view('templates/footer_dashboard', $data);
        } else {

            // membuat token email dan insert database
            $token = base64_encode(random_bytes(32));
            $email = $this->db->query('select email from user where id_user =' . $user)->row_array();

            $user_token = [
                'email' => $email['email'],
                'token' => $token,
                'date_created' => time() //waktu saat ini time()
            ];

            $this->db->insert('user_token', $user_token);


            // setelah user di insert ke DB
            // kirim email dengan token

            $this->_sendEmail($token, 'verify', $email['email']);

            $this->db->where('email', $email['email']);
            $this->db->set('token_sent', '1');
            $this->db->update('user');


            $this->session->set_flashdata(
                'message',
                '<div class="alert alert-success" role="alert">
                     Email Token Telah Dikirim Ke ' . $email['email'] . '.
                </div>'
            );
            redirect('admin/member');
        }
    }

    public function _sendEmail($token, $type, $email)
    {
        // Konfigurasi email
        $config = [
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.gmail.com',
            'smtp_user' => '191116015@mhs.stiki.ac.id',  // Email gmail
            'smtp_pass'   => '',  // Password gmail
            'smtp_port'   => 465,
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'crlf'    => "\r\n",
            'newline' => "\r\n"
        ];

        // mengnisialisasi email
        $this->email->initialize($config);

        $this->email->from('191116015@mhs.stiki.ac.id', 'Achmad Musfi\'ul Chaggi');

        $this->email->to($email);

        if ($type == 'verify') {

            $this->email->subject('Account Verification');

            $this->email->message('Click This Link to Verify : <a href="' . base_url('auth/verify?email=' . $email . '&token=' . urlencode($token)) . '">Activate</a>');
        } elseif ($type == 'forgot') {
            $this->email->subject('Reset Password');

            $this->email->message('Click This Link to Reset Your Password : <a href="' . base_url('auth/resetpassword?email=' . $email . '&token=' . urlencode($token)) . '">Reset Password</a>');
        }



        if (!$this->email->send()) {
            echo $this->email->print_debugger();
        } else {
            $this->email->send();
            return true;
        }
    }

    public function edituser()
    {
        $id = $this->input->post('id_user');
        $status = $this->input->post('status_user');

        $this->db->where('id_user', $id);

        if ($status == 'Aktif') {
            $this->db->set('is_active', 0);
        } else {
            $this->db->set('is_active', 1);
        }

        $this->db->update('user');
        redirect('admin/member');
    }

    public function hapus($user)
    {
        $id = $user;

        $this->db->where('id_user', $id);

        $this->db->delete('user');
        redirect('admin/member');
    }


    public function tampil_notif()
    {
        // data yang dikirim adalah arrray dan yg digunakan mulai index 1 bukan 0
        $data['title'] = 'Member';
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email'),
        ])->row_array();
        $data['notifikasi'] = $this->db->query('select * from notifikasi where id_user=' . $data['user']['id_user'] . ' order by tanggal desc limit 6;')->result_array();

        $this->load->view('admin/tampil_notif', $data);
    }

    public function transaksi()
    {
        // data yang dikirim adalah arrray dan yg digunakan mulai index 1 bukan 0
        $data['title'] = 'Transaksi';
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email'),
        ])->row_array();
        $data['notifikasi'] = $this->db->query('select * from notifikasi where id_user=' . $data['user']['id_user'] . ' order by tanggal desc limit 6;')->result_array();
        $data['transaksi'] = $this->db->query("SELECT data_penjualan_mesin.id_penjualan, data_penjualan_mesin.total,data_penjualan_mesin.sales, data_penjualan_mesin.ship_date, data_penjualan_mesin.ship_via, data_penjualan_mesin.fob, data_penjualan_mesin.term,data_penjualan_mesin.ongkir, data_penjualan_mesin.keterangan,user.name,daftar_mesin.nama_mesin, daftar_mesin.harga , daftar_mesin_dijual.jumlah_mesin,customer.nama,customer.nama_perusahaan,customer.nomor_wa FROM data_penjualan_mesin JOIN user on data_penjualan_mesin.id_user = user.id_user JOIN daftar_mesin_dijual on data_penjualan_mesin.id_penjualan = daftar_mesin_dijual.id_penjualan JOIN daftar_mesin on daftar_mesin.id_mesin = daftar_mesin_dijual.id_mesin join customer on data_penjualan_mesin.id_customer = customer.id_customer and data_penjualan_mesin.status_transaksi = 'Diproses' order by data_penjualan_mesin.id_penjualan DESC;")->result_array();
        $data['dibayarkan'] = $this->db->query("SELECT data_penjualan_mesin.id_penjualan, SUM(invoice.jumlah) as dibayarkan FROM data_penjualan_mesin JOIN invoice ON data_penjualan_mesin.id_penjualan=invoice.id_penjualan GROUP BY id_penjualan;")->result_array();

        $this->load->view('templates/header_dashboard', $data);
        $this->load->view('templates/sidebar_dashboard', $data);
        $this->load->view('templates/topbar_dashboard', $data);
        $this->load->view('admin/transaksi.php', $data);
        $this->load->view('templates/footer_dashboard', $data);
    }

    public function pilihanTrans($pilihan = null)
    {
        if ($pilihan == 1) {
            $data['transaksi'] = $this->db->query("SELECT data_penjualan_mesin.id_penjualan, data_penjualan_mesin.total,data_penjualan_mesin.sales, data_penjualan_mesin.ship_date, data_penjualan_mesin.ship_via, data_penjualan_mesin.fob, data_penjualan_mesin.term,data_penjualan_mesin.ongkir, data_penjualan_mesin.keterangan,user.name,daftar_mesin.nama_mesin, daftar_mesin.harga , daftar_mesin_dijual.jumlah_mesin,customer.nama,customer.nama_perusahaan,customer.nomor_wa FROM data_penjualan_mesin JOIN user on data_penjualan_mesin.id_user = user.id_user JOIN daftar_mesin_dijual on data_penjualan_mesin.id_penjualan = daftar_mesin_dijual.id_penjualan JOIN daftar_mesin on daftar_mesin.id_mesin = daftar_mesin_dijual.id_mesin join customer on data_penjualan_mesin.id_customer = customer.id_customer and data_penjualan_mesin.status_transaksi = 'Diproses' AND data_penjualan_mesin.keterangan = 'lunas' order by data_penjualan_mesin.id_penjualan DESC;")->result_array();
        } elseif ($pilihan == 2) {
            $data['transaksi'] = $this->db->query("SELECT data_penjualan_mesin.id_penjualan, data_penjualan_mesin.total,data_penjualan_mesin.sales, data_penjualan_mesin.ship_date, data_penjualan_mesin.ship_via, data_penjualan_mesin.fob, data_penjualan_mesin.term,data_penjualan_mesin.ongkir, data_penjualan_mesin.keterangan,user.name,daftar_mesin.nama_mesin, daftar_mesin.harga , daftar_mesin_dijual.jumlah_mesin,customer.nama,customer.nama_perusahaan,customer.nomor_wa FROM data_penjualan_mesin JOIN user on data_penjualan_mesin.id_user = user.id_user JOIN daftar_mesin_dijual on data_penjualan_mesin.id_penjualan = daftar_mesin_dijual.id_penjualan JOIN daftar_mesin on daftar_mesin.id_mesin = daftar_mesin_dijual.id_mesin join customer on data_penjualan_mesin.id_customer = customer.id_customer and data_penjualan_mesin.status_transaksi = 'Diproses' AND data_penjualan_mesin.keterangan != 'lunas' order by data_penjualan_mesin.id_penjualan DESC;")->result_array();
        } elseif ($pilihan == null) {
            $data['transaksi'] = $this->db->query("SELECT data_penjualan_mesin.id_penjualan, data_penjualan_mesin.total,data_penjualan_mesin.sales, data_penjualan_mesin.ship_date, data_penjualan_mesin.ship_via, data_penjualan_mesin.fob, data_penjualan_mesin.term,data_penjualan_mesin.ongkir, data_penjualan_mesin.keterangan,user.name,daftar_mesin.nama_mesin, daftar_mesin.harga , daftar_mesin_dijual.jumlah_mesin,customer.nama,customer.nama_perusahaan,customer.nomor_wa FROM data_penjualan_mesin JOIN user on data_penjualan_mesin.id_user = user.id_user JOIN daftar_mesin_dijual on data_penjualan_mesin.id_penjualan = daftar_mesin_dijual.id_penjualan JOIN daftar_mesin on daftar_mesin.id_mesin = daftar_mesin_dijual.id_mesin join customer on data_penjualan_mesin.id_customer = customer.id_customer and data_penjualan_mesin.status_transaksi = 'Diproses' order by data_penjualan_mesin.id_penjualan DESC;")->result_array();
        }
        $data['dibayarkan'] = $this->db->query("SELECT data_penjualan_mesin.id_penjualan, SUM(invoice.jumlah) as dibayarkan FROM data_penjualan_mesin JOIN invoice ON data_penjualan_mesin.id_penjualan=invoice.id_penjualan GROUP BY id_penjualan;")->result_array();

        $index = 0;
        foreach ($data['transaksi'] as $ts) {

            foreach ($data['dibayarkan'] as $db) {
                if ($ts['id_penjualan'] == $db['id_penjualan']) {
                    $data['transaksi'][$index]['dibayarkan'] = $db['dibayarkan'];
                }
            }
            $index++;
        }

        echo json_encode($data['transaksi']);
    }

    public function dataTermin()
    {
        $idpjl = $this->input->post('idpjl');
        $data['termin'] = $this->db->query('SELECT * FROM invoice join data_penjualan_mesin on invoice.id_penjualan = data_penjualan_mesin.id_penjualan join customer on data_penjualan_mesin.id_customer = customer.id_customer and invoice.id_penjualan=' . $idpjl . ' ORDER by invoice.termin DESC')->result_array();

        echo json_encode($data['termin']);
    }

    public function lihatTermin($idpjl, $termin)
    {
        $idPJL = $idpjl;
        $termin = $termin;
        $komentar1 = "Pembayaran dengan giro atau check dianggap sah setelah diuangkan";
        $komentar2 = "";
        $komentar3 = "";

        $data['termin'] = $this->db->get_where('invoice', ['id_penjualan' => $idPJL, 'termin' => $termin])->row_array();
        $data['penjualan_cust'] = $this->db->query("select * from data_penjualan_mesin,customer where id_penjualan=" . $idPJL . " and data_penjualan_mesin.id_customer=customer.id_customer")->row_array();
        $data['mesin_dijual'] = $this->db->query("select * from data_penjualan_mesin,daftar_mesin_dijual,daftar_mesin where data_penjualan_mesin.id_penjualan=daftar_mesin_dijual.id_penjualan and daftar_mesin_dijual.id_mesin=daftar_mesin.id_mesin and data_penjualan_mesin.id_penjualan='" . $idPJL . "';")->result_array();
        $data['komentar'] = [$komentar1, $komentar2, $komentar3];

        $this->load->library('pdf');
        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->filename = "laporan-termin" . $idPJL . "-" . $termin . ".pdf";
        $this->pdf->load_view('termin', $data);
    }

    public function stok()
    {
        // data yang dikirim adalah arrray dan yg digunakan mulai index 1 bukan 0
        $data['title'] = 'Stok';
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email'),
        ])->row_array();
        $data['notifikasi'] = $this->db->query('select * from notifikasi where id_user=' . $data['user']['id_user'] . ' order by tanggal desc limit 6;')->result_array();

        $this->load->view('templates/header_dashboard', $data);
        $this->load->view('templates/sidebar_dashboard', $data);
        $this->load->view('templates/topbar_dashboard', $data);
        $this->load->view('admin/stok.php', $data);
        $this->load->view('templates/footer_dashboard', $data);
    }

    public function export($value = null)
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
        $sheet->setCellValue('B3', "ID Transaksi"); // Set kolom B3 dengan tulisan "NIS"
        $sheet->setCellValue('C3', "Total Trans"); // Set kolom C3 dengan tulisan "NAMA"
        $sheet->setCellValue('D3', "Admin"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
        $sheet->setCellValue('E3', "Nama Mesin"); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('F3', "Harga Mesin"); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('G3', "Jumlah Mesin"); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('H3', "Nama Pelanggan"); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('I3', "Perusahaan"); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('J3', "Nomor WA"); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('K3', "Ongkir"); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('L3', "Ship Date"); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('M3', "Ship Via"); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('N3', "FOB"); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('O3', "Term"); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('P3', "Sales"); // Set kolom E3 dengan tulisan "ALAMAT"
        $sheet->setCellValue('Q3', "Keterangan"); // Set kolom E3 dengan tulisan "ALAMAT"
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
        $sheet->getStyle('N3')->applyFromArray($style_col);
        $sheet->getStyle('O3')->applyFromArray($style_col);
        $sheet->getStyle('P3')->applyFromArray($style_col);
        $sheet->getStyle('Q3')->applyFromArray($style_col);


        if ($value == null) {
            // Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya
            $data = $this->db->query("SELECT data_penjualan_mesin.id_penjualan, data_penjualan_mesin.total,data_penjualan_mesin.sales, data_penjualan_mesin.ship_date, data_penjualan_mesin.ship_via, data_penjualan_mesin.fob, data_penjualan_mesin.term,data_penjualan_mesin.ongkir, data_penjualan_mesin.keterangan,user.name,daftar_mesin.nama_mesin, daftar_mesin.harga , daftar_mesin_dijual.jumlah_mesin,customer.nama,customer.nama_perusahaan,customer.nomor_wa FROM data_penjualan_mesin JOIN user on data_penjualan_mesin.id_user = user.id_user JOIN daftar_mesin_dijual on data_penjualan_mesin.id_penjualan = daftar_mesin_dijual.id_penjualan JOIN daftar_mesin on daftar_mesin.id_mesin = daftar_mesin_dijual.id_mesin join customer on data_penjualan_mesin.id_customer = customer.id_customer and data_penjualan_mesin.status_transaksi = 'Diproses' order by data_penjualan_mesin.id_penjualan DESC;")->result_array();
        } elseif ($value == 1) {
            $data = $this->db->query("SELECT data_penjualan_mesin.id_penjualan, data_penjualan_mesin.total,data_penjualan_mesin.sales, data_penjualan_mesin.ship_date, data_penjualan_mesin.ship_via, data_penjualan_mesin.fob, data_penjualan_mesin.term,data_penjualan_mesin.ongkir, data_penjualan_mesin.keterangan,user.name,daftar_mesin.nama_mesin, daftar_mesin.harga , daftar_mesin_dijual.jumlah_mesin,customer.nama,customer.nama_perusahaan,customer.nomor_wa FROM data_penjualan_mesin JOIN user on data_penjualan_mesin.id_user = user.id_user JOIN daftar_mesin_dijual on data_penjualan_mesin.id_penjualan = daftar_mesin_dijual.id_penjualan JOIN daftar_mesin on daftar_mesin.id_mesin = daftar_mesin_dijual.id_mesin join customer on data_penjualan_mesin.id_customer = customer.id_customer and data_penjualan_mesin.status_transaksi = 'Diproses' AND data_penjualan_mesin.keterangan = 'lunas' order by data_penjualan_mesin.id_penjualan DESC;")->result_array();
        } elseif ($value == 2) {
            $data = $this->db->query("SELECT data_penjualan_mesin.id_penjualan, data_penjualan_mesin.total,data_penjualan_mesin.sales, data_penjualan_mesin.ship_date, data_penjualan_mesin.ship_via, data_penjualan_mesin.fob, data_penjualan_mesin.term,data_penjualan_mesin.ongkir, data_penjualan_mesin.keterangan,user.name,daftar_mesin.nama_mesin, daftar_mesin.harga , daftar_mesin_dijual.jumlah_mesin,customer.nama,customer.nama_perusahaan,customer.nomor_wa FROM data_penjualan_mesin JOIN user on data_penjualan_mesin.id_user = user.id_user JOIN daftar_mesin_dijual on data_penjualan_mesin.id_penjualan = daftar_mesin_dijual.id_penjualan JOIN daftar_mesin on daftar_mesin.id_mesin = daftar_mesin_dijual.id_mesin join customer on data_penjualan_mesin.id_customer = customer.id_customer and data_penjualan_mesin.status_transaksi = 'Diproses' AND data_penjualan_mesin.keterangan = 'lunas' order by data_penjualan_mesin.id_penjualan DESC;")->result_array();
        }

        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach ($data as $data) { // Lakukan looping pada variabel siswa
            $sheet->setCellValue('A' . $numrow, $no);
            $sheet->setCellValue('B' . $numrow, $data['id_penjualan']);
            $sheet->setCellValue('C' . $numrow, $data['total']);
            $sheet->setCellValue('D' . $numrow, $data['name']);
            $sheet->setCellValue('E' . $numrow, $data['nama_mesin']);
            $sheet->setCellValue('F' . $numrow, $data['harga']);
            $sheet->setCellValue('G' . $numrow, $data['jumlah_mesin']);
            $sheet->setCellValue('H' . $numrow, $data['nama']);
            $sheet->setCellValue('I' . $numrow, $data['nama_perusahaan']);
            $sheet->setCellValue('J' . $numrow, $data['nomor_wa']);
            $sheet->setCellValue('K' . $numrow, $data['ongkir']);
            $sheet->setCellValue('L' . $numrow, $data['ship_date']);
            $sheet->setCellValue('M' . $numrow, $data['ship_via']);
            $sheet->setCellValue('N' . $numrow, $data['fob']);
            $sheet->setCellValue('O' . $numrow, $data['term']);
            $sheet->setCellValue('P' . $numrow, $data['sales']);
            $sheet->setCellValue('Q' . $numrow, $data['keterangan']);

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
            $sheet->getStyle('N' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('O' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('P' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('Q' . $numrow)->applyFromArray($style_row);

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
        $sheet->getColumnDimension('N')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('O')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('P')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('Q')->setWidth(30); // Set width kolom E

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $sheet->setTitle("Data Transaksi");
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Data Transaksi.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
