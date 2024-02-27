<?php
   session_start();
   include ('db.php');
   include ('./razorpay/Razorpay.php');
   $status = "";
   if (isset($_POST['code']) && $_POST['code'] != "") {
       $code = $_POST['code'];
       if (isset($_POST['price']) && $_POST['price'] > 0) {
           $donation = $_POST['price'];
           $name = "Donation";
           $image = "donate.svg";

       }
       else {
         $result = mysqli_query($con, "SELECT * FROM `products` WHERE `code`='$code'");
         $row = mysqli_fetch_assoc($result);
         $name = $row['name'];
         $code = $row['code'];
         $price = $row['price'];
         $image = $row['image'];

       }
       if (empty($donation)) {
           $donation = null;
       }
       // if price is not defined, then it is a donation
       if (empty($price)) {
           $price = $donation;
       } else {
           $price = $row['price'];
       }
       $cartArray = array($code => array('name' => $name, 'code' => $code, 'price' => $price, 'image' => $image));
       // if price is more than 0 then status is success
       if ($price > 0) {
           if (empty($_SESSION["shopping_cart"])) {
               $_SESSION["shopping_cart"] = $cartArray;
               $status = "<div class='box'>Product is added to your cart!</div>";
           } else {
               $array_keys = array_keys($_SESSION["shopping_cart"]);
               if (in_array($code, $array_keys)) {
                   $status = "<div class='box' style='color:red;'>Product is already added to your cart!</div>";
               } else {
                   $_SESSION["shopping_cart"] = array_merge($_SESSION["shopping_cart"], $cartArray);
                   $status = "<div class='box'>Product is added to your cart!</div>";
               }
           }
       }
   }
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Pritish Purav</title>
      <!-- 
         - favicon
         -->
      <link rel="shortcut icon" href="./assets/images/favicon.png">
      <!-- 
         - custom css link
         -->
      <link rel="stylesheet" href="./assets/css/style.css">
      <!-- 
         - google font link
         -->
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link
         href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap"
         rel="stylesheet">
      <!-- 
         - preload banner
         -->
      <link rel="preload" href="./assets/images/hero-banner.png" as="image">
   </head>
   <body id="top">
      <?php
         if (!empty($_SESSION["shopping_cart"])) {
             $cart_count = count(array_keys($_SESSION["shopping_cart"]));
         } else {
             $cart_count = 0;
         }
         ?>
      <div style="clear:both;"></div>
      <div class="message_box" style="margin:10px 0px;"></div>
      <!-- 
         - #HEADER
         -->
      <header class="header" data-header>
         <div class="container">
            <div class="overlay" data-overlay></div>
            <a href="#" class="logo">
            <img src="./assets/images/logo.svg" width="160" height="50" alt=" logo">
            </a>
            <button class="nav-open-btn" data-nav-open-btn aria-label="Open Menu">
               <ion-icon name="menu-outline"></ion-icon>
            </button>
            <nav class="navbar" data-navbar>
               <button class="nav-close-btn" data-nav-close-btn aria-label="Close Menu">
                  <ion-icon name="close-outline"></ion-icon>
               </button>
               <a href="#" class="logo">
               <img src="./assets/images/logo.svg" width="190" height="50" alt=" logo">
               </a>
               <ul class="navbar-list">
                  <li class="navbar-item">
                     <a href="https://www.pritishpurav.in/" class="navbar-link">Home</a>
                  </li>
                  <li class="navbar-item">
                     <a href="#" class="navbar-link">Products</a>
                  </li>
                  <li class="navbar-item">
                     <a href="https://www.pritishpurav.in/contact" class="navbar-link">Contact</a>
                  </li>
                  <li>
                     <a href="./cart.php" class="nav-action-btn">
                        <ion-icon name="bag-outline" aria-hidden="true"></ion-icon>
                        <data class="nav-action-text" >Cart</data>
                        <data class="nav-action-badge"  aria-hidden="true"><?php echo $cart_count ?></data>
                     </a>
                  </li>
               </ul>
            </nav>
         </div>
      </header>
      <main>
      <article>
      <!-- 
         - #PRODUCT
         -->
      <!-- get product name where id = 1 and store that name into $prod1 variable -->
      <div style="clear:both;"></div>
      <div class="message_box" style="margin:10px 0px;">
         <?php echo $status; ?>
      </div>
      <section class="section product">
         <div class="container">
         <h2 class="h2 section-title">Products</h2>
         <ul class="filter-list">
            <!-- <li>
               <button class="filter-btn  active">All</button>
               </li> -->
         </ul>
         <ul class="product-list">
            <?php
               $result = mysqli_query($con, "SELECT * FROM `products`");
               while ($row = mysqli_fetch_assoc($result)) {
                   if ($row['code'] != 'Donate01') {
                       echo " 
                               <li class='product-item'>
                                         <div class='product-card' tabindex='0'>
                                           <figure class='card-banner'>
                                             <img src='./assets/images/" . $row['image'] . "' width='312' height='350' loading='lazy'
                                               alt='' class='image-contain'>
                                             <div class='card-badge'>New</div>
                                             <ul class='card-action-list'>
                                               <li class='card-action-item'>
                                               <form method='post' action=''>
                                                 <input type='hidden' name='code' value=" . $row['code'] . " />
                                                 <button type=submit class='card-action-btn' aria-labelledby='card-label-1'>
                                                   <ion-icon name='cart-outline'></ion-icon>
                                                 </button>
                                                 <div class='card-action-tooltip' id='card-label-1'>Add to Cart</div>
                                               </form>
                                             </ul>
                                           </figure>
                                           <div class='card-content'>
                                             <h3 class='h3 card-title'>
                                               <a href='#'>" . $row['name'] . "</a>
                                             </h3>
                                             <data class='card-price'>₹" . $row['price'] . "</data>
                                           </div>
                                         </div>
                                       </li>";
                   }
               }
               ?>
            <li class='product-item'>
               <div class='product-card' tabindex='0'>
                  <figure class='card-banner'>
                     <img src='./assets/images/donate.svg' loading='lazy'
                        alt='' class='image-contain'>
                     <div class='card-badge donate-badge'>Donation</div>
                     <ul class='card-action-list'>
                        <li class='card-action-item'>
                     </ul>
                  </figure>
                  <div class='card-content'>
                     <h3 class='h3 card-title'>
                        <a href='#'>Donation</a>
                     </h3>
                     <data class='card-price'>Any Amount</data>
                     <form method='post' action="">
                        <input required type="number" id="amount" min=1 name='price' >
                        <input type='hidden' name='code' value="Donate01" />
                        <button type=submit class=donateBtn>Donate</button>
                     </form>
                  </div>
            </li>
         </ul>
         </div>
      </section>
      <!-- 
         - #SERVICE
         -->
      <section class="section service">
         <div class="container">
            <ul class="service-list">
               <li class="service-item">
                  <div class="service-card">
                     <div class="card-icon">
                        <img src="./assets/images/service-2.png" width="43" height="35" loading="lazy" alt="Service icon">
                     </div>
                     <div>
                        <h3 class="h4 card-title">Quick Payment</h3>
                        <p class="card-text">
                           100% secure payment
                        </p>
                     </div>
                  </div>
               </li>
               <li class="service-item">
                  <div class="service-card">
                     <div class="card-icon">
                        <img src="./assets/images/service-3.png" width="40" height="40" loading="lazy" alt="Service icon">
                     </div>
                     <div>
                        <h3 class="h4 card-title">Ask for refund</h3>
                        <p class="card-text">
                           Ask for refund within 7 days
                        </p>
                     </div>
                  </div>
               </li>
               <li class="service-item">
                  <div class="service-card">
                     <div class="card-icon">
                        <img src="./assets/images/service-4.png" width="40" height="40" loading="lazy" alt="Service icon">
                     </div>
                     <div>
                        <h3 class="h4 card-title">Support</h3>
                        <p class="card-text">
                           Get Quick Support
                        </p>
                     </div>
                  </div>
               </li>
            </ul>
         </div>
      </section>
      <!-- 
         - #FOOTER
         -->
      <footer class="footer">
         <div class="footer-top section">
            <div class="container">
               <div class="footer-brand">
                  <a href="#" class="logo">
                  <img src="./assets/images/logo.svg" width="160" height="50" alt=" logo">
                  </a>
                  <ul class="social-list">
                     <li>
                        <!-- github pritish384 -->
                        <a href="https://github.com/pritish384" class="social-link">
                           <ion-icon name="logo-github"></ion-icon>
                        </a>
                     </li>
                  </ul>
               </div>
               <div class="footer-link-box">
                  <ul class="footer-list">
                     <li>
                        <p class="footer-list-title">Contact Us</p>
                     </li>
                     <li>
                        <address class="footer-link">
                           <ion-icon name="location"></ion-icon>
                           <span class="footer-link-text">
                           Virar , Mumbai , India
                           </span>
                        </address>
                     </li>
                     <li>
                        <a href="https://www.pritishpurav.in/contact" class="footer-link">
                           <ion-icon name="mail"></ion-icon>
                           <span class="footer-link-text">admin@pritishpurav.in</span>
                        </a>
                     </li>
                  </ul>
                  <ul class="footer-list">
                     <li>
                        <p class="footer-list-title">My Account</p>
                     </li>
                     <li>
                        <a href="./cart.php" class="footer-link">
                        <span class="footer-link-text">View Cart</span>
                        </a>
                     </li>
                  </ul>
                  <div class="footer-list">
                     <p class="footer-list-title">Opening Time</p>
                     <table class="footer-table">
                        <tbody>
                           <tr class="table-row">
                              <th class="table-head" scope="row">Mon - Sun:</th>
                              <td class="table-data">24x7</td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
         <div class="footer-bottom">
            <div class="container">
               <p class="copyright">
                  &copy; 2023 <a href="#" class="copyright-link">Pritish Purav</a>. All Rights Reserved
               </p>
            </div>
         </div>
      </footer>
      <!-- 
         - #GO TO TOP
         -->
      <a href="#top" class="go-top-btn" data-go-top>
         <ion-icon name="arrow-up-outline"></ion-icon>
      </a>
      <!-- 
         - custom js link
         -->
      <script src="./assets/js/script.js"></script>
      <!-- 
         - ionicon link
         -->
      <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
      <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
   </body>
</html>