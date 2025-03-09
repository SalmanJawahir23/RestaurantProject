<?php
  require_once '../config.php';

  $sqlmainDishes = "SELECT * FROM Menu WHERE item_category = 'Main Dishes' ORDER BY item_type; ";
  $resultmainDishes = mysqli_query($link, $sqlmainDishes);
  $mainDishes = mysqli_fetch_all($resultmainDishes, MYSQLI_ASSOC);

  $sqldrinks = "SELECT * FROM Menu WHERE item_category = 'Drinks' ORDER BY item_type; ";
  $resultdrinks = mysqli_query($link, $sqldrinks);
  $drinks = mysqli_fetch_all($resultdrinks, MYSQLI_ASSOC);

  $sqlsides = "SELECT * FROM Menu WHERE item_category = 'Side Snacks' ORDER BY item_type; ";
  $resultsides = mysqli_query($link, $sqlsides);
  $sides = mysqli_fetch_all($resultsides, MYSQLI_ASSOC);

  // Check user login
  if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    echo '<div class="user-profile">';
    echo 'Welcome, ' . $_SESSION["member_name"] . '!';
    echo '<a href="../customerProfile/profile.php">Profile</a>';
    echo '</div>';
  }
  session_start();
?>
<?php
$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0"
    />
    <link rel="shortcut icon" href="../image/icon/favicon.png" type="image/x-icon">
    <title>Document</title>
    <style>
      @import 'https://fonts.googleapis.com/css?family=Montserrat:300, 400, 700&display=swap';
      @import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');
      :root {
        --color1: #f39823;
        --color2: #965b31;
        --color3: #678677;
        --color4: #24491e;
        --txt1: #fdf6e3;
        --txt2: #333333;
        --btn1: #e63946;
        --btn2: #a8dadc;
      }

      ::-webkit-scrollbar {
        width: 0px;
        height: 0px;
      }

      * {
        margin: 0;
        padding: 0px;
        font-family: "Open Sans", sans-serif;
        scrollbar-width: none;
      }
      html {
        font-family: 'Montserrat', sans-serif;
        scroll-behavior: smooth;
      }
      a{
        text-decoration: none;
        color: var(--txt1);
      }
      ul, ol{
        list-style: none;
      }

      /* header----------------------------------------------------- */

      #nav_bar ul {
        list-style: none;
      }
      #nav_bar ul li{
        margin-left: 10px;
      }
      #nav_bar ul li, #nav_bar ul li a {
        text-decoration: none;
        color: var(--txt2);
      }
      #nav_bar ul li button{
        background: none;
        outline: none;
        border: none;
        color: var(--txt2);
        font-size: 16px;
        font-weight: bold;
      }
      #nav_bar ul .li:hover , #nav_bar ul li a:hover, #nav_bar ul li button:hover {
        color: var(--btn1);
      }

      header {
        width: 85vw;
        height: 10vh;
        margin: auto;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
      }
      #header_domain{
        color: var(--btn1);
      }
      #navbar_domain{
        visibility: hidden;
      }
      header nav{
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
      }
      header nav ul {
        display: flex;
        flex-direction: row;
        font-weight: bold;
      }
      header nav ul li{
        display: flex;
        align-items:center;
      }
      header .floating_btn{
        color: var(--color1);
        visibility: hidden;
        cursor: pointer;
        transition: 0.4s ease-in-out;
      }
      @media (max-width: 430px) {

      }
      @media (max-width: 800px) {
        #humburger{
          visibility: visible;
          font-weight: 900;
          font-size: larger;
          position: absolute;
          right: 5vw;
        }
        #close_humburger{
          visibility: visible;
          position: absolute;
          right: 5vw;
          top: 10px;
          font-size: xx-large;
        }
        header nav{
          transition: 0.5s ease-in-out;
          position: fixed;
          top: 0px;
          left: -100vw;
          width: 100vw;
          height: 100vh;
          background-color: var(--txt1);

          display: flex;
          flex-direction: row;
          align-items:baseline;
          padding-top: 20px;
          z-index: 10;
        }
        header nav ul{
          position: absolute;
          width: fit-content;
          left: 20px;
          top: 60px;
          display: flex;
          flex-direction: column;
          font-weight: bold;
        }
        header nav ul .link{
          margin-block: 5px;
          font-size: large;
        }
        #navbar_domain{
          visibility: visible;
          position: absolute;
          left: 20px;
          top: 20px;
          color: var(--btn1);
        }
      }

      .dropdown {
        position: relative;
      }

      .dropdown-content {
        display: none;
        position: absolute;
        background-color: var(--color3);
        padding: 10px;
        margin-right: 10px;
        border-radius: 5px;
        top: 100%;
        min-width: 120px;
        z-index: 1000;
      }
      .dropdown-content .dropdown-content-align{
        color: var(--txt1);
        display: flex;
        flex-direction: column;
      }
      .dropdown-content .dropdown-content-align .dropdown-text{
        font-weight: 500;
        font-size: 15px;
        margin-bottom: 5px;
      }
      .dropdown-content .dropdown-content-align .logout-link{
        display: flex;
        flex-direction: row;
        height: 30px;
        align-items:center;
        justify-content: center;
        transition: 0.3s;
      }

      .dropdown-content .dropdown-content-align a {
        display: block;
        padding: 5px 10px;
        text-decoration: none;
        color: var(--txt1);
        transition: 0.3s;
      }

      .dropdown-content a:hover {
        background-color: var(--btn2);
      }

      .dropdown:hover .dropdown-content {
        display: block;
      }
  
      /*hero section-----------------------------------------------------*/

      .hero-banner {
        position: relative;
        width: 100%;
        height: 100vh;
        background: url('../image/banner-chatgpt.jpg') center/cover no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--txt1);
        text-align: center;
        overflow: hidden;
      }

      .hero-banner .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 1;
      }

      .hero-content {
        position: relative;
        z-index: 2;
      }

      .hero-content h1 {
        font-size: 3.5rem;
        margin: 0;
      }

      .hero-content p {
        font-size: 1.5rem;
        margin: 15px 0;
      }

      .buttons {
        margin-top: 20px;
      }

      .buttons a {
        text-decoration: none;
        padding: 12px 20px;
        margin: 0 10px;
        border-radius: 30px;
        font-size: 1rem;
        transition: all 0.3s ease-in-out;
      }
      .buttons button {
        outline: none;
        border: none;
        text-decoration: none;
        padding: 12px 20px;
        margin: 0 10px;
        border-radius: 30px;
        font-size: 1rem;
        transition: all 0.3s ease-in-out;
      }

      .btn-primary {
        background: var(--btn1);
        color: white;
      }

      .btn-primary:hover {
        background: var(--txt1);
        color: var(--btn1);
      }

      .btn-secondary {
        background: var(--btn2);
        color: var(--txt2);
      }

      .btn-secondary:hover {
        background: var(--txt2);
        color: var(--btn2);
      }


      /*Food Menu ------------------------------------------------------ */
      #menu {
        flex-direction: column;
        max-width: 90vw;
        margin: 0 auto;
        padding: 100px 0;
        display:flex;
        justify-content:center;
        align-items:center;
      }
      #menu .section-title{
        color: var(--btn1);
        font-size: 2.5rem;
      }
      #menu .menu{
        width:100%;
      }
      #menu .menu-header{
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
      }
      #menu .menu div h1 {
        margin-bottom: 50px;
        margin-top:50px;
        color: var(--color4);
      }

      .food-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
      }

      .food-item {
        background-color: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        text-align: center;
      }
      .cropped-image{
        width: 200px;
        height: 200px;
        overflow: hidden;
        display: inline-block;
        position: relative;
        border-radius: 8px;
      }
      .food-item img {
        position: absolute;
        width: auto;
        height: 100%;
        left: 50%;
        transform: translateX(-50%);
      }
      .food-item h3 {
        margin: 10px 0;
      }

      .food-item p {
        color: #666;
        margin-bottom: 10px;
      }

      .food-item span {
        font-weight: bold;
        color: #333;
      }

      .cropped-image .food-item-layer{
          position: absolute;
          bottom: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: linear-gradient(rgba(0, 0, 0, .4), var(--color1));
          display: flex;
          justify-content: center;
          align-items: center;
          flex-direction: column;
          text-align: center;
          /* padding: 5px; */
          transform: translateY(100%);
          transition: .5s ease;
          
          /* word-wrap: break-word; */
      }
      .cropped-image:hover .food-item-layer{
          transform: translateY(0);
      }
      .food-item-layer p{
          font-size: 16px;
          margin: 5px 0 16px;
          color: var(--txt1);
      }


      /* End Food menu---------------------------------------------------*/

      /* About Section ------------------------------------------------- */
      .about-section {
        padding: 80px 20px;
        background: #f8f9fa;
        color: #333;
      }

      .about-container {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto;
      }

      .about-image img {
        width: 100%;
        max-width: 500px;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      }

      .about-content {
        max-width: 600px;
        text-align: left;
      }

      .about-content h2 {
        font-size: 2.5rem;
        color: #e63946;
        margin-bottom: 20px;
      }

      .about-content p {
        font-size: 1.2rem;
        line-height: 1.8;
        margin-bottom: 20px;
      }

      .btn-about {
        display: inline-block;
        padding: 12px 25px;
        background: var(--btn1);
        color: var(--txt1);
        text-decoration: none;
        font-size: 1rem;
        border-radius: 30px;
        transition: background 0.3s ease;
      }

      .btn-about:hover {
        background: var(--txt1);
        color: var(--btn1);
      }
      /* end of about section--------------------------------------------*/
      
      /* Contact Section ------------------------------------------------*/
      .contact-section {
        padding: 80px 20px;
        background: #fff;
        color: #333;
      }

      .contact-container {
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto;
        align-items: flex-start;
      }

      .contact-info {
        flex: 1;
        min-width: 300px;
      }

      .contact-info h2 {
        font-size: 2.5rem;
        color: #e63946;
        margin-bottom: 20px;
      }

      .contact-info p {
        font-size: 1.2rem;
        margin-bottom: 20px;
        line-height: 1.8;
      }

      .contact-details {
        list-style: none;
        padding: 0;
      }

      .contact-details li {
        font-size: 1rem;
        margin: 10px 0;
      }

      .contact-details a {
        text-decoration: none;
        color: #e63946;
      }

      .contact-details a:hover {
        text-decoration: underline;
      }

      .contact-form {
        flex: 1;
        min-width: 300px;
      }

      .contact-form form {
        display: flex;
        flex-direction: column;
      }

      .contact-form label {
        font-size: 1rem;
        margin-bottom: 5px;
      }

      .contact-form input,
      .contact-form textarea {
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
      }

      .contact-form input:focus,
      .contact-form textarea:focus {
        outline: none;
        border-color: var(--btn1);
      }

      .btn-submit {
        padding: 12px 20px;
        background: var(--btn1);
        color: var(--txt1);
        border: none;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.3s ease;
      }

      .btn-submit:hover {
        background: var(--txt1);
        color: var(--btn1);
        border: 1px solid var(--btn1);
      }
      /* end of the contact section--------------------------------------*/

      /* Footer----------------------------------------------------------*/
      footer {
        width: 100vw;
        min-height: 40vh;
        background-color: var(--btn1);
        color: var(--txt1);
        padding-top: 30px;
      }
      footer .f_top{
        min-height: 45vh;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: space-around;
      }
      footer .f_top div{
        max-width: 30%;
        min-width: 100px;
      }
      footer .f_top .f_text{
        min-width: 200px;
        text-align: left;
        color: var(--txt1);
      }
      footer .f_top .f_text hr{
        margin-bottom: 5px;
        border:1px solid var(--txt2);
      }
      footer .f_top ul li{
        word-wrap: break-word;
        color: var(--txt2);
        font-weight: 100;
      }
      footer .f_top ul li:hover{
        color: var(--txt1);
      }
      footer .f_top .f_contact ul li span{
        margin-right: 10px;
      }
      footer .f_base{
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--txt2);
        font-weight: bold;
        font-size: small;
      }
      /* End Footer----------------------------------------------------------------*/

    </style>
    <!-- If user logged in, go reservation page else go to login page-->
    <script>
        function handleReservation() {
            const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;

            if (isLoggedIn) {
                window.location.href = "../CustomerReservation/reservePage.php";
            } else {
                alert('You need to login to make reservations.');
                window.location.href = "../customerLogin/login.php";
            }
        }
    </script>
  </head>

  <body>

    <!-- header section -->
    <header>
      <div id="header_domain">
        <h2>Central Ceylon</h2>
      </div>

      <div id="humburger" class="open_nav floating_btn">
        <h3>&#9776;</h3>
      </div>

      <nav id="nav_bar">
        <h2 id="navbar_domain">Central Ceylon</h2>

        <ul>
          <li><a class="link" href="#hero">Home</a></li>
          <li><a class="link" href="#about">About</a></li>
          <li><a class="link" href="#menu">Menu</a></li>
          <li><button class="link" onclick="handleReservation()" href="">Reservation</button></li>
          <li><a class="link" href="#contact">Contact</a></li>
          <li><a class="link" href="../../adminSide/StaffLogin/login.php">Staff</a></li>
          <li class="dropdown">
            <a class="dropbtn"> Account </a>
            <div class="dropdown-content">
              <div class="dropdown-content-align">
              <?php
                // Member_id from query parameters
                $account_id = $_SESSION['account_id'] ?? null;
                
                // Check user login
                if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $account_id != null) {
                  $query = "SELECT member_name, points FROM memberships WHERE account_id = $account_id";

                  $result = mysqli_query($link, $query);
                  if ($result) {
                    $row = mysqli_fetch_assoc($result);

                    if ($row) {
                      $member_name = $row['member_name'];
                      $points = $row['points'];

                      $vip_status = ($points >= 1000) ? 'VIP' : 'Regular';

                      // Output member info
                      echo "<p class='dropdown-text'>$member_name</p>";
                      echo "<p class='dropdown-text'>Points: $points</p>";
                      echo "<p class='dropdown-text'>Status: $vip_status</p>";
                      echo "<hr>";

                    } else {
                      echo "Member not found.";
                    }
                  
                  } else {
                    echo "Error: " . mysqli_error($link);
                  }
                  echo '<a class="logout-link"  href="../customerLogin/logout.php"><span>Logout </span><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg></a>';
                
                } else {
                  // If not logged in, show "Login" link
                  echo '<a class="signin-link"  href="../customerLogin/register.php">Sign Up </a> ';
                  echo '<a class="login-link"  href="../customerLogin/login.php">Log In</a>';
                }
                // Close the database connection
                mysqli_close($link);
              ?>
              </div>
            </div>
          </li> 
        </ul>

        <div id="close_humburger" class="close_nav floating_btn">
          <h3>&#215;</h3>
        </div>
      </nav>
    </header>

    <script>
      const openBtn = document.getElementById('humburger');
      const closeBtn = document.getElementById('close_humburger');
      const nav_bar = document.getElementById('nav_bar');

      openBtn.addEventListener('click', function () {
          nav_bar.style.left = '0';
          openBtn.style.right = '-5vw'
      });
      closeBtn.addEventListener('click', function () {
          nav_bar.style.left = '-100vw';
          openBtn.style.right = '5vw'
      });

    </script>
    <!-- End of header section -->


    <!-- hero section -->
    <section id="hero" class="hero-banner">
      <div class="overlay"></div>
      <div class="hero-content">
        <h1>Welcome to Central Ceylon restaurant</h1>
        <p>Indulge in culinary artistry crafted with passion and fresh ingredients.</p>
        <div class="buttons">
          <a href="#menu" class="btn-primary">Explore Menu</a>
          <button href="../CustomerReservation/reservePage.php" onclick="handleReservation()" class="btn-secondary">Make a Reservation</button>
        </div>
      </div>
    </section>
    <!-- End of hero section -->

    <!-- about section -->
    <section id="about" class="about-section">
      <div class="about-container">
        <div class="about-image">
          <img src="../image/food-plate.jpg" alt="food plate">
        </div>
        <div class="about-content">
          <h2>About Us</h2>
          <p>
            Welcome to <strong>Central Ceylon</strong>, where every dish tells a story. Our restaurant is the perfect blend of tradition and innovation, bringing you flavors that linger in your heart and on your palate. Whether it`s a cozy dinner for two or a grand celebration, we aim to make every moment memorable.
          </p>
          <p>
            Our chefs craft every dish with love and the freshest ingredients, drawing inspiration from global cuisines while honoring local traditions. Step into a world of exquisite flavors, warm hospitality, and unforgettable dining experiences.
          </p>
          <a href="../CustomerReservation/reservePage.php" class="btn-about">Reserve a Table</a>
        </div>
      </div>
    </section>
    <!-- end of the about section -->


    <!-- menu Section -->
    <section id="menu">
      <div class="menu-header">
        <h1 class="section-title">Menu</h1>
        <div>
          <label for="menu-category">Fillter By:</label>
          <select id="menu-category" class="menu-category">
            <option value="allItemSelected">All items</option>
            <option value="mainDisheshSelected" selected>Main dishes</option>
            <option value="sideDishesSelected">Side dishes</option>
            <option value="drinksSelected">Drinks</option>
          </select>
        </div>
      </div>
      
      
      <br>
      <hr style="width: 100%;">
      <div class="menu container">

        <div class="mainDisheshSelected msg"> 
          <div class="mainDish">
            <h1 style="text-align:center;">MAIN DISHES</h1>
            <div class="food-grid">
              <?php foreach ($mainDishes as $item): ?>
                <div class="food-item">
                  <div class="cropped-image">
                    <img class="item-img-url" src="<?php echo $item['item_image']; ?>" alt="Food <?php echo $item['item_name']; ?>">
                    <div class="food-item-layer">
                      <p class="item-description"> <i><?php echo $item['item_description']; ?></i> </p>
                    </div>
                  </div>
                  <h3 class="item-name"><strong><?php echo $item['item_name']; ?></strong></h3>
                  <p class="item_type"><i><?php echo $item['item_type']; ?></i></p>
                  <span class="item-price">LKR <?php echo $item['item_price']; ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <div class="sideDishesSelected msg"> 
          <div class="sideDish">
            <h1 style="text-align:center;">SIDE DISHES</h1>
            <div class="food-grid">
              <?php foreach ($sides as $item): ?>
                <div class="food-item">
                  <div class="cropped-image">
                    <img class="item-img-url" src="<?php echo $item['item_image']; ?>" alt="Food <?php echo $item['item_name']; ?>">
                    <div class="food-item-layer">
                      <p class="item-description"> <i><?php echo $item['item_description']; ?></i> </p>
                    </div>
                  </div>
                  <h3 class="item-name"><strong><?php echo $item['item_name']; ?></strong></h3>
                  <p class="item_type"><i><?php echo $item['item_type']; ?></i></p>
                  <span class="item-price">LKR <?php echo $item['item_price']; ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        
        <div class="drinksSelected msg"> 
          <div class="drinks">
            <h1 style="text-align:center;">DRINKS</h1>
            <div class="food-grid">
              <?php foreach ($drinks as $item): ?>
                <div class="food-item">
                  <div class="cropped-image">
                    <img class="item-img-url" src="<?php echo $item['item_image']; ?>" alt="Food <?php echo $item['item_name']; ?>">
                    <div class="food-item-layer">
                      <p class="item-description"> <i><?php echo $item['item_description']; ?></i> </p>
                    </div>
                  </div>
                  <h3 class="item-name"><strong><?php echo $item['item_name']; ?></strong></h3>
                  <p class="item_type"><i><?php echo $item['item_type']; ?></i></p>
                  <span class="item-price">LKR <?php echo $item['item_price']; ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        
        <div class="allItemSelected msg">  

          <div class="mainDish">
            <h1 style="text-align:center">MAIN DISHES</h1>
            <div class="food-grid">
              <?php foreach ($mainDishes as $item): ?>
                <div class="food-item">
                  <div class="cropped-image">
                    <img class="item-img-url" src="<?php echo $item['item_image']; ?>" alt="Food <?php echo $item['item_name']; ?>">
                    <div class="food-item-layer">
                      <p class="item-description"> <i><?php echo $item['item_description']; ?></i> </p>
                    </div>
                  </div>
                  <h3 class="item-name"><strong><?php echo $item['item_name']; ?></strong></h3>
                  <p class="item_type"><i><?php echo $item['item_type']; ?></i></p>
                  <span class="item-price">LKR <?php echo $item['item_price']; ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="sideDish">
            <h1 style="text-align:center">SIDE DISHES</h1>
            <div class="food-grid">
              <?php foreach ($sides as $item): ?>
                <div class="food-item">
                  <div class="cropped-image">
                    <img class="item-img-url" src="<?php echo $item['item_image']; ?>" alt="Food <?php echo $item['item_name']; ?>">
                    <div class="food-item-layer">
                      <p class="item-description"> <i><?php echo $item['item_description']; ?></i> </p>
                    </div>
                  </div>
                  <h3 class="item-name"><strong><?php echo $item['item_name']; ?></strong></h3>
                  <p class="item_type"><i><?php echo $item['item_type']; ?></i></p>
                  <span class="item-price">LKR <?php echo $item['item_price']; ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="drinks">
            <h1 style="text-align:center">DRINKS</h1>
            <div class="food-grid">
              <?php foreach ($drinks as $item): ?>
                <div class="food-item">
                  <div class="cropped-image">
                    <img class="item-img-url" src="<?php echo $item['item_image']; ?>" alt="Food <?php echo $item['item_name']; ?>">
                    <div class="food-item-layer">
                      <p class="item-description"> <i><?php echo $item['item_description']; ?></i> </p>
                    </div>
                  </div>
                  <h3 class="item-name"><strong><?php echo $item['item_name']; ?></strong></h3>
                  <p class="item_type"><i><?php echo $item['item_type']; ?></i></p>
                  <span class="item-price">LKR <?php echo $item['item_price']; ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>      
        </div>
      </div>
    </section>

    <script> 
      document.addEventListener("DOMContentLoaded", function() {
        const selectElement = document.querySelector("select");
        const messages = document.querySelectorAll(".msg");

        if (selectElement) {
          selectElement.addEventListener("change", function() {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const val = selectedOption ? selectedOption.value : null;

            messages.forEach(function(msg) {
              if (val) {
                if (msg.classList.contains(val)) {
                  msg.style.display = "block";
                } else {
                  msg.style.display = "none";
                }
              } else {
                msg.style.display = "none";
              }
            });
          });
          // Trigger the change event on load
          selectElement.dispatchEvent(new Event("change"));
        }
      });

      // document.addEventListener("DOMContentLoaded", function() {
      //   const searchInput = document.getElementById("search-input");
      //   const searchButton = document.getElementById("search-button");
      //   const items = document.querySelectorAll(".item-name");

      //   function filterMenuItems(searchTerm) {
      //     items.forEach(function(item) {
      //       const itemName = item.textContent.toLowerCase();
      //       const msg = item.closest(".msg");
      //       if (msg) {
      //         if (itemName.includes(searchTerm)) {
      //           msg.style.display = "block";
      //         } else {
      //           msg.style.display = "none";
      //         }
      //       }
      //     });
      //   }

      //   if (searchButton) {
      //     searchButton.addEventListener("click", function() {
      //       const searchTerm = searchInput ? searchInput.value.toLowerCase() : "";
      //       filterMenuItems(searchTerm);
      //     });
      //   }

      //   if (searchInput) {
      //     searchInput.addEventListener("keyup", function() {
      //       const searchTerm = searchInput.value.toLowerCase();
      //       filterMenuItems(searchTerm);
      //     });
      //   }
      // });

      // document.addEventListener("DOMContentLoaded", function() {
      //   const dropdownToggles = document.querySelectorAll(".dropdown-toggle");

      //   dropdownToggles.forEach(function(toggle) {
      //     toggle.addEventListener("click", function(event) {
      //       event.preventDefault();
      //       const menu = toggle.nextElementSibling;

      //       if (menu) {
      //         const isVisible = menu.style.display === "block";
      //         menu.style.display = isVisible ? "none" : "block";
      //       }
      //     });
      //   });
      // });

    </script>
    <!-- End menu Section -->

    <!-- contact section -->
    <section id="contact" class="contact-section">
      <div class="contact-container">
        <div class="contact-info">
          <h2>Contact Us</h2>
          <p>We`d love to hear from you! Whether it`s a question, feedback, or reservation inquiry, feel free to get in touch with us.</p>
          <ul class="contact-details">
            <li><strong>Address:</strong> 123 Spice Lane, Kandy, Sri Lanka</li>
            <li><strong>Phone:</strong> <a href="tel:+94 11 2345678">+94 11 2345678</a></li>
            <li><strong>Email:</strong> <a href="info@centralceylon.lk">info@centralceylon.lk</a></li>
            <li><strong>Hours:</strong> Mon - Sun: 10:00 AM - 11:00 PM</li>
          </ul>
        </div>
        <div class="contact-form">
          <form action="/submit-contact" method="POST">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your name" required>
            
            <label for="email">Your Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            
            <label for="message">Your Message</label>
            <textarea id="message" name="message" rows="5" placeholder="Write your message here..." required></textarea>
            
            <button type="submit" class="btn-submit">Send Message</button>
          </form>
        </div>
      </div>
    </section>
    <!-- end of the contact section -->


    <!-- Footer -->
    <footer id="footer">
      <div class="f_top">
        <div class="f_text">
          <h3>Get in Touch</h3>
          <hr>
          <p>
            We`d love to hear from you! Whether you have a question about our
            services, want to collaborate, or simply want to share your thoughts or
            experiences, feel free to reach out to us.
          </p>
        </div>
        <div class="f_nav">
          <ul>
            <a href="#hero"><li>Home</li></a>
            <a href="#menu"><li>Menu</li></a>
            <a href="#about"><li>About</li></a>
            <a href="../customerLogin/register.php"><li>Register</li></a>
          </ul>
        </div>
        <div class="f_contact">
          <ul>
            <li><span>Phone:</span>+94 11 2345678</li>
            <li><span>Email:</span>info@centralceylon.lk</li>
            <li><span>Address:</span>123 Spice Lane, Kandy, Sri Lanka</li>
          </ul>
        </div>
        <div class="f_follow">
          <dl>
            <dt>Folow us on</dt>
            <dd>
              <ul>
                <li>
                  <a href="www.facebook.com">
                    <img src="../image/icon/facebook.png" width="20px" alt="" />
                  </a>
                </li>
                <li>
                  <a href="www.instagram.com">
                    <img src="../image/icon/instagram.png" width="20px" alt=""/>
                  </a>
                </li>
                <li>
                  <a href="www.linkedin.com">
                    <img src="..//image/icon/linkedin.png" width="20px" alt=""/>
                  </a>
                </li>
                <li>
                  <a href="www.x.com">
                    <img src="../image/icon/twitter.png" width="20px" alt=""/>
                  </a>
                </li>
              </ul>
            </dd>
          </dl>
        </div>
      </div>

      <div class="f_base">
        <p>&copy;2024 Central Ceylon(Pvt)Ltd. All right reserved.</p>
      </div>
    </footer>
    <!-- end of footer -->

  </body>
</html>