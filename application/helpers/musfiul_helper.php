<?php
function sudah_masuk()
{
    // karena $this tidak bisa langsung diakses di helper baru karena diluar dari MVC
    // maka membuat instansiasi kelas CI baru

    // inatnsiasi kelas CI
    $CI = get_instance();
    if (!$CI->session->userdata('email')) {
        redirect('auth');
    } else {
        $role_id = $CI->session->userdata('role_id');

        // cara ambil lokasi saat ini dengan uri
        $menu = $CI->uri->segment(1);

        $query = $CI->db->get_where('user_menu', ['menu' => $menu])->row_array();
        $menu_id = $query['id'];

        $userAccess = $CI->db->get_where('user_access_menu', [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ]);

        if ($userAccess->num_rows() < 1) {
            redirect('auth/blocked');
        }
    }
}
function cek_akses($id_role, $id)
{
    $CI = get_instance();

    $CI->db->where('role_id', $id_role);
    $CI->db->where('menu_id', $id);
    $result = $CI->db->get('user_access_menu');

    if ($result->num_rows() > 0) {
        return "checked = 'checked'"; 
    }
}
