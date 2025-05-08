<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hmreportsystem";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch reports from the database
$sql = "SELECT report_id, user_id, category, description, location, status, priority, created_at FROM reports";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Page</title>
    <link rel="stylesheet" href="staffCSS/staff.css">
</head>
<body>
    <main>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="report-card">
                    <h3>Report #<?php echo $row['report_id']; ?></h3>
                    <p><strong>Submitted by:</strong> User ID <?php echo $row['user_id']; ?></p>
                    <p><strong>Category:</strong> <?php echo $row['category']; ?></p>
                    <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
                    <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
                    <p><strong>Status:</strong> <?php echo ucfirst($row['status']); ?></p>
                    <p><strong>Priority:</strong> <?php echo ucfirst($row['priority']); ?></p>
                    <p><strong>Created At:</strong> <?php echo $row['created_at']; ?></p>
                    
                    <!-- Fetch and display attached images -->
                    <?php
                    $report_id = $row['report_id'];
                    $image_sql = "SELECT image_blob FROM report_images WHERE report_id = ?";
                    $stmt = $conn->prepare($image_sql);
                    $stmt->bind_param("i", $report_id);
                    $stmt->execute();
                    $image_result = $stmt->get_result();
                    ?>
                    <?php if ($image_result->num_rows > 0): ?>
                        <div class="report-images">
                            <?php while ($image_row = $image_result->fetch_assoc()): ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($image_row['image_blob']); ?>" alt="Report Image" class="clickable-image">
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p>No images attached.</p>
                    <?php endif; ?>
                    <?php $stmt->close(); ?>

                    <div class="actions">
                        <form action="process.php" method="POST">
                            <input type="hidden" name="report_id" value="<?php echo $row['report_id']; ?>">
                            <input type="hidden" name="action" value="accept">
                            <button class="accept-btn">Accept</button>
                        </form>
                        <form action="process.php" method="POST">
                            <input type="hidden" name="report_id" value="<?php echo $row['report_id']; ?>">
                            <input type="hidden" name="action" value="reject">
                            <button class="reject-btn">Reject</button>
                        </form>
                        <form action="process.php" method="POST">
                            <input type="hidden" name="report_id" value="<?php echo $row['report_id']; ?>">
                            <input type="hidden" name="action" value="forward">
                            <button class="forward-btn">Forward</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No reports found.</p>
        <?php endif; ?>
        <?php $conn->close(); ?>
    </main>

     <!-- Modal for fullscreen image -->
     <div id="imageModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <script src="js/modal.js"></script>
</body>
</html>