<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // memanggil library form_validation
        $this->load->library('form_validation');
        // load model Sub Menu
        $this->load->model('Menu_model', 'menu');

        // cek masuk terlebih dahulu
        // memanggil fungsi yang ada di helper musfiul_helper.php
        sudah_masuk();
    }

    public function index()
    {
        // data yang dikirim adalah arrray dan yg digunakan mulai index 1 bukan 0
        $data['title'] = 'Menu Management';
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email'),
        ])->row_array();
        $data['notifikasi'] = $this->db->query('select * from notifikasi where id_user=' . $data['user']['id_user'] . ' order by tanggal desc limit 6;')->result_array();

        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('menu', 'Menu', 'required');


        if ($this->form_validation->run() == false) {

            $this->load->view('templates/header_dashboard', $data);
            $this->load->view('templates/sidebar_dashboard', $data);
            $this->load->view('templates/topbar_dashboard', $data);
            $this->load->view('menu/index.php', $data);
            $this->load->view('templates/footer_dashboard', $data);
        } else {
            $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
               New Menu Has Been Added!
                </div><br>');

            redirect('menu');
        }
    }

    public function submenu()
    {
        // data yang dikirim adalah arrray dan yg digunakan mulai index 1 bukan 0
        $data['title'] = 'Sub Menu Management';
        $data['user'] = $this->db->get_where('user', [
            'email' => $this->session->userdata('email'),
        ])->row_array();
        $data['notifikasi'] = $this->db->query('select * from notifikasi where id_user=' . $data['user']['id_user'] . ' order by tanggal desc limit 6;')->result_array();

        $data['submenu'] = $this->menu->getSubMenu();
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('menu_id', 'Menu_id', 'required');
        $this->form_validation->set_rules('url', 'Url', 'required');
        $this->form_validation->set_rules('icon', 'Icon', 'required');


        if ($this->form_validation->run() == false) {

            $this->load->view('templates/header_dashboard', $data);
            $this->load->view('templates/sidebar_dashboard', $data);
            $this->load->view('templates/topbar_dashboard', $data);
            $this->load->view('menu/submenu', $data);
            $this->load->view('templates/footer_dashboard', $data);
        } else {

            $is_active = $this->input->post('is_active');
            $active = 0;
            if ($is_active == 'on') {
                $active = 1;
            }

            $this->db->insert('user_sub_menu', [
                'title' => $this->input->post('title'),
                'menu_id' => $this->input->post('menu_id'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon'),
                'is_active' =>    $active
            ]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
               New Sub Menu Has Been Added!
                </div><br>');

            redirect('menu/submenu');
        }
    }
}
