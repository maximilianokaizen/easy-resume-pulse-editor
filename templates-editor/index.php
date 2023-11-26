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

  <!-- Place the first <script> tag in your HTML's <head> -->
<script src="https://cdn.tiny.cloud/1/udmojy176j2qur9i9xd0vhn545k2yh41e7prx8y2r98foje1/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<!-- Place the following <script> and <textarea> tags your HTML's <body> -->
<script>
  tinymce.init({
    selector: 'textarea',
    plugins: 'ai tinycomments mentions anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
    tinycomments_mode: 'embedded',
    tinycomments_author: 'Author name',
    mergetags_list: [
      { value: 'First.Name', title: 'First Name' },
      { value: 'Email', title: 'Email' },
    ],
    ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
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

  <button id="generatePdf">Test Pdf</button>
  <button id="previewTheme">Preview Theme</button>
  <button id="createTemplate">Create Template!</button>
  <iframe id="previewFrame"></iframe>

  <script>
    // TEST PDF

    document.getElementById('generatePdf').addEventListener('click', function() {
      const htmlContent = tinymce.get('htmlContent').getContent();
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
      const htmlContent = tinymce.get('htmlContent').getContent();
      const previewFrame = document.getElementById('previewFrame');
      previewFrame.srcdoc = htmlContent;
    });

    document.getElementById('createTemplate').addEventListener('click', function() {
      const htmlContent = tinymce.get('htmlContent').getContent();
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

    
  </script>
</body>
</html>