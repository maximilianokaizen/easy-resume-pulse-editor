<?php
include_once 'init-panel.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once 'head.php'; ?>
<script>
try {

  const token = sessionStorage.getItem('token');
  const uuid = sessionStorage..getItem('uuid');

  if (!token || !uuid) {
    throw new Error('No se encontraron el token o el uuid en el almacenamiento.');
  }

  const url = `<?=$baseUrl?>/api/resumes/listResumes.php?token=${token}&uuid=${uuid}`;

  // Realizar la petición GET
  fetch(url, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json'
    }
  })
  .then(response => response.json())
  .then(data => {
    // Mostrar el resultado en la consola
    console.log(data);
  })
  .catch(error => {
    // Capturar y manejar errores de la petición
    console.error('Error en la petición:', error);
  });
} catch (error) {
  // Capturar y manejar errores generales
  console.error('Error:', error);
}
</script>
</head>
<body>
<?php include_once 'header-panel.php'; ?>
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