<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Test PDF</title>
  <style>
    /* Estilos CSS */
    body {
      font-family: 'Arial', sans-serif;
    }
    textarea {
      width: 90%;
      height: 300px;
      background-color: black;
      color: white;
      padding: 10px;
      border: 1px solid #ccc;
      font-family: monospace;
    }
    button {
      padding: 10px 20px;
      background-color: #007bff;
      color: white;
      border: none;
      cursor: pointer;
      font-size: 16px;
      margin-top: 10px;
    }
    /* Estilos para el iframe de vista previa */
    #previewFrame {
      width: 90%;
      height: 400px;
      border: 1px solid #ccc;
      margin-top: 20px;
    }
    input[type="text"] {
      width: 90%;
      padding: 10px;
      margin-top: 10px;
      font-size: 16px;
    }
  </style>
<script>

// DELETE

document.addEventListener("DOMContentLoaded", function() {
      let urlParams = new URLSearchParams(window.location.search);
      const deleteTemplateBtn = document.getElementById('deleteTemplate');

      // Función para enviar la solicitud POST al eliminar el template
      function deleteTemplate() {
        const id = urlParams.get('id');

        const confirmed = confirm('¿Realmente desea ELIMINAR este template?'); // Mensaje de confirmación

        if (confirmed) {
          const data = {
            id: id,
            token: 'kaizen'
          };

          fetch('https://easyresumepulse.com/en/api/templates/deleteFromEditor.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
          })
          .then(response => response.json())
          .then(result => {
            // Manejar la respuesta si es necesario
            console.log(result);
          })
          .catch(error => {
            console.error('Error:', error);
          });
        }
      }

      // Evento click para el botón de eliminar template
      deleteTemplateBtn.addEventListener('click', deleteTemplate);
    });

document.addEventListener("DOMContentLoaded", function() {

  let urlParams = new URLSearchParams(window.location.search);
  const htmlContentTextarea = document.getElementById('htmlContent');
  const templateNameInput = document.getElementById('templateName');
  const editTemplateBtn = document.getElementById('editTemplate');

  // Función para cargar los datos en los elementos correspondientes
  function loadDataIntoElements(html, name) {
    htmlContentTextarea.value = html; // Cargar HTML en el textarea
    templateNameInput.value = name; // Cargar nombre en el input

    // Mostrar el botón después de cargar los datos
    editTemplateBtn.style.display = 'block';
  }

  // Función para enviar la solicitud POST al editar el template
  function editTemplate() {
    const id = urlParams.get('id');
    const html = htmlContentTextarea.value;
    const name = templateNameInput.value;

    const confirmed = confirm('Realmente desea EDITAR este template?'); // Mensaje de confirmación

    if (confirmed){
      // Objeto con los datos a enviar en la solicitud POST
    const data = {
      id: id,
      html: html,
      name: name,
      token: 'kaizen'
    };

    fetch('https://easyresumepulse.com/en/api/templates/editFromEditor.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
      // Manejar la respuesta si es necesario
      console.log(result);
    })
    .catch(error => {
      console.error('Error:', error);
    });
    }
    
  }

  // Evento click para el botón de editar template
  editTemplateBtn.addEventListener('click', editTemplate);
  const params = {
      id: urlParams.get('id'),
      token: 'kaizen'
  };

  fetch(`https://easyresumepulse.com/en/api/templates/getTemplateFromEditor.php?id=${params.id}&token=${params.token}`, {
    method: 'GET',
    // Otros headers si es necesario
  })
  .then(response => response.json())
  .then(data => {
    if (data.success === true) {
      const html = data.template[0].html;
      const name = data.template[0].name;
      loadDataIntoElements(html, name);
    } else {
      // Manejar el caso en que success es false
    }
  })
  .catch(error => {
    console.error('Error:', error);
  });
});

