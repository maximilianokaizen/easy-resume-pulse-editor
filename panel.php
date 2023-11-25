<?php
include_once 'init-panel.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once 'head.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script>

let selectedTemplateId = null;

// Function to check if token and uuid exist
function checkTokenAndUUID() {
  const token = sessionStorage.getItem('jwt');
  const uuid = sessionStorage.getItem('uuid');

  if (!token || !uuid) {
    const redirectUrl = isSecure ? '<?=$baseUrl?>/signout.php' : '<?=$baseUrl?>/signout.php';
    window.location.href = redirectUrl;
  }

  return { token, uuid };
}

// Function to get resumes
function getResumes(token, uuid) {
  const url = '<?=$baseUrl?>/api/resumes/listResumes.php';
  const bodyData = { token, uuid };

  return fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(bodyData)
  })
  .then(response => response.json());
}

// Function to create a new resume
function createResume() {
  const nameInput = document.getElementById('nameInput');
  const name = nameInput.value.trim();
  const templateId = selectedTemplateId || 2; 
  const token = sessionStorage.getItem('jwt');
  const uuid = sessionStorage.getItem('uuid');

  if (!token || !uuid) {
    console.log('Token or UUID not found in storage.');
    return;
  }

  if (name.length === 0 || name.length < 3) {
    console.log('Please enter a valid name (minimum 3 characters).');
    return;
  }

  const formData = {
    name,
    uuid,
    token,
    templateId
  };

  return fetch('<?=$baseUrl?>/api/resumes/createResume.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(formData)
  })
  .then(response => response.json());
}

function deleteResume() {
  const deleteResumeLinks = document.querySelectorAll('.resume-list-delete-link');
  deleteResumeLinks.forEach(deleteLink => {
    deleteLink.addEventListener('click', function(event) {
      event.preventDefault();
      const deleteResumeModal = new bootstrap.Modal(document.getElementById('deleteResumeModal'));
      deleteResumeModal.show();
      const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
      confirmDeleteBtn.addEventListener('click', function() {
        const token = sessionStorage.getItem('jwt');
        const uuid = sessionStorage.getItem('uuid');
        if (!token || !uuid) {
          console.log('Token or UUID not found in storage.');
          return;
        }
        const resumeUuid = deleteLink.dataset.uuid; // Assuming you set the data-uuid attribute on the delete link
        const deleteUrl = '<?=$baseUrl?>/api/resumes/deleteResume.php';
        const formData = {
          token: token,
          uuid: uuid,
          resumeUuid: resumeUuid // Sending the resume UUID to delete
        };
        fetch(deleteUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(formData)
        })
        .then(response => {
          if (response.status === 200) {
            location.reload(); // Reload the page
          } else {
            console.error('Failed to delete the resume. Status:', response.status);
          }
        })
        .catch(error => {
          console.error('Error deleting the resume:', error);
        });
      });
    });
  });
}


// Check token and UUID
const { token, uuid } = checkTokenAndUUID();

// Get resumes
getResumes(token, uuid)
  .then(data => {
    if (data.success === true && data.resumes.length === 0) {
      const createMyFirstResume = document.getElementById('create-my-first-resume');
      createMyFirstResume.style.display = 'block';
    }
    if (data.success === true && data.resumes.length >= 1) {
      const resumeListDiv = document.getElementById('resume-list');
      const ulElement = document.createElement('ul');

      data.resumes.forEach(resume => {
        const resumeUuid = resume.uuid;
        const resumeName = resume.name;
        const liElement = document.createElement('li');
        const link = document.createElement('a');
        const deleteLink = document.createElement('a');
        deleteLink.href = '<?=$baseUrl?>/api/resumes/deleteResume.php';
        deleteLink.innerHTML = '<i class="fa fa-trash"></i>Delete this resume' ;
        deleteLink.classList.add('resume-list-delete-link');
        deleteLink.dataset.uuid = resumeUuid;
        link.href = '<?=$baseUrl?>/editor/editor.php?token=' + token + '&uuid=' + resumeUuid + '&template=' + resume.template;
        link.textContent = resumeName;

        liElement.appendChild(link);
        liElement.appendChild(deleteLink);
        ulElement.appendChild(liElement);
      });

      resumeListDiv.appendChild(ulElement);

      const createMyFirstResume = document.getElementById('resume-list');
      createMyFirstResume.style.display = 'block';

      deleteResume(); // Call function to handle delete resume action
    }
    // Check if canCreate is true and resumes length is greater than 1
    if (data.resumes.length >= 1 && data.resumes.length <= 8) {
      const createOtherResume = document.getElementById('create-other-resume');
      createOtherResume.style.display = 'block';

      const createOtherResumeBtn = document.getElementById('createOtherResumeBtn');
      createOtherResumeBtn.addEventListener('click', function() {
        const OtherNameInput = document.getElementById('OtherNameInput');
        const name = OtherNameInput.value.trim();
        // fetch
        let templateId = selectedTemplateId || 2; // Si no hay uno seleccionado, usa 2 como predeterminado
        const formData = {
          name,
          uuid,
          token,
          templateId
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
          if (data.success === true){
            location.reload();
          }
          console.log('Server response:', data); // Log the data received from the server
          return data; // Return the data for further handling if needed
        });
      });
    }
  })
  .catch(error => {
    console.error('Error in request:', error);
  });

// Create new resume
document.addEventListener('DOMContentLoaded', function() {
  createResumeBtn.addEventListener('click', function() {
    createResume()
      .then(data => {
        location.reload();
      })
      .catch(error => {
        console.error('Error creating resume:', error);
      });
  });
});

