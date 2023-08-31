<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
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
        $data['title'] = 'My Profile';
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email')
        ])->row_array();
        $data['notifikasi'] = $this->db->query('select * from notifikasi where id_user=' . $data['user']['id_user'] . ' order by tanggal desc limit 6;')->result_array();


        $this->load->view('templates/header_dashboard', $data);
        $this->load->view('templates/sidebar_dashboard', $data);
        $this->load->view('templates/topbar_dashboard', $data);
        $this->load->view('user/index.php', $data);
        $this->load->view('templates/footer_dashboard', $data);
    }

    public function edit()
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

    public function changePassword()
    { // data yang dikirim adalah arrray dan yg digunakan mulai index 1 bukan 0
        $data['title'] = 'Change Password';
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email'),
        ])->row_array();
        $data['notifikasi'] = $this->db->query('select * from notifikasi where id_user=' . $data['user']['id_user'] . ' order by tanggal desc limit 6;')->result_array();


        $this->form_validation->set_rules('current_password', 'Current Password', 'required|trim');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|trim|min_length[8]|matches[repeat_password]');
        $this->form_validation->set_rules('repeat_password', 'Repeat Password', 'required|trim|min_length[8]|matches[new_password]');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header_dashboard', $data);
            $this->load->view('templates/sidebar_dashboard', $data);
            $this->load->view('templates/topbar_dashboard', $data);
            $this->load->view('user/changepassword', $data);
            $this->load->view('templates/footer_dashboard', $data);
        } else {
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password');
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);




            if (!password_verify($current_password, $data['user']['password'])) {
                // membuat flashdata untuk menampilkan pesan
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Wrong Password!
                </div><br>');

                redirect('user/changepassword');
            } else {
                // cek apakah password sama dengan yang lama
                if ($current_password == $new_password) {
                    // membuat flashdata untuk menampilkan pesan
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                New Password cannot be the same as current password!
                </div><br>');

                    redirect('user/changepassword');
                } else {
                    // password diacak
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                    $this->db->where('email', $data['user']['email']);
                    $this->db->update('user', ['password' => $password_hash]);
                    // membuat flashdata untuk menampilkan pesan
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                              Password Changed!
                              </div><br>');

                    redirect('user/changepassword');
                }
            }
        }
    }

    public function bukanotif()
    {
        $id = $this->input->post('id_user');
        $this->db->where('id_user', $id);
        $this->db->set('dibuka', 0);
        $this->db->update('notifikasi');

        echo json_encode('berhasil ubah notif dibuka');
    }
}
