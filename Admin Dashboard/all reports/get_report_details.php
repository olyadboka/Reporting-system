<?php
session_start();
require_once '../../dataBasesls/dbConnection.php';
$kebele_id = $_SESSION['kebele_id'] ?? '';

// Get filter parameters from URL if they exist
$categoryFilter = $_GET['category'] ?? '';
$priorityFilter = $_GET['priority'] ?? '';


if(isset($_GET['id'])) {
    $reportId = $_GET['id'];
    
  
    $sql = "SELECT * FROM reports WHERE report_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $reportId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $report = mysqli_fetch_assoc($result);
    
    if($report) {
       
        $historySql = "SELECT * FROM report_status_history 
                      WHERE report_id = ? 
                      ORDER BY changed_at DESC";
        $historyStmt = mysqli_prepare($con, $historySql);
        mysqli_stmt_bind_param($historyStmt, "i", $reportId);
        mysqli_stmt_execute($historyStmt);
        $historyResult = mysqli_stmt_get_result($historyStmt);
        
        echo '<div class="report-details">';
        
        echo '<div class="detail-group">
                <h6><i class="fas fa-info-circle me-2"></i>Report Information</h6>
                <p><strong>ID:</strong> #'.htmlspecialchars($report['report_id']).'</p>
                <p><strong>Status:</strong> <span class="badge status-'.strtolower(str_replace(' ', '-', $report['status'])).'">'.htmlspecialchars($report['status']).'</span></p>
                <p><strong>Priority:</strong> <span class="badge priority-'.strtolower($report['priority']).'">'.htmlspecialchars($report['priority']).'</span></p>
                <p><strong>Category:</strong> '.htmlspecialchars($report['category']).'</p>
                <p><strong>Specific Issue:</strong> '.htmlspecialchars($report['specific_issue']).'</p>
                <p><strong>Created:</strong> '.date('M d, Y H:i', strtotime($report['created_at'])).'</p>
              </div>';
     
        echo '<div class="detail-group">
                <h6><i class="fas fa-user me-2"></i>User Information</h6>
                <p><strong>User ID:</strong> '.htmlspecialchars($report['user_id']).'</p>
                <p><strong>Resident ID:</strong> '.htmlspecialchars($report['user_id']).'</p>
              </div>';
        
    
        echo '<div class="detail-group">
                <h6><i class="fas fa-map-marker-alt me-2"></i>Location</h6>
                <p>'.htmlspecialchars($report['location']).'</p>
              </div>';
        
        echo '<div class="detail-group full-width">
                <h6><i class="fas fa-align-left me-2"></i>Full Description</h6>
                <div class="card card-body bg-light">
                  '.nl2br(htmlspecialchars($report['description'])).'
                </div>
              </div>';
       
        $imageFields = ['image_url_1', 'image_url_2', 'image_url_3', 'image_url_4'];
        $hasImages = false;
       
        foreach ($imageFields as $field) {
            if (!empty($report[$field]) && $report[$field] !== 'NULL') {
                $hasImages = true;
                break;
            }
        }
        
        if ($hasImages) {
            echo '<div class="detail-group full-width">
                    <h6><i class="fas fa-images me-2"></i>Attachments</h6>
                    <div class="d-flex flex-wrap gap-3">';
            
            foreach ($imageFields as $field) {
                if (!empty($report[$field]) && $report[$field] !== 'NULL') {
                    
                    if (is_string($report[$field]) && strpos($report[$field], 'BLOB') !== false) {
                      
                        echo '<div class="alert alert-warning">BLOB image detected (needs proper retrieval)</div>';
                    } else {
                        
                        $imageData = $report[$field];
                        
                        if (base64_encode(base64_decode($imageData)) === $imageData) {
                           
                            echo '<img src="data:image/jpeg;base64,'.$imageData.'" class="report-image img-thumbnail">';
                        } else {
                            
                            echo '<img src="data:image/jpeg;base64,'.base64_encode($imageData).'" class="report-image img-thumbnail">';
                        }
                    }
                }
            }
            
            echo '</div></div>';
        } else {
            echo '<div class="detail-group full-width">
                    <h6><i class="fas fa-images me-2"></i>Attachments</h6>
                    <p>No images attached to this report</p>
                  </div>';
        }
    
        if(mysqli_num_rows($historyResult) > 0) {
            echo '<div class="detail-group full-width">
                    <h6><i class="fas fa-history me-2"></i>Status History</h6>
                    <div class="timeline">';
            
            while($history = mysqli_fetch_assoc($historyResult)) {
                echo '<div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-date">'.date('M d, Y H:i', strtotime($history['changed_at'])).'</div>
                        <div class="timeline-content">
                          <span class="badge status-'.strtolower(str_replace(' ', '-', $history['status'])).'">'.htmlspecialchars($history['status']).'</span>
                          <p class="mb-0 mt-1">'.htmlspecialchars($history['notes']).'</p>
                        </div>
                      </div>';
            }
            
            echo '</div></div>';
        }
        
        echo '</div>'; 
    } else {
        echo '<div class="alert alert-warning">Report not found</div>';
    }
} else {
    echo '<div class="alert alert-danger">Invalid request</div>';
}