<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Test PDF</title>
  <style>
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
  </style>
</head>
<body>
  <textarea id="htmlContent" rows="10" cols="50">
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

  <script>
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
  </script>
</body>
</html>
