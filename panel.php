<?php
include_once 'init-panel.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once 'head.php'; ?>
<script>

const token = sessionStorage.getItem('jwt');
const uuid =  sessionStorage.getItem('uuid');

try {
  
  if (!token || !uuid) {
    const redirectUrl = isSecure ? 'https://easyresumepulse.com/en/signout.php' : 'http://localhost:8080/signout.php';
    window.location.href = redirectUrl;
  }

  const url = '<?=$baseUrl?>/api/resumes/listResumes.php';
  const bodyData = {
    token: token,
    uuid: uuid
  };

  fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(bodyData)
  })
  .then(response => response.json())
  .then(data => {
    if (data.success === true && data.resumes.length === 0){
      const createMyFirstResume = document.getElementById('create-my-first-resume');
      createMyFirstResume.style.display = 'block';
    }
    if (data.success === true && data.resumes.length >= 1){
      console.log(data.resumes);
      const resumeListDiv = document.getElementById('resume-list');
      const ulElement = document.createElement('ul');

      data.resumes.forEach(resume => {
        const resumeUuid  = resume.uuid;
        const liElement = document.createElement('li');
        const link = document.createElement('a');
        link.href = `<?=$baseUrl?>/editor/editor.php?token=${token}&uuid=${resumeUuid}&template=2`;
        link.textContent = `Resume ${resumeUuid}`; 
        liElement.appendChild(link);
        ulElement.appendChild(liElement);
      });

      resumeListDiv.appendChild(ulElement);

      const createMyFirstResume = document.getElementById('resume-list');
      createMyFirstResume.style.display = 'block';
    }
  })
  .catch(error => {
    // Capturar y manejar errores de la petición
    console.error('Error en la petición:', error);
  });
} catch (error) {
  // Capturar y manejar errores generales
  console.error('Error:', error);
}

// create new resume

document.addEventListener('DOMContentLoaded', function() {
  const createResumeBtn = document.getElementById('createResumeBtn');
  createResumeBtn.addEventListener('click', createResume);

  function createResume() {
    const nameInput = document.getElementById('nameInput');
    const name = nameInput.value.trim();
    const templateId = 2;
    if (name.length === 0 || name.length < 3) {
      console.log('Por favor, ingresa un nombre válido (mínimo 3 caracteres).');
      return;
    }

    // Obtener el token y el uuid del Session Storage
    const token = sessionStorage.getItem('jwt');
    const uuid = sessionStorage.getItem('uuid');

    if (!token || !uuid) {
      console.log('No se encontró el token o el uuid en el almacenamiento.');
      return;
    }

    const formData = {
      name: name,
      uuid: uuid,
      token: token,
      templateId : templateId
    };

    fetch('<?=$baseUrl?>/api/resumes/createResume.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
      console.log('Respuesta de la creación del currículum:', data);
    })
    .catch(error => {
      console.error('Error al crear el currículum:', error);
    });
  }
});

</script>
</head>
<body>
<?php include_once 'header-panel.php'; ?>
  <main id="main">
  <div class="container" data-aos="fade-up" style="margin-top:100px;">

  <div id="create-my-first-resume" style="display:none">
    <form id="resumeForm">
      <label for="nameInput">Name:</label>
      <input type="text" id="nameInput" name="name">
      <button type="button" id="createResumeBtn">Create My First Resume!</button>
    </form>
  </div>
   
  <div id="resume-list" style="display:none">
  </div>

  </div>
  </main><!-- End #main -->
  <?php include_once 'footer.php'; ?>
  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <?php include_once('footer_js_css.php') ?>
</body>
</html>