</script>
</head>
<body>
  <textarea id="htmlContent" rows="10" cols="50">
    <!-- Contenido de ejemplo -->
    <!DOCTYPE html>
    <html>
    <head>
      <title>My HTML Document</title>
      <style>
        body {
          background-color: #f0f0f0;
          color: #333;
          font-family: Arial, sans-serif;
        }
      </style>
    </head>
    <body>
      <h1>Hello, World!</h1>
      <p>This is a sample HTML content.</p>
    </body>
    </html>
  </textarea>
   
  <input type="text" id="templateName" placeholder="Template Name">
  <br/>

  <button id="generatePdfPupi">Test PDF with Pupi</button>
  <button id="previewTheme">Preview Theme</button>
  <button id="createTemplate">Create Template!</button>
  <button id="editTemplate">Edit this Template!</button>
  <button id="deleteTemplate">Delete Template!</button>
  <iframe id="previewFrame"></iframe>

  <script>
    // TEST PDF

    document.getElementById('generatePdf').addEventListener('click', function() {
      const htmlContent = document.getElementById('htmlContent').value;

      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'https://easyresumepulse.com/en/api/TestTemplate.php');
      xhr.setRequestHeader('Content-Type', 'application/json');
      xhr.responseType = 'blob';

      xhr.onload = function() {
        if (xhr.status === 200) {
          const blob = new Blob([xhr.response], { type: 'application/pdf' });
          const url = window.URL.createObjectURL(blob);
          const a = document.createElement('a');
          a.href = url;
          a.download = 'generated.pdf';
          document.body.appendChild(a);
          a.click();
          document.body.removeChild(a);
          window.URL.revokeObjectURL(url);
        }
      };

      xhr.onerror = function() {
        console.error('Error fetching the PDF');
      };

      const requestData = {
        htmlContent: htmlContent
      };

      xhr.send(JSON.stringify(requestData));
    });


    document.getElementById('previewTheme').addEventListener('click', function() {
      const htmlContent = document.getElementById('htmlContent').value;
      const previewFrame = document.getElementById('previewFrame');
      previewFrame.srcdoc = htmlContent;
    });

    document.getElementById('createTemplate').addEventListener('click', function() {
      const htmlContent = document.getElementById('htmlContent').value;
      const cssStart = htmlContent.indexOf('<style>');
      const cssEnd = htmlContent.indexOf('</style>');
      const css = htmlContent.substring(cssStart + 7, cssEnd);
      const html = htmlContent;
      const templateName = document.getElementById('templateName').value;

      if (templateName === '') {
        alert('Please enter a template name.'); // Validar si el campo está vacío
        return;
      }

      const requestData = {
        html: html,
        css: css,
        templateName: templateName
      };

      const confirmed = confirm('Do you want to create the template?');
      if (confirmed) {
        const htmlContent = document.getElementById('htmlContent').value;
        const cssStart = htmlContent.indexOf('<style>');
        const cssEnd = htmlContent.indexOf('</style>');
        const css = htmlContent.substring(cssStart + 7, cssEnd);
        const html = htmlContent;
        const requestData = {
          html: html,
          css: css,
          templateName: templateName
        };
        fetch('https://easyresumepulse.com/en/api/templates/saveFromEditor.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(requestData)
        })
        .then(response => response.json())
        .then(data => {
          console.log('Template created:', data);
          // Puedes manejar la respuesta aquí
        })
        .catch(error => {
          console.error('Error creating template:', error);
        });
      }
    });

    document.getElementById('generatePdfPupi').addEventListener('click', function() {
    const htmlContent = document.getElementById('htmlContent').value;

    fetch('https://easyresumepulse.com/en/api/downloadPdfPupi.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ html: htmlContent })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success === false) {
        alert('Ocurrió un error al generar el PDF.');
      } else {
        if (data.filePath) {
          window.open(data.filePath, '_blank'); 
        } else {
          alert('No se ha proporcionado la ruta del archivo.');
        }
      }
    })
    .catch(error => {
      console.error('Error fetching the PDF:', error);
    });
  });
  </script>
</body>
</html>