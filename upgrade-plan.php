<?php
include_once 'init-zero.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once 'head.php'; ?>
</head>
<body>
<?php include_once 'header-panel.php'; ?>
  <main id="main">
    
  <div class="container" style="margin-top:100px !important">
  <h1>Upgrade your account</h1>

  <p>We will be in touch shortly after the form has been submitted.</p>


<!-- Formulario -->
<form id="contactForm" action="https://easyresumepulse.com/en/contact-plan.php" method="POST" style="width:600px; margin-bottom: 20px">
  <div class="mb-3">
    <label for="firstName" class="form-label">First Name</label>
    <input type="text" class="form-control" id="firstName" name="firstName" required>
  </div>
  <div class="mb-3">
    <label for="lastName" class="form-label">Last Name</label>
    <input type="text" class="form-control" id="lastName" name="lastName" required>
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control" id="email" name="email" required>
  </div>
  <div class="mb-3">
    <label for="plan" class="form-label">Choose a Plan</label>
    <select class="form-select" id="plan" name="plan" required>
      <option value="STANDARD">STANDARD</option>
      <option value="PREMIUM">PREMIUM</option>
    </select>
  </div>
  <div class="mb-3">
    <label for="message" class="form-label">Message (Optional)</label>
    <textarea class="form-control" id="message" name="message" rows="4"></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
<!-- Fin del Formulario -->

<div id="successMessage" style="display: none;">
  <p>We have received your message and will get in touch with you shortly.</p>
</div>
  </div>
     
  </main><!-- End #main -->
  <?php include_once 'footer.php'; ?>
  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <?php include_once('footer_js_css.php') ?>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const successMessage = document.getElementById('successMessage');

    contactForm.addEventListener('submit', function(event) {
      event.preventDefault();
      const formData = new FormData(contactForm);
      fetch('<?=$baseUrl?>/contact-plan.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          contactForm.style.display = 'none';
          successMessage.style.display = 'block';
        }
      })
      .catch(error => console.error('Error:', error));
    });
  });
</script>
</body>
</html>