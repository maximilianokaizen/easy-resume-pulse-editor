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
  </style>
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

  <button id="generatePdf">Test Pdf</button>
  <button id="previewTheme">Preview Theme</button>
  <iframe id="previewFrame"></iframe>

  <script>
    document.getElementById('generatePdf').addEventListener('click', function() {
      // ... tu lógica existente para generar el PDF ...
    });

    document.getElementById('previewTheme').addEventListener('click', function() {
      const htmlContent = document.getElementById('htmlContent').value;
      const previewFrame = document.getElementById('previewFrame');
      previewFrame.srcdoc = htmlContent;
    });
  </script>
</body>
</html>