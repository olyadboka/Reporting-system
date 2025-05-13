<?php
include "../commonAdmin.php";
include "../../reportDB/dbconnection.php";

// Handle Delete User
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']); // Sanitize input
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: residentsManagent.php"); // Refresh the page
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

    $sql = "UPDATE users SET fname = ?, mname = ?, fathersName = ?, phone = ?, email = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $fname, $mname, $fathersName, $phone, $email, $role, $edit_id);
    $stmt->execute();
    $stmt->close();
    header("Location: residentsManagent.php"); // Refresh the page
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Users Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../dashboardHome.css">
</head>

<body>
  <div class="container">
    <h1>Users Management</h1>

    <!-- Users Table -->
    <table class="table table-bordered">
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
        $sql = "SELECT id, fname, mname, fathersName, phone, email, role FROM users";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['fname']} {$row['mname']} {$row['fathersName']}</td>
                        <td>{$row['phone']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['role']}</td>
                        <td>
                          <button class='btn btn-info' onclick=\"editUser({$row['id']}, '{$row['fname']}', '{$row['mname']}', '{$row['fathersName']}', '{$row['phone']}', '{$row['email']}', '{$row['role']}')\">Edit</button>
                          <a href='?delete_id={$row['id']}' class='btn btn-danger'>Delete</a>
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
        <button type="submit" name="edit_user" class="btn btn-success">Save Changes</button>
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