<?php
session_start();

include './functions/function.php';

$categories = query("SELECT * FROM tb_kategori");

if (isset($_POST['tambah'])) {
  if (addCategory($_POST) > 0) {
    $_SESSION["flash"] = [
      "icon" => "success",
      "title" => "Berhasil",
      "text" => "Kategori berhasil ditambahkan."
    ];
  } else {
    $_SESSION["flash"] = [
      "icon" => "error",
      "title" => "Gagal",
      "text" => "Kategori gagal ditambahkan."
    ];
  }

  header("Location: categories.php");
  exit;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Arsipan - Kategori Sistem Arsip</title>
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
                  <h4 class="page-title">Kategori</h4>
                  <ol class="breadcrumb">
                    <!-- An Unactive Breadcrumb -->
                    <!-- <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Dastyle</a>
                      </li> -->
                    <li class="breadcrumb-item active">Kategori</li>
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

        <!-- Tabel Manajemen Pengguna -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Tabel Kategori</h4>
              </div>
              <!--end card-header-->

              <div class="card-body">
                <!-- Button -->
                <div>
                  <button
                    type="button"
                    class="btn btn-outline-primary btn-sm mb-2"
                    data-toggle="modal"
                    data-target="#dataModal"
                  >
                    <i class="mdi mdi-plus-circle"></i> Tambah
                  </button>

                  <!-- Modal -->
                  <div
                    class="modal fade"
                    id="dataModal"
                    tabindex="-1"
                    role="dialog"
                    aria-labelledby="dataModalLabel"
                    aria-hidden="true"
                  >
                    <div
                      class="modal-dialog modal-dialog-centered"
                      role="document"
                    >
                      <div class="modal-content">
                        <div class="modal-header">
                          <h6 class="modal-title m-0" id="dataModalLabel">
                            Tambah Kategori
                          </h6>
                          <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                          >
                            <span aria-hidden="true"
                              ><i class="la la-times"></i
                            ></span>
                          </button>
                        </div>
                        <!--end modal-header-->
                        <form action="" method="post">
                          <div class="modal-body">
                            <div class="form-group">
                              <label for="kode">Kode Kategori</label>
                              <input
                                type="text"
                                class="form-control"
                                id="kode_kategori"
                                name="kode_kategori"
                                placeholder="Masukkan kode kategori (Contoh: ADM)"
                                required
                              />
                            </div>
                            <div class="form-group">
                              <label for="nama">Nama Kategori</label>
                              <input
                                type="text"
                                class="form-control"
                                id="nama_kategori"
                                name="nama_kategori"
                                placeholder="Masukkan nama kategori"
                                required
                              />
                            </div>
                          </div>
                          <!--end modal-body-->
                          <div class="modal-footer">
                            <button
                              type="button"
                              class="btn btn-secondary btn-sm"
                              data-dismiss="modal"
                            >
                              Tutup
                            </button>
                            <button
                              type="submit"
                              name="tambah"
                              class="btn btn-primary btn-sm"
                            >
                              Tambah
                            </button>
                          </div>
                        </form>
                        <!--end modal-footer-->
                      </div>
                      <!--end modal-content-->
                    </div>
                    <!--end modal-dialog-->
                  </div>
                  <!--end modal-->
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
                      <th class="text-center">No.</th>
                      <th class="text-center">ID Kategori</th>
                      <th class="text-center">Kode Kategori</th>
                      <th class="text-center">Nama Kategori</th>
                      <th class="text-center">Hapus</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $i = 1; foreach ($categories as $category) : ?>
                    <tr>
                      <td class="text-center"><?= $i; ?></td>
                      <td><?= $category['id_kategori']; ?></td>
                      <td class="text-center"><?= $category['kode_kategori']; ?></td>
                      <td><?= $category['nama_kategori']; ?></td>
                      <td class="text-center">
                        <a href="#" class="btn btn-danger btn-sm tombol-hapus" data-href="categories_delete.php?id_kategori=<?= $category['id_kategori']; ?>"
                          ><i class="mdi mdi-delete"></i
                        ></a>
                      </td>
                    </tr>
                    <?php $i++; endforeach; ?>
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
      <?php endif; ?>

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
            text: "Data pengguna akan dihapus.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
        if (willDelete) {
              document.location.href = href;
            }
          });
        });
    </script>
  </body>
</html>
