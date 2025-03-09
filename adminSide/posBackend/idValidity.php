<?php 
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Check Staff Member Reservation Validity</title>
    <link rel="stylesheet" href="../css/header-main.css">
    <style>
        .page-content{
            width: 100vw;
            height: 100vh;
        }
        .section-div{
            margin: 40px auto;
            padding: 20px;
            width: fit-content;
            box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
        }
        .page-header{
            margin: auto;
            text-align: center;
            margin-bottom: 20px;
        }
        .btn{
            padding: 13px;
            border-radius: 8px;
            width: 100px;
        }
        .link{
            text-decoration: double;
            color: blueviolet;
        }
        .link:hover{
            text-decoration: double;
            color: blue;
        }
        .alert{
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
        }
        .alert table{
            box-shadow: none;
        }
        .alert table td{
            text-align: center;
            background: #fff;
            border: none;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="page-content">
        <div class="section-div">
            <div class="page-header">
                <h2 class="text-center">Check Staff Member Reservation Validity</h2>
            </div>
            <div class="table-search-bar add-form">
                <form action="" method="post">
                    <table class="add-form-table">
                        <tr class="serach-input">
                            <?php
                                $currentStaffId = $_SESSION['logged_staff_id'] ?? "Please Login"; 
                            ?>
                            <td class="label">
                                <label for="staffId">Staff ID:</label>
                            </td>
                            <td>
                                <input type="text" id="staffId" name="staffId" class="form-control" value="<?= htmlspecialchars($currentStaffId) ?>" readonly required>
                            </td>
                        </tr>
                        <tr class="serach-input">
                            <td class="label">
                                <label for="memberId">Member ID:</label>
                            </td>
                            <td>
                                <input type="text" id="memberId" name="memberId" placeholder="If have any" class="form-control">
                            </td>
                        </tr>
                        <tr class="serach-input">
                            <td class="label">
                                <label for="reservationId">Reservation ID:</label>
                            </td>
                            <td>
                                <input type="text" id="reservationId" placeholder="If have any" name="reservationId" class="form-control">
                            </td>
                        </tr>
                        <tr class="search-btn">
                            <td>
                                <div>
                                    <button type="submit">Check Validity</button>
                                </div>
                            </td>
                            <td>
                                <a class="btn delete" href="javascript:window.history.back();">Cancel</a>
                                <a class="btn link" href="posTable.php">Tables Page</a>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>

            <div class="alert">
                <?php
                    require_once('../config.php');

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $staffId = mysqli_real_escape_string($link, $_POST['staffId']);
                        $memberId = !empty($_POST['memberId']) ? mysqli_real_escape_string($link, $_POST['memberId']) : 1;
                        $reservationId = !empty($_POST['reservationId']) ? mysqli_real_escape_string($link, $_POST['reservationId']) : 1111111;
                        $bill_id = isset($_GET['bill_id']) ? htmlspecialchars($_GET['bill_id']) : null;

                        $errors = [];

                        // Check if the staff ID exists in the database
                        $stmt = $link->prepare("SELECT * FROM Staffs WHERE staff_id = ?");
                        $stmt->bind_param("s", $staffId);
                        $stmt->execute();
                        $staffResult = $stmt->get_result();
                        $staffExists = $staffResult->num_rows > 0;

                        if (!$staffExists) {
                            $errors[] = "Invalid Staff ID.";
                        }

                        $memberExists = true; // Assume valid if ID is not provided
                        if (!empty($memberId)) {
                            $stmt = $link->prepare("SELECT * FROM Memberships WHERE member_id = ?");
                            $stmt->bind_param("s", $memberId);
                            $stmt->execute();
                            $memberResult = $stmt->get_result();
                            $memberExists = $memberResult->num_rows > 0;

                            if (!$memberExists) {
                                $errors[] = "Invalid Member ID.";
                            }
                        }

                        $reservationExists = true; // Assume valid if ID is not provided
                        if (!empty($reservationId)) {
                            $stmt = $link->prepare("SELECT * FROM Reservations WHERE reservation_id = ?");
                            $stmt->bind_param("s", $reservationId);
                            $stmt->execute();
                            $reservationResult = $stmt->get_result();
                            $reservationExists = $reservationResult->num_rows > 0;

                            if (!$reservationExists) {
                                $errors[] = "Invalid Reservation ID.";
                            }
                        }

                        if (empty($errors) && $staffExists && $memberExists && $reservationExists) {
                            echo '<div>';
                                    echo '<table>';
                                            echo '<tr>';
                                                    echo '<td colspan="2">';
                                                            echo "Cash Or Card";
                                                    echo '</td>';
                                                    echo '<td>';
                                                    echo '</td>';
                                            echo '</tr>';
                                            echo '<tr>';
                                                    echo '<td>';
                                                            echo '<a class="btn view" href="posCashPayment.php?bill_id=' . urlencode($bill_id) . '&staff_id=' . urlencode($staffId) . '&member_id=' . urlencode($memberId) . '&reservation_id=' . urlencode($reservationId) . '">Cash</a>';
                                                    echo '</td>';
                                                    echo '<td>';
                                                            echo '<a class="btn edit" href="posCardPayment.php?bill_id=' . urlencode($bill_id) . '&staff_id=' . urlencode($staffId) . '&member_id=' . urlencode($memberId) . '&reservation_id=' . urlencode($reservationId) . '">Credit Card</a>';
                                                    echo '</td>';
                                            echo '</tr>';
                                    echo '</table>';
                            echo '</div>';
                        } else {
                            foreach ($errors as $error) {
                                echo "<p style='color: red; text-align: center;'>$error</p>";
                            }
                        }

                        $stmt->close();
                    }
                ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>
