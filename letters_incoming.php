<?php
session_start();

include './functions/function.php';

$letters = query("SELECT * FROM tb_surat_masuk");

if (isset($_POST['rentang_tanggal'])) {
  $tanggal_awal = $_POST['tanggal_awal'];
  $tanggal_akhir = $_POST['tanggal_akhir'];

  $letters = query("SELECT * FROM tb_surat_masuk
    WHERE tanggal_terima BETWEEN '$tanggal_awal' 
    AND '$tanggal_akhir'");
}

if (isset($_POST['tolak'])) {
  if (rejectLetter($_POST) > 0) {
    $_SESSION['flash'] = [
      "icon" => "success",
      "title" => "Berhasil",
      "text" => "Surat masuk berhasil ditolak",
    ];
  } else {
    $_SESSION['flash'] = [
      "icon" => "error",
      "title" => "Gagal",
      "text" => "Surat masuk gagal ditolak",
    ];
  }

  header("Location: letters_incoming.php");
  exit;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Arsipan - Surat Masuk Sistem Arsip</title>
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta
      content="Premium Multipurpose Admin & Dashboard Template"
      name="description"
    />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico" />

    <!-- jvectormap -->
    <link
      href="./plugins/jvectormap/jquery-jvectormap-2.0.2.css"
      rel="stylesheet"
    />

    <!-- App css -->
    <link
      href="assets/css/bootstrap.min.css"
      rel="stylesheet"
      type="text/css"
    />
    <link href="assets/css/jquery-ui.min.css" rel="stylesheet" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link
      href="assets/css/metisMenu.min.css"
      rel="stylesheet"
      type="text/css"
    />
    <link
      href="./plugins/daterangepicker/daterangepicker.css"
      rel="stylesheet"
      type="text/css"
    />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="./styles/style.css" />
  </head>

  <body class="dark-sidenav">
    <!-- Left Sidenav -->
    <div class="left-sidenav">
      <!-- LOGO -->
      <div class="brand">
        <a href="dashboard/crm-index.php" class="logo">
          <span>
            <img
              src="assets/images/logo-sm.png"
              alt="logo-small"
              class="logo-sm"
            />
          </span>
          <span>
            <img
              src="assets/images/logo.png"
              alt="logo-large"
              class="logo-lg logo-light"
            />
            <img
              src="assets/images/logo-dark.png"
              alt="logo-large"
              class="logo-lg logo-dark"
            />
          </span>
        </a>
      </div>
      <!--end logo-->
      <div class="menu-content h-100" data-simplebar>
        <ul class="metismenu left-sidenav-menu">
          <li class="menu-label mt-0">Menu Utama</li>
          <li>
            <a href="./index.php">
              <i data-feather="home" class="align-self-center menu-icon"></i
              ><span>Dashboard</span></a
            >
            <a href="./user_management.php" class="link-active">
              <i data-feather="user" class="align-self-center menu-icon"></i
              ><span>Manajemen Pengguna</span></a
            >
          </li>

          <hr class="hr-dashed hr-menu" />
          <li class="menu-label my-2">Master</li>

          <li>
            <a href="./letters_incoming.php"
              ><i
                data-feather="arrow-up-right"
                class="align-self-center menu-icon"
              ></i
              ><span>Surat Masuk</span></a
            >
          </li>
          <li>
            <a href="./letters_outgoing.php"
              ><i
                data-feather="arrow-down-right"
                class="align-self-center menu-icon"
              ></i
              ><span>Surat Keluar</span></a
            >
          </li>
          <li>
            <a href="archives.php"
              ><i data-feather="folder" class="align-self-center menu-icon"></i
              ><span>Arsip</span></a
            >
          </li>
          <li>
            <a href="./categories.php"
              ><i data-feather="package" class="align-self-center menu-icon"></i
              ><span>Kategori</span></a
            >
          </li>

          <hr class="hr-dashed hr-menu" />
          <li class="menu-label my-2">Lainnya</li>

          <li>
            <a href="javascript: void(0);"
              ><i data-feather="log-out" class="align-self-center menu-icon"></i
              ><span>Keluar</span></a
            >
          </li>
        </ul>
      </div>
    </div>
    <!-- end left-sidenav-->

    <div class="page-wrapper">
      <!-- Top Bar Start -->
      <div class="topbar">
        <!-- Navbar -->
        <nav class="navbar-custom">
          <ul class="list-unstyled topbar-nav float-right mb-0">
            <li class="dropdown notification-list">
              <a
                class="nav-link dropdown-toggle arrow-none waves-light waves-effect"
                data-toggle="dropdown"
                href="#"
                role="button"
                aria-haspopup="false"
                aria-expanded="false"
              >
                <i
                  data-feather="bell"
                  class="align-self-center topbar-icon"
                ></i>
                <span class="badge badge-danger badge-pill noti-icon-badge"
                  >2</span
                >
              </a>
              <div class="dropdown-menu dropdown-menu-right dropdown-lg pt-0">
                <h6
                  class="dropdown-item-text font-15 m-0 py-3 border-bottom d-flex justify-content-between align-items-center"
                >
                  Notifications
                  <span class="badge badge-primary badge-pill">2</span>
                </h6>
                <div class="notification-menu" data-simplebar>
                  <!-- item-->
                  <a href="#" class="dropdown-item py-3">
                    <small class="float-right text-muted pl-2">2 min ago</small>
                    <div class="media">
                      <div class="avatar-md bg-soft-primary">
                        <i
                          data-feather="shopping-cart"
                          class="align-self-center icon-xs"
                        ></i>
                      </div>
                      <div
                        class="media-body align-self-center ml-2 text-truncate"
                      >
                        <h6 class="my-0 font-weight-normal text-dark">
                          Your order is placed
                        </h6>
                        <small class="text-muted mb-0"
                          >Dummy text of the printing and industry.</small
                        >
                      </div>
                      <!--end media-body-->
                    </div>
                    <!--end media--> </a
                  ><!--end-item-->
                  <!-- item-->
                  <a href="#" class="dropdown-item py-3">
                    <small class="float-right text-muted pl-2"
                      >10 min ago</small
                    >
                    <div class="media">
                      <div class="avatar-md bg-soft-primary">
                        <img
                          src="assets/images/users/user-4.jpg"
                          alt=""
                          class="thumb-sm rounded-circle"
                        />
                      </div>
                      <div
                        class="media-body align-self-center ml-2 text-truncate"
                      >
                        <h6 class="my-0 font-weight-normal text-dark">
                          Meeting with designers
                        </h6>
                        <small class="text-muted mb-0"
                          >It is a long established fact that a reader.</small
                        >
                      </div>
                      <!--end media-body-->
                    </div>
                    <!--end media--> </a
                  ><!--end-item-->
                  <!-- item-->
                  <a href="#" class="dropdown-item py-3">
                    <small class="float-right text-muted pl-2"
                      >40 min ago</small
                    >
                    <div class="media">
                      <div class="avatar-md bg-soft-primary">
                        <i
                          data-feather="users"
                          class="align-self-center icon-xs"
                        ></i>
                      </div>
                      <div
                        class="media-body align-self-center ml-2 text-truncate"
                      >
                        <h6 class="my-0 font-weight-normal text-dark">
                          UX 3 Task complete.
                        </h6>
                        <small class="text-muted mb-0"
                          >Dummy text of the printing.</small
                        >
                      </div>
                      <!--end media-body-->
                    </div>
                    <!--end media--> </a
                  ><!--end-item-->
                  <!-- item-->
                  <a href="#" class="dropdown-item py-3">
                    <small class="float-right text-muted pl-2">1 hr ago</small>
                    <div class="media">
                      <div class="avatar-md bg-soft-primary">
                        <img
                          src="assets/images/users/user-5.jpg"
                          alt=""
                          class="thumb-sm rounded-circle"
                        />
                      </div>
                      <div
                        class="media-body align-self-center ml-2 text-truncate"
                      >
                        <h6 class="my-0 font-weight-normal text-dark">
                          Your order is placed
                        </h6>
                        <small class="text-muted mb-0"
                          >It is a long established fact that a reader.</small
                        >
                      </div>
                      <!--end media-body-->
                    </div>
                    <!--end media--> </a
                  ><!--end-item-->
                  <!-- item-->
                  <a href="#" class="dropdown-item py-3">
                    <small class="float-right text-muted pl-2">2 hrs ago</small>
                    <div class="media">
                      <div class="avatar-md bg-soft-primary">
                        <i
                          data-feather="check-circle"
                          class="align-self-center icon-xs"
                        ></i>
                      </div>
                      <div
                        class="media-body align-self-center ml-2 text-truncate"
                      >
                        <h6 class="my-0 font-weight-normal text-dark">
                          Payment Successfull
                        </h6>
                        <small class="text-muted mb-0"
                          >Dummy text of the printing.</small
                        >
                      </div>
                      <!--end media-body-->
                    </div>
                    <!--end media--> </a
                  ><!--end-item-->
                </div>
                <!-- All-->
                <a
                  href="javascript:void(0);"
                  class="dropdown-item text-center text-primary"
                >
                  View all <i class="fi-arrow-right"></i>
                </a>
              </div>
            </li>

            <li class="dropdown">
              <a
                class="nav-link dropdown-toggle waves-effect waves-light nav-user"
                data-toggle="dropdown"
                href="#"
                role="button"
                aria-haspopup="false"
                aria-expanded="false"
              >
                <span class="ml-1 nav-user-name hidden-sm">Admin</span>
                <img
                  src="assets/images/users/user-5.jpg"
                  alt="profile-user"
                  class="rounded-circle"
                />
              </a>
            </li>
          </ul>
          <!--end topbar-nav-->

          <ul class="list-unstyled topbar-nav mb-0">
            <li>
              <button class="nav-link button-menu-mobile">
                <i
                  data-feather="menu"
                  class="align-self-center topbar-icon"
                ></i>
              </button>
            </li>
          </ul>
        </nav>
        <!-- end navbar-->
      </div>
      <!-- Top Bar End -->

      <!-- Page Content-->
      <div class="page-content">
        <div class="container-fluid"></div>
        <!-- container -->

        <!-- Your content Goes Here -->
        <div class="row">
          <div class="col-sm-12">
            <div class="page-title-box">
              <div class="row">
                <div class="col">
                  <h4 class="page-title">Surat Masuk</h4>
                  <ol class="breadcrumb">
                    <!-- An Unactive Breadcrumb -->
                    <!-- <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Dastyle</a>
                      </li> -->
                    <li class="breadcrumb-item active">Surat Masuk</li>
                  </ol>
                </div>
                <!--end col-->
              </div>
              <!--end row-->
            </div>
            <!--end page-title-box-->
          </div>
          <!--end col-->
        </div>
        <!--end row-->

        <!-- Tabel Surat Masuk -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Data Surat Masuk</h4>
              </div>
              <!--end card-header-->

              <div class="card-body">
                <div class="col-auto mr-1" style="margin-left: 0.1rem">
                  <!-- date range -->
                  <form action="" method="POST" class="form-horizontal">
                    <fieldset>
                      <div class="repeater-default">
                        <div data-repeater-list="car">
                          <div data-repeater-item="">
                            <div class="form-group row d-flex">
                              <div class="col-sm-6">
                                <label class="control-label"
                                  >Tanggal Awal</label
                                >
                                <input
                                  type="date"
                                  name="tanggal_awal"
                                  class="form-control"
                                  value="<?= date('Y-m-d'); ?>"
                                />
                              </div>
                              <!--end col-->

                              <div class="col-sm-6">
                                <label class="control-label"
                                  >Tanggal Akhir</label
                                >
                                <input
                                  type="date"
                                  name="tanggal_akhir"
                                  class="form-control"
                                  value="<?= date('Y-m-d'); ?>"
                                />
                              </div>
                              <!--end col-->
                            </div>
                            <!--end row-->
                          </div>
                          <!--end /div-->
                        </div>
                        <!--end repet-list-->
                        <div class="form-group row">
                          <div class="col-sm-12">
                            <a
                              href="./letters_add_incoming.php"
                              type="button"
                              name="tambah_surat_masuk"
                              class="btn btn-outline-primary btn-sm"
                            >
                              <i class="mdi mdi-plus-circle"></i>
                              Tambah</a
                            >

                            <button
                              type="submit"
                              name="rentang_tanggal"
                              class="btn btn-outline-success btn-sm"
                            >
                              <i class="mdi mdi-calendar"></i>
                              Periksa Tanggal
                            </button>
                          </div>
                          <!--end col-->
                        </div>
                        <!--end row-->
                      </div>
                      <!--end repeter-->
                    </fieldset>
                    <!--end fieldset-->
                  </form>
                  <!--end form-->
                </div>

                <table
                  id="datatable"
                  class="table table-striped table-bordered table-hover dt-responsive nowrap"
                  style="
                    border-collapse: collapse;
                    border-spacing: 0;
                    width: 100%;
                  "
                >
                  <thead class="bg-soft-primary">
                    <tr>
                      <th class="text-center">Nomor Surat</th>
                      <th class="text-center">Sumber Surat</th>
                      <th class="text-center">Perihal Surat</th>
                      <th class="text-center">Keterangan Surat</th>
                      <th class="text-center">Tanggal Terima</th>
                      <th class="text-center">Status</th>
                      <th class="text-center">File Surat</th>
                      <th class="text-center">Detail</th>
                      <th class="text-center">Ubah</th>
                      <th class="text-center">Hapus</th>
                      <th class="text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($letters as $letter) : ?>
                    <tr>
                      <td class="text-center"><?= $letter['nomor_surat']; ?></td>
                      <td><?= $letter['sumber_surat']; ?></td>
                      <td><?= $letter['perihal_surat']; ?></td>
                      <td><?= $letter['keterangan_surat']; ?></td>
                      <td class="text-center"><?= $letter['tanggal_terima']; ?></td>
                      <td class="text-center">
                        <?php if (empty($letter['alasan'])) : ?>
                        <span 
                          class="badge 
                            <?php 
                              if ($letter['status'] == 'validasi') {
                                echo "badge-soft-warning";
                              } else if ($letter['status'] == 'draft') {
                                echo "badge-soft-info";
                              } else {
                                echo "badge-soft-success";
                              }
                            ?>
                          "
                        >
                          <?= ucfirst($letter['status']); ?>
                        </span>
                        <?php endif; ?>
                        <?php if ($letter['status'] === 'draft' && !empty($letter['alasan'])) : ?>
                        <button 
                          class="badge badge-soft-danger btn btn-default"
                          data-toggle="modal"
                          data-target="#modalAlasan<?= $letter['id_surat_masuk']; ?>"
                        >
                          <?= ucfirst($letter['status']); ?> / Ditolak
                        </button>
                        <?php endif; ?>

                        <!-- Modal alasan -->
                        <div class="modal fade" id="modalAlasan<?= $letter['id_surat_masuk']; ?>" tabindex="-1">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <form method="POST" action="">
                                <div class="modal-header">
                                  <h5 class="modal-title">Alasan Ditolak</h5>
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body text-left">
                                  <p><?= $letter['alasan']; ?></p>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Tutup</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="text-center">
                        <a href="./download.php?id_versi=<?= $letter['id_versi_aktif']; ?>&type=<?= 'surat_masuk'; ?>" class="text-primary">
                          <i class="mdi mdi-download"></i> Unduh
                        </a>
                      </td>
                      <td class="text-center">
                        <a
                          href="./letters_detail.php?id_surat_masuk=<?= $letter['id_surat_masuk']; ?>&type=<?= 'surat_masuk'; ?>"
                          class="btn btn-success btn-sm"
                          ><i class="mdi mdi-archive-arrow-up"></i
                        ></a>
                      </td>
                      <?php if ($letter['status'] !== 'arsip') : ?>
                      <td class="text-center">
                        <a
                          href="./letters_edit_incoming.php?id_surat_masuk=<?= $letter['id_surat_masuk']; ?>"
                          class="btn btn-warning btn-sm"
                          ><i class="mdi mdi-pencil"></i
                        ></a>
                      </td>
                      <?php else : ?>
                      <td class="text-center">
                        <span class="btn btn-secondary btn-sm" style="cursor: default;"
                          ><i class="mdi mdi-pencil"></i
                        ></span>
                      </td>
                      <?php endif; ?>
                      <?php if ($letter['status'] !== 'arsip') : ?>
                      <td class="text-center">
                        <a href="#" class="btn btn-danger btn-sm tombol-hapus" data-href="letters_delete_incoming.php?id_surat_masuk=<?= $letter['id_surat_masuk']; ?>"
                          ><i class="mdi mdi-delete"></i
                        ></a>
                      </td>
                      <?php else : ?>
                      <td class="text-center">
                        <span class="btn btn-secondary btn-sm" style="cursor: default;"
                          ><i class="mdi mdi-delete"></i
                        ></span>
                      </td>
                      <?php endif; ?>
                      <?php if ($letter['status'] !== 'arsip') : ?>
                      <td>
                        <div class="dropdown d-inline">
                          <a class="dropdown-toggle btn btn-outline-success btn-sm" data-toggle="dropdown">
                            Aksi
                          </a>
                          <div class="dropdown-menu">
                            <a class="dropdown-item text-primary tombol-validasi" href="#" data-href="letters_validation.php?id_surat_masuk=<?= $letter['id_surat_masuk']; ?>&type=<?= 'surat_masuk'; ?>">Validasi</a>
                            <a class="dropdown-item text-success tombol-setujui" href="#" data-href="letters_approve.php?id_surat_masuk=<?= $letter['id_surat_masuk']; ?>&type=surat_masuk">Setujui</a>
                            <a 
                              class="dropdown-item text-danger btn-tolak" 
                              href="#"
                              data-toggle="modal"
                              data-target="#modalTolak"
                              data-id="<?= $letter['id_surat_masuk']; ?>"
                              data-type="surat_masuk"
                            >Tolak</a
                            >
                          </div>
                        </div>
                      </td>
                      <?php else : ?>
                      <td class="text-center">
                        <span class="badge badge-soft-primary">
                          Final
                        </span>
                      </td>
                      <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                    
                    <!-- Modal tolak -->
                    <div class="modal fade" id="modalTolak" tabindex="-1">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <form method="POST" action="">
                            <div class="modal-header">
                              <h5 class="modal-title">Tolak Surat</h5>
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <div class="modal-body">

                              <input type="hidden" name="id_surat" id="id_surat">
                              <input type="hidden" name="type" id="type">

                              <div class="form-group">
                                <label>Alasan Penolakan</label>
                                <select name="alasan_select" id="alasan_select" class="form-control" required>
                                  <option value="">-- Pilih Alasan --</option>
                                  <option value="Perihal tidak jelas">Perihal tidak jelas</option>
                                  <option value="Nomor surat salah">Nomor surat salah</option>
                                  <option value="File rusak">File rusak</option>
                                  <option value="Sumber surat tidak jelas">Sumber surat tidak jelas</option>
                                  <option value="lainnya">Lainnya</option>
                                </select>
                              </div>
                              <div class="form-group" id="form_lainnya" style="display:none;">
                                <label>Alasan Lainnya</label>
                                <textarea name="alasan_lainnya" class="form-control" rows="3"></textarea>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Batal</button>
                              <button type="submit" class="btn btn-outline-danger btn-sm" name="tolak">Tolak</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    <!-- End modal -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- end col -->
        </div>
        <!-- end row -->

        <footer class="footer text-center text-sm-left">
          &copy; 2026 Arsipan
          <span class="d-none d-sm-inline-block float-right"
            >Crafted with <i class="mdi mdi-heart text-danger"></i
          ></span>
        </footer>
        <!--end footer-->
      </div>
      <!-- end page content -->
    </div>
    <!-- end page-wrapper -->

    <!-- jQuery  -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/metismenu.min.js"></script>
    <script src="assets/js/waves.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/js/simplebar.min.js"></script>
    <script src="assets/js/jquery-ui.min.js"></script>
    <script src="assets/js/moment.js"></script>
    <script src="./plugins/daterangepicker/daterangepicker.js"></script>

    <!-- Required datatable js -->
    <script src="./plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="./plugins/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Buttons examples -->
    <script src="./plugins/datatables/dataTables.buttons.min.js"></script>
    <script src="./plugins/datatables/buttons.bootstrap4.min.js"></script>
    <script src="./plugins/datatables/jszip.min.js"></script>
    <script src="./plugins/datatables/pdfmake.min.js"></script>
    <script src="./plugins/datatables/vfs_fonts.js"></script>
    <script src="./plugins/datatables/buttons.html5.min.js"></script>
    <script src="./plugins/datatables/buttons.print.min.js"></script>
    <script src="./plugins/datatables/buttons.colVis.min.js"></script>

    <!-- Responsive examples -->
    <script src="./plugins/datatables/dataTables.responsive.min.js"></script>
    <script src="./plugins/datatables/responsive.bootstrap4.min.js"></script>
    <script src="assets/pages/jquery.datatable.init.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

    <!-- Datatables -->
    <script>
      $("#datatable").DataTable();
    </script>

    <!-- SweatAlert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>
      <?php if (isset($_SESSION["flash"])) : ?>
      swal({
          title: <?= json_encode($_SESSION["flash"]["title"]); ?>,
          text: <?= json_encode($_SESSION["flash"]["text"]); ?>,
          icon: <?= json_encode($_SESSION["flash"]["icon"]); ?>,
          button: "OK",
      });
      <?php unset($_SESSION["flash"]); ?>
      <?php endif; ?>

      $('.tombol-hapus').on('click', function(e) {
        e.preventDefault();

        const href = $(this).data('href');

        swal({
          title: "Yakin?",
            text: "Data surat masuk akan dihapus",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
        if (willDelete) {
              document.location.href = href;
            }
          });
        }
      );

      // Handle validasi arsip
      $('.tombol-validasi').on('click', function(e) {
        e.preventDefault();

        const href = $(this).data('href');

        swal({
          title: "Yakin?",
            text: "Validasi surat",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willValidate) => {
            if (willValidate) {
              document.location.href = href;
            }
          });
        }
      );

      // Handle setujui arsip
      $('.tombol-setujui').on('click', function(e) {
        e.preventDefault();

        const href = $(this).data('href');

        swal({
          title: 'Yakin?',
          text: 'Setujui surat',
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        }).then((willValidate) => {
          if (willValidate) {
            document.location.href = href;
          }
        })
      });

      // Handle tolak surat dengan opsi lainnya
      $('.btn-tolak').on('click', function () {
        let id = $(this).data('id');
        let type = $(this).data('type');

        $('#id_surat').val(id);
        $('#type').val(type);
      });

      $('#alasan_select').on('change', function () {
        if ($(this).val() === 'lainnya') {
          $('#form_lainnya').show();
        } else {
          $('#form_lainnya').hide();
        }
      });
    </script>
  </body>
</html>
