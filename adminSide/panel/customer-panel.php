<?php
    session_start();
    require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php include '../inc/Header-dash.php'; ?>

<div class="container">
    <div class="page-content">
        <div class="page-header">
            <h2>Membership Details</h2>
            <a href="../customerCrud/createCust.php"><i class="fa fa-plus"></i> Add Membership</a>
        </div>
        <div class="table-search-bar">
            <form method="POST" action="#">
                <div class="serach-input">
                    <input required type="text" id="search" name="search"  placeholder="Enter Member ID, Name">
                </div>
                <div class="search-btn">
                    <button type="submit">Search</button>
                </div>
                <div class="showall-btn">
                    <a href="customer-panel.php">Show All</a>
                </div>
            </form>
        </div>
        <?php
            // Include config file
            require_once "../config.php";

            if (isset($_POST['search'])) {
                if (!empty($_POST['search'])) {
                    $search = $_POST['search'];
                    // Modified query to search memberships by member_name or member_id
                    $sql = "SELECT *
                            FROM Memberships AS M
                            INNER JOIN Accounts AS A ON M.account_id = A.account_id
                            WHERE M.member_name LIKE '%$search%' OR M.member_id = '$search'
                            ORDER BY M.member_id";
                    // $sql = "SELECT * FROM Memberships WHERE member_name LIKE '%$search%' OR member_id = '$search'ORDER BY member_id";
                } else {
                    // Default query to fetch all memberships with account information
                    $sql = "SELECT *
                            FROM Memberships M
                            INNER JOIN Accounts A ON M.account_id = A.account_id
                            ORDER BY M.member_id";
                    // $sql = "SELECT * FROM Memberships ORDER BY member_id";
                }
            } else {
                // Default query to fetch all memberships with account information
                $sql = "SELECT *
                        FROM Memberships M
                        INNER JOIN Accounts A ON M.account_id = A.account_id
                        ORDER BY M.member_id";
                // $sql = "SELECT * FROM Memberships ORDER BY member_id";
            }
            if ($result = mysqli_query($link, $sql)) {
                if (mysqli_num_rows($result) > 0) {
                    echo '<table>';
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th>Member Id</th>";
                                echo "<th>Member Name</th>";
                                echo "<th>Points</th>";
                                echo "<th>Account ID</th>";
                                echo "<th>Email</th>";
                                echo "<th>Phone Number</th>";
                                echo "<th>Delete</th>";
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                                echo "<td>" . $row['member_id'] . "</td>";
                                echo "<td>" . $row['member_name'] . "</td>";
                                echo "<td>" . $row['points'] . "</td>";
                                echo "<td>" . $row['account_id'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['phone_number'] . "</td>";
                                echo "<td>";
                                    echo '<div class="action-buttons">';
                                        $deleteSQL = "DELETE FROM Memberships WHERE member_id = '" . $row['member_id'] . "';";
                                        echo '<a class="btn delete" href="../customerCrud/deleteCustomerVerify.php?id=' . $row['member_id'] . '" 
                                                    onclick="return confirm(\'Admin permission Required!\n\nAre you sure you want to delete this Member?\n\nThis will alter other modules related to this Member!\n\')">
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
