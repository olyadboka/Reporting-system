
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Users Management</title>
  <style>
    :root {
      --primary-color: #4e73df;
      --secondary-color: #858796;
      --success-color: #1cc88a;
      --danger-color: #e74a3b;
      --light-color: #f8f9fc;
      --dark-color: #5a5c69;
      --info-color: #36b9cc;
    }

    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: var(--light-color);
    }

    .container {
      max-width: 1200px;
      margin: 20px auto;
      padding: 20px;
      background-color: white;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    h1 {
      color: var(--primary-color);
      text-align: center;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table th,
    table td {
      border: 1px solid var(--secondary-color);
      padding: 10px;
      text-align: left;
    }

    table th {
      background-color: var(--primary-color);
      color: white;
    }

    table tr:nth-child(even) {
      background-color: var(--light-color);
    }

    table tr:hover {
      background-color: var(--info-color);
      color: white;
    }

    .edit-btn {
      background-color: var(--info-color);
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 5px;
      cursor: pointer;
    }

    .edit-btn:hover {
      background-color: var(--dark-color);
    }

    .delete-btn {
      background-color: var(--danger-color);
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 5px;
      cursor: pointer;
    }

    .delete-btn:hover {
      background-color: var(--dark-color);
    }

    .form-container {
      display: none;
      margin-top: 20px;
      padding: 20px;
      background-color: var(--light-color);
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .form-container.active {
      display: block;
    }

    .form-container h3 {
      color: var(--primary-color);
      margin-bottom: 10px;
    }

    .form-container label {
      display: block;
      margin-bottom: 5px;
      color: var(--dark-color);
    }

    .form-container input,
    .form-container select {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid var(--secondary-color);
      border-radius: 5px;
    }

    .form-container button {
      background-color: var(--success-color);
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 5px;
      cursor: pointer;
    }

    .form-container button:hover {
      background-color: var(--dark-color);
    }
  </style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../dashboardHome.css">
</head>

<body>
    <?php include "../commonAdmin.php"; ?>
 
  <div class="container">
    <h1>Residents Management</h1>

    <?php
    // Database connection
     include "../../reportDB/dbconnection.php"; 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hmreportsystem";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle Delete User
    if (isset($_GET['delete_id'])) {
        $delete_id = intval($_GET['delete_id']); // Sanitize input
        $sql = "DELETE FROM residents WHERE residence_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();
        header("Location: residentsManagement.php"); // Refresh the page
        exit();
    }

    // Handle Edit User
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
        $edit_id = intval($_POST['id']); // Sanitize input
        $fname = htmlspecialchars(trim($_POST['fname']));
        $mname = htmlspecialchars(trim($_POST['mname']));
        $fathersName = htmlspecialchars(trim($_POST['fathersName']));
        $phone = htmlspecialchars(trim($_POST['phone']));
        $email = htmlspecialchars(trim($_POST['email']));
        $role = htmlspecialchars(trim($_POST['role']));

        $sql = "UPDATE residents SET fname = ?, mname = ?, fathersName = ?, phone = ?, email = ?, role = ? WHERE residence_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $fname, $mname, $fathersName, $phone, $email, $role, $edit_id);
        $stmt->execute();
        $stmt->close();
        header("Location: residentsManagement.php"); // Refresh the page
        exit();
    }
    ?>

    <!-- Users Table -->
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT residence_id, fname, mname, fathersName, phone, email, role FROM residents";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['residence_id']}</td>
                        <td>{$row['fname']} {$row['mname']} {$row['fathersName']}</td>
                        <td>{$row['phone']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['role']}</td>
                        <td>
                          <button class='edit-btn' onclick=\"editUser({$row['residence_id']}, '{$row['fname']}', '{$row['mname']}', '{$row['fathersName']}', '{$row['phone']}', '{$row['email']}', '{$row['role']}')\">Edit</button>
                          <a href='?delete_id={$row['residence_id']}' class='delete-btn'>Delete</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No users found.</td></tr>";
        }
        ?>
      </tbody>
    </table>

    <!-- Edit User Form -->
    <div id="editUserForm" class="form-container">
      <h3>Edit User</h3>
      <form method="POST" action="">
        <input type="hidden" name="id" id="edit_id">
        <label for="fname">First Name:</label>
        <input type="text" name="fname" id="edit_fname" required>
        <label for="mname">Middle Name:</label>
        <input type="text" name="mname" id="edit_mname">
        <label for="fathersName">Father's Name:</label>
        <input type="text" name="fathersName" id="edit_fathersName" required>
        <label for="phone">Phone:</label>
        <input type="text" name="phone" id="edit_phone" required>
        <label for="email">Email:</label>
        <input type="email" name="email" id="edit_email" required>
        <label for="role">Role:</label>
        <select name="role" id="edit_role" required>
          <option value="resident">Resident</option>
          <option value="staff">Staff</option>
          <option value="admin">Admin</option>
        </select>
        <button type="submit" name="edit_user">Save Changes</button>
      </form>
    </div>
  </div>

  <script>
    function editUser(id, fname, mname, fathersName, phone, email, role) {
      document.getElementById('editUserForm').classList.add('active');
      document.getElementById('edit_id').value = id;
      document.getElementById('edit_fname').value = fname;
      document.getElementById('edit_mname').value = mname;
      document.getElementById('edit_fathersName').value = fathersName;
      document.getElementById('edit_phone').value = phone;
      document.getElementById('edit_email').value = email;
      document.getElementById('edit_role').value = role;
    }
  </script>
</body>

</html>