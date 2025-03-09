<?php
    session_start();
    require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php 
    include '../inc/Header-dash.php'; 
    require_once '../config.php';
    $currentMonthStart = date('Y-m-01');
    $currentMonthEnd = date('Y-m-t');
    $currentMonth = date('Y-m');
?>

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
    .statics-charts{
        width: 100%;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
    }
    .google-pie{
        max-height: 300px;
        max-width: 500px;
    }
</style>

<div class="container">
    <div class="page-content">
        <div class="page-header">
            <h3>Most Purchased Items  (<?php echo $currentMonth; ?>)</h3>
            <h3></h3>
        </div>  
        
        <?php
            // Get the sorting order
            $sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'desc';

            // Get the first and last day of the current month
            // Modify the SQL query for menu item sales to consider the current month
            $menuItemSalesQuery = "SELECT Menu.item_name, SUM(Bill_Items.quantity) AS total_quantity
                                FROM Bill_Items
                                INNER JOIN Menu ON Bill_Items.item_id = Menu.item_id
                                INNER JOIN Bills ON Bill_Items.bill_id = Bills.bill_id
                                WHERE Bills.bill_time BETWEEN '$currentMonthStart 00:00:00' AND '$currentMonthEnd 23:59:59'
                                GROUP BY Menu.item_name
                                ORDER BY total_quantity $sortOrder";

            $menuItemSalesResult = mysqli_query($link, $menuItemSalesQuery);

            echo '<table>';
                echo '<thead>';
                    echo '<tr class="undo-btn">';
                        echo '<td></td>';
                        echo '<td>    
                                <a class="btn delete" href="?sortOrder=desc">Most</a>
                                <a class="btn delete" href="?sortOrder=asc">Least</a> 
                              </td>';
                    echo '</tr>';
                    echo '<tr>';
                        echo '<th>Item Name</th>';
                        echo '<th>Units</th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                while ($row = mysqli_fetch_assoc($menuItemSalesResult)) {
                    echo '<tr>';
                        echo '<td>' . $row['item_name'] . '</td>';
                        echo '<td>' . $row['total_quantity'] . '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
            echo '</table>';   
        ?>
        <br><br><br><br>
        <div class="statics-charts">
            <!-- Add a div for Google Charts -->
            <canvas id="mostPurchased" class="google-pie"></canvas>
            <canvas id="mostPurchasedMain" class="google-pie"></canvas>
            <canvas id="mostPurchasedDrinks" class="google-pie"></canvas>
            <canvas id="mostPurchasedSide" class="google-pie"></canvas>
        </div>
        <br><br><br><br>
    </div>
</div>

