<?php
    session_start();
    require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php
    include '../inc/Header-dash.php';
    require_once '../config.php';
    $query = "SELECT * FROM Kitchen WHERE time_ended IS NULL";
    $result = mysqli_query($link, $query);
?>

<link href="../css/pos.css" rel="stylesheet" />
<meta http-equiv="refresh" content="5">
<style>
    .undo-btn{
        background-color: white;
    }
    .undo-btn th a{
        padding: 5px 10px;
        text-decoration: none;
        color: white;
        background-color: #1E90FF;
        border-radius: 4px;
        font-size: 12px;
        text-align: center;
    }
    .undo-btn th a:hover{
        background-color: var(--light-blue);
    }
</style>
<div class="container">
    <div class="page-content">
        <div class="page-header">
            <h2>Kitchen Orders</h2>
        </div>
        <table>
            <thead>
                <tr class="undo-btn">
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>
                        <a href="../posBackend/kitchenBackend/undo.php?UndoUnshow=true">Undo</a>
                     
                    </th>
                </tr>
                <tr>
                    <th>Kitchen ID</th>
                    <th>Table ID</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Time Submitted</th>
                    <th>Time Ended</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $kitchen_id = $row['kitchen_id'];
                            $table_id = $row['table_id'];
                            $item_id = $row['item_id'];
                            $quantity = $row['quantity'];
                            $time_submitted = $row['time_submitted'];
                            $time_ended = $row['time_ended'];

                            // Get item name from Menu table
                            $itemQuery = "SELECT item_name FROM Menu WHERE item_id = '$item_id'";
                            $itemResult = mysqli_query($link, $itemQuery);
                            $itemRow = mysqli_fetch_assoc($itemResult);
                            $item_name = $itemRow['item_name']??"Deleted";

                            echo '<tr>';
                                echo '<td>' . $kitchen_id . '</td>';
                                echo '<td>' . $table_id . '</td>';
                                echo '<td>' . $item_name . '</td>';
                                echo '<td>' . $quantity . '</td>';
                                echo '<td>' . $time_submitted . '</td>';
                                echo '<td>' . ($time_ended ?: 'Not Ended') . '</td>';
                                echo '<td>';
                                    echo '<div class="action-buttons">';
                                        if (!$time_ended) {
                                            echo '<a class="btn delete" href="../posBackend/kitchenBackend/kitchen-panel-back.php?action=set_time_ended&kitchen_id=' . $kitchen_id . '">Done</a>';
                                        }
                                    echo '</div>';
                                echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="7">No records in the Kitchen table.</td></tr>';
                    }
                ?>
            </tbody>
        </table>
        <br><br><br>
    </div>
</div>


