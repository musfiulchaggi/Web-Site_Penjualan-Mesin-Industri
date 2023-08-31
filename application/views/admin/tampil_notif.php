  <!-- Nav Item - Alerts -->
  <li class="nav-item dropdown no-arrow mx-1">
      <a class="nav-link dropdown-toggle" id="alertsDropdown" role="button" data-id="<?= $user['id_user'] ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-bell fa-fw"></i>
          <!-- Counter - Alerts -->
          <span class="badge badge-danger badge-counter"><?php $tot = 0;
                                                            $cek = 0;
                                                            foreach ($notifikasi as $nt) {
                                                                if ($nt['dibuka'] == 1) {
                                                                    $tot++;
                                                                }
                                                                $cek++;
                                                            }
                                                            if ($tot != 0) {

                                                                echo ($tot) . '+';
                                                            } else {
                                                                echo '';
                                                            } ?></span>
      </a>
      <!-- Dropdown - Alerts -->
      <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
          <h6 class="dropdown-header">
              <?php if ($cek == 0) {
                    echo 'Tidak Ada Notifikasi';
                } else {
                    echo 'Notifikasi';
                } ?>

          </h6>

          <div id="lihattblpmb">
              <!-- kirim notifikasi -->
              <?php foreach ($notifikasi as $nt) : ?>


                  <?php
                    $idpjl = trim($nt['keterangan'], 'Id Penjualan=');

                    if ($nt['judul'] == 'Transaksi Pembayaran') {

                        echo ' <a class="lihatterminadm dropdown-item d-flex align-items-center bg-warning text-light" data-toggle="modal" data-target="#mdllihatterminadm" data-idpjl="' . $idpjl . '">';
                    } else if ($nt['judul'] == 'Transaksi Penjualan ') {
                        echo ' <a class="dropdown-item d-flex align-items-center bg-success text-light" href="' . base_url($nt['url']) . '">';
                    } else {
                        echo ' <a class="dropdown-item d-flex align-items-center text-dark" href="' . base_url($nt['url']) . '">';
                    }

                    ?>

                  <div class="mr-3">
                      <!-- <div class="icon-circle bg-primary">
                                                        <i class="fas fa-file-alt text-white"></i>
                                                    </div> -->
                  </div>
                  <div>
                      <div class="small text-500 text-dark"><?= date('F d, Y', strtotime($nt['tanggal'])); ?></div>
                      <div><span class="font-weight-bold"><?= $nt['judul'] ?></span></div>
                      <span class="font-weight-bold"><?= $nt['keterangan'] ?></span>
                  </div>

                  </a>



              <?php endforeach; ?>

          </div>
      </div>
  </li>