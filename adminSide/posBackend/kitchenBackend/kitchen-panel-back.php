<?php
require_once '../../config.php';

// Handle setting time_ended and undo actions
if (isset($_GET['action']) && isset($_GET['kitchen_id'])) {
    $action = $_GET['action'];
    $kitchen_id = $_GET['kitchen_id'];
    
    if ($action === 'set_time_ended') {
        $currentTime = date('Y-m-d H:i:s');
        $updateQuery = "UPDATE Kitchen SET time_ended = '$currentTime' WHERE kitchen_id = $kitchen_id";
        if ($link->query($updateQuery) === TRUE) {
            header("Location: ../../panel/kitchen-panel.php"); // Redirect
        } else {
            // Error updating time_ended
            echo "Error updating time_ended: " . $link->error;
        }
        
    }
}

?>