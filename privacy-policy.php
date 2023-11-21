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
  <main id="main">
    <section class="inner-page page-content">
      <div class="container">
       <div class="content-center">
            <h3>Login or Register</h3>
        <!-- login or register -->
        <section id="login" class="login">
  <div class="container" data-aos="fade-up">

    <div class="section-title">
      
    <h1>Privacy Policy</h1>

<p>This Privacy Policy describes how [Name of the Application] ("we," "our," or "the application") collects, uses, and shares personal information from users ("you" or "user") who use our web application and related services.</p>

<h2>Information We Collect</h2>

<ul>
  <li><strong>Personal Information:</strong> We may collect personal information voluntarily provided by you when using our application, such as name, email address, contact information, and profile details.</li>
  <li><strong>Usage Information:</strong> We may automatically collect information about how you interact with our application, including data about your device, IP address, browser type, pages visited, visit duration, and usage patterns.</li>
</ul>

<h2>Use of Information</h2>

<p><strong>Enhancing User Experience:</strong> We use the collected information to provide and improve our services, customize your experience, and respond to your inquiries or requests.</p>
<p><strong>Communication:</strong> We may use your contact information to send you updates, newsletters, or important notices about our services, but you can opt out of these communications at any time.</p>

<h2>Sharing of Information</h2>

<p>[Insert information about how and with whom the collected data is shared, if applicable.]</p>

<p>This is a general overview of our privacy practices. For more detailed information, please review our complete Privacy Policy.</p>
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
          <a href="login-with-facebook.php" class="btn btn-facebook">Login with Google</a>
          <a href="login-with-facebook.php" class="btn btn-facebook">Login with Facebooke</a>
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