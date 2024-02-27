<?php
session_start();
session_regenerate_id(true);
require 'db.php';
require 'config.php';
require './razorpay/Razorpay.php';
if(!isset($_SESSION['login_id'])){
    header('Location: login.php');
    exit;
}
$id = $_SESSION['login_id'];
$get_user = mysqli_query($con, "SELECT * FROM `users` WHERE `google_id`='$id'");
if(mysqli_num_rows($get_user) > 0){
    $user = mysqli_fetch_assoc($get_user);
}
else{
    header('Location: logout.php');
    exit;
}


// get total price of cart from session
if(isset($_SESSION["shopping_cart"])){
    $total_price = 0;

foreach ($_SESSION["shopping_cart"] as $product){
    $total_price += ($product["price"]);
}
}
else{
    header('Location: index.php');
}

// make random number for receipt also store in session
$randno = rand(100000, 999999);
$_SESSION['receipt'] = $randno;


// create order
use Razorpay\Api\Api;
$api = new Api('rzp_test_aycQK8JsbXvYqI', 'BLRFFI8jLezkoez3Jy64beuZ');
$orderData = [
    'receipt'         => $randno,
    'amount'          => $total_price * 100, // 2000 rupees in paise
    'currency'        => 'INR',
];
$razorpayOrder = $api->order->create($orderData);
$razorpayOrderId = $razorpayOrder['id'];
$_SESSION['razorpay_order_id'] = $razorpayOrderId;

// when payment is done then redirect to success page
if (isset($_POST['razorpay_payment_id']) === true){
    header('Location: success.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $user['name']; ?></title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  
    <script src=./assets/js/payment.js></script>
    <link rel="stylesheet" href="./assets/css/checkout.css">

</head>
<body>
    <div class="_container">
        <h2 class="heading">My Account</h2>
    </div>
    <div class="_container">
        <div class="_img">
            <img src="<?php echo $user['profile_image']; ?>" alt="<?php echo $user['name']; ?>">
        </div>
        <div class="_info">
            <h1><?php echo $user['name']; ?></h1>
            <p><?php echo $user['email']; ?></p>
            <!-- cart total -->
            <p><strong>Cart Total: ₹<?php echo $total_price; ?></strong></p>
            <section class='btnsec'>
                <div class="greenbtn">
                    <a  type='button' href="index.php">Home</a>
                    <a  href="cart.php">Cart</a>
                    <br>
                    </br>
                    <form action="./success.php" method="POST">
                    <!-- payment id  -->
                    <style>
                        .razorpay-payment-button{
                            background-color: #38A169;
                            color: white;
                            border: none;
                            padding: 10px 20px;
                            border-radius: 5px;
                            cursor: pointer;
                        }
                    </style>
                    <form method='post' action='./success.php'>
                        <script
                            src="https://checkout.razorpay.com/v1/checkout.js"
                            data-key="<?php echo $razorpay_api_key; ?>"
                            data-amount="<?php echo $total_price?>"
                            data-currency="INR"
                            data-buttontext="Pay Now!"
                            data-order_id="<?php echo $razorpayOrderId?>"
                            data-name="Pritish Enterprises"
                            data-description="100% of money will be utilized for my education."
                            data-image="./assets/images/paylogo.jpg"
                            data-prefill.name="<?php echo $user['name']?>"
                            data-prefill.email="<?php echo $user['email']?>"
                            data-prefill.contact="9999999999"
                            data-products = "product"
                            data-theme.color="#A020F0"
                        ></script>
                        <input type="hidden" custom="Hidden Element" name="hidden"/>
                    </form>


                </div>
            </section>
            <br>
            <a href="logout.php">Logout</a>
            
            
           
        </div>
        
        
    </div>
</body>
</html>