document.addEventListener('DOMContentLoaded', function() {
  let { token, uuid } = checkTokenAndUUID();
  const baseUrl = '<?=$baseUrl?>'; 
  const url = `<?=$baseUrl?>/api/templates/listTemplates.php?token=${token}`;
  return fetch(url)
    .then(response => response.json())
    .then(data => {
      let templatesListContainer = document.getElementById('templates-list');
      templatesListContainer.innerHTML = ''; 
      data.template.forEach(template => {
        const templateElement = createTemplateElement(template);
        templateElement.addEventListener('click', function() {
          console.log(template.id);
          setSelectedTemplateId(template.id);
          createResume();
        });
        templatesListContainer.appendChild(templateElement);
      });
    })
    .catch(error => {
      console.error('Error fetching templates:', error);
      throw error; 
    });
});


// RENDER TEMPLATES

function createTemplateElement(template) {
  const templateLi = document.createElement('li');
  templateLi.classList.add('template-list-element', 'd-inline-block', 'mr-5'); // Añade clases personalizadas

  const templateDiv = document.createElement('div');
  templateDiv.id = 'header-template-list'; // ID para el contenedor del nombre y el checkbox

  const checkboxContainer = document.createElement('div');
  checkboxContainer.classList.add('form-check', 'd-flex', 'align-items-center'); // Flexbox para alinear checkbox y nombre

  const templateCheckbox = document.createElement('input');
  templateCheckbox.type = 'checkbox';
  templateCheckbox.value = template.templateId; 
  templateCheckbox.classList.add('form-check-input', 'me-3'); // Estilo para el checkbox

  const templateName = document.createElement('p');
  templateName.textContent = template.name;
  templateName.classList.add('mb-0', 'me-3'); // Estilo para el nombre

  // Hacer clic en el nombre selecciona/deselecciona el checkbox
  templateName.addEventListener('click', function () {
    templateCheckbox.checked = !templateCheckbox.checked;

    if (templateCheckbox.checked) {
      sessionStorage.setItem('selectedTemplateId', templateCheckbox.value);
    } else {
      sessionStorage.removeItem('selectedTemplateId');
    }
  });

  checkboxContainer.appendChild(templateCheckbox);
  checkboxContainer.appendChild(templateName);

  const templateContent = document.createElement('div');
  templateContent.classList.add('d-flex', 'flex-column');

  templateContent.appendChild(checkboxContainer);

  const templateImage = document.createElement('img');

  templateImage.src = `<?=$baseUrl?>/themes/${template.id}.png`;
  templateImage.alt = template.name; 
  templateImage.classList.add('img-fluid', 'template-image');
  //templateImage.style.width = '450px'; 
  templateImage.style.height = '450px'; 
  templateImage.addEventListener('click', function () {
    const allCheckboxes = document.querySelectorAll('.form-check-input');
    allCheckboxes.forEach(checkbox => {
      checkbox.checked = false;
    });
    templateCheckbox.checked = true;
    selectedTemplateId = templateCheckbox.value; 
    sessionStorage.setItem('selectedTemplateId', selectedTemplateId);
  });
  templateCheckbox.addEventListener('click', function () {
    const allCheckboxes = document.querySelectorAll('.form-check-input');
    allCheckboxes.forEach(checkbox => {
      if (checkbox !== templateCheckbox) {
        checkbox.checked = false;
      }
    });
    if (templateCheckbox.checked) {
      selectedTemplateId = templateCheckbox.value; // Almacenar el templateId seleccionado
      sessionStorage.setItem('selectedTemplateId', selectedTemplateId);
    } else {
      selectedTemplateId = null;
      sessionStorage.removeItem('selectedTemplateId');
    }
  });
  templateContent.appendChild(templateImage);
  templateDiv.appendChild(templateContent);
  templateLi.appendChild(templateDiv);
  return templateLi;
}

function setSelectedTemplateId(templateId) {
  selectedTemplateId = templateId;
}

</script>
</head>
<body>
<?php include_once 'header-panel.php'; ?>
  <main id="main">
  <div class="container" data-aos="fade-up" style="margin-top:100px;min-height:550px">

  <div id="create-my-first-resume" style="display:none">
    <form id="resumeForm">
      <label for="nameInput">Name:</label>
      <input type="text" id="nameInput" name="name" required>
      <button type="button" id="createResumeBtn" class="green-button">Create My First Resume!</button>
    </form>
  </div>
   
  <div id="resume-list" style="display:none">
   <h4>My Created Resumes</h4>
  </div>

  <div id="create-other-resume" style="display:none">
    <form id="otherResumeForm">
      <label for="OtherNameInput">Name:</label>
      <input type="text" id="OtherNameInput" name="other-name" required>
      <button type="button" id="createOtherResumeBtn" class="green-button">Create Other Resume!</button>
    </form>
  </div>

  <div id="templates-list"></div>
  
  <div id="alertContainer"></div>

  </div>
  </main><!-- End #main -->
  <?php include_once 'footer.php'; ?>
  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <?php include_once('footer_js_css.php') ?>

  <!-- modal delete -->
  <div class="modal fade" id="deleteResumeModal" tabindex="-1" aria-labelledby="deleteResumeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteResumeModalLabel">Confirm</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      Are you certain you wish to delete this resume? Please note that deleting this resume will permanently remove all its content, and this action cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</button>
      </div>
    </div>
  </div>
</div>
<!-- end of modal -->
</body>
</html>