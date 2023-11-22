<?php
include_once 'init.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<script>
function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    let regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    let results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}
let jwtToken = getUrlParameter('token');
let userUuid = getUrlParameter('uuid');
const isSecure = window.location.protocol === 'https:';
if (jwtToken && userUuid) {
    sessionStorage.setItem('jwt', jwtToken);
    sessionStorage.setItem('uuid', userUuid);
    const redirectUrl = isSecure ? 'https://easyresume.com/en/user-panel.php' : 'http://localhost:8080/user-panel.php';
    window.location.href = redirectUrl;
} else {
    const redirectUrl = isSecure ? 'https://easyresume.com/en/?err=1&code=003' : 'http://localhost:8080/index.php?err=1&code=003';
    window.location.href = redirectUrl;
}
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