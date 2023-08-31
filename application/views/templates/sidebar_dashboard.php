<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion " id="accordionSidebar">


    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('user') ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-solid fa-laptop"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin Inagi</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- melakukan query menu untuk menampilkan menu -->
    <?php
    $role_id = $this->session->userdata('role_id');
    $query_menu =   'SELECT `user_menu`.`id` , `menu`
                        FROM  `user_menu` JOIN `user_access_menu`
                        ON `user_menu`.`id` = `user_access_menu`.`menu_id`
                    WHERE `user_access_menu`.`role_id` = ' . $role_id . '
                    ORDER BY `user_menu`.`priority` ASC';

    $menu = $this->db->query($query_menu)->result_array();

    ?>

    <!-- LOOPING MENU -->
    <?php foreach ($menu as $m) : ?>

        <!-- Heading -->
        <div class="sidebar-heading">
            <?= $m['menu'] ?>
        </div>

        <!-- mengambil sub menu -->
        <?php
        $query_sub_menu =   'SELECT *
                             FROM  `user_sub_menu` JOIN `user_menu`
                             ON `user_sub_menu`.`menu_id` = `user_menu`.`id`
                            WHERE `user_sub_menu`.`menu_id` = ' . $m['id'] . '
                            AND `is_active` = 1';

        $submenu = $this->db->query($query_sub_menu)->result_array();

        ?>

        <!-- looping sub menu -->
        <?php foreach ($submenu as $sm) : ?>

            <?php if ($title == $sm['title']) : ?>

                <!-- Nav Item - Dashboard -->
                <li class="nav-item active">
                <?php else : ?>
                    <!-- Nav Item - Dashboard -->
                <li class="nav-item">
                <?php endif; ?>


                <a class="nav-link pb-0" href="<?= base_url($sm['url']) ?>">
                    <i class="<?= $sm['icon'] ?>"></i>
                    <span><?= $sm['title'] ?></span></a>
                </li>


            <?php endforeach; ?>


            <!-- Divider -->
            <hr class="sidebar-divider mt-3">


        <?php endforeach; ?>

        <!-- Nav Item - Logout -->
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('auth/logout') ?>" data-toggle="modal" data-target="#logoutModal">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Log Out</span></a>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

</ul>
<!-- End of Sidebar -->