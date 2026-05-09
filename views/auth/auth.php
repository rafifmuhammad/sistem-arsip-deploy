<?php
  session_start();

  if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: /sistem-arsip/dashboard");
    exit;
  }

  include '../../functions/function.php';

  if (isset($_POST['daftar'])) {
    if (addUser($_POST) > 0) {
      $_SESSION["flash"] = [
        "icon" => "success",
        "title" => "Berhasil",
        "text" => "Berhasil mendaftar"
      ];
    } else {
      $_SESSION["flash"] = [
        "icon" => "success",
        "title" => "Berhasil",
        "text" => "Berhasil mendaftar"
      ];
    }

    header("Location: /sistem-arsip/auth");
    exit;
  }

  $active_tab = 'daftar';

  if (isset($_POST['masuk'])) {
    $active_tab = 'masuk';

    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    $result = query("SELECT * FROM tb_user 
      WHERE username = '$username'"
    );

    if (count($result) === 1 && password_verify($password, $result[0]['password'])) {
        $_SESSION['login'] = true;
        $_SESSION['user'] = [
            'id_user'  => $result[0]['id_user'],
            'username' => $result[0]['username'],
            'email'    => $result[0]['email'] ?? '',
            'nama'     => $result[0]['nama'],
            'role'     => $result[0]['role'],
        ];
        
        header("Location: /sistem-arsip/dashboard/");
        exit;
    } else {
        $_SESSION["flash"] = [
            "icon"  => "error",
            "title" => "Gagal",
            "text"  => "Username atau password salah"
        ];
        
        $active_tab = 'masuk';
    }
  }
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Arsipan - Masuk / Daftar</title>
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

    <!-- App css -->
    <link
      href="/sistem-arsip/assets/css/bootstrap.min.css"
      rel="stylesheet"
      type="text/css"
    />
    <link href="/sistem-arsip/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/sistem-arsip/assets/css/app.min.css" rel="stylesheet" type="text/css" />
  </head>

  <body class="account-body accountbg">
    <!-- Register page -->
    <div class="container">
      <div class="row vh-100 d-flex justify-content-center">
        <div class="col-12 align-self-center">
          <div class="row">
            <div class="col-lg-5 mx-auto">
              <div class="card">
                <div class="card-body p-0 auth-header-box">
                  <div class="text-center p-3">
                    <a href="/sistem-arsip/" class="logo logo-admin">
                      <img
                        src="/sistem-arsip/assets/images/logo-kantor-preview.png"
                        height="140"
                        alt="logo"
                        class="auth-logo"
                      />
                    </a>
                    <h4
                      class="mt-3 mb-1 font-weight-semibold text-white font-18"
                    >
                      Selamat Datang di Sistem Arsip Kepala Desa Sungai Gelampeh
                    </h4>
                    <p class="text-muted mb-0">
                      Masuk untuk melanjutkan
                    </p>
                  </div>
                </div>
                <div class="card-body">
                  <ul class="nav-border nav nav-pills" role="tablist">
                    <li class="nav-item">
                      <a
                        class="nav-link font-weight-semibold <?= ($active_tab === 'masuk') ? 'active' : ''; ?>"
                        data-toggle="tab"
                        href="#LogIn_Tab"
                        role="tab"
                        >Masuk</a
                      >
                    </li>
                    <li class="nav-item">
                      <a
                        class="nav-link font-weight-semibold <?= ($active_tab === 'daftar') ? 'active' : ''; ?>"
                        data-toggle="tab"
                        href="#Register_Tab"
                        role="tab"
                        >Daftar</a
                      >
                    </li>
                  </ul>
                  <!-- Tab panes -->
                  <div class="tab-content">
                    <div
                      class="tab-pane p-3 pt-3 <?= ($active_tab === 'masuk') ? 'active' : ''; ?>"
                      id="LogIn_Tab"
                      role="tabpanel"
                    >
                      <form
                        class="form-horizontal auth-form my-4"
                        action=""
                        method="post"
                      >
                        <div class="form-group">
                          <label for="username">Username</label>
                          <div class="input-group mb-3">
                            <input
                              type="text"
                              class="form-control"
                              name="username"
                              id="username"
                              placeholder="Masukkan username"
                            />
                          </div>
                        </div>
                        <!--end form-group-->

                        <div class="form-group">
                          <label for="userpassword">Password</label>
                          <div class="input-group mb-3">
                            <input
                              type="password"
                              class="form-control"
                              name="password"
                              id="password"
                              placeholder="Masukkan password"
                            />
                          </div>
                        </div>
                        <!--end form-group-->

                        <div class="form-group mb-0 row">
                          <div class="col-12 mt-2">
                            <button
                              class="btn btn-primary btn-block waves-effect waves-light"
                              type="submit"
                              name="masuk"
                            >
                              Masuk <i class="fas fa-sign-in-alt ml-1"></i>
                            </button>
                          </div>
                          <!--end col-->
                        </div>
                        <!--end form-group-->
                      </form>
                      <!--end form-->
                    </div>
                    <div
                      class="tab-pane px-3 pt-3 <?= ($active_tab === 'daftar') ? 'active' : ''; ?>"
                      id="Register_Tab"
                      role="tabpanel"
                    >
                      <form
                        class="form-horizontal auth-form my-4"
                        action=""
                        method="post"
                      >
                        <input type="hidden" name="role" id="role" value="pegawai">
                        <div class="form-group">
                          <label for="username">Username</label>
                          <div class="input-group mb-3">
                            <input
                              type="text"
                              class="form-control"
                              name="username"
                              id="username"
                              placeholder="Masukkan username"
                              required
                            />
                          </div>
                        </div>
                        <!--end form-group-->

                        <div class="form-group">
                          <label for="nama">Nama Lengkap</label>
                          <div class="input-group mb-3">
                            <input
                              type="text"
                              class="form-control"
                              name="nama"
                              id="nama"
                              placeholder="Masukkan nama lengkap"
                              required
                            />
                          </div>
                        </div>
                        <!--end form-group-->

                        <div class="form-group">
                          <label for="password">Password</label>
                          <div class="input-group mb-3">
                            <input
                              type="password"
                              class="form-control"
                              name="password"
                              id="userpassword"
                              placeholder="Masukkan password"
                              required
                            />
                          </div>
                        </div>
                        <!--end form-group-->

                        <div class="form-group mb-0 row">
                          <div class="col-12 mt-2">
                            <button
                              class="btn btn-primary btn-block waves-effect waves-light"
                              type="submit"
                              name="daftar"
                            >
                              Daftar <i class="fas fa-sign-in-alt ml-1"></i>
                            </button>
                          </div>
                          <!--end col-->
                        </div>
                        <!--end form-group-->
                      </form>
                      <!--end form-->
                    </div>
                  </div>
                </div>
                <!--end card-body-->
                <div class="card-body bg-light-alt text-center">
                  <span class="text-muted d-none d-sm-inline-block"
                    >Wanda Lestian © 2020</span
                  >
                </div>
              </div>
              <!--end card-->
            </div>
            <!--end col-->
          </div>
          <!--end row-->
        </div>
        <!--end col-->
      </div>
      <!--end row-->
    </div>
    <!--end container-->
    <!-- End Register page -->

    <!-- jQuery  -->
    <script src="/sistem-arsip/assets/js/jquery.min.js"></script>
    <script src="/sistem-arsip/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/sistem-arsip/assets/js/waves.js"></script>
    <script src="/sistem-arsip/assets/js/feather.min.js"></script>
    <script src="/sistem-arsip/assets/js/simplebar.min.js"></script>

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

    <?php 
      // Hapus flash message setelah ditampilkan
      unset($_SESSION["flash"]); 
    ?>
    </script>
  </body>
</html>
