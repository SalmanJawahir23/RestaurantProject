<?php
    session_start();
    require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php include '../inc/Header-dash.php'; ?>
<style>
    .item-description{
        max-width: 200px;
        word-wrap: break-word;
    }
</style>

<div class="container">
    <div class="page-content">
        <div class="page-header">
            <h2>Items Details</h2>
            <a href="../menuCrud/createItem.php"><i class="fa fa-plus"></i> Add Item</a>
        </div>
        <div class="table-search-bar">
            <form method="POST" action="#">
                <div class="serach-input">
                    <input required type="text" id="search" name="search"  placeholder="Enter Item">
                </div>
                <div class="search-btn">
                    <button type="submit">Search</button>
                </div>
                <div class="showall-btn">
                    <a href="menu-panel.php">Show All</a>
                </div>
            </form>
        </div>
        <?php
            // Include config file
            require_once "../config.php";

            if (isset($_POST['search'])) {
                if (!empty($_POST['search'])) {
                    $search = $_POST['search'];

                    $sql = "SELECT * FROM Menu WHERE item_type LIKE '%$search%' OR item_category LIKE '%$search%' OR item_name LIKE '%$search%' OR item_id LIKE '%$search%' ORDER BY item_id;";
                } else {
                    // query to fetch all items
                    $sql = "SELECT * FROM Menu ORDER BY item_id;";
                }
            } else {
                // query to fetch all items
                $sql = "SELECT * FROM Menu ORDER BY item_id;";
            }

            if ($result = mysqli_query($link, $sql)) {
                if (mysqli_num_rows($result) > 0) {
                    echo '<table>';
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th>Item ID</th>";
                                echo "<th>Name</th>";
                                echo "<th>Type</th>";
                                echo "<th>Category</th>";
                                echo "<th>Price</th>";
                                echo "<th>Description</th>";
                                echo "<th>Update</th>";
                                //echo "<th>Delete</th>";
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                                echo "<td>" . $row['item_id'] . "</td>";
                                echo "<td>" . $row['item_name'] . "</td>";
                                echo "<td>" . $row['item_type'] . "</td>";
                                echo "<td>" . $row['item_category'] . "</td>";
                                echo "<td>LKR " . $row['item_price'] . "</td>";
                                echo "<td class='item-description'>" . $row['item_description'] . "</td>";
                                echo "<td>";
                                    // Modify link with the pencil icon
                                    
                                    echo '<div class="action-buttons">';
                                        $update_sql = "UPDATE Menu SET item_name=?, item_type=?, item_category=?, item_price=?, item_description=? WHERE item_id=?";
                                        echo '<a class="btn edit" href="../menuCrud/updateItemVerify.php?id='. $row['item_id'] .'" 
                                                    onclick="return confirm(\'Admin permission Required!\n\nAre you sure you want to Edit this Item?\')">
                                                    Edit
                                            </a>';
                                        $deleteSQL = "DELETE FROM items WHERE item_id = '" . $row['item_id'] . "';";
                                        echo '<a class="btn delete" href="../menuCrud/deleteMenuVerify.php?id='. $row['item_id'] .'"                                                
                                                    onclick="return confirm(\'Admin permission Required!\n\nAre you sure you want to delete this Item?\n\nThis will alter other modules related to this Item!\n\nYou see unwanted changes in bills.\')">
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
