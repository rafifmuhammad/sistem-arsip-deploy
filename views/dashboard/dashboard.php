<?php
  session_start();

  if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: /sistem-arsip/auth");
    exit;
  }

  include '../../functions/function.php';

  $total_surat_masuk = query("SELECT COUNT(*) AS total FROM tb_surat_masuk")[0]['total'] ?? 0;
  $total_surat_keluar = query("SELECT COUNT(*) AS total FROM tb_surat_keluar")[0]['total'] ?? 0;
  $total_pengguna = query("SELECT COUNT(*) AS total FROM tb_user")[0]['total'] ?? 0;
  $total_arsip = query("SELECT
      (
        (SELECT COUNT(*) FROM tb_surat_masuk WHERE status = 'arsip') +
        (SELECT COUNT(*) FROM tb_surat_keluar WHERE status = 'arsip')
      ) AS total
    ")[0]['total'] ?? 0;

  $surat_masuk_hari_ini = query("SELECT COUNT(*) AS total FROM tb_surat_masuk WHERE tanggal_terima = CURDATE()")[0]['total'] ?? 0;
  $surat_keluar_hari_ini = query("SELECT COUNT(*) AS total FROM tb_surat_keluar WHERE tanggal_keluar = CURDATE()")[0]['total'] ?? 0;
  $pengguna_hari_ini = query("SELECT COUNT(*) AS total FROM tb_user WHERE created_at = CURDATE()")[0]['total'] ?? 0;
  $arsip_hari_ini = query("SELECT
      (
        (SELECT COUNT(*) FROM tb_surat_masuk WHERE status = 'arsip' AND tanggal_terima = CURDATE()) +
        (SELECT COUNT(*) FROM tb_surat_keluar WHERE status = 'arsip' AND tanggal_keluar = CURDATE())
      ) AS total
    ")[0]['total'] ?? 0;

  $dashboard_cards = [
    [
      'title' => 'Surat Masuk',
      'total' => $total_surat_masuk,
      'today' => $surat_masuk_hari_ini,
      'icon' => 'book-open'
    ],
    [
      'title' => 'Surat Keluar',
      'total' => $total_surat_keluar,
      'today' => $surat_keluar_hari_ini,
      'icon' => 'clock'
    ],
    [
      'title' => 'Pengguna',
      'total' => $total_pengguna,
      'today' => $pengguna_hari_ini,
      'icon' => 'user'
    ],
    [
      'title' => 'Jumlah Surat Diarsipkan',
      'total' => $total_arsip,
      'today' => $arsip_hari_ini,
      'icon' => 'database'
    ]
  ];
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Arsipan - Dashboard Sistem Arsip</title>
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
            <a href="/sistem-arsip/dashboard/" class="link-active">
              <i data-feather="home" class="align-self-center menu-icon"></i
              ><span>Dashboard</span></a
            >
            <?php if ($_SESSION['user']['role'] === 'admin') : ?>
            <a href="/sistem-arsip/users/">
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
        <div class="container-fluid">
          <!-- Page-Title -->
          <div class="row">
            <div class="col-sm-12">
              <div class="page-title-box">
                <div class="row">
                  <div class="col">
                    <h4 class="page-title">Dashboard</h4>
                    <ol class="breadcrumb">
                      <!-- An Unactive Breadcrumb -->
                      <!-- <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Dastyle</a>
                      </li> -->
                      <li class="breadcrumb-item active">Dashboard</li>
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
          <!-- end page title end breadcrumb -->
          <div class="row justify-content-around">
            <?php foreach ($dashboard_cards as $card) : ?>
            <div class="col-md-6 col-lg-3">
              <div class="card report-card">
                <div class="card-body">
                  <div class="row d-flex justify-content-center">
                    <div class="col">
                      <p class="text-dark mb-1 font-weight-semibold">
                        <?= $card['title']; ?>
                      </p>
                      <h3 class="my-2"><?= $card['total']; ?></h3>
                      <p class="mb-0 text-truncate text-muted">
                        <span class="text-success"
                          ><i class="mdi mdi-trending-up"></i>+<?= $card['today']; ?></span
                        >
                        Hari ini
                      </p>
                    </div>
                    <div class="col-auto align-self-center">
                      <div class="report-main-icon bg-light-alt">
                        <i
                          data-feather="<?= $card['icon']; ?>"
                          class="align-self-center text-muted icon-md"
                        ></i>
                      </div>
                    </div>
                  </div>
                </div>
                <!--end card-body-->
              </div>
              <!--end card-->
            </div>
            <!--end col-->
            <?php endforeach; ?>
          </div>
          <!--end row-->
        </div>
        <!-- container -->

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

    <script src="/sistem-arsip/plugins/apex-charts/apexcharts.min.js"></script>
    <script src="/sistem-arsip/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="/sistem-arsip/plugins/jvectormap/jquery-jvectormap-us-aea-en.js"></script>
    <script src="/sistem-arsip/assets/pages/jquery.analytics_dashboard.init.js"></script>

    <!-- App js -->
    <script src="/sistem-arsip/assets/js/app.js"></script>
  </body>
</html>
