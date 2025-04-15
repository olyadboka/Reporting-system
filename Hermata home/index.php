<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- 
    - primary meta tags
  -->

  <title>hermata mentina kebele Report Management</title>

  <meta name="title" content="Hirmata Mentina - Kebele & Report Managment System">
  <meta name="description" content="This is a ReportSystem made by 3rd year Students">

  <!-- 
    - favicon
  -->
  <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml">

  <!-- 
    - google font link
  -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&family=Forum&display=swap" rel="stylesheet">

  <!-- 
    - custom css link
  -->
  <link rel="stylesheet" href="../CSS/style.css">

  <!-- 
    - preload images
  -->
  <link rel="preload" as="image" href="./assets/images/hero-slider-1.jpg">
  <link rel="preload" as="image" href="./assets/images/hero-slider-2.jpg">
  <link rel="preload" as="image" href="./assets/images/hero-slider-3.jpg">

</head>

<body id="top">

  <!-- 
    - #PRELOADER
  -->

  <div class="preload" data-preaload>
    <div class="circle"></div>
    <p class="text">Hermata Mentina Kebele Report Management System </p>
  </div>





  <!-- 
    - #TOP BAR
  -->

  <div class="topbar">
    <div class="container">

      <address class="topbar-item">
        <div class="icon">
          <ion-icon name="location-outline" aria-hidden="true"></ion-icon>
        </div>

        <span class="span">
          Hermata Mentina Kebele, jimma City, Ormia Region, Ethiopia
        </span>
      </address>

      <div class="separator"></div>

      <!-- <div class="topbar-item item-2">
        <div class="icon">
          <ion-icon name="time-outline" aria-hidden="true"></ion-icon>
        </div>

        <span class="span">Morning 2.00 - 6.00 && Afternoon 8:00 - 11:00 </span>
      </div> -->

      <a href="tel:+11234567890" class="topbar-item link">
        <div class="icon">
          <ion-icon name="call-outline" aria-hidden="true"></ion-icon>
        </div>

        <span class="span">+251 923 456 7890</span>
      </a>

      <div class="separator"></div>

      <a href="mailto:hermatamentina@kebele.com" class="topbar-item link">
        <div class="icon">
          <ion-icon name="mail-outline" aria-hidden="true"></ion-icon>
        </div>

        <span class="span">www.Hermata Mentina Kebele@gmail.com</span>
      </a>

    </div>
  </div>





  <!-- 
    - #HEADER
  -->

  <header class="header" data-header>
    <div class="container">

      <a href="#" class="logo">
        <img src="./assets/img/Flag_of_Ethiopia.svg" width="140" height="40" alt="Ethiopian-logo ">
      </a>

      <nav class="navbar" data-navbar>

        <button class="close-btn" aria-label="close menu" data-nav-toggler>
          <ion-icon name="close-outline" aria-hidden="true"></ion-icon>
        </button>

        <a href="#" class="logo">
          <img src="./assets/img/Flag_of_Ethiopia.svg" width="160" height="50" alt="Ethiopia - Home">
          <h2>Hermata-Mentina </h2>
        </a>


        <ul class="navbar-list">
          <li class="navbar-item">
            <a href="#home" class="navbar-link hover-underline active">
              <div class="separator"></div>
              <span class="span">Home</span>
            </a>
          </li>

          <li class="navbar-item">
            <a href="#menu" class="navbar-link hover-underline">
              <div class="separator"></div>
              <span class="spans" id="spansss">objectives</span>
            </a>
          </li>

          <li class="navbar-item">
            <a href="#about" class="navbar-link hover-underline">
              <div class="separator"></div>
              <span class="span">About Us</span>
            </a>
          </li>

          <li class="navbar-item">
            <a href="#" class="navbar-link hover-underline">
              <div class="separator"></div>
              <span class="span" id="rep">About Representative</span>
            </a>
          </li>

          <li class="navbar-item">
            <a href="#" class="navbar-link hover-underline">
              <div class="separator"></div>
              <span class="span" id="contactsss">Contact</span>
            </a>
          </li>

          <li class="navbar-item">
            <a href="../report.php" class="navbar-link hover-underline report-link">
              <div class="separator"></div>
              <span class="span" id="reports">Report Now</span>
            </a>
          </li>
        </ul>
        <div class="text-center">
          <p class="headline-1 navbar-title">Visit Us</p>

          <address class="body-4">
            Hermata Mentina, Jimma City, <br>
            Oromia, Ethiopia
          </address>

          <p class="body-4 navbar-text">Open: 8:30 am - 5.30pm</p>

          <a href="mailto:booking@grilli.com" class="body-4 sidebar-link">hermatamentina@keble.org</a>

          <div class="separator"></div>

          <p class="contact-label">Report system</p>

          <a href="tel:+88123123456" class="body-1 contact-number hover-underline">
            +251-5765757
          </a>
        </div>

      </nav>

      <a href="#" class="btn btn-secondary">
        <span class="text text-1">Register</span>

        <span class="text text-2" aria-hidden="true">Register</span>
      </a>

      <button class="nav-open-btn" aria-label="open menu" data-nav-toggler>
        <span class="line line-1"></span>
        <span class="line line-2"></span>
        <span class="line line-3"></span>
      </button>

      <div class="overlay" data-nav-toggler data-overlay></div>

    </div>
  </header>




  <main>
    <article>

      <!-- 
        - #HERO
      -->

      <section class="hero text-center" aria-label="home" id="home">

        <ul class="hero-slider" data-hero-slider>

          <li class="slider-item active" data-hero-slider-item>

            <div class="slider-bg">
              <img src="./assets/img/form-pattern.png" width="1880" height="950" alt="" class="img-cover">
            </div>

            <p class="label-2 section-subtitle slider-reveal">Together, we can improve our community</p>

            <h1 class="display-1 hero-title slider-reveal">
              Welcome to the Hermata Mentina Kebele <br>
              Report Management System!
            </h1>

            <p class="body-2 hero-text slider-reveal">
              Your voice matters
            </p>

            <a href="#" class="btn btn-primary slider-reveal">
              <span class="text text-1">LOg in</span>

              <span class="text text-2" aria-hidden="true">LOg in</span>
            </a>

          </li>

          <li class="slider-item" data-hero-slider-item>

            <div class="slider-bg">
              <img src="./assets/img/form-pattern.png" width="1880" height="950" alt="" class="img-cover">
            </div>

            <p class="label-2 section-subtitle slider-reveal">Be as a family </p>

            <h1 class="display-1 hero-title slider-reveal">
              Let's build a better community together.<br>

            </h1>

            <p class="body-2 hero-text slider-reveal">
              we can improve our community
            </p>

            <a href="#" class="btn btn-primary slider-reveal">
              <span class="text text-1">LOg in</span>

              <span class="text text-2" aria-hidden="true">LOg in</span>
            </a>

          </li>

          <li class="slider-item" data-hero-slider-item>

            <div class="slider-bg">
              <img src="./assets/img/form-pattern.png" width="1880" height="950" alt="" class="img-cover">
            </div>

            <p class="label-2 section-subtitle slider-reveal">Togethere we lock beautiful </p>

            <h1 class="display-1 hero-title slider-reveal">
              Better family <br>
              better community
            </h1>

            <p class="body-2 hero-text slider-reveal">
              Let's build a better community together
            </p>

            <a href="#" class="btn btn-primary slider-reveal">
              <span class="text text-1">LOg in</span>

              <span class="text text-2" aria-hidden="true">LOg in</span>
            </a>

          </li>

        </ul>

        <button class="slider-btn prev" aria-label="slide to previous" data-prev-btn>
          <ion-icon name="chevron-back"></ion-icon>
        </button>

        <button class="slider-btn next" aria-label="slide to next" data-next-btn>
          <ion-icon name="chevron-forward"></ion-icon>
        </button>



      </section>









      <!-- 
        - #ABOUT
      -->

      <section class="section about text-center" aria-labelledby="about-label" id="about">
        <div class="container">

          <div class="about-content">

            <p class="label-2 section-subtitle" id="about-label">Hermata Mentian</p>

            <h2 class="headline-1 section-title">The Story of Hermata Mentian Kebele</h2>

            <p class="section-text">
              Nestled in the picturesque landscape of Jimma, Hermata Mentian Kebele has a rich tapestry woven from the
              lives of its residents. The kebele was established several decades ago, rooted in a vision of community
              and cooperation. Its name, "Hermata Mentian," translates to "the place of hope," reflecting the
              aspirations of its founders.

              The story began with a group of passionate individuals who sought to create a space where families could
              thrive. They gathered in the shade of the ancient coffee trees that dot the area,
              sharing dreams of a community that values education, agriculture, and cultural heritage. This
              collaboration led to the establishment of schools, health clinics, and cooperative farms,
              providing essential services to residents.
            </p>

            <div class="contact-label">Call</div>

            <a href="tel:+804001234567" class="body-1 contact-number hover-underline">+251-9 123 4567</a>

            <a href="#" class="btn btn-primary">
              <span class="text text-1">Read More</span>

              <span class="text text-2" aria-hidden="true">Read More</span>
            </a>

          </div>

          <figure class="about-banner">

            <img src="./assets/img/2 image jim.jpg" width="570" height="570" loading="lazy" alt="about banner"
              class="w-100" data-parallax-item data-parallax-speed="1">

            <div class="abs-img abs-img-1 has-before" data-parallax-item data-parallax-speed="1.75">
              <img src="./assets/img/jimma 222b.jpg" width="285" height="285" loading="lazy" alt="" class="w-100">
            </div>

            <div class="abs-img abs-img-2 has-before">
              <img src="./assets/img/Flag_of_the_Oromia_Region.svg.png" width="133" height="134" loading="lazy" alt="">
            </div>

          </figure>



        </div>
      </section>




      <section class="section testi text-center has-bg-image"
        style="background-image: url('./assets/img/form-pattern.png')" aria-label="testimonials" id="lover">
        <div class="container">

          <div class="quote">”</div>

          <p class="headline-2 testi-text">
            is a prominent Ethiopian political figure, known for his transformative leadership since taking office in
            April 2018
          </p>

          <div class="wrapper">
            <div class="separator"></div>
            <div class="separator"></div>
            <div class="separator"></div>
          </div>

          <div class="profile">
            <img src="./assets/img/Abiys.jpg" width="100" height="100" loading="lazy" alt="Sam Jhonson" class="img">

            <p class="label-2 profile-name">Abiy Ahmed</p>
          </div>

        </div>
      </section>







      <div class="form-right text-center" style="background-image: url('./assets/images/form-pattern.png')">

        <h2 class="headline-1 text-center">Contact Us</h2>

        <p class="contact-label"> contact-number</p>

        <a href="tel:+88123123456" class="body-1 contact-number hover-underline">+251-3456</a>

        <div class="separator"></div>

        <p class="contact-label">Location</p>

        <address class="body-4">
          Hermata mentina, Jimma City, <br>
          Ethiopia
        </address>

        <p class="contact-label">Morning work time</p>

        <p class="body-4">
          Monday to Friday <br>
          2.00 am - 6.00
        </p>

        <p class="contact-label">Afternoon work time</p>

        <p class="body-4">
          Monday to Friday <br>
          8.00 am - 10.00 am
        </p>

      </div>

      </div>

      </div>
      </section>





      <!-- 
        - #FEATURES
      -->

      <section class="section features text-center" aria-label="features" id="whyus">
        <div class="container">

          <!-- <p class="section-subtitle label-2">Why Choose Us</p> -->

          <h2 class="headline-1 section-title">objectives</h2>

          <ul class="grid-list">

            <li class="feature-item">
              <div class="feature-card">

                <div class="card-icon">
                  <img src="./assets/img/community focus.jpg" width="100" height="80" loading="lazy" alt="icon">
                </div>

                <h3 class="title-2 card-title">Community-Centric Focus</h3>

                <p class="label-1 card-text">Hermata Mentian Kebele is a vibrant community nestled in the heart of
                  Jimma. We pride ourselves on our rich cultural heritage and strong community bonds.</p>

              </div>
            </li>

            <li class="feature-item">
              <div class="feature-card">

                <div class="card-icon">
                  <img src="./assets/img/sow.jpg" width="100" height="80" loading="lazy" alt="icon">
                </div>

                <h3 class="title-2 card-title">Environmental Stewardship</h3>

                <p class="label-1 card-text">we are committed to preserving our natural environment while promoting
                  sustainable practices</p>

              </div>
            </li>

            <li class="feature-item">
              <div class="feature-card">

                <div class="card-icon">
                  <img src="./assets/img/gender.jpg" width="100" height="80" loading="lazy" alt="icon">
                </div>

                <h3 class="title-2 card-title">Enhance Gender Equality</h3>

                <p class="label-1 card-text">crease women's participation in local governance and
                  decision-making processes by 30% within the next two years</p>

              </div>
            </li>

            <li class="feature-item">
              <div class="feature-card">

                <div class="card-icon">
                  <img src="./assets/img/infa.jpg" width="100" height="80" loading="lazy" alt="icon">
                </div>

                <h3 class="title-2 card-title">Improve Infrastructure</h3>

                <p class="label-1 card-text">Upgrade and maintain 10 kilometers of local roads to facilitate better
                  access to markets and services within the next year.</p>

              </div>
            </li>

          </ul>
        </div>
      </section>

    </article>
  </main>





  <!-- 
    - #FOOTER
  -->

  <footer class="footer section has-bg-image text-center" style="background-image: url('./assets/img/form-pattern.png')"
    id="footerss">
    <div class="container">

      <div class="footer-top grid-list">

        <div class="footer-brand has-before has-after">

          <a href="#" class="logo">
            <img src="./assets/img/Flag_of_Ethiopia.svg" width="160" height="60" loading="lazy" alt="grilli home">
            <br>
            <br>
            <img src="./assets/img/Flag_of_the_Oromia_Region.svg.png" width="160" height="60">
          </a>

          <address class="body-4">
            Hermata mentina, jimma City, Oromia 9578, Ethiopia
          </address>

          <a href="hermatamentinakebele@gmail.com" class="body-4 contact-link">"hermatamentinakebele@gmail.com</a>

          <a href="tel:+88123123456" class="body-4 contact-link">Phone no : +251-123-123456</a>



          <div class="wrapper">
            <div class="separator"></div>
            <div class="separator"></div>
            <div class="separator"></div>
          </div>

          <p class="title-1">We are Family</p>



          <form action="" class="input-wrapper">
            <div class="icon-wrapper">
              <ion-icon name="mail-outline" aria-hidden="true"></ion-icon>

              <input type="file" name="email_address" placeholder="Your email" autocomplete="off" class="input-field">
            </div>

            <button type="submit" class="btn btn-secondary">
              <span class="text text-1">Submite</span>

              <span class="text text-2" aria-hidden="true">Submite</span>
            </button>
          </form>

        </div>

        <ul class="footer-list">

          <li>
            <a href="#" id class="label-2 footer-link hover-underline">Home</a>
          </li>

          <li>
            <a href="#whyus" class="label-2 footer-link hover-underline">objectives</a>
          </li>

          <li>
            <a href="#about" class="label-2 footer-link hover-underline">About Us</a>
          </li>

          <li>
            <a href="#lover" class="label-2 footer-link hover-underline">About Represntative</a>
          </li>

          <li>
            <a href="#footerss" id="contactsss" class="label-2 footer-link hover-underline">Contact</a>
          </li>
          <li>
            <a href="#represntative" class="label-2 footer-link hover-underline">Report</a>
          </li>

        </ul>

        <ul class="footer-list">

          <li>
            <a href="#" class="label-2 footer-link hover-underline">Facebook</a>
          </li>

          <li>
            <a href="#" class="label-2 footer-link hover-underline">Instagram</a>
          </li>

          <li>
            <a href="#" class="label-2 footer-link hover-underline">Twitter</a>
          </li>

          <li>
            <a href="#" class="label-2 footer-link hover-underline">Youtube</a>
          </li>

          <li>
            <a href="https://www.google.com/maps/place/Hermata+Kebele+Adminstration+Office/@7.6753577,36.8268682,1003m/data=!3m1!1e3!4m15!1m8!3m7!1s0x17adb882f6e6a723:0xf45a83bb90208fd8!2sJimma!3b1!8m2!3d7.6753269!4d36.8372893!16zL20vMDZwcG5q!3m5!1s0x17adb986136ad31f:0x118e5f177492d41!8m2!3d7.674528!4d36.8325806!16s%2Fg%2F11pztbq07j?entry=ttu&g_ep=EgoyMDI1MDMwNC4wIKXMDSoASAFQAw%3D%3D"
              class="label-2 footer-link hover-underline">location</a>
          </li>

        </ul>

      </div>

      <div class="footer-bottom">

        <p class="copyright">
          &copy; hermatamentinakebele. All Rights Reserved |</a>
        </p>

      </div>

    </div>
  </footer>





  <!-- 
    - #BACK TO TOP
  -->

  <a href="#top" class="back-top-btn active" aria-label="back to top" data-back-top-btn>
    <ion-icon name="chevron-up" aria-hidden="true"></ion-icon>
  </a>





  <!-- 
    - custom js link
  -->
  <script src="./assets/js/script.js"></script>

  <!-- 
    - ionicon link
  -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>