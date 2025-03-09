<?php
    session_start();
    require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php  include '../inc/Header-dash.php'?>   

    <div class="container">
        <div class="page-content">
            <div class="page-header">
                <h2>Table Details</h2>
                <a href="../tableCrud/createTable.php"><i class="fa fa-plus"></i> Add Table</a>
            </div>
            <div class="table-search-bar">
                <form method="POST" action="#">
                    <div class="serach-input">
                        <input required type="text" id="search" name="search"  placeholder="Enter Table ID, Capacity">
                    </div>
                    <div class="search-btn">
                        <button type="submit">Search</button>
                    </div>
                    <div class="showall-btn">
                        <a href="table-panel.php">Show All</a>
                    </div>
                </form>
            </div>
            <?php
                // Include config file
                require_once "../config.php";
                
                if (isset($_POST['search'])) {
                    if (!empty($_POST['search'])) {
                        $search = $_POST['search'];

                        $sql = "SELECT *
                                FROM Restaurant_Tables
                                WHERE table_id LIKE '%$search%' OR capacity LIKE '%$search%' 
                                ORDER BY table_id;";
                    } else {
                        // Default query to fetch all Restaurant_tables
                        $sql = "SELECT *
                                FROM Restaurant_Tables
                                ORDER BY table_id;";
                    }
                } else {
                    // Default query to fetch all Restaurant_tables
                    $sql = "SELECT *
                            FROM Restaurant_Tables
                            ORDER BY table_id;";
                }

                if($result = mysqli_query($link, $sql)){
                    if(mysqli_num_rows($result) > 0){
                        echo '<table>';
                            echo "<thead>";
                                echo "<tr>";
                                    echo "<th>Table ID</th>";
                                    echo "<th>Capacity</th>";
                                    echo "<th>Availability</th>";
                                    echo "<th>Delete</th>";
                                echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while($row = mysqli_fetch_array($result)){
                                echo "<tr>";
                                    echo "<td>" . $row['table_id'] . "</td>";
                                    echo "<td>" . $row['capacity'] . " Persons </td>";
                                    if ($row['is_available'] == true) {
                                        echo "<td>" . "Yes" . "</td>";
                                    } else {
                                        echo "<td>" . "No" . "</td>";
                                    }
                                
                                    echo "<td>";
                                        $deleteSQL = "DELETE FROM Reservations WHERE reservation_id = '" . $row['table_id'] . "';";
                                        echo '<div class="action-buttons">';
                                            echo '<a class="btn delete" href="../tableCrud/deleteTableVerify.php?id=' . $row['table_id'] . '" 
                                                onclick="return confirm(\'Admin Permissions Required!\n\nAre you sure you want to delete this Table?\n\nThis will alter other modules related to this Table!\')">
                                                Delete
                                            </a>';
                                        echo '</div>';
                                    echo "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";                            
                        echo "</table>";
                        // Free result set
                        mysqli_free_result($result);
                    } else{
                        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close connection
                mysqli_close($link);
            ?>
        </div>
    </div>
<?php  include '../inc/dashFooter.php'?>

