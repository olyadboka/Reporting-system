<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <link rel="stylesheet" href="../CSS/Signup.css
  ">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
  <div class="signup-box">

    <h1 class="header">Sign up now</h1>
    <div class="from">
      <form action="./login/signupDB.php" method="post" enctype="multipart/form-data">
        <table>

          <tr>
            <td>
              <!-- for name -->
              <div class="name-container">
                <label for="name">Name :</label>
            </td>
            <td>
              <input type="text" name="name" placeholder="Enter your FullName...." id="name">
    </div>
    </td>
    </tr>
    <tr>


      <td>
        <!--  for house Number -->
        <div class="house-number">
          <label for="house-number">House Number :</label>
      </td>
      <td>
        <input type="number" name="house-number" placeholder="Enter your House number...." id="house-number">
  </div>

  </td>
  </tr>
  <tr>

    <td>

      <!-- for id -->
      <div class="id-no">
        <label for="id-no">ID Number :</label>

    </td>
    <td>
      <input type="number" name="id-no" placeholder="Enter your ID number...." id="house-number">

      </div>

    </td>

  </tr>
  <tr>
    <td>
      <!-- for gender -->
      <div class="gender-container">
        <label for="name"> Gender :</label>
        <div>
    </td>
    <td>
      <input type="radio" name="gender" id="male">Male
      <input type="radio" name="gender" id="female">Female
      </div>
      </div>


    </td>
  </tr>
  <tr>
    <td>


      <!-- for kebele -->
      <div class="kebele-container">
        <label for="kebele">Kebele :</label>
    </td>
    <td>
      <input type="text" name="kebele" placeholder="Enter your Kebele...." id="kebele">
      </div>


    </td>
  </tr>
  <tr>


    <td>


      <!-- for create password
  
   -->
      <div class="create-password">
        <label for="create-password">Password :</label>
    </td>
    <td>
      <input type="text" name="create-password" placeholder="Enter your New password...." id="create-password">

      </div>
    </td>
  </tr>
  <tr>
    <td>

      <!-- for confirm password -->
      <div class="confirm-password-container">
        <label for="confirm-password">Confirm Password :</label>
    </td>
    <td>
      <input type="text" name="confirm-password" placeholder="Enter your confirm password...." id="confirm-password">
      </div>
    </td>


  </tr>


  <tr align="center">
    <td colspan="2">
      <button>
        Sign up
        <div class="arrow-wrapper">
          <div class="arrow"></div>

        </div>
      </button>
    </td>

  </tr>
  </table>





  </form>
  </div>



</body>

</html>