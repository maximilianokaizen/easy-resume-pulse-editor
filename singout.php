<?php
include_once 'init.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<script>
sessionStorage.removeItem('jwt');
sessionStorage.removeItem('uuid');
window.location.href = 'index.php';
</script>
<?php include_once 'head.php'; ?>
</head>
<body>
<?php include_once 'header.php'; ?>
  <main id="main">
  <div class="container" data-aos="fade-up">
  </div>
  </main><!-- End #main -->
  <?php include_once 'footer.php'; ?>
  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <?php include_once('footer_js_css.php') ?>
</body>
</html>