<?php
require_once "../config.php";

// Check if the reservation_id parameter is set in the URL
if (isset($_GET['id'])) {
    // Get the reservation_id from the URL
    $reservation_id = intval($_GET['id']);

    // Construct the DELETE query with a prepared statement
    $deleteBillItemsSQL = "DELETE FROM bill_items WHERE bill_id IN (SELECT bill_id FROM bills WHERE reservation_id = ?)";
    if ($stmt = mysqli_prepare($link, $deleteBillItemsSQL)) {
        mysqli_stmt_bind_param($stmt, "i", $reservation_id);
        if (mysqli_stmt_execute($stmt)) {
            // Delete the bills
            $deleteBillsSQL = "DELETE FROM bills WHERE reservation_id = ?";
            if ($stmt = mysqli_prepare($link, $deleteBillsSQL)) {
                mysqli_stmt_bind_param($stmt, "i", $reservation_id);
                if (mysqli_stmt_execute($stmt)) {
                    // Delete the reservation
                    $deleteReservationSQL = "DELETE FROM Reservations WHERE reservation_id = ?";
                    if ($stmt = mysqli_prepare($link, $deleteReservationSQL)) {
                        mysqli_stmt_bind_param($stmt, "i", $reservation_id);
                        if (mysqli_stmt_execute($stmt)) {
                            // Redirect back to the main page
                            header("location: ../panel/reservation-panel.php");
                            exit();
                        } else {
                            // Error occurred during reservation deletion
                            echo "Error: " . mysqli_stmt_error($stmt);
                        }
                    }
                } else {
                    // Error occurred during bill deletion
                    echo "Error: " . mysqli_stmt_error($stmt);
                }
            }
        } else {
            // Error occurred during bill items deletion
            echo "Error: " . mysqli_stmt_error($stmt);
        }

        // Close the statements
        mysqli_stmt_close($stmt);
    } else {
        // Error occurred while preparing statements
        echo "Error: " . mysqli_error($link);
    }

    // Close the connection
    mysqli_close($link);
}
?>
