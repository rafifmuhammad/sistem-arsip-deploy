<?php
session_start();

include './functions/function.php';

$letter_codes = query("SELECT * FROM tb_kategori");

if (isset($_POST['tambah'])) {
  $result = addOutgoingLetter($_POST);

  if ($result === -1) {
    $_SESSION["flash"] = [
        "icon" => "warning",
        "title" => "Duplikat",
        "text" => "Surat sudah ada. Silahkan ubah surat untuk menambah versi terbaru!"
    ];
  } elseif ($result > 0) {
      $_SESSION["flash"] = [
          "icon" => "success",
          "title" => "Berhasil",
          "text" => "Surat berhasil ditambahkan."
      ];
  } else {
      $_SESSION["flash"] = [
          "icon" => "error",
          "title" => "Gagal",
          "text" => "Terjadi kesalahan."
      ];
  }

  header("Location: letters_outgoing.php");
  exit;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Arsipan - Tambah Surat Keluar Sistem Arsip</title>
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
                  <h4 class="page-title">Tambah Surat Keluar</h4>
                  <ol class="breadcrumb">
                    <!-- An Unactive Breadcrumb -->
                    <li class="breadcrumb-item">
                      <a href="./letters_outgoing.php">Surat Keluar</a>
                    </li>
                    <li class="breadcrumb-item active">Tambah Surat Keluar</li>
                  </ol>
                </div>
                <!--end col-->
              </div>
              <!--end row-->

              <div class="card mt-2">
                <div class="card-header">
                  <h4 class="card-title">Tambah Surat Masuk</h4>
                </div>
                <!--end card-header-->

                <div class="card-body">
                  <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                      <label for="kode_surat">Kode Surat</label>
                      <select
                        class="form-control"
                        name="kode_surat"
                        id="kode_surat"
                      >
                      <option selected="selected">Pilih kode surat</option>
                      <?php foreach ($letter_codes as $code) : ?>
                        <option value="<?= $code['kode_kategori']; ?>"><?= $code['nama_kategori']; ?></option>
                      <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="nomor_surat">Nomor Surat</label>
                      <input
                        type="text"
                        class="form-control"
                        name="nomor_surat"
                        placeholder="Masukkan nomor surat. Contoh: 140/ADM-DS/IV/2026"
                      />
                      <div class="text-info mt-1">
                        <i class="mdi mdi-comment-question-outline"></i> Nomor
                        surat secara otomatis diperoleh dari kode surat!
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="perihal_surat">Perihal Surat</label>
                      <input
                        type="text"
                        class="form-control"
                        name="perihal_surat"
                        placeholder="Masukkan perihal surat"
                      />
                    </div>
                    <div class="form-group">
                      <label for="keterangan">Keterangan Surat</label>
                      <input
                        type="text"
                        class="form-control"
                        name="keterangan"
                        placeholder="Masukkan keterangan surat"
                      />
                      <div class="card mt-2">
                        <div class="card-header">
                          <h4 class="card-title">Unggah Dokumen</h4>
                          <p class="text-danger mb-0">
                            Unggah fail dalam format PDF/Docx/Doc
                          </p>
                        </div>
                        <!--end card-header-->
                        <div class="card-body">
                          <input
                            type="file"
                            id="file"
                            name="file"
                            class="dropify"
                            data-default-file="./assets/images/logo.jpg"
                          />
                          
                        </div>
                        <!--end card-body-->
                      </div>
                      <!--end card-->
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm" name="tambah">
                      Simpan
                    </button>
                    <button type="button" class="btn btn-danger btn-sm">
                      Batal
                    </button>
                  </form>
                  <!--end card-body-->
                </div>
                <!--end card-->
              </div>
              <!--end col-->
            </div>
            <!--end page-title-box-->
          </div>
          <!--end col-->
        </div>
        <!--end row-->

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

    <script src="./plugins/apex-charts/apexcharts.min.js"></script>
    <script src="./plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="./plugins/jvectormap/jquery-jvectormap-us-aea-en.js"></script>
    <script src="assets/pages/jquery.analytics_dashboard.init.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

    <script>
      $(document).ready(function() {
          $('#kode_surat').on('change', function() {
              var kode = $(this).val();
              console.log('Kode dipilih:', kode);
              if (kode && kode !== 'Pilih kode surat') {
                  $.ajax({
                      url: 'letters_generate_number.php',  
                      type: 'POST',
                      data: { 
                        kode_surat: kode,
                        type: 'surat_keluar'
                      },
                      success: function(response) {
                          console.log('Response:', response);  
                          try {
                              var data = JSON.parse(response);
                              if (data.nomor_surat) {
                                  $('input[name="nomor_surat"]').val(data.nomor_surat);
                                  console.log('Nomor terisi:', data.nomor_surat);  
                              } else {
                                  console.log('Nomor kosong di response');
                              }
                          } catch (e) {
                              console.log('Error parse JSON:', e);
                          }
                      },
                      error: function(xhr, status, error) {
                          console.log('AJAX error:', status, error);
                          alert('Gagal generate nomor surat');
                      }
                  });
              }
          });
      });
    </script>
  </body>
</html>
