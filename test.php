<html>
  <head>
    <title>My first website</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Hedvig+Letters+Sans&display=swap"
      rel="stylesheet"
    />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@900&display=swap"
      rel="stylesheet"
    />

    <style>
      html {
        -webkit-print-color-adjust: exact;
      }

      :root {
        --primary: #efefef;
        --secondary: rgb(214 221 230);
        --title: #0e3569;
        --font-title: "League Spartan", sans-serif;
        --font-subtitle: "Hedvig Letters Sans", sans-serif;
      }

      body {
        margin: 0px;
        padding: 0px;
        width: 100vw;
        overflow: hidden;
        box-sizing: border-box;
      }

      @page {
        size: A4;
        margin: 0px;
      }

      .mt-2 {
        margin-top: 20px;
      }

      .mt-3 {
        margin-top: 30px;
      }

      .mt-4 {
        margin-top: 40px;
      }

      .mb-2 {
        margin-bottom: 20px;
      }

      .ps-1 {
        padding-left: 10px;
      }

      .ps-5 {
        padding-left: 50px;
      }

      .ps-0 {
        padding-left: 0px;
      }

      .px-5 {
        padding-left: 50px;
        padding-right: 50px;
      }

      .font-subtitle {
        font-family: var(--font-subtitle);
      }

      .container {
      }

      .box-1 {
        width: 40%;
        float: left;
        height: 100%;
        position: relative;
        padding-right: 20px;
        box-sizing: border-box;
      }

      .box-2 {
        width: 60%;
        float: right;
        height: 100%;
        position: relative;
        padding-left: 10px;
        box-sizing: border-box;
      }

      .first-subtitle {
        margin-bottom: 20px;
        margin-top: 0px;
        font-family: var(--font-title);
        font-size: 11.6pt;
        font-weight: bold;
        color: var(--title);
        letter-spacing: 1pt;
        box-sizing: border-box;
      }

      .subtitle {
        margin-bottom: 20px;
        margin-top: 40px;
        font-family: var(--font-title);
        font-size: 11.6pt;
        font-weight: bold;
        color: var(--title);
        letter-spacing: 1pt;
        box-sizing: border-box;
      }

      .about-me-text {
        font-family: var(--font-subtitle);
        font-size: 9.5pt;
      }

      .img-profile {
        width: 255px;
        height: 255px;
        border-radius: 50%;
        display: block;
        margin: 0px auto 40px auto;
        border: 10px solid white;
        background: url("https://cdn-images.livecareer.es/pages/foto_cv_lc_es_7.jpg");
        background-size: cover;
        background-position: center top;
      }

      .list-education {
        font-family: var(--font-subtitle);
      }

      .list-contact {
        list-style: none;
        padding-left: 50px;
        background: r;
        font-family: var(--font-subtitle);
        font-size: 9.5pt;
      }

      .list-contact li {
        margin-top: 5px;
      }

      h1 {
        font-size: 45.3pt;
        color: var(--title);
        line-height: 30px;
        font-family: var(--font-title);
        font-weight: 100;
        margin-bottom: 0px;
      }

      h3 {
        font-size: 13.6pt;
        color: black;
        line-height: 30px;
        font-family: var(--font-title);
        font-weight: 100;
      }

      .list-experience {
        padding-left: 25px;
        font-family: var(--font-subtitle);
      }

      .list-education h5 {
        font-size: 12pt;
        font-weight: bold;
        padding: 0px;
        margin: 0px 0px 10px 0px;
      }

      .list-education p {
        padding: 0px;
        margin: 0px;
        font-size: 10.5pt;
        margin: 0px 0px 10px 0px;
      }

      .list-education li {
        font-size: 9.5pt;
        margin: 0px 0px 5px 0px;
        margin-left: 18px;
        padding-right: 30px;
      }

      .separator {
        margin: 18px 0px;
      }

      hr {
        margin: 18px 0px;
        border: #a6a6a64e solid 1px;
      }

      .background-blue {
        background-color: var(--secondary);
        padding-top: 10px;
        padding-bottom: 10px;
        padding-right: 10px;
      }

      .background-1 {
        background-color: var(--primary);
        padding-bottom: 20px;
        padding-top: 30px;
      }
    </style>
  </head>

  <body>
    <div class="container" style="width: 780px; margin: 0px auto">
      <div class="box-1">
        <div class="background-1">
          <div class="img-profile"></div>

          <h5 class="first-subtitle px-5">ABOUT ME</h5>

          <p class="about-me-text px-5">
            I am a proactive, organized, and responsible individual with good
            interpersonal skills. I always have the best disposition for
            carrying out my tasks. I am seeking a challenging job opportunity.
          </p>

          <h5 class="subtitle px-5">CONTACT INFORMATION</h5>

          <ul class="list-contact px-5">
            <li>Phone: (011) 1234 5678</li>
            <li>Email: hello@amazingwebsite.com</li>
            <li>Website: @amazingwebsite</li>
            <li>Address: Any Street 123, Anywhere</li>
          </ul>
        </div>

        <h5 class="subtitle background-blue ps-5">EDUCATION</h5>

        <ul class="list-education ps-5">
          <h5><b>Amazing University</b></h5>
          <p>Advanced proficiency in both oral and written communication.</p>
          <li>Graduated with academic honors</li>
          <li>Vice President of the Mathematics Club, 2012</li>
        </ul>
        <ul class="list-education mt-3 ps-5">
          <h5><b>Amazing High School</b></h5>
          <p>Bachelor in Goods and Services, 2012</p>
          <li>Graduated with academic honors</li>
          <li>Vice President of the Mathematics Club, 2012</li>
        </ul>
      </div>

      <div class="box-2">
        <h1>SOFIA</h1>
        <h1>SANCHEZ</h1>
        <h3>BACHELOR IN BUSINESS ADMINISTRATION</h3>

        <h5 class="subtitle background-blue ps-1">WORK EXPERIENCE</h5>

        <ul class="list-education mt-3 ps-1">
          <h5><b>Management Assistant</b></h5>
          <p>Amazing Company, Aug 2019 - Present</p>
          <li>Comprehensive administrative support to management.</li>
          <li>
            Agenda management. Review of document suitability and file control.
            Creation of monthly presentations.
          </li>
        </ul>

        <ul class="list-education mt-3 ps-1">
          <h5><b>Administrative Assistant</b></h5>
          <p>Amazing Company, Jan 2016 - Jul 2017</p>
          <li>Customer reception. Switchboard management.</li>
          <li>
            Front desk assistance. Organization of incoming and outgoing logs.
            File maintenance.
          </li>
          <li>Preparation of weekly reports.</li>
        </ul>

        <ul class="list-education mt-3 ps-1">
          <h5><b>Administrative Intern</b></h5>
          <p>Amazing Company, Aug 2019 - Present</p>
          <li>Customer reception. Switchboard management.</li>
          <li>
            Front desk assistance. Organization of incoming and outgoing logs.
            File maintenance.
          </li>
          <li>Preparation of weekly reports.</li>
        </ul>

        <ul class="list-education mt-3 ps-1">
          <h5><b>Customer Service</b></h5>
          <p>Amazing Company, Aug 2019 - Present</p>
          <li>Face-to-face customer assistance</li>
          <li>Maintenance and monitoring of stock and logistics</li>
          <li>Preparation of weekly reports.</li>
        </ul>
      </div>
    </div>
  </body>
</html>
