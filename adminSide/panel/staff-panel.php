<?php
    session_start();
    require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php include '../inc/Header-dash.php'; ?>

<div class="container">
    <div class="page-content">
        <div class="page-header">
            <h2>Staff Details</h2>
            <a href="../staffCrud/createStaff.php"><i class="fa fa-plus"></i> Add Staff</a>
        </div>
        <div class="table-search-bar">
            <form method="POST" action="#">
                <div class="serach-input">
                    <input required type="text" id="search" name="search" placeholder="Enter Staff ID, Name">
                </div>
                <div class="search-btn">
                    <button type="submit">Search</button>
                </div>
                <div class="showall-btn">
                    <a href="staff-panel.php">Show All</a>
                </div>
            </form>
        </div>
        <?php
            require_once "../config.php";

            if (isset($_POST['search'])) {
                if (!empty($_POST['search'])) {
                    $search = $_POST['search'];
                    // Query to search staff members by staff_name or staff_id
                    $sql = "SELECT *
                            FROM Staffs AS stf
                            INNER JOIN Accounts AS acc ON stf.account_id = acc.account_id
                            WHERE stf.staff_name LIKE '%$search%' OR stf.staff_id = '$search'
                            ORDER BY stf.staff_id";
                    // $sql = "SELECT * FROM Staffs WHERE staff_name LIKE '%$search%' OR staff_id = '$search' ORDER BY account_id";
                } else {
                    // query to fetch all staff members
                    $sql = "SELECT *
                            FROM Staffs AS stf
                            INNER JOIN Accounts AS acc ON stf.account_id = acc.account_id
                            ORDER BY stf.staff_id";                
                    // $sql = "SELECT * FROM Staffs ORDER BY account_id";
                }
            } else {
                //query to fetch all staff members
                $sql = "SELECT *
                        FROM Staffs AS stf
                        INNER JOIN Accounts AS acc ON stf.account_id = acc.account_id
                        ORDER BY stf.staff_id";
                // $sql = "SELECT * FROM Staffs ORDER BY account_id";
            }
            if ($result = mysqli_query($link, $sql)) {
                if (mysqli_num_rows($result) > 0) {
                    echo '<table>';
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th>Staff ID</th>";
                                echo "<th>Staff Name</th>";
                                echo "<th>Role</th>";
                                echo "<th>Account ID</th>";
                                echo "<th>Email</th>";
                                echo "<th>Phone Number</th>";
                                echo "<th>Delete</th>";
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                    echo "<td>" . $row['staff_id'] . "</td>";
                                    echo "<td>" . $row['staff_name'] . "</td>";
                                    echo "<td>" . $row['role'] . "</td>";
                                    echo "<td>" . $row['account_id'] . "</td>";
                                    echo "<td>" . $row['email'] . "</td>";
                                    echo "<td>" . $row['phone_number'] . "</td>";
                                    echo "<td>";
                                        echo '<div class="action-buttons">';
                                            echo '<a class="btn delete" href="../staffCrud/delete_staffVerify.php?id=' . $row['staff_id'] . '" 
                                                        onclick="return confirm(\'Admin permission Required!\n\nAre you sure you want to delete this Staff?\n\nThis will alter other modules related to this Staff!\n\')">
                                                        Delete
                                                    </a>';
                                        echo '</div>';
                                    echo "</td>";
                                echo "</tr>";
                            }
                        echo "</tbody>";
                    echo "</table>";
                    echo'<br><br><br>';
                    // Free result set
                    mysqli_free_result($result);
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
