<?php
session_start();

include './functions/function.php';

$id_surat_keluar = $_GET['id_surat_keluar'];

$letter_codes = query("SELECT * FROM tb_kategori");
$letter = query("SELECT sk.id_surat_keluar, sk.id_versi_aktif, 
         sk.perihal_surat, sk.nomor_surat, sk.keterangan_surat, k.kode_kategori,
         k.nama_kategori, sk.id_kategori
  FROM tb_surat_keluar sk
  JOIN tb_kategori k
    ON sk.id_kategori = k.id_kategori
  WHERE sk.id_surat_keluar = '$id_surat_keluar'
")[0];
$letter_versions = query("SELECT * FROM tb_user 
    JOIN tb_surat_keluar_versi 
        ON tb_user.id_user = tb_surat_keluar_versi.id_user
    JOIN tb_surat_keluar 
        ON tb_surat_keluar.id_surat_keluar = tb_surat_keluar_versi.id_surat_keluar
    JOIN tb_kategori 
        ON tb_surat_keluar.id_kategori = tb_kategori.id_kategori
    WHERE tb_surat_keluar.id_surat_keluar = '$id_surat_keluar'
");

// Tambah versi baru
if (isset($_POST['tambah'])) {
  $resultMeta = updateOutgoingLetter($letter['id_surat_keluar'], $_POST);
  $resultVersion = addNewOutgoingLetterVersion(
    $letter['id_surat_keluar'], 
    $_FILES['file'], 
    '69e55e13c41bb'
  );

  if ($resultVersion === -1 && $resultMeta === 0) {
    $_SESSION["flash"] = [
      "icon" => "info",
      "title" => "Tidak ada perubahan",
      "text" => "Tidak ada data yang diubah."
    ];
  } else if ($resultVersion === -1 && $resultMeta > 0) {
    $_SESSION["flash"] = [
      "icon" => "success",
      "title" => "Berhasil",
      "text" => "Metadata berhasil diperbarui."
    ];
  } else if ($resultVersion > 0) {
    $_SESSION["flash"] = [
      "icon" => "success",
      "title" => "Berhasil",
      "text" => "Versi baru berhasil ditambahkan."
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
    <title>Arsipan - Perubahan Surat Keluar Sistem Arsip</title>
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
                  <h4 class="page-title">Perubahan Surat Keluar</h4>
                  <ol class="breadcrumb">
                    <!-- An Unactive Breadcrumb -->
                    <li class="breadcrumb-item">
                      <a href="./letters_outgoing.php">Surat Keluar</a>
                    </li>
                    <li class="breadcrumb-item active">
                      Perubahan Surat Keluar
                    </li>
                  </ol>
                </div>
                <!--end col-->
              </div>
              <!--end row-->

              <div class="card mt-2">
                <div class="card-header">
                  <h4 class="card-title">Riwayat Versi Surat Keluar</h4>
                </div>

                <div class="card-body">
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
                        <th class="text-center">Versi</th>
                        <th class="text-center">Dokumen</th>
                        <th class="text-center">Dibuat Oleh</th>
                        <th class="text-center">Tanggal Dibuat</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Preview</th>
                        <th class="text-center">Terapkan</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($letter_versions as $version) : ?>
                      <tr>
                        <td class="text-center"><?= $version['versi']; ?></td>
                        <td><?= $letter['perihal_surat']; ?></td>
                        <td><?= $version['nama']; ?></td>
                        <td class="text-center"><?= $version['tanggal_keluar']; ?></td>
                        <td class="text-center">
                          <?php if ($version['id_versi'] == $letter['id_versi_aktif']) : ?>
                            <span class="badge badge-outline-success">Aktif</span>
                          <?php else : ?>
                            <span class="badge badge-outline-secondary">Tidak Aktif</span>
                          <?php endif; ?>
                        </td>
                        <td class="text-center">
                          <a 
                            href="#" 
                            class="text-info preview-btn"
                            data-file="<?= $version['file']; ?>"
                            ><i class="mdi mdi-eye"></i> Preview</a
                          >
                        </td>
                        <td class="text-center">
                          <a
                            href="#"
                            class="text-success tombol-terapkan"
                            data-href="./letters_apply_version.php?id_surat_keluar=<?= $letter['id_surat_keluar']; ?>&id_versi=<?= $version['id_versi']; ?>&type=<?= 'surat_keluar'; ?>"
                            >
                            <i class="mdi mdi-eye"></i> 
                              Terapkan
                            </a
                          >
                        </td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                  <!-- Modal live preview file pdf -->
                   <div class="modal fade" id="previewModal" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5>Preview Dokumen</h5>
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                          <iframe id="pdfFrame" width="100%" height="500px"></iframe>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- End modal -->
                </div>
              </div>

              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Perubahan Surat Keluar</h4>
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
                      <option selected="selected" value="<?= $letter['kode_kategori']; ?>"><?= $letter['nama_kategori']; ?></option>
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
                        value="<?= $letter['nomor_surat']; ?>"
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
                        value="<?= $letter['perihal_surat']; ?>"
                      />
                    </div>
                    <div class="form-group">
                      <label for="keterangan">Keterangan Surat</label>
                      <input
                        type="text"
                        class="form-control"
                        name="keterangan_surat"
                        placeholder="Masukkan keterangan surat"
                        value="<?= $letter['keterangan_surat']; ?>"
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

                    <button type="submit" name="tambah" class="btn btn-primary btn-sm">
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
      $("#datatable").DataTable({
        lengthMenu: [5, 10, 15, 20],
      });
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

      $('.tombol-terapkan').on('click', function(e) {
        e.preventDefault();

        const href = $(this).data('href');

        swal({
          title: "Yakin?",
            text: "Versi saat ini akan diubah.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
        if (willDelete) {
              document.location.href = href;
            }
          });
        });

        // tampilkan modal preview
        document.querySelectorAll('.preview-btn').forEach(btn => {
          btn.addEventListener('click', function(e) {
            e.preventDefault();

            const file = this.getAttribute('data-file');
            document.getElementById('pdfFrame').src = file;

            $('#previewModal').modal('show');
          });
        });
    </script>
  </body>
</html>
