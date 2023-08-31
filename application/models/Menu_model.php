<?php

defined('BASEPATH') or exit('No direct script access allowed'); //agar file tidak bisa langsung diakses oleh user

class Menu_model extends CI_Model
{
    public function getSubMenu()
    {
        $query = 'select `user_sub_menu`.*, `user_menu`.`menu`
        from `user_sub_menu` join `user_menu`
        ON `user_sub_menu`.`menu_id` = `user_menu`.`id` 
        ';

        return $this->db->query($query)->result_array();
    }
}
