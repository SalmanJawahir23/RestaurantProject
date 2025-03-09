<?php
    session_start();
    require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php 
    include '../inc/Header-dash.php'; 
    require_once '../config.php';

    // Get current date
    $currentDate = date('Y-m-d');

    // Calculate total revenue for today
    $totalRevenueTodayQuery = "SELECT SUM(item_price * quantity) AS total_revenue FROM Bill_Items
                            INNER JOIN Menu ON Bill_Items.item_id = Menu.item_id
                            INNER JOIN Bills ON Bill_Items.bill_id = Bills.bill_id
                            WHERE DATE(Bills.bill_time) = '$currentDate'";
    $totalRevenueTodayResult = mysqli_query($link, $totalRevenueTodayQuery);
    $totalRevenueTodayRow = mysqli_fetch_assoc($totalRevenueTodayResult);
    $totalRevenueToday = $totalRevenueTodayRow['total_revenue'];

    // Calculate total revenue for this week
    $currentWeekStart = date('Y-m-d', strtotime('monday this week'));
    $totalRevenueThisWeekQuery = "SELECT SUM(item_price * quantity) AS total_revenue FROM Bill_Items
                                INNER JOIN Menu ON Bill_Items.item_id = Menu.item_id
                                INNER JOIN Bills ON Bill_Items.bill_id = Bills.bill_id
                                WHERE DATE(Bills.bill_time) >= '$currentWeekStart'";
    $totalRevenueThisWeekResult = mysqli_query($link, $totalRevenueThisWeekQuery);
    $totalRevenueThisWeekRow = mysqli_fetch_assoc($totalRevenueThisWeekResult);
    $totalRevenueThisWeek = $totalRevenueThisWeekRow['total_revenue'];

    // Calculate total revenue for this month
    $currentMonthStart = date('Y-m-01');
    $totalRevenueThisMonthQuery = "SELECT SUM(item_price * quantity) AS total_revenue FROM Bill_Items
                                INNER JOIN Menu ON Bill_Items.item_id = Menu.item_id
                                INNER JOIN Bills ON Bill_Items.bill_id = Bills.bill_id
                                WHERE DATE(Bills.bill_time) >= '$currentMonthStart'";
    $totalRevenueThisMonthResult = mysqli_query($link, $totalRevenueThisMonthQuery);
    $totalRevenueThisMonthRow = mysqli_fetch_assoc($totalRevenueThisMonthResult);
    $totalRevenueThisMonth = $totalRevenueThisMonthRow['total_revenue'];
?>

<style>
    .sideByside{
        width: 100%;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 30px;
    }
    .view{
        padding: 10px;
        width: fit-content;
    }
    .statics-charts{
        width: 500px;
    }
</style>


<div class="container"> 
    <div class="page-content">
        <?php
            require_once '../config.php';
            // Calculate total revenue
            $totalRevenueQuery = "SELECT SUM(item_price * quantity) AS total_revenue FROM Bill_Items
                                INNER JOIN Menu ON Bill_Items.item_id = Menu.item_id";
            $totalRevenueResult = mysqli_query($link, $totalRevenueQuery);
            $totalRevenueRow = mysqli_fetch_assoc($totalRevenueResult);
            $totalRevenue = $totalRevenueRow['total_revenue'];
        ?>
        <div class="page-header">
            <h2>Revenue</h2>
        </div>
        <div class="sideByside">
            <div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Metric</th>
                            <th>Amount (LKR)</th>
                        </tr>
                    </thead>
                    <tbody>  
                        <tr>
                            <th>Total Revenue Today</th>
                            <td><?php echo number_format($totalRevenueToday, 2); ?></td>
                        </tr>
                        <tr>
                            <th>Total Revenue This Week</th>
                            <td><?php echo number_format($totalRevenueThisWeek, 2); ?></td>
                        </tr>
                        <tr>
                            <th>Total Revenue This Month</th>
                            <td><?php echo number_format($totalRevenueThisMonth, 2); ?></td>
                        </tr>
                        <tr>
                            <th>Total Revenue</th>
                            <td><?php echo number_format($totalRevenue, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
                <a class="btn view" href="../report/generate_report.php">Print Report</a>
                <br><br><br><br>
            </div>

            <div class="statics-charts">
                <canvas id="myChart" style="width:100%; max-width:500px"></canvas>
                <br><br><br><br>
            </div>
            <div></div>
        </div>        
    </div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<?php
    $currentMonthStart = date('Y-m-01');
    $currentMonthEnd = date('Y-m-t');
    // format 'YYYY-MM'
    $currentMonth = date('Y-m');

    //SQL query to calculate card revenue for the current month
    $cardQuery = "  SELECT
                    IFNULL(SUM(bi.quantity * m.item_price), 0) AS card_revenue
                    FROM
                        Bills b
                    LEFT JOIN
                        Bill_Items bi ON b.bill_id = bi.bill_id
                    LEFT JOIN
                        Menu m ON bi.item_id = m.item_id
                    WHERE
                        b.payment_method LIKE 'Card'
                        AND b.bill_time BETWEEN '$currentMonthStart 00:00:00' AND '$currentMonthEnd 23:59:59';  ";

    //SQL query to calculate cash revenue for the current month
    $cashQuery = "  SELECT
                    IFNULL(SUM(bi.quantity * m.item_price), 0) AS cash_revenue
                    FROM
                        Bills b
                    LEFT JOIN
                        Bill_Items bi ON b.bill_id = bi.bill_id
                    LEFT JOIN
                        Menu m ON bi.item_id = m.item_id
                    WHERE
                        b.payment_method LIKE 'Cash'
                        AND b.bill_time BETWEEN '$currentMonthStart 00:00:00' AND '$currentMonthEnd 23:59:59';  ";

    $cardResult = $link->query($cardQuery);
    $cashResult = $link->query($cashQuery);

    if ($cardResult->num_rows > 0) {
        $cardRow = $cardResult->fetch_assoc();
        $cardRevenue = $cardRow['card_revenue'];
    } else {
        $cardRevenue = 0;
    }

    if ($cashResult->num_rows > 0) {
        $cashRow = $cashResult->fetch_assoc();
        $cashRevenue = $cashRow['cash_revenue'];
    } else {
        $cashRevenue = 0;
    }
?>

<!--chrt.js charts --------------------------------------------------------------------------->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

<script>
    const xValues = ["Card", "Cash", "Total"];
    const yValues = [<?php echo $cardRevenue; ?>, <?php echo $cashRevenue; ?>, <?php echo $cashRevenue + $cardRevenue; ?>, 0];
    const barColors = ['#4CAF50', '#FFC107', '#FF5722'];

    new Chart("myChart", {
        type: "bar",
        data: {
            labels: xValues,
            datasets: [{
                backgroundColor: barColors,
                data: yValues
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            legend: {
                display: false
            },
            title: {
                display: true,
                text: "Revenue Generated - <?php echo date('F Y'); ?>"
            }
        }
    });
</script>
<!--chrt.js charts --------------------------------------------------------------------------->
