<?php
    session_start();
?>
<?php  include '../inc/Header-dash.php'?>

<?php
    require_once '../config.php';

    $reservationStatus = $_GET['reservation'] ?? null;
    $message = '';
    if ($reservationStatus === 'success') {
        $message = "Reservation successful";
    }
    $head_count = $_GET['head_count'] ?? 1;
?>

 
<style>
    .table-search-bar form{
        flex-direction: column;
        align-items: start;
    }
    .no-table .serach-input{
        display: flex;
        flex-direction: row;
        gap: 20px;
    }
</style>

<div class="container">
    <div class="page-content">

        <div class="page-header">
            <h2>Search for Available Time</h2>
        </div>
        
        <div class="table-search-bar add-form">
            <form id="reservation-form" method="GET" action="availability.php">
                <table class="add-form-table">
                    <tr class="serach-input">
                        <td class="label">
                            <label for="reservation_date" >Select Date</label>
                        </td>
                        <td>
                            <input class="form-control" type="date" id="reservation_date" name="reservation_date" required>
                        </td>
                    </tr>
                    
                    <tr class="serach-input">
                        <td class="label">
                            <label for="reservation_time" >Available Reservation Times</label>
                        </td>
                        <td>
                            <?php
                                $availableTimes = array();
                                for ($hour = 10; $hour <= 20; $hour++) {
                                    for ($minute = 0; $minute < 60; $minute += 60) {
                                        $time = sprintf('%02d:%02d:00', $hour, $minute);
                                        $availableTimes[] = $time;
                                    }
                                }
                                echo '<select name="reservation_time" id="reservation_time" class="form-control" >';
                                    echo '<option value="" selected disabled>Select a Time</option>';
                                    foreach ($availableTimes as $time) {
                                        echo "<option  value='$time'>$time</option>";
                                    }
                                echo '</select>';
                                if (isset($_GET['message'])) {
                                    $message = $_GET['message'];
                                    echo "<p>$message</p>";
                                }
                            ?>
                        </td>
                    </tr>

                    <input type="number" id="head_count" name="head_count" value=1 hidden required>

                    <tr>
                        <td></td>
                        <td>
                            <div class="search-btn">
                                <button type="submit" name="submit">Search Available</button>
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <hr><hr><br><br>
        <!-- AFTER SEARCH -->
        <div>
            <h2>Make the Reservation</h2>
            <br><br>
        </div>
        
        <div class="table-search-bar">
            <form class="no-table" id="reservation-form" method="POST" action="insertReservation.php">
                
                <div class="serach-input">
                    <label for="customer_name">Customer Name: </label>
                    <input type="text" id="customer_name" name="customer_name" class="form-control" required placeholder="Perera">
                </div>
                <?php
                    $defaultReservationDate = $_GET['reservation_date'] ?? date("Y-m-d");
                    $defaultReservationTime = $_GET['reservation_time'] ?? "13:00:00";
                ?>
                
                <div class="serach-input">
                    <label for="reservation_date">Reservation Date & Time: </label><br>
                    <input type="date" id="reservation_date" name="reservation_date" value="<?= $defaultReservationDate ?>" readonly required>
                    <input type="time" id="reservation_time" name="reservation_time" value="<?= $defaultReservationTime ?>" readonly required>
                </div>

                <div class="serach-input">
                    <label for="table_id_reserve">Pick a Table: </label>
                    <select class="form-control" name="table_id" id="table_id_reserve" required>
                        <option value="" selected disabled>Select a Table</option>
                        <?php
                        $table_id_list = $_GET['reserved_table_id'];
                        $head_count = $_GET['head_count'] ?? 1;
                        $reserved_table_ids = explode(',', $table_id_list);
                        $select_query_tables = "SELECT * FROM restaurant_tables WHERE capacity >= '$head_count'";
                        if (!empty($reserved_table_ids)) {
                            $reserved_table_ids_string = implode(',', $reserved_table_ids);
                            $select_query_tables .= " AND table_id NOT IN ($reserved_table_ids_string)";
                        }
                        $result_tables = mysqli_query($link, $select_query_tables);
                        $resultCheckTables = mysqli_num_rows($result_tables);
                        if ($resultCheckTables > 0) {
                            while ($row = mysqli_fetch_assoc($result_tables)) {
                                echo '<option value="' . $row['table_id'] . '">For ' . $row['capacity'] . ' people. (Table Id: ' . $row['table_id'] . ')</option>';
                            }
                        }  else {
                            echo '<option disabled>No tables available, please choose another time.</option>';
                            echo '<script>alert("No reservation tables found for the selected time. Please choose another time.");</script>';
                        }
                        ?>
                    </select>
                    <input type="number" id="head_count" name="head_count" value="<?= $head_count ?>" required hidden>
                </div>
                
                <div class="serach-input" >
                    <label for="special_request" >Special request:</label><br>
                    <input type="text" id="special_request" name="special_request" class="ht-600 w-50" placeholder="One baby chair"><br>
                </div>
                
                <div class="search-btn">
                    <button type="submit" name="submit">Make Reservation</button>
                </div>                  
            </form>
            <br><br><br>
        </div>
        

    </div>
</div>

<script>
    const viewDateInput = document.getElementById("reservation_date");
    const makeDateInput = document.getElementById("reservation_date");

    viewDateInput.addEventListener("change", function () {
        makeDateInput.value = this.value;
    });
</script>

<?php include '../inc/dashFooter.php'; ?>
