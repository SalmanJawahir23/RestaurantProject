<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Seating</title>
    <link rel="stylesheet" href="../css/header-main.css">
</head>
<style>
    
    .container div{
        width: 300px;
        padding: 30px;
        height: fit-content;
        margin: 30px auto;
        border-radius: 8px;
        box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
    }
    h2, p, a{
        margin-top: 20px;
    }
    .btn{
        padding: 10px 15px;
    }
</style>
<body>

<div class="container">
    <div style="text-align: center; margin: auto; width: 300px;">
        <?php
            require_once '../config.php';

            if (isset($_GET['new_customer']) && $_GET['new_customer'] === 'true') {
                $table_id = $_GET['table_id'];
                $bill_time = date('Y-m-d H:i:s');
                $insertQuery = "INSERT INTO Bills (table_id, bill_time) VALUES ('$table_id', '$bill_time')";

                if ($link->query($insertQuery) === TRUE) {
                    $bill_id = $link->insert_id;
                    echo "<h2 style='text-align: center;'>Central Ceylon</h2>";
                    echo "<p>You're now seated at Table ID: $table_id</p>";
                    echo "<p>Your bill has been created with Bill ID: $bill_id</p>";
                    echo '<a href="orderItem.php?bill_id=' . $bill_id . '&table_id=' . $table_id . '" class="btn edit">Back</a>';
                } else {
                    echo "<div class='alert danger'>Error inserting data into Bills table: " . $link->error . "</div>";
                }
            }
        ?>
    </div>
</div>

</body>
</html>
