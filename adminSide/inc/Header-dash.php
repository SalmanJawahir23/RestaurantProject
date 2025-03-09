<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central Ceylon</title>
    <link rel="shortcut icon" href="./favicon.png" type="image/x-icon">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/header-main.css">
    <style>
    </style>
</head>
<body>
    <header>
        <div class="header-bar">
            <div class="header-bar-button">
                <button class="open-btn" onclick="openNav()">&#9776;</button>
                <h2>Central Ceylon</h2>
            </div>
            <div class="profile-btn">
                <h2 id="profile-switch" >Profile</h2>
                <div class="profile-login-content" id="profile-content">
                    <h4>Logged in as:</h4>
                    <hr>
                    <?php
                        // Check if the session variables are set
                        if (isset($_SESSION['logged_account_id']) && isset($_SESSION['logged_staff_name'])) {
                            // Display the loggedin staff ID and name
                            echo "<p>STAFF ID:" . $_SESSION['logged_account_id']."</p>";
                            echo "<p>" . $_SESSION['logged_staff_name'] ."</p>";
                            
                        } else {
                            echo "<script> alert('Not loggedin'); <script>";
                        }
                    ?>
                    <div class="logout-btn">
                        <a href="../StaffLogin/logout.php">Log out</a>
                    </div>
                </div>
            </div>
            <script>
                //  dropdown visibility
                document.getElementById('profile-switch').addEventListener('click', function () {
                    const profileContent = document.getElementById('profile-content');
                    
                    if (profileContent.style.display === 'none' || profileContent.style.display === '') {
                        profileContent.style.display = 'block';
                    } else {
                        profileContent.style.display = 'none';
                    }
                });
            </script>
        </div>
        <div id="myNavbar" class="navbar">
            <div class="navbar-content">

                <div class="sidebar-header-button">
                    <div class="header-btn-div h2-div">
                        <h2>Central Ceylon</h2>
                    </div>
                    <div class="header-btn-div close-btn-div">
                        <button class="close-btn" onclick="closeNav()">&times;</button>
                    </div>
                </div>
                <br>
                <div class="sidenav-content">
                    <div class="menu-heading">Main</div>
                    <a class="nav-link" href="../panel/pos-panel.php"><i class="fa-solid fa-cash-register"></i>&nbsp;Point of Sale</a>
                    <a class="nav-link" href="../panel/bill-panel.php"><i class="fa-solid fa-receipt"></i>&nbsp;&nbsp;Bills</a>
                    <a class="nav-link" href="../panel/table-panel.php"><i class="fas fa-table-cells"></i>&nbsp;&nbsp;Table</a>
                    <a class="nav-link" href="../panel/menu-panel.php"><i class="fas fa-utensils"></i>&nbsp;&nbsp;Menu</a>
                    <a class="nav-link" href="../panel/reservation-panel.php"><i class="fas fa-book"></i>&nbsp;&nbsp;Reservations</a>
                    <a class="nav-link" href="../panel/customer-panel.php"><i class="fas fa-person-shelter"></i>&nbsp;&nbsp;Members</a>
                    <a class="nav-link" href="../panel/staff-panel.php"><i class="fas fa-people-group"></i>&nbsp;Staff</a>
                    <a class="nav-link" href="../panel/account-panel.php"><i class="fas fa-eye"></i>&nbsp;&nbsp;View All Accounts</a>
                    <a class="nav-link" href="../panel/kitchen-panel.php"><i class="fas fa-kitchen-set"></i>&nbsp;&nbsp;Kitchen</a>
                </div>
                <br>
                <div class="sidenav-content">
                    <div class="menu-heading">Report & Analytics</div>
                    <a class="nav-link" href="../panel/sales-panel.php"><i class="fas fa-fire"></i>&nbsp;&nbsp;Items Sales</a>
                    <a class="nav-link" href="../panel/statistics-panel.php"><i class="fas fa-chart-area"></i>&nbsp;&nbsp;Revenue Statistics</a>
                    <a class="nav-link" href="../panel/profiles-panel.php"><i class="fas fa-users"></i>&nbsp;Member Profiles</a>
                    <a class="nav-link" href="../StaffLogin/logout.php"><i class="fas fa-key"></i>&nbsp;&nbsp;Log out</a>
                </div>
            </div>
        </div>

        <script>
            function openNav() {
                document.getElementById("myNavbar").style.left = "0";
                document.querySelector('.page-content').style.width = "calc(100vw - 250px)";
            }

            function closeNav() {
                document.getElementById("myNavbar").style.left = "-250px";
                document.querySelector('.page-content').style.width = "calc(100vw - 20px)";
            }
        </script>
    </header>
