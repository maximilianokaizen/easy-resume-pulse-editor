 <!-- ======= Header ======= -->
 <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center justify-content-between">

      <h1 class="logo"><a href="<?=$baseUrl?>">
      <!--<img src="<?=$baseUrl?>/assets/logo-small.png" class="logo-header"  alt=""/>-->
      <i class="lni lni-pencil-alt no-mobile"></i>  EasyResumePulse<span class="green-color">.com</span></a></h1>
      <!-- Uncomment below if you prefer to use an image logo -->
      <!-- <a href="index.html" class="logo"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->

      <nav id="navbar" class="navbar">
        <ul>
        <!--<li><a class="nav-link scrollto active" href="<?=$baseUrl?>/upload-image.php"><i class="fas fa-camera"></i></i> &nbsp; Upload your photo </a></li>-->
          <li><a class="nav-link scrollto active" href="<?=$baseUrl?>/upgrade-plan.php"> <i class="fas fa-star"></i> &nbsp; Upgrade your account </a></li>
          <li><a class="nav-link scrollto active" href="<?=$baseUrl?>/panel.php"> <i class="lni lni-home"></i> &nbsp; Home </a></li>
          <li><a class="getstarted scrollto show-ui" href="#" id="signOut"> <i class="fas fa-door-open"></i>
            &nbsp;
             Logout
        </a></li>
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