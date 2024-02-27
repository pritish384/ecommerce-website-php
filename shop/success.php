<?php
session_start();
session_regenerate_id(true);
require ('db.php');
require ('config.php');
$id = $_SESSION['login_id'];
$get_user = mysqli_query($con, "SELECT * FROM `users` WHERE `google_id`='$id'");
if(mysqli_num_rows($get_user) > 0){
    $user = mysqli_fetch_assoc($get_user);
}
else{
    header('Location: logout.php');
    exit;
}

// if payment is not done then redirect to checkout page
if(!isset($_POST['razorpay_payment_id'])){
    header('Location: checkout.php');
    exit;
}

if(isset($_SESSION["shopping_cart"])){
  $total_price = 0;

foreach ($_SESSION["shopping_cart"] as $product){
  $total_price += ($product["price"]);
}
}
else{
  header('Location: index.php');
}



// get razorpay order id from post
$razorpayOrderId = $_POST['razorpay_order_id'];
// get razorpay payment id from post
$razorpayPaymentId = $_POST['razorpay_payment_id'];
// get razorpay signature from post
$razorpaySignature = $_POST['razorpay_signature'];


$item_names = "";

foreach ($_SESSION["shopping_cart"] as $product){
    $item_names .= $product["name"].", ";
}
$item_names = substr($item_names, 0, -2);

// if payment id , order id and signature is not empty then store in database
if(!empty($razorpayPaymentId) && !empty($razorpayOrderId) && !empty($razorpaySignature)){

$randno = $_SESSION['receipt'];

// insert this 	name	email	items	total_price	order_id	receipt	razorpay_payment_id	razorpay_signature
$sql = "INSERT INTO `orders` (`name`, `email`, `items`, `total_price`, `order_id`, `receipt`, `razorpay_payment_id`, `razorpay_signature`) VALUES ('".$user['name']."', '".$user['email']."', '".$item_names."', '".$total_price."', '".$razorpayOrderId."', '".$randno."', '".$razorpayPaymentId."', '".$razorpaySignature."')";

mysqli_query($con, $sql);


unset($_SESSION["shopping_cart"]);
// redirect to index page after 7 seconds
header("refresh:7;url=index.php");
}
else{
    header('HTTP/1.1 500 Internal Server Error');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/success.css">
    <title>Success</title>
</head>
<body>
    <div class="card">
    <div class="main-container">
        <div class="check-container">
            <div class="check-background">
                <svg viewBox="0 0 65 51" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 25L27.3077 44L58.5 7" stroke="white" stroke-width="13" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <div class="check-shadow"></div>
            <audio autoplay>
                <source src="./assets/audio/success.ogg" type="audio/mp3">
            </audio>
        </div>
        <div class="text-container">
            <h1>Success</h1>
        </div>

    </div>
    <p>You will receive an email with your receipt within 24 hours.</p>

    </div>
</body>
</html>
