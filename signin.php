<?php
include_once 'init.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once 'head.php'; ?>
</head>
<body>
<?php include_once 'header.php'; ?>

<script>
    function onSuccess(googleUser) {
      console.log('Logged in as: ' + googleUser.getBasicProfile().getName());
    }
    function onFailure(error) {
      console.log(error);
    }
    function renderButton() {
      gapi.signin2.render('my-signin2', {
        'scope': 'profile email',
        'width': 240,
        'height': 50,
        'longtitle': true,
        'theme': 'dark',
        'onsuccess': onSuccess,
        'onfailure': onFailure
      });
    }
  </script>

  <script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>

  <main id="main">
    <section class="inner-page page-content">
      <div class="container">
       <div class="content-center">
            <h3>Login or Register</h3>
        <!-- login or register -->
        <section id="login" class="login">
  <div class="container" data-aos="fade-up">

    <div class="section-title">
      <h2>Login</h2>
      <p>Enter your credentials to access your account.</p>
    </div>

    <div class="row mt-5">
      <div class="col-lg-6 offset-lg-3">

        <form action="login.php" method="post" role="form" class="php-login-form">
          <div class="row gy-2">
            <div class="col-12 form-group">
              <input type="email" name="email" class="form-control" id="email" placeholder="Your Email" required>
            </div>
            <div class="col-12 form-group">
              <input type="password" class="form-control" name="password" id="password" placeholder="Your Password" required>
            </div>
            <div class="col-12">
              <button type="submit" class="btn btn-primary">Login</button>
            </div>
          </div>
        </form>
        <div class="separator mt-4 mb-3 text-center">OR</div>
        <!-- Botón para iniciar sesión con Facebook -->
        <div class="text-center">
         <!-- google -->
         <div id="my-signin2"></div>
         <!-- end of google -->
        </div>
      </div>
    </div>

  </div>
</section><!-- End Login Section -->

        <!-- end of login or register -->
       </div>
      </div>
    </section>
  </main><!-- End #main -->
  <?php include_once 'footer.php'; ?>
  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <?php include_once('footer_js_css.php') ?>
</body>
</html>