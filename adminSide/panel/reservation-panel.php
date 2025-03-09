<?php
    session_start();
    require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php include '../inc/Header-dash.php'; ?>

<div class="container">
    <div class="page-content">
        <div class="page-header">
            <h2>Reservation Details</h2>
            <a href="../reservationsCrud/createReservation.php"><i class="fa fa-plus"></i> Add Reservation</a>
        </div>
        <div class="table-search-bar">
            <form method="POST" action="#">
                <div class="serach-input">
                    <input required type="text" id="search" name="search" placeholder="Enter Reservation ID, Customer Name, Reservation Date (2023-09)">
                </div>
                <div class="search-btn">
                    <button type="submit">Search</button>
                </div>
                <div class="showall-btn">
                    <a href="reservation-panel.php">Show All</a>
                </div>
            </form>
        </div>       
        <?php
            // Include config file
            require_once "../config.php";
            $sql = "SELECT * FROM reservations ORDER BY reservation_id;";

            if (isset($_POST['search'])) {
                if (!empty($_POST['search'])) {
                    $search = $_POST['search'];

                    $sql = "SELECT * FROM reservations WHERE reservation_date LIKE '%$search%' OR reservation_id LIKE '%$search%' OR customer_name LIKE '%$search%'";
                } else {
                    // Default query to fetch all reservations
                    $sql = "SELECT * FROM reservations ORDER BY reservation_date DESC, reservation_time DESC;";
                }
            } else{
                $sql = "SELECT * FROM reservations ORDER BY reservation_date DESC, reservation_time DESC;";
            }
            
            if ($result = mysqli_query($link, $sql)) {
                if (mysqli_num_rows($result) > 0) {
                    echo '<table>';
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th>Reservation ID</th>";
                                echo "<th>Customer Name</th>";
                                echo "<th>Table ID</th>";
                                echo "<th>Reservation Time</th>";
                                echo "<th>Reservation Date</th>";
                                echo "<th>Head Count</th>";
                                echo "<th>Special Request</th>";
                                echo "<th>Option</th>";
                                // echo "<th>Receipt</th>";
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                    echo "<td>" . $row['reservation_id'] . "</td>";
                                    echo "<td>" . $row['customer_name'] . "</td>";
                                    echo "<td>" . $row['table_id'] . "</td>";
                                    echo "<td>" . $row['reservation_time'] . "</td>";
                                    echo "<td>" . $row['reservation_date'] . "</td>";
                                    echo "<td>" . $row['head_count'] . "</td>";
                                    echo "<td>" . $row['special_request'] . "</td>";
                                    echo "<td>";
                                        echo '<div class="action-buttons">';
                                            echo '<a class="btn delete" href="../reservationsCrud/deleteReservationVerify.php?id='. $row['reservation_id'] .'"
                                            onclick="return confirm(\'Admin permission Required!\n\nAre you sure you want to delete this Reservation?\n\nThis will alter other modules related to this Reservation!\n\')">
                                            Delete
                                            </a>';
                                            echo '<a class="btn view" href="../reservationsCrud/reservationReceipt.php?reservation_id='. $row['reservation_id'] .'">
                                            Reciept
                                            </a>';
                                        echo '</div>';
                                    echo "</td>";
                                echo "</tr>";
                            }
                        echo "</tbody>";
                    echo "</table>";
                } else {
                    echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close connection
            mysqli_close($link);
        ?>
    </div>
</div>

<?php include '../inc/dashFooter.php'; ?>
