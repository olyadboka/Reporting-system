<?php
session_start();

// include '../dataBasels/dbconnection.php'; // Make sure the path is correct

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.php');
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="title" content="Hirmata Mentina - Kebele & Report Managment System">
  <meta name="description" content="This is a ReportSystem made by 3rd year Students">
  <title>Hermata Mentina Kebele</title>

  <link rel="stylesheet" href="../CSS/header.css" />
  <link rel="stylesheet" href="../CSS/footer.css" />
  <link rel="stylesheet" href="./CSS/ContactUs.css" />
  <link rel="stylesheet" href="./CSS/services.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
    integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&family=Forum&display=swap" rel="stylesheet">

  <link rel="preload" as="image" href="../Hermata home/assets/images/hero-slider-1.jpg">
  <link rel="preload" as="image" href="../Hermata home/assets/images/hero-slider-2.jpg">
  <link rel="preload" as="image" href="../Hermata home/assets/images/hero-slider-3.jpg">
</head>

<body id="top">

  <div class="preload" data-preaload>
    <div class="circle"></div>
    <p class="text">Hermata Mentina Kebele Report Management System</p>
  </div>

  <header class="header" data-header>
    <div class="container">

      <a href="#" class="logo">
        <img src="../Hermata home/assets/img/Flag_of_Ethiopia.svg" width="140" height="40" alt="Ethiopian-logo ">
        <img src="../Hermata home/assets/img/Flag_of_the_Oromia_Region.svg.png" width="160" height="50"
          alt="flag- Home">
      </a>

      <nav class="navbar" data-navbar>
        <button class="close-btn" aria-label="close menu" data-nav-toggler>
          <ion-icon name="close-outline" aria-hidden="true"></ion-icon>
        </button>

        <ul class="navbar-list">
          <li class="navbar-item">
            <a href="../Hermata home/index.php" class="navbar-link hover-underline active">
              <div class="separator"></div>
              <span class="span">Home</span>
            </a>
          </li>

          <li class="navbar-item">
            <a href="#menu" class="navbar-link hover-underline">
              <div class="separator"></div>
              <span class="spans" id="spansss">Objectives</span>
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


          <?php
        if (isset($_SESSION['user_id'])) {
          $user_id = $_SESSION['user_id'];
          $role = $_SESSION['role'];
          $name = $_SESSION['user_name'];
          $email = $_SESSION['user_email'];
          $phone = $_SESSION['user_phone'];
          $kebele_id = $_SESSION['user_kebele_id'];

          $stmt = mysqli_prepare($con, "SELECT photo FROM users WHERE kebele_id = ?");
          mysqli_stmt_bind_param($stmt, "s", $kebele_id);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);

          $imageData = '';
          if ($row = mysqli_fetch_assoc($result)) {
            $imageData = base64_encode($row['photo']); 
          }

          if ($role === 'admin') {
            echo '<a href="../Admin Dashboard/dashboardhome.php" class="btn btn-primary">Dashboard</a>';
          } elseif ($role === 'staff') {
            echo '<a href="../staffCSS/staff.php" class="btn btn-primary">Dashboard</a>';
          }

          if ($imageData) {
            echo '<a href="../editProfile/editProfile.php">';
            echo '<img src="data:image/jpeg;base64,' . $imageData . '" alt="Profile" style="width:100px;height:100px;border-radius:50%; object-fit:cover;">';
            echo '</a>';
          }

          echo "<a href='../login/logout.php' class='btn btn-danger' style='height:3rem;'>Logout</a>";
        }
        ?>
        </ul>
        <button class="nav-open-btn" aria-label="open menu" data-nav-toggler>
          <span class="line line-1"></span>
          <span class="line line-2"></span>
          <span class="line line-3"></span>
        </button>

        <div class="overlay" data-nav-toggler data-overlay></div>
      </nav>

    </div>
  </header>

</body>

</html>