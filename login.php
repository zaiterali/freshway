<!DOCTYPE html>

<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Freshway | ADMIN</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap" rel="stylesheet" />

  <link rel="stylesheet" href="assets/vendor/fonts/materialdesignicons.css" />

  <!-- Menu waves for no-customizer fix -->
  <link rel="stylesheet" href="assets/vendor/libs/node-waves/node-waves.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

  <!-- Page CSS -->
  <!-- Page -->
  <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />

  <!-- Helpers -->
  <script src="assets/vendor/js/helpers.js"></script>
  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="assets/js/config.js"></script>
</head>

<body>
  <!-- Content -->

  <div class="position-relative">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner py-4">
        <!-- Login -->
        <div class="card p-2">
          <!-- Logo -->
          <div class="app-brand justify-content-center mt-5">
            <a href="index.html" class="app-brand-link gap-2">
              <span class="app-brand-logo demo">

                <img src="assets/logonew.png" style="width: 6rem;" alt="logo" srcset="">

              </span>
              <span class="app-brand-text demo text-heading fw-semibold">FRESHWAY</span>
            </a>
          </div>
          <!-- /Logo -->

          <div class="card-body mt-2">
            <h4 class="mb-2">Welcome to Freshway Admin Panel! 👋</h4>
            <p class="mb-4">Please sign-in to your account</p>
            <?php if (isset($_GET['error'])) { ?>
              <p class="error text-danger"><?php echo $_GET['error']; ?></p>
            <?php } ?>

            <form id="formAuthentication" class="mb-3" action="config/login1.php" method="POST">
              <div class="form-floating form-floating-outline mb-3">
                <input type="text" class="form-control" id="email" name="username" placeholder="Enter your email or username" autofocus />
                <label for="email">Username</label>
                <?php if (isset($_GET['erroruser'])) { ?>
                  <p class="error"><?php echo $_GET['erroruser']; ?></p>
                <?php } ?>
              </div>
              <div class="mb-3">
                <div class="form-password-toggle">
                  <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                      <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                      <label for="password">Password</label>
                      <?php if (isset($_GET['errorpass'])) { ?>
                        <p class="error"><?php echo $_GET['errorpass']; ?></p>
                      <?php } ?>
                    </div>
                    <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" type="submit">Log in</button>
              </div>
            </form>

            <!-- <p class="text-center">
              <span>New on our platform?</span>
              <a href="auth-register-basic.html">
                <span>Create an account</span>
              </a>
            </p> -->
          </div>
        </div>
        <!-- /Login -->
        <img src="assets/img/illustrations/tree-3.png" alt="auth-tree" class="authentication-image-object-left d-none d-lg-block" />
        <img src="assets/img/illustrations/auth-basic-mask-light.png" class="authentication-image d-none d-lg-block" alt="triangle-bg" data-app-light-img="illustrations/auth-basic-mask-light.png" data-app-dark-img="illustrations/auth-basic-mask-dark.png" />
        <img src="assets/img/illustrations/tree.png" alt="auth-tree" class="authentication-image-object-right d-none d-lg-block" />
      </div>
    </div>
  </div>

  <!-- / Content -->



  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  <script src="assets/vendor/libs/jquery/jquery.js"></script>
  <script src="assets/vendor/libs/popper/popper.js"></script>
  <script src="assets/vendor/js/bootstrap.js"></script>
  <script src="assets/vendor/libs/node-waves/node-waves.js"></script>
  <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="assets/vendor/js/menu.js"></script>

  <!-- endbuild -->

  <!-- Vendors JS -->

  <!-- Main JS -->
  <script src="assets/js/main.js"></script>

  <!-- Page JS -->

  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>