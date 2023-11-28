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
  <div class="container" data-aos="fade-up">
    <div style="margin-top:100px">
    <h1>Data Deletion Request</h1>

<form action="mailto:deletemydata@easyresumepulse.com" method="post" enctype="text/plain">
  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required>
  
  <label for="message">Message:</label>
  <textarea id="message" name="message" rows="4" required>
    Please delete all my data associated with my account.
  </textarea>
  
  <button type="submit">Send Request</button>
</form>
    </div>
  </div>
  </main><!-- End #main -->
  <?php include_once 'footer.php'; ?>
  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <?php include_once('footer_js_css.php') ?>
</body>
</html>