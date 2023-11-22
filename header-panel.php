 <!-- ======= Header ======= -->
 <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center justify-content-between">

      <h1 class="logo"><a href="<?=$baseUrl?>">EasyResumePulse<span class="green-color">.com</span></a></h1>
      <!-- Uncomment below if you prefer to use an image logo -->
      <!-- <a href="index.html" class="logo"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto active" href="<?=$baseUrl?>">Home</a></li>
          <li><a class="nav-link scrollto" href="#pricing">Pricing</a></li>
          </li>
          <li><a class="nav-link scrollto" href="#contact">Feedback / Contact</a></li>
          <li><a class="getstarted scrollto btn-get-green" href="<?=$baseUrl?>/signin.php">Create Resume Now</a></li>
          <li><a class="getstarted scrollto show-ui" href="#" id="signOut">Sign Out</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
</header><!-- End Header -->
<script>
    document.getElementById('signOut').addEventListener('click', function(event) {
    event.preventDefault();
    sessionStorage.removeItem('jwt');
    sessionStorage.removeItem('uuid');
    window.location.href = 'index.php';
});
</script>