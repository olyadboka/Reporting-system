<?php
session_start();
include '../dataBase.all/dbconnection.php';
if (!isset($_SESSION['username'])) {
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

  <!-- <link rel="stylesheet" href="../CSS/style.css"> -->

  <!-- 
    - preload images
  -->
  <link rel="preload" as="image" href="../Hermata home/assets/images/hero-slider-1.jpg">
  <link rel="preload" as="image" href="../Hermata home/assets/images/hero-slider-2.jpg">
  <link rel="preload" as="image" href="../Hermata home/assets/images/hero-slider-3.jpg">

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
    - #HEADER
  -->

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

              <span class="span" id="rep">About Represntative</span>
            </a>
          </li>

          <li class="navbar-item">
            <a href="#" class="navbar-link hover-underline">
              <div class="separator"></div>

              <span class="span" id="contactsss">Contact</span>
            </a>
          </li>
          <li class="navbar-item">
            <a href="#" class="navbar-link hover-underline">
              <div class="separator"></div>

              <span class="span" id="reports"><a href="../report.php" class="report-link">Report Now</a></span>
            </a>
          </li>


        </ul>

        <?php 


if(isset($_SESSION['user_id'])){
       $user_id = $_SESSION['user_id'];
       $sql = "SELECT * FROM users WHERE user_id = '$user_id'";
       $result = mysqli_query($con, $sql);
       if(mysqli_num_rows($result)> 0){
           $row = mysqli_fetch_array($result);
           $full_name = $row['full_name'];
           $role = $row['role'];
           $status = $row['status'];
           $email = $row['email'];
           $phone = $row['phone'];
           $image = $row['image'];
           $kebele_id = $row['kebele_id'];
               
          }
          if($role == 'admin'){
            echo '<a href= "../Admin Dashboard/dashboardhome.php" class="btn btn-primary"> Dashboard</a>';
          } else if($role == 'stuff'){
            echo '<a href= "../staffCSS/staff.php" class="btn btn-primary"> Dashboard</a>';
          }
      
          echo '<a href="../editProfile/editProfile.php"><img src="' . htmlspecialchars($image) . '" alt="Profile" style="width:40px;height:40px;border-radius:50%;"></a>';

            echo "<a href= '../login/logout.php' class= 'btn btn-danger'>Logout</a>";
            }else{
              echo '<a href= "../login/login.php" class ="btn btn-primary">Login</a>';
            }

?>
        <button class="nav-open-btn" aria-label="open menu" data-nav-toggler>
          <span class="line line-1"></span>
          <span class="line line-2"></span>
          <span class="line line-3"></span>
        </button>

        <div class="overlay" data-nav-toggler data-overlay></div>

    </div>
  </header>