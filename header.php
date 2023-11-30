
 <!-- ======= Header ======= -->
 <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center justify-content-between">

      <h1 class="logo"><a href="<?=$baseUrl?>">
      <!--<img src="<?=$baseUrl?>/assets/logo-small.png" alt="" class="logo-header" />-->
      <i class="lni lni-pencil-alt"></i>  EasyResumePulse<span class="green-color">.com</span></a></h1>
      <!-- Uncomment below if you prefer to use an image logo -->
      <!-- <a href="index.html" class="logo"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto active" href="<?=$baseUrl?>"><i class="lni lni-home"></i> &nbsp; Home</a></li>
          <li id="pricingLink" style="display: none;"><a class="nav-link scrollto" href="#pricing">Pricing</a></li>
          </li>
          <li><a class="getstarted scrollto btn-get-green" href="<?=$baseUrl?>/signin.php">
            <i class="lni lni-plus"></i> &nbsp; Create Resume Now
        </a></li>
          <li><a class="getstarted scrollto show-ui" href="<?=$baseUrl?>/signin.php">
            <i class="lni lni-user"></i> &nbsp; Sign in / Register
        </a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
</header><!-- End Header -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Verifica si el elemento con el ID 'pricing' existe
    if (document.getElementById('pricing')) {
      // Si existe, muestra el enlace en la barra de navegación
      document.getElementById('pricingLink').style.display = 'block';
    }
  });
</script>