<?php

use phpDocumentor\Reflection\Types\This;

defined('BASEPATH') or exit('No direct script access allowed');



class Auth extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

    public function __construct()
    {
        parent::__construct();

        // memanggil form_validation(library)
        $this->load->library('form_validation');
    }

    //index == login() 
    public function index()
    {
        $data['title'] = 'Inagi Login';
        $data['message'] = $this->session->flashdata('message');

        // cek dulu apakah sudah login
        if ($this->session->userdata('email')) {
            redirect('user');
        }

        // set_rules
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() == false) {

            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_header');
        } else {
            // membuat method login yg private dg _
            $this->_login();
        }
    }


    public function registration()
    {
        // mengirimkan judul dengan data[]
        $data['title'] = 'Inagi User Regiatration';

        // cek dulu apakah sudah login
        if ($this->session->userdata('email')) {
            redirect('user');
        }

        // membuat aturan validasi
        // yang digunakan adalah atribut 'name' pada html
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        // is_unique dari ci untuk cek unique email [table.field]
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'is_unique' => 'This email has already registered!'
        ]);
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[8]|matches[password2]', [
            'matches' => 'Password dont match!',
            'min_length' => 'Password too short!'
        ]);
        $this->form_validation->set_rules('password2', 'Name', 'required|trim|matches[password1]');

        // mengecek apakah validasinya berhasil
        // default dari form_validation adalah false
        if ($this->form_validation->run() == false) {
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/registration');
            $this->load->view('templates/auth_header');
        } else {
            $data = [
                // untuk menghindari xss (cross site scripting) digunakan parameter true dan htmlspecialchars untuk sanitasi karakter special (password tidak perlu)
                'name' => htmlspecialchars($this->input->post('name', true)),
                'email' => htmlspecialchars($this->input->post('email', true)),

                'image' => 'default.jpg',
                // menyimpan password dengan enkripsi menggunakan ci dengan password_hash
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id' => '2',
                'is_active' => 0,
                'date_created' => time()
            ];

            $this->db->insert('user', $data);

            // // membuat token email dan insert database
            // $token = base64_encode(random_bytes(32));
            // $user_token = [
            //     'email' => $this->input->post('email', true),
            //     'token' => $token,
            //     'date_created' => time() //waktu saat ini time()
            // ];

            // $this->db->insert('user_token', $user_token);


            // // setelah user di insert ke DB
            // // kirim email dengan token

            // $this->_sendEmail($token, 'verify');


            // membuat pemberitahuan pendaftaran admin untuk super admin
            $this->db->insert('notifikasi', [
                'id_user' => '1',
                'judul' => 'Pendaftaran Admin',
                'url' => 'admin/member',
                'keterangan' => 'Email = ' . $this->input->post('email', true)
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

            $pesan['message'] = 'Pendaftaran Admin Baru';
            $pusher->trigger('my-channel', 'my-event', $pesan);

            // membuat flashdata untuk menampilkan pesan
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Congratulation, Your account has been registered and will be checked by super admin! <br> Please check your email regurally and activate your account!
            </div><br>');
            // redirect menuju kelas controller
            redirect('auth');
        }
    }


    private function _login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user   = $this->db->get_where('user', [
            'email' => $email
        ])->row_array();


        if ($user) {

            // mengecek apakah user active
            if ($user['is_active'] == 1) {

                // mengecek password benar
                if (password_verify($password, $user['password'])) {
                    $data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id']
                    ];

                    // menyimpan data di session
                    // session bisa menampung data array
                    $this->session->set_userdata($data);

                    // cek role id(admin/user)
                    if ($data['role_id'] == '1') {

                        redirect('admin');
                    } else {

                        redirect('transaksi');
                    }
                } else {

                    // membuat flashdata untuk menampilkan pesan
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    Wrong password!
                    </div><br>');

                    redirect('auth');
                }
            } else {
                // membuat flashdata untuk menampilkan pesan
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Email is still not registered!
                </div><br>');
                redirect('auth');
            }
        } else {
            // membuat flashdata untuk menampilkan pesan
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Email is not registered!
            </div><br>');
            redirect('auth');
        }
    }

    public function _sendEmail($token, $type)
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

        $this->email->to($this->input->post('email'));

        if ($type == 'verify') {

            $this->email->subject('Account Verification');

            $this->email->message('Click This Link to Verify : <a href="' . base_url('auth/verify?email=' . $this->input->post('email', true) . '&token=' . urlencode($token)) . '">Activate</a>');
        } elseif ($type == 'forgot') {
            $this->email->subject('Reset Password');

            $this->email->message('Click This Link to Reset Your Password : <a href="' . base_url('auth/resetpassword?email=' . $this->input->post('email', true) . '&token=' . urlencode($token)) . '">Reset Password</a>');
        }




        if (!$this->email->send()) {
            echo $this->email->print_debugger();
        } else {
            $this->email->send();
            return true;
        }
    }

    public function verify()
    {

        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        if ($user) {
            // cek token ada di DB atau tidak
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

            if ($user_token) {
                if (time() - $user_token['date_created'] < (60 * 60 * 24)) {

                    $this->db->set('is_active', 1);
                    $this->db->where('email', $email);
                    // update is_active di user
                    $this->db->update('user');

                    // delete user token
                    $this->db->delete('user_token', ['email' => $email]);

                    // membuat flashdata untuk menampilkan pesan
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                    Your account ' . $email . ' has been activated!<br> Please Login!
                    </div><br>');
                    redirect('auth');
                } else {

                    $this->db->delete('user', ['email' => $email]);
                    $this->db->delete('user_token', ['email' => $email]);
                    // membuat flashdata untuk menampilkan pesan
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    Account activation failed! <br> Token Expired!
                    </div><br>');
                    redirect('auth');
                }
            } else {
                // membuat flashdata untuk menampilkan pesan
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Account activation failed! <br> Token Invalid!
                </div><br>');
                redirect('auth');
            }
        } else {
            // membuat flashdata untuk menampilkan pesan
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Account activation failed! <br>Wrong Email!
            </div><br>');
            redirect('auth');
        }
    }

    public function forgotPassword()
    {
        $data['title'] = 'Forgot Password';

        $data['message'] = $this->session->flashdata('message');

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

        if ($this->form_validation->run() == false) {

            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/forgot-password');
            $this->load->view('templates/auth_header');
        } else {
            $email = $this->input->post('email', true);

            $user = $this->db->get_where('user', [
                'email' => $email,
                'is_active' => 1
            ])->row_array();
            if ($user) {
                $token = base64_encode(random_bytes(32));

                $user_token = [
                    'email' => $email,
                    'token' => $token,
                    'date_created' => time()
                ];

                $this->db->insert('user_token', $user_token);

                // karena private harus pakai this
                $this->_sendEmail($token, 'forgot');
                $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">
                Please check your email to reset your password!
                </div><br>');
                redirect('auth/forgotPassword');
            } else {
                // membuat flashdata untuk menampilkan pesan
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Email is not registered or still not activated!
                </div><br>');
                redirect('auth/forgotPassword');
            }
        }
    }

    public function resetpassword()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        if ($user) {
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
            if ($user_token) {
                if (time() - $user_token['date_created'] < (60 * 60 * 24)) {
                    $this->session->set_userdata('reset_password', $email);


                    // menggunakan fungsi public change password
                    $this->changePassword();
                } else {
                    // membuat flashdata untuk menampilkan pesan
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                      Reset Password Failed, Token has been expired!
                      </div><br>');
                    redirect('auth/');
                }
            } else {
                // membuat flashdata untuk menampilkan pesan
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Reset Password Failed, Wrong token!
                </div><br>');
                redirect('auth/');
            }
        } else {
            // membuat flashdata untuk menampilkan pesan
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
             Reset Password Failed, Wrong email!
             </div><br>');
            redirect('auth/');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');

        // membuat flashdata untuk menampilkan pesan
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        You have been log out!
         </div><br>');


        redirect('auth');
    }


    public function changePassword()
    {
        if (!$this->session->userdata('reset_password')) {
            redirect('auth');
        }

        $data['title'] = 'Change Password';

        $data['message'] = $this->session->flashdata('message');

        $this->form_validation->set_rules('password1', 'Password', 'trim|required|matches[password2]|min_length[8]');
        $this->form_validation->set_rules('password2', 'Password', 'trim|required');

        if ($this->form_validation->run() == false) {

            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/change-password');
            $this->load->view('templates/auth_header');
        } else {
            $password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);
            $email = $this->session->userdata('reset_password');


            $this->db->where('email', $email);
            $this->db->update('user', ['password' => $password]);

            // hapus session reset_email email
            $this->session->unset_userdata('reset_password');

            // membuat flashdata untuk menampilkan pesan
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Password Has Been Reset! Please Login!
            </div><br>');


            redirect('auth');
        }
    }

    public function blocked()
    {
        $this->load->view('auth/blocked');
    }
}
