<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <link rel="stylesheet" href="../CSS/login.css
  ">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
  <div class="login-box">

    <h1 class="header">Login now</h1>
    <div class="from">
      <form action="./login/loginDB.php" method="post" enctype="multipart/form-data">
        <table>

          <tr>
            <td>
              <!-- for username -->
              <div class="name-container">
                <label for="name">User Name :</label>
            </td>
            <td>
              <input type="text" name="name" placeholder="Enter your FullName...." id="name">
    </div>
    </td>
    </tr>

    <tr>
      <!-- for  password
  
   -->
      <td>
        <div class="create-password">
          <label for="create-password">Password :</label>
      </td>
      <td>
        <input type="text" name="create-password" placeholder="Enter yourpassword...." id="create-password">

  </div>
  </td>


  </tr>


  <tr align="center">
    <td colspan="2">
      <button>
        Login
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