<?php
session_start();
?>
<?php
    require_once '../config.php';
    include '../inc/Header-dash.php'; 
    $bill_id = $_GET['bill_id'];
    $staff_id = $_GET['staff_id'];
    $member_id = intval($_GET['member_id']);
    $reservation_id = $_GET['reservation_id'];
?>
<link rel="stylesheet" href="../css/header-main.css">
<style>
    .btn{
        width: 100px;
        margin: 3px;
    }
    .edit{
        width: 60px;
    }       
    .side-by-side {
        display: flex;
        flex-direction: row;
        gap: 10px;
    }

    button {
        border: none;
        outline: none;
    }
</style>

<div class="container">
    <div class="page-content">
        <div class="side-by-side">
            <div class="left-side-table">
                <div class="page-header">
                    <h2>Bill (Cash Payment)</h2>
                    <h5>Bill ID: <?php echo $bill_id; ?></h5>
                </div>
                <div class="table-search-bar">
                    <table>
                        <thead>
                            <tr>
                                <th>Item ID</th>
                                <th>Item Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // Query to fetch cart items for the given bill_id
                                $cart_query = "SELECT bi.*, m.item_name, m.item_price FROM bill_items bi
                                            JOIN Menu m ON bi.item_id = m.item_id
                                            WHERE bi.bill_id = '$bill_id'";
                                $cart_result = mysqli_query($link, $cart_query);
                                $cart_total = 0;
                                $tax = 0.1; 

                                if ($cart_result && mysqli_num_rows($cart_result) > 0) {
                                    while ($cart_row = mysqli_fetch_assoc($cart_result)) {
                                        $item_id = $cart_row['item_id'];
                                        $item_name = $cart_row['item_name'];
                                        $item_price = $cart_row['item_price'];
                                        $quantity = $cart_row['quantity'];
                                        $total = $item_price * $quantity;
                                        $bill_item_id = $cart_row['bill_item_id'];
                                        $cart_total += $total;
                                        echo '<tr>';
                                            echo '<td>' . $item_id . '</td>';
                                            echo '<td>' . $item_name . '</td>';
                                            echo '<td>LKR ' . $item_price . '</td>';
                                            echo '<td>' . $quantity . '</td>';
                                            echo '<td>LKR ' . number_format($total,2) . '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr>
                                            <td colspan="6">No Items in Cart.</td>
                                            </tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="table-search-bar">
                    <table class="add-form-table">
                        <?php 
                            echo "<tr><td><strong>Total:</strong> LKR </td><td>" . number_format($cart_total, 2) . "</td><tr>";
                            echo "<tr><td><strong>Tax (10%):</strong> LKR </td><td>" . number_format($cart_total * $tax, 2) . "</td><tr>";
                            $GRANDTOTAL = $tax * $cart_total + $cart_total;
                            echo "<tr><td><strong>Grand Total:</strong> LKR </td><td>" . number_format($GRANDTOTAL, 2). "</td><tr>";
                        ?>
                    </table>
                </div>
            </div>

            <div id="cash-payment" class="right-side-table">
                <div class="page-header">
                    <h2>Cash Payment</h2>
                </div>
                <div class="table-search-bar add-form">
                    <form action="" method="get">
                        <table class="add-form-table">
                            <tr class="serach-input">
                                <td class="label">
                                    <label for="payment_amount">Payment Amount:</label>
                                </td>
                                <td>
                                    <input type="number" placeholder="LKR." min="0" id="payment_amount" name="payment_amount" class="form-control" required>
                                </td>
                            </tr>
                            <tr class="search-btn">
                                <td>
                                    <!-- Add hidden input fields for bill_id, staff_id, member_id, and reservation_id -->
                                    <input type="hidden" name="bill_id" value="<?php echo $bill_id; ?>">
                                    <input type="hidden" name="staff_id" value="<?php echo $staff_id; ?>">
                                    <input type="hidden" name="member_id" value="<?php echo $member_id; ?>">
                                    <input type="hidden" name="reservation_id" value="<?php echo $reservation_id; ?>">
                                    <input type="hidden" name="GRANDTOTAL" value="<?php echo $tax * $cart_total + $cart_total; ?>">
                                </td>
                                <td>
                                    <button type="submit" id="cardSubmit" class="btn edit">Pay</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                    <div>
                        <?php
                            function calculateChange(float $paymentAmount, float $GrandTotal) {
                                return $paymentAmount - $GrandTotal;
                            }

                            if (isset($_GET['payment_amount'])) {
                                $payment_amount = isset($_GET['payment_amount']) ? floatval($_GET['payment_amount']) : 0.0;
                                $billCheckQuery = "SELECT payment_time FROM Bills WHERE bill_id = $bill_id";
                                $billCheckResult = $link->query($billCheckQuery);

                                if ($billCheckResult) {
                                    if ($billCheckResult->num_rows > 0) {
                                        $billRow = $billCheckResult->fetch_assoc();
                                        if ($billRow['payment_time'] !== null) {
                                            echo '<div class="alert warning" style="width:100%;">';
                                                echo "Bill with ID $bill_id has already been paid.
                                                    </div><br>";
                                            echo '<br><a href="posTable.php" class="btn delete">Back to Tables</a><br>';
                                            echo '<a href="receipt.php?bill_id=' . $bill_id . '&payment_amount=' . $payment_amount . '" class="btn view">Print Receipt</a><br><br>';
                                            exit;
                                        }
                                    }
                                } else {
                                    echo "Error checking bill: " . $link->error;
                                    exit;
                                }

                                if ($payment_amount >= $GRANDTOTAL) {
                                    echo '<div class="alert">';
                                        echo "Balance is LKR " . number_format(calculateChange($payment_amount, $GRANDTOTAL),2);
                                    echo '</div><br>';

                                    // Update the payment method, bill time, and other details in the Bills table
                                    $currentTime = date('Y-m-d H:i:s');
                                    $updateQuery = "UPDATE Bills SET payment_method = 'cash', payment_time = '$currentTime',
                                                    staff_id = $staff_id, member_id = $member_id, reservation_id = $reservation_id
                                                    WHERE bill_id = $bill_id;";
                                    
                                    // Update member points if member_id is not empty

                                    $points = intval($GRANDTOTAL);
                                    if ($link->query($updateQuery) === TRUE) {
                                        $update_points_sql = "UPDATE Memberships SET points = points + $points WHERE member_id = $member_id;";
                                        $link->query($update_points_sql);
                                        echo '<div class="alert success">
                                                Bill successfully Paid!
                                                </div>';
                                        echo '<br><a href="posTable.php" class="btn delete">Back to Tables</a><br>';
                                        echo '<a href="receipt.php?bill_id=' . $bill_id . '&payment_amount=' . $payment_amount . '" class="btn view">Print Receipt</a>';
                                    } else {
                                        echo "Error updating bill: " . $link->error;
                                    }
                                } else {
                                    echo '<div class="alert warning">
                                            Payment amount is not sufficient
                                            </div>';
                                    echo '<br><a href="posTable.php" class="btn delete">Back to Tables</a>';
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../inc/dashFooter.php'; ?>
