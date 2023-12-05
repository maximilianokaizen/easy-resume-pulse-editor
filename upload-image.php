<?php
include_once 'init-panel.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once 'head.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script>
function checkTokenAndUUID() {
  const token = sessionStorage.getItem('jwt');
  const uuid = sessionStorage.getItem('uuid');
  if (!token || !uuid) {
    const redirectUrl = isSecure ? '<?=$baseUrl?>/signout.php' : '<?=$baseUrl?>/signout.php';
    window.location.href = redirectUrl;
  }
  return { token, uuid };
}
const { token, uuid } = checkTokenAndUUID();
function convertImageToBase64(file) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => resolve(reader.result);
    reader.onerror = error => reject(error);
  });
}
function validateImage() {
      const fileInput = document.getElementById('image');
      const fileSize = fileInput.files[0].size; // Size in bytes
      const allowedFormats = ['image/jpeg', 'image/png', 'image/gif']; // Add more formats if needed

      if (fileSize > 3 * 1024 * 1024) { // 3MB in bytes
        alert('Please select an image file smaller than 3MB.');
        return false;
      }

      if (!allowedFormats.includes(fileInput.files[0].type)) {
        alert('Please select a valid image file format (JPEG, PNG, GIF).');
        return false;
      }

      return true;
    }

    function uploadImage() {
   
    var fileInput = document.getElementById('image');
    var file = fileInput.files[0];

    var formData = new FormData();
    formData.append('image', file);
    
    const token = sessionStorage.getItem('jwt');
    const uuid = sessionStorage.getItem('uuid');

    formData.append('token', token);
    formData.append('uuid', uuid);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?=$baseUrl?>/api/users/uploadImage.php', true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.onload = function() {
        if (xhr.status === 200) {
    var response = JSON.parse(xhr.responseText);
    var modal = new bootstrap.Modal(document.getElementById('uploadResultModal'));
    var uploadForm = document.getElementById('uploadForm');
    var successAlert = `
      <div class="container mt-4" style="margin-left:-20px;margin-bottom:20px;">
        <div class="alert alert-warning alert-dismissible fade show alert-message" role="alert">
          <strong>Success!</strong> Your image has been uploaded.
          <a href="#" class="btn-close close-btn" data-bs-dismiss="alert" aria-label="Close"></a>
        </div>
      </div>
    `;
    var errorAlert = `
      <div class="container mt-4" style="margin-left:-20px;margin-bottom:20px;">
        <div class="alert alert-danger alert-dismissible fade show alert-message" role="alert">
          <strong>Error!</strong> Temporarily unable to upload image.
          <a href="#" class="btn-close close-btn" data-bs-dismiss="alert" aria-label="Close"></a>
        </div>
      </div>
    `;

    if (response.success) {
      // Si es éxito, mostramos el mensaje de éxito y ocultamos el formulario
      uploadForm.style.display = 'none';
      document.body.insertAdjacentHTML('afterbegin', successAlert);
    } else {
      // Si es error, mostramos el mensaje de error
      document.body.insertAdjacentHTML('afterbegin', errorAlert);
    }

    modal.show(); // Mostrar el modal
  } else {
    console.error('Error:', xhr.statusText);
  }
    };

    xhr.send(formData);
}

</script>
</head>
<body>
<?php include_once 'header-panel.php'; ?>
  <main id="main">
  <div class="container" data-aos="fade-up" style="margin-top:100px;min-height:550px">
      <h1>Upload your Profile Photo</h1>
      <form id="uploadForm">
    <div class="mb-3">
        <label for="image" class="form-label">Select Image (PNG, JPG, GIF, max 3MB)</label>
        <input type="file" class="form-control" id="image" accept=".png, .jpg, .jpeg, .gif" required>
    </div>
    <button type="button" onclick="uploadImage()" class="btn btn-primary">Upload</button>
</form>
    </div>
</main>



<!-- end of modal -->
</body>
</html>