<!--chrt.js charts --------------------------------------------------------------------------->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart.js template function
    function renderChart(ctx, title, data) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Total Quantity',
                    data: data.values,
                    backgroundColor: [
                        '#4CAF50', '#FFC107', '#FF5722', '#03A9F4', '#9C27B0',
                        '#E91E63', '#00BCD4', '#8BC34A', '#FFEB3B', '#FF9800'
                    ]
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: title,
                        font: {
                            size: 20,
                            weight: 'bold'
                        }
                    }
                },
                responsive: true
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        const mostPurchasedCtx = document.getElementById('mostPurchased').getContext('2d');
        const mostPurchasedDrinksCtx = document.getElementById('mostPurchasedDrinks').getContext('2d');
        const mostPurchasedMainCtx = document.getElementById('mostPurchasedMain').getContext('2d');
        const mostPurchasedSideCtx = document.getElementById('mostPurchasedSide').getContext('2d');

        // chart of most purchased items
        const mostPurchasedData = {
            labels: [
                <?php
                    $topPurchasedItemsQuery = "SELECT Menu.item_name, SUM(Bill_Items.quantity) AS total_quantity
                                                FROM Bill_Items
                                                INNER JOIN Menu ON Bill_Items.item_id = Menu.item_id
                                                INNER JOIN Bills ON Bill_Items.bill_id = Bills.bill_id
                                                WHERE Bills.bill_time BETWEEN '$currentMonthStart 00:00:00' AND '$currentMonthEnd 23:59:59'
                                                GROUP BY Menu.item_name
                                                ORDER BY total_quantity DESC
                                                LIMIT 10";
                    $topPurchasedItemsResult = mysqli_query($link, $topPurchasedItemsQuery);
                    $labels = [];
                    $values = [];
                    while ($row = mysqli_fetch_assoc($topPurchasedItemsResult)) {
                        $labels[] = "'" . $row['item_name'] . "'";
                        $values[] = $row['total_quantity'];
                    }
                    echo implode(',', $labels);
                ?>
            ],
            values: [<?php echo implode(',', $values); ?>]
        };

        // Chart for Drinks
        const drinksData = {
            labels: [
                <?php
                    $topPurchasedDrinksQuery = "SELECT Menu.item_name, SUM(Bill_Items.quantity) AS total_quantity
                                                FROM Bill_Items
                                                INNER JOIN Menu ON Bill_Items.item_id = Menu.item_id
                                                INNER JOIN Bills ON Bill_Items.bill_id = Bills.bill_id
                                                WHERE Bills.bill_time BETWEEN '$currentMonthStart 00:00:00' AND '$currentMonthEnd 23:59:59' AND Menu.item_category = 'Drinks'
                                                GROUP BY Menu.item_name
                                                ORDER BY total_quantity DESC
                                                LIMIT 10";
                    $topPurchasedItemsResult = mysqli_query($link, $topPurchasedDrinksQuery);
                    $labels = [];
                    $values = [];
                    while ($row = mysqli_fetch_assoc($topPurchasedItemsResult)) {
                        $labels[] = "'" . $row['item_name'] . "'";
                        $values[] = $row['total_quantity'];
                    }
                    echo implode(',', $labels);
                ?>
            ],
            values: [<?php echo implode(',', $values); ?>]
        };

        // Chart for Main Dishes
        const mainDishesData = {
            labels: [
                <?php
                    $topPurchasedMainDishesQuery = "SELECT Menu.item_name, SUM(Bill_Items.quantity) AS total_quantity
                                                FROM Bill_Items
                                                INNER JOIN Menu ON Bill_Items.item_id = Menu.item_id
                                                INNER JOIN Bills ON Bill_Items.bill_id = Bills.bill_id
                                                WHERE Bills.bill_time BETWEEN '$currentMonthStart 00:00:00' AND '$currentMonthEnd 23:59:59' AND Menu.item_category = 'Main Dishes'
                                                GROUP BY Menu.item_name
                                                ORDER BY total_quantity DESC
                                                LIMIT 10";
                    $topPurchasedItemsResult = mysqli_query($link, $topPurchasedMainDishesQuery);
                    $labels = [];
                    $values = [];
                    while ($row = mysqli_fetch_assoc($topPurchasedItemsResult)) {
                        $labels[] = "'" . $row['item_name'] . "'";
                        $values[] = $row['total_quantity'];
                    }
                    echo implode(',', $labels);
                ?>
            ],
            values: [<?php echo implode(',', $values); ?>]
        };

        // Chart for Side Snacks
        const sideSnacksData = {
            labels: [
                <?php
                    $topPurchasedSideSnacksQuery = "SELECT Menu.item_name, SUM(Bill_Items.quantity) AS total_quantity
                                                FROM Bill_Items
                                                INNER JOIN Menu ON Bill_Items.item_id = Menu.item_id
                                                INNER JOIN Bills ON Bill_Items.bill_id = Bills.bill_id
                                                WHERE Bills.bill_time BETWEEN '$currentMonthStart 00:00:00' AND '$currentMonthEnd 23:59:59' AND Menu.item_category = 'Side Snacks'
                                                GROUP BY Menu.item_name
                                                ORDER BY total_quantity DESC
                                                LIMIT 10";
                    $topPurchasedItemsResult = mysqli_query($link, $topPurchasedSideSnacksQuery);
                    $labels = [];
                    $values = [];
                    while ($row = mysqli_fetch_assoc($topPurchasedItemsResult)) {
                        $labels[] = "'" . $row['item_name'] . "'";
                        $values[] = $row['total_quantity'];
                    }
                    echo implode(',', $labels);
                ?>
            ],
            values: [<?php echo implode(',', $values); ?>]
        };

        // Render Charts
        renderChart(mostPurchasedCtx, 'Top 10 Most Purchased Items - <?php echo date('F Y'); ?>', mostPurchasedData);
        renderChart(mostPurchasedDrinksCtx, 'Top 10 Most Purchased Drinks - <?php echo date('F Y'); ?>', drinksData);
        renderChart(mostPurchasedMainCtx, 'Top 10 Most Purchased Main Dishes - <?php echo date('F Y'); ?>', mainDishesData);
        renderChart(mostPurchasedSideCtx, 'Top 10 Most Purchased Side Snacks - <?php echo date('F Y'); ?>', sideSnacksData);
    });
</script>
<!--chrt.js charts --------------------------------------------------------------------------->
