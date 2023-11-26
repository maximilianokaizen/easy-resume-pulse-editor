 <!-- ======= Footer ======= -->
 <footer id="footer">

<div class="footer-top">
  <div class="container">
    <div class="row">

      <div class="col-lg-3 col-md-6 footer-contact">
        <h3>EasyResumePulse</h3>
        <p>
          <strong>Email:</strong> hello@easyresumepulse.com<br>
        </p>
      </div>

      <div class="col-lg-2 col-md-6 footer-links">
        <h4>Useful Links</h4>
        <ul>
          <li><i class="bx bx-chevron-right"></i> <a href="#">Home</a></li>
        </ul>
      </div>

      <div class="col-lg-4 col-md-6 footer-newsletter">
        <h4>Connect with Us</h4>
        <p>Are you a template designer? Interested in investing or becoming a part of our service? Let's collaborate!</p>
        <form id="emailForm" action="" method="post">
          <input type="email" name="email-footer" id="email-footer" placeholder="Enter your email">
          <input type="submit" value="Let's Go!" id="send-email-footer">
      </form>
      <div id="thank-you-message" style="display: none;">Thanks for contacting us!</div>
      </div>
    </div>
  </div>
</div>

<div class="container d-md-flex py-4">

  <div class="me-md-auto text-center text-md-start">
    <div class="copyright">
      &copy; Copyright <strong><span>EasyResumePulse</span></strong>. All Rights Reserved
    </div>
  </div>
  <div class="social-links text-center text-md-right pt-3 pt-md-0">
    <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
    <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
  </div>
</div>
</footer><!-- End Footer -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('emailForm');
  const sendButton = document.getElementById('send-email-footer');
  const emailInput = document.getElementById('email-footer');
  const thankYouMessage = document.getElementById('thank-you-message');

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const email = emailInput.value;

    // Objeto a enviar en formato JSON
    const data = {
      email: email
    };

    fetch('<?=$baseUrl?>/contact-footer.php', {
       method: 'POST',
       body: JSON.stringify(data),
       headers: {
         'Content-Type': 'application/json'
       }
     })
     .then(response => response.json())
     .then(result => {
       console.log('result =>', result);
       if (result.success === true) {
         form.style.display = 'none';
         thankYouMessage.style.display = 'block';
       }
     })
    .catch(error => console.error('Error:', error));
  });
});
</script>