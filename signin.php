<?php
include_once 'init.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once 'head.php'; ?>
</head>
<body>
<script src="https://accounts.google.com/gsi/client" async defer></script>
<div id="g_id_onload"
data-client_id="223124831209-p04fqq68spt1pm60j69drbrcgknjsnl5.apps.googleusercontent.com"
        data-ux_mode="redirect"
        data-login_uri="https://easyresumepulse.com/en/login/google.php">
</div>
<div class="g_id_signin" data-type="standard"></div>

<?php include_once 'header.php'; ?>
  <main id="main">
    <section class="inner-page page-content">
      <div class="container">
       <div class="content-center">
        <!-- login or register -->
        <section id="login" class="login" style="padding 40 0px">
  <div class="container" data-aos="fade-up">

    <div class="section-title">
      <h2>Login / Register</h2>
      <p>Enter your credentials to access your account.</p>
    </div>

    <div class="row mt-5">
      <div class="col-lg-6 offset-lg-3">
        <form action="user-login.php" method="post" role="form" class="php-login-form">
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
        <div class="separator mt-4 mb-3 text-center"></div>
        <hr/>
        <!-- Botón para iniciar sesión con Facebook -->

        <div id="fb-root"></div>
        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v18.0&appId=870799537749809" nonce="sSmWdNn8"></script>
        <div class="fb-login-button" data-width="100" data-size="" data-button-type="" data-layout="" data-auto-logout-link="true" data-use-continue-as="false"></div>

        <div class="text-center">
        <h5>Login / Register with social media</h5>
        <h6><b>Hey there!</b> Using your social media account for both registration and login is not only faster but also highly recommended for a smoother experience. Feel free to log in or sign up effortlessly using your preferred social platform!</h6>
         <!-- google -->
         <?php
          if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
          } else {
            echo '<a href="login/google.php">
            <img src="assets/img/login-google.jpeg" alt="" />
            </a>';
          }
        ?>
         <!-- end of google -->
         <!-- facebook -->

         <p id="profile"></p>

      <script>
        <!-- Add the Facebook SDK for Javascript -->
        (function(d, s, id){
                              var js, fjs = d.getElementsByTagName(s)[0];
                              if (d.getElementById(id)) {return;}
                              js = d.createElement(s); js.id = id;
                              js.src = "https://connect.facebook.net/en_US/sdk.js";
                              fjs.parentNode.insertBefore(js, fjs);
                            }(document, 'script', 'facebook-jssdk')
        );
        window.fbAsyncInit = function() {
            FB.init({
                      appId            : '870799537749809',
                      xfbml            : true,
                      version          : 'v18.0'
                    });
            };
      </script>

         <hr/>
         <h5>Create your account using your email.</h5>
         <form action="create-new-user.php" method="post" role="form" class="php-login-form" id="create-new-user-form">
            <div class="row gy-2">
              <div class="col-12 form-group">
                <input type="email" name="newUserEmail" class="form-control" id="newUseremail" placeholder="Your Email" required>
                <label id="email-error" style="display: none; color: red;"></label>
              </div>
              <div class="col-6 form-group">
                <input type="text" class="form-control" name="name" id="name" placeholder="Your Name" required>
              </div>
              <div class="col-6 form-group">
                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Your Last Name" required>
              </div>
              <div class="col-12 form-group">
                <input type="password" class="form-control" name="newUserpassword" id="newUserpassword" placeholder="Your Password" required>
              </div>
              <div class="col-12 form-group">
                <input type="password" class="form-control" name="newUserpasswordTwo" id="newUserpasswordTwo" placeholder="Repeat your Password" required>
                <label id="password-error" style="display: none; color: red;"></label>
              </div>
              <div class="col-12">
                <button type="button" class="btn btn-primary" id="register-with-email">Create my account now!</button>
              </div>
            </div>
          </form>
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

<script>

// LOGIN


document.addEventListener("DOMContentLoaded", function() {
    document.querySelector(".php-login-form").addEventListener("submit", function(event) {
      event.preventDefault(); 
      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return;
      }
      if (email.trim() === '' || password.trim() === '') {
        alert("Email and password are required.");
        return;
      }
      const payload = {
        email: email,
        password: password
      };
      fetch('<?=$baseUrl?>/user-login.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
      })
      .then(response => response.json())
      .then(data => {
        if (data.code === '001' && data.url !== undefined){
          window.location.href = data.url;
        }
      })
      .catch(error => {
        console.error('Error:', error);
        // Handle errors, display a message, etc.
      });
    });
});

// REGISTER
document.addEventListener('DOMContentLoaded', function() {
  const registerBtn = document.getElementById('register-with-email');
  const emailInput = document.getElementById('newUseremail');
  const passwordInput = document.getElementById('newUserpassword');
  const confirmPasswordInput = document.getElementById('newUserpasswordTwo');
  const nameInput = document.getElementById('name');
  const lastNameInput = document.getElementById('last_name');
  const emailError = document.getElementById('email-error');
  const passwordError = document.getElementById('password-error');

  registerBtn.addEventListener('click', function(event) {
    event.preventDefault();
    emailError.style.display = 'none';
    passwordError.style.display = 'none';

    const email = emailInput.value.trim();
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;
    const name = nameInput.value.trim();
    const lastName = lastNameInput.value.trim();

    if (!email || !password || !confirmPassword || !name || !lastName) {
      emailError.innerText = 'Please fill in all fields.';
      emailError.style.display = 'block';
      return;
    }

    if (password !== confirmPassword) {
      passwordError.innerText = 'Passwords do not match.';
      passwordError.style.display = 'block';
      return;
    }

    if (!isValidEmail(email)) {
      emailError.innerText = 'Please enter a valid email address.';
      emailError.style.display = 'block';
      return;
    }

    const userData = {
      email: email,
      pwd: password,
      name: name,
      last_name: lastName
    };

    fetch('<?=$baseUrl?>/new-user.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(userData)
    })
    .then(response => response.json())
    .then(data => {
      // Handle the server response if needed
      if (data.code === '001' && data.url !== undefined){
        window.location.href = data.url;
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
  });

  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }
});
</script>
</body>
</html>