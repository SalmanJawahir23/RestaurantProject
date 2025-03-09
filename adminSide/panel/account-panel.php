<?php
    session_start();
    require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php include '../inc/Header-dash.php'; ?>
<style>
    .add-btn{
        float: right;
        margin-block: 10px;
    }
</style>

<div class="container">
    <div class="page-content">
        <div class="row">
            <div class="m-50">
                <div class="page-header">
                    <h2>Account Details</h2>
                    <div class="add-btn">
                        <a href="../staffCrud/createStaff.php"><i class="fa fa-plus"></i> Add Staff</a>
                        <a href="../customerCrud/createCust.php"><i class="fa fa-plus"></i> Add Memberships</a>
                    </div>                    
                </div>  
                <div class="table-search-bar">
                    <form method="POST" action="#">
                        <div class="serach-input">
                            <input required type="text" id="search" name="search" placeholder="Enter Account ID, Email">
                        </div>
                        <div class="search-btn">
                            <button type="submit">Search</button>
                        </div>
                        <div class="showall-btn">
                            <a href="account-panel.php">Show All</a>
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
                                    FROM Accounts
                                    WHERE email LIKE '%$search%' OR account_id LIKE '%$search%'
                                    ORDER BY account_id;";
                        } else {
                            // Default query to fetch all accounts
                            $sql = "SELECT *
                                    FROM Accounts
                                    ORDER BY account_id;";
                        }
                    } else {
                        // Default query to fetch all accounts
                        $sql = "SELECT *
                                FROM Accounts
                                ORDER BY account_id;";
                    }

                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            echo '<table>';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Account ID</th>";
                                        echo "<th>Email</th>";
                                        echo "<th>Register Date</th>";
                                        echo "<th>Phone Number</th>";
                                        echo "<th>Password</th>";
                                        //echo "<th>Account Type</th>"; // Display account type
                                        echo "<th>Delete</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<tr>";
                                        echo "<td>" . $row['account_id'] . "</td>";
                                        echo "<td>" . $row['email'] . "</td>";
                                        echo "<td>" . $row['register_date'] . "</td>";
                                        echo "<td>" . $row['phone_number'] . "</td>";
                                        echo "<td>" . $row['password'] . "</td>";
                                        //echo "<td>" . ucfirst($row['account_type']) . "</td>"; // Display account type
                                        echo "<td>";
                                            echo '<div class="action-buttons">';
                                                $deleteSQL = "DELETE FROM Accounts WHERE account_id = '" . $row['account_id'] . "';";
                                                echo '<a class="btn delete" href="../accountCrud/deleteAccountVerify.php?id=' . $row['account_id'] . '"
                                                            onclick="return confirm(\'Admin permission Required!\n\nAre you sure you want to delete this Account?\n\nThis will alter other modules related to this Account!\n\')">
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
    </div>
</div>

<?php include '../inc/dashFooter.php'; ?>
