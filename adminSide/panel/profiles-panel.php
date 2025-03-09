<?php
session_start();
require_once '../posBackend/checkIfLoggedIn.php';
include '../inc/Header-dash.php'; 
require_once '../config.php';

?>
<style>
    .sideByside{
        width: 100%;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-items:start;
        justify-content: space-between;
        gap: 30px;
    }
    .statics-charts{
        max-width: 500px;
    }
</style>

<div class="container">
    <div class="page-content">
        <div class="page-header">
            <h2>Most Ordered Items of All time</h2>
        </div>
        <div class="sideByside">
            <div>
                <div class="table-search-bar">
                    <form method="get" action="#">
                        <div class="serach-input">
                            <input required type="text" id="member_id" name="member_id" placeholder="Enter Member ID">
                        </div>
                        <div class="search-btn">
                            <button type="submit">Search</button>
                        </div> 
                    </form>
                </div>

                <?php
                    require_once '../config.php';
                    $currentMonthStart = date('Y-m-01');
                    $currentMonthEnd = date('Y-m-t');
                    //format 'YYYY-MM'
                    $currentMonth = date('Y-m');

                    $memberId = isset($_GET['member_id']) ? $_GET['member_id'] : 1;
                    // Get member's most ordered items
                    $mostOrderedItemsQuery = "SELECT Menu.item_name, SUM(Bill_Items.quantity) AS order_count
                                            FROM Bill_Items
                                            INNER JOIN Menu ON Bill_Items.item_id = Menu.item_id
                                            INNER JOIN Bills ON Bill_Items.bill_id = Bills.bill_id
                                            WHERE Bills.member_id = $memberId
                                            GROUP BY Bill_Items.item_id
                                            ORDER BY order_count DESC";
                    $mostOrderedItemsResult = mysqli_query($link, $mostOrderedItemsQuery);
                    // Check if any results were returned
                    if(mysqli_num_rows($mostOrderedItemsResult) == 0) {
                        echo "Member ID not found.";
                    }
                    else {
                ?>
                <!-- --------------- inside else condition ------------ -->
                <div class="page-header">
                    <h3>Member ID - <?php echo $memberId; ?></h3>
                </div>
            
                <table class="table ">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($mostOrderedItemsResult)) : ?>
                            <tr>
                                <td><?php echo $row['item_name']; ?></td>
                                <td><?php echo $row['order_count']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php } ?>
                <!-- --------------- inside else condition ------------ -->
            </div>
                    
            <div class="statics-charts">
                <canvas id="mostOrderedItemsChart"></canvas>
            </div>
            <div></div>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Get data for the donut chart
    <?php
        $chartLabels = [];
        $chartData = [];

        $chartItemsResult = mysqli_query($link, $mostOrderedItemsQuery);
        $itemCount = 0;

        while ($row = mysqli_fetch_assoc($chartItemsResult)) {
            if ($itemCount >= 5) {
                break;
            }
            array_push($chartLabels, $row['item_name']);
            array_push($chartData, $row['order_count']);
            $itemCount++;
        }
    ?>

    // Create the donut chart
    var ctx = document.getElementById('mostOrderedItemsChart');
    
    var mostOrderedItemsChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($chartLabels); ?>,
            datasets: [{
                data: <?php echo json_encode($chartData); ?>,
                backgroundColor: [
                    '#4CAF50', '#FFC107', '#FF5722', '#03A9F4', '#9C27B0'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            legend: {
                display: true,
            },
            title: {
                display: true,
                text: "Customer Favourites"
            },
            is3D:true
        }
    });
</script>

<?php include '../inc/dashFooter.php';?>