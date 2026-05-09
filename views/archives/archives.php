<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
  header("Location: /sistem-arsip/auth");
  exit;
}

include '../../functions/function.php';

$archives = query("SELECT 
    sm.id_surat_masuk AS id_surat,
    sm.nomor_surat,
    sm.perihal_surat,
    sm.tanggal_terima AS tanggal,
    smv.file,
    sm.id_versi_aktif,
    'masuk' AS type
  FROM tb_surat_masuk sm
  JOIN tb_surat_masuk_versi smv 
    ON sm.id_versi_aktif = smv.id_versi
  WHERE sm.status = 'arsip'

  UNION ALL

  SELECT 
    sk.id_surat_keluar AS id_surat,
    sk.nomor_surat,
    sk.perihal_surat,
    sk.tanggal_keluar AS tanggal,
    skv.file,
    sk.id_versi_aktif,
    'keluar' AS type
  FROM tb_surat_keluar sk
  JOIN tb_surat_keluar_versi skv 
    ON sk.id_versi_aktif = skv.id_versi
  WHERE sk.status = 'arsip'
");

// cetak laporan
if (isset($_POST['cetak'])) {
  $tanggal_awal = $_POST['tanggal_awal'] ?? date('Y-01-01');
  $tanggal_akhir = $_POST['tanggal_akhir'] ?? date('Y-m-d');

  header("Location: /sistem-arsip/archives/report/?tanggal_awal=$tanggal_awal&tanggal_akhir=$tanggal_akhir");
  exit;
}

// cek arsip rentang tanggal
if (isset($_POST['rentang_tanggal'])) {
  $tanggal_awal = $_POST['tanggal_awal'];
  $tanggal_akhir = $_POST['tanggal_akhir'];

  $archives = query("SELECT 
    sm.id_surat_masuk AS id_surat,
    sm.nomor_surat,
    sm.perihal_surat,
    sm.tanggal_terima AS tanggal,
    smv.file,
    sm.id_versi_aktif,
    'masuk' AS type
  FROM tb_surat_masuk sm
  JOIN tb_surat_masuk_versi smv 
    ON sm.id_versi_aktif = smv.id_versi
  WHERE sm.status = 'arsip'
  AND sm.tanggal_terima 
  BETWEEN '$tanggal_awal' AND '$tanggal_akhir'

  UNION ALL

  SELECT 
    sk.id_surat_keluar AS id_surat,
    sk.nomor_surat,
    sk.perihal_surat,
    sk.tanggal_keluar AS tanggal,
    skv.file,
    sk.id_versi_aktif,
    'keluar' AS type
  FROM tb_surat_keluar sk
  JOIN tb_surat_keluar_versi skv 
    ON sk.id_versi_aktif = skv.id_versi
  WHERE sk.status = 'arsip'
  AND sk.tanggal_keluar 
  BETWEEN '$tanggal_awal' AND '$tanggal_akhir'");
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Arsipan - Arsip Sistem Arsip</title>
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
    <link rel="shortcut icon" href="/sistem-arsip/assets/images/favicon.ico" />

    <!-- jvectormap -->
    <link
      href="/sistem-arsip/plugins/jvectormap/jquery-jvectormap-2.0.2.css"
      rel="stylesheet"
    />

    <!-- App css -->
    <link
      href="/sistem-arsip/assets/css/bootstrap.min.css"
      rel="stylesheet"
      type="text/css"
    />
    <link href="/sistem-arsip/assets/css/jquery-ui.min.css" rel="stylesheet" />
    <link href="/sistem-arsip/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link
      href="/sistem-arsip/assets/css/metisMenu.min.css"
      rel="stylesheet"
      type="text/css"
    />
    <link
      href="/sistem-arsip/plugins/daterangepicker/daterangepicker.css"
      rel="stylesheet"
      type="text/css"
    />
    <link href="/sistem-arsip/assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/sistem-arsip/styles/style.css" />
  </head>

  <body class="dark-sidenav">
    <!-- Left Sidenav -->
    <div class="left-sidenav">
      <!-- LOGO -->
      <div class="brand">
        <a href="/sistem-arsip/dashboard/" class="logo">
          <span>
            <img
              src="/sistem-arsip/assets/images/logo-sm.png"
              alt="logo-small"
              class="logo-sm"
            />
          </span>
          <span>
            <img
              src="/sistem-arsip/assets/images/logo.png"
              alt="logo-large"
              class="logo-lg logo-light"
            />
            <img
              src="/sistem-arsip/assets/images/logo-dark.png"
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
            <a href="/sistem-arsip/dashboard/">
              <i data-feather="home" class="align-self-center menu-icon"></i
              ><span>Dashboard</span></a
            >
            <?php if ($_SESSION['user']['role'] === 'admin') : ?>
            <a href="/sistem-arsip/users/" class="link-active">
              <i data-feather="user" class="align-self-center menu-icon"></i
              ><span>Manajemen Pengguna</span></a
            >
            <?php endif; ?>
          </li>

          <hr class="hr-dashed hr-menu" />
          <li class="menu-label my-2">Master</li>

          <li>
            <a href="/sistem-arsip/incoming/"
              ><i
                data-feather="arrow-up-right"
                class="align-self-center menu-icon"
              ></i
              ><span>Surat Masuk</span></a
            >
          </li>
          <li>
            <a href="/sistem-arsip/outgoing/"
              ><i
                data-feather="arrow-down-right"
                class="align-self-center menu-icon"
              ></i
              ><span>Surat Keluar</span></a
            >
          </li>
          <li>
            <a href="/sistem-arsip/archives/"
              ><i data-feather="folder" class="align-self-center menu-icon"></i
              ><span>Arsip</span></a
            >
          </li>
          <?php if ($_SESSION['user']['role'] === 'admin') : ?>
          <li>
            <a href="/sistem-arsip/categories/"
              ><i data-feather="package" class="align-self-center menu-icon"></i
              ><span>Kategori</span></a
            >
          </li>
          <?php endif; ?>

          <hr class="hr-dashed hr-menu" />
          <li class="menu-label my-2">Lainnya</li>

          <li>
            <a href="/sistem-arsip/actions/logout/logout.php"
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
                          src="/sistem-arsip/assets/images/users/user-4.jpg"
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
                          src="/sistem-arsip/assets/images/users/user-5.jpg"
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
                  src="/sistem-arsip/assets/images/users/user-5.jpg"
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
                  <h4 class="page-title">Arsip</h4>
                  <ol class="breadcrumb">
                    <!-- An Unactive Breadcrumb -->
                    <!-- <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Dastyle</a>
                      </li> -->
                    <li class="breadcrumb-item active">Arsip</li>
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

        <!-- Tabel Arsip -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Data Arsip</h4>
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
                                  value="<?= date('Y-01-01') ?>"
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
                                  value="<?= date('Y-m-d') ?>"
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
                            <button
                              type="submit"
                              name="rentang_tanggal"
                              class="btn btn-outline-success btn-sm"
                            >
                              <i class="mdi mdi-calendar"></i>
                              Periksa Tanggal
                            </button>

                            <button
                              type="submit"
                              name="cetak"
                              class="btn btn-outline-danger btn-sm"
                            >
                              <i class="mdi mdi-printer"></i>
                              Cetak Laporan
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
                      <th class="text-center">Kode Arsip</th>
                      <th class="text-center">Nomor Surat</th>
                      <th class="text-center">Jenis Surat</th>
                      <th class="text-center">Keterangan Surat</th>
                      <th class="text-center">Tanggal</th>
                      <th class="text-center">File Surat</th>
                      <th class="text-center">Detail</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($archives as $archive) : ?>
                    <tr>
                      <td><?= $archive['id_surat']; ?></td>
                      <td><?= $archive['nomor_surat']; ?></td>
                      <td>Surat <?= ucfirst($archive['type']); ?></td>
                      <td><?= $archive['perihal_surat']; ?></td>
                      <td class="text-center"><?= $archive['tanggal']; ?></td>
                      <td class="text-center">
                        <a href="/sistem-arsip/files/download/<?= $archive['type'] == 'masuk' ? 'surat_masuk' : 'surat_keluar'; ?>/<?= $archive['id_versi_aktif']; ?>/" class="text-primary">
                          <i class="mdi mdi-download"></i> Unduh
                        </a>
                      </td>
                      <td class="text-center">
                        <a
                          href="/sistem-arsip/<?= $archive['type'] == 'masuk' ? 'incoming' : 'outgoing'; ?>/<?= $archive['id_surat']; ?>/"
                          class="btn btn-success btn-sm"
                          ><i class="mdi mdi-archive-arrow-up"></i
                        ></a>
                      </td>
                    </tr>
                    <?php endforeach; ?>
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
    <script src="/sistem-arsip/assets/js/jquery.min.js"></script>
    <script src="/sistem-arsip/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/sistem-arsip/assets/js/metismenu.min.js"></script>
    <script src="/sistem-arsip/assets/js/waves.js"></script>
    <script src="/sistem-arsip/assets/js/feather.min.js"></script>
    <script src="/sistem-arsip/assets/js/simplebar.min.js"></script>
    <script src="/sistem-arsip/assets/js/jquery-ui.min.js"></script>
    <script src="/sistem-arsip/assets/js/moment.js"></script>
    <script src="/sistem-arsip/plugins/daterangepicker/daterangepicker.js"></script>

    <!-- Required datatable js -->
    <script src="/sistem-arsip/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/sistem-arsip/plugins/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Buttons examples -->
    <script src="/sistem-arsip/plugins/datatables/dataTables.buttons.min.js"></script>
    <script src="/sistem-arsip/plugins/datatables/buttons.bootstrap4.min.js"></script>
    <script src="/sistem-arsip/plugins/datatables/jszip.min.js"></script>
    <script src="/sistem-arsip/plugins/datatables/pdfmake.min.js"></script>
    <script src="/sistem-arsip/plugins/datatables/vfs_fonts.js"></script>
    <script src="/sistem-arsip/plugins/datatables/buttons.html5.min.js"></script>
    <script src="/sistem-arsip/plugins/datatables/buttons.print.min.js"></script>
    <script src="/sistem-arsip/plugins/datatables/buttons.colVis.min.js"></script>

    <!-- Responsive examples -->
    <script src="/sistem-arsip/plugins/datatables/dataTables.responsive.min.js"></script>
    <script src="/sistem-arsip/plugins/datatables/responsive.bootstrap4.min.js"></script>
    <script src="/sistem-arsip/assets/pages/jquery.datatable.init.js"></script>

    <!-- App js -->
    <script src="/sistem-arsip/assets/js/app.js"></script>

    <!-- Datatables -->
    <script>
      $("#datatable").DataTable();
    </script>
  </body>
</html>
