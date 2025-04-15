<?php
include "dbconnection.php";  


if (!$db) {
    die("Database selection failed: " . mysqli_error($con));
}


$sql = "
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('user', 'admin','staff') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reports (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    category VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(255) NOT NULL,
    image_url VARCHAR(255),
    status ENUM('Pending', 'In Progress', 'Resolved') DEFAULT 'Pending',
    priority ENUM('Low', 'Medium', 'High') DEFAULT 'Medium',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
);

CREATE TABLE comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT NOT NULL,
    user_id INT NULL,
    comment_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (report_id) REFERENCES reports(report_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
);

CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    report_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (report_id) REFERENCES reports(report_id) ON DELETE CASCADE
);

CREATE TABLE admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'Super Admin') DEFAULT 'Admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE report_status_history (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT NOT NULL,
    previous_status ENUM('Pending', 'In Progress', 'Resolved') NOT NULL,
    new_status ENUM('Pending', 'In Progress', 'Resolved') NOT NULL,
    changed_by INT NOT NULL,
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (report_id) REFERENCES reports(report_id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES admins(admin_id) ON DELETE CASCADE
);
";

$sqll =  "ALTER TABLE reports ADD COLUMN is_considered TINYINT(1) DEFAULT 0";


if (mysqli_multi_query($con, $sqll)) {
    echo "Tables created successfully.";
} else {
    echo "Error creating tables: " . mysqli_error($con);
}