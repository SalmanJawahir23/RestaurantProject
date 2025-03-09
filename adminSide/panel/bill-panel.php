<?php
session_start();
require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php include '../inc/Header-dash.php'; ?>

<div class="container">
    <div class="page-content">
        <div class="page-header">
            <h2>Search Bills Details</h2>
        </div>
        <div class="table-search-bar">
            <form method="POST" action="#">
                <div class="serach-input">
                    <input required type="text" id="search" name="search" placeholder="Enter Bill ID, Table ID, Card ID, Payment Method">
                </div>
                <div class="search-btn">
                    <button type="submit">Search</button>
                </div>
                <div class="showall-btn">
                    <a href="bill-panel.php">Show All</a>
                </div>
            </form>
        </div>
        <?php
            // Include config file
            require_once "../config.php";
            
            if (isset($_POST['search'])) {
                if (!empty($_POST['search'])) {
                    $search = $_POST['search'];
                    
                    $sql = "SELECT * FROM Bills WHERE table_id LIKE '%$search%' OR payment_method LIKE '%$search%' OR bill_id LIKE '%$search%' OR card_id LIKE '%$search%'";
                } else {
                    // Default query to fetch all bills
                    $sql = "SELECT * FROM Bills ORDER BY bill_id;";
                }
            } else {
                $sql = "SELECT * FROM Bills ORDER BY bill_id;";
            }
            
            if ($result = mysqli_query($link, $sql)) {
                if (mysqli_num_rows($result) > 0) {
                    echo '<table>';
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th>Bill ID</th>";
                                echo "<th>Staff ID</th>";
                                echo "<th>Member ID</th>";
                                echo "<th>Reservation ID</th>";
                                echo "<th>Table ID</th>";
                                echo "<th>Card ID</th>";
                                echo "<th>Payment Method</th>";
                                echo "<th style='width:13em'>Bill Time</th>";
                                echo "<th style='width:13em'>Payment Time</th>";
                                echo "<th>Update</th>";
                                //echo "<th>Receipt</th>";
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                    echo "<td>" . $row['bill_id'] . "</td>";
                                    echo "<td>" . $row['staff_id'] . "</td>";
                                    echo "<td>" . $row['member_id'] . "</td>";
                                    echo "<td>" . $row['reservation_id'] . "</td>";
                                    echo "<td>" . $row['table_id'] . "</td>";
                                    echo "<td>" . $row['card_id'] . "</td>";
                                    echo "<td>" . $row['payment_method'] . "</td>";
                                    echo "<td>" . $row['bill_time'] . "</td>";
                                    echo "<td>" . $row['payment_time'] . "</td>";
                                    echo "<td>";
                                        echo '<div class="action-buttons">';
                                            echo '<a class="btn delete" href="../billsCrud/deleteBill.php?id='. $row['bill_id'] .'" 
                                                        onclick="return confirm(\'Are you sure you want to delete this bill? This action is unrecoverable. \')">
                                                        Delete
                                                    </a>';
                                                    // echo "</td>";
                                                    // echo "<td>";
                                            echo '<a class="btn view" href="../posBackend/receipt.php?bill_id='. $row['bill_id'] .'">
                                                        Print
                                                    </a>';
                                        echo '</div>';
                                    echo "</td>";
                                echo "</tr>";
                            }
                        echo "</tbody>";
                    echo "</table>";
                    echo "<br><br><br>";
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
