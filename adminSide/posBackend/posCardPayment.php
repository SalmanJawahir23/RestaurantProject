<?php
session_start();
?>
<?php
require_once '../config.php';
include '../inc/Header-dash.php'; 
$bill_id = $_GET['bill_id'];
$staff_id = $_GET['staff_id'];
$member_id = $_GET['member_id'];
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
    .check-agree td{
        width: 100%;
    }
    .check-agree td div{
        width: 100%;
        display: flex;
        flex-direction: row;
    }

</style>

<div class="container">
    <div class="page-content">
        <div class="side-by-side">
            <div class="left-side-table">
                <div class="page-header">
                    <h2 class="card-title">Bill (Credit Card Payment)</h2>
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
                                            echo '<td>LKR ' . number_format($item_price,2) . '</td>';
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
                            echo "<tr><td><strong>Total:</strong> LKR </td><td>" . number_format($cart_total, 2) . "</td></tr>";
                            echo "<tr><td><strong>Tax (10%):</strong> LKR </td><td>" . number_format($cart_total * $tax, 2) . "</td></tr>";
                            $GRANDTOTAL = $tax * $cart_total + $cart_total;
                            echo "<tr><td><strong>Grand Total:</strong> LKR </td><td>" . number_format($GRANDTOTAL, 2). "</td></tr>";
                        ?>
                    </table>
                </div>
            </div>
        
            <div id="card-payment" class="right-side-table">
                <div class="page-header">
                    <h2>Fill in your card details</h2>
                </div>
                <div class="table-search-bar add-form">
                    <form action="creditCard.php?bill_id=<?php echo $bill_id; ?>" method="post">
                        <table class="add-form-table">
                            <tr class="serach-input">
                                <td class="label">
                                    <label for="cardNameField">Account Holder Name</label>
                                </td>
                                <td>
                                    <input type="text" placeholder="HK Perera" id="cardNameField" name="cardName" class="form-control" required>
                                </td>
                            </tr>
                            <tr class="serach-input">
                                <td class="label">
                                    <label for="cardField">Card Number</label>
                                </td>
                                <td>
                                    <input type="text" id="cardField" name="cardNumber" maxlength="19" minlength="15" class="form-control" placeholder="15 to 19 digits" required>
                                </td>
                            </tr>
                            <tr class="serach-input">
                                <td class="label">
                                    <label for="expiryDate">Expiry Date</label>
                                </td>
                                <td>
                                    <input type="text" id="expiryDate" name="expiryDate" pattern="(0[1-9]|1[0-2])\/[0-9]{4}" maxlength="7" placeholder="MM/YYYY" class="form-control" required>
                                </td>
                            </tr>
                            <tr class="serach-input">
                                <td class="label">
                                    <label for="securityCode">Security Code</label>
                                </td>
                                <td>
                                    <input type="text" id="securityCode" name="securityCode" maxlength="3" class="form-control" placeholder="CCV" pattern="[0-9]{3}" required><br>
                                    <small class="form-text text-muted">Please enter a 3-digit security code.</small>
                                </td>
                            </tr>

                            <!-- Add hidden input fields for bill_id, staff_id, member_id, and reservation_id -->
                            <input type="hidden" name="bill_id" value="<?php echo $bill_id; ?>">
                            <input type="hidden" name="staff_id" value="<?php echo $staff_id; ?>">
                            <input type="hidden" name="member_id" value="<?php echo $member_id; ?>">
                            <input type="hidden" name="reservation_id" value="<?php echo $reservation_id; ?>">
                            <input type="hidden" name="GRANDTOTAL" value="<?php echo $tax * $cart_total + $cart_total; ?>">

                            <tr class="serach-input check-agree">
                                <td class="label" colspan="2">
                                    <div>
                                        <input type="checkbox" class="form-check-input" id="privacyCheckbox" required>
                                    
                                        <div>
                                            <em><label class="form-check-label" for="privacyCheckbox">I agree to the Private Data Terms and Conditions</label><br>
                                            <small id="privacyHelp" class="form-text text-muted">By checking the box you understand we will save your credit card information.</small></em>
                                        </div>
                                    </div>
                                    
                                </td>
                            </tr>
                            <tr class="search-btn">
                                <td></td>
                                <td>
                                    <button type="submit" id="cardSubmit" class="btn edit">Pay</button>
                                </td>
                            </tr>
                            
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include '../inc/dashFooter.php'; ?>

         