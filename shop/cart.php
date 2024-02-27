<?php
session_start();
$status="";
if (isset($_POST['action']) && $_POST['action']=="remove"){
if(!empty($_SESSION["shopping_cart"])) {
    foreach($_SESSION["shopping_cart"] as $key => $value) {
      if($_POST["code"] == $key){
      unset($_SESSION["shopping_cart"][$key]);
      $status = "<div class='box' style='color:red;'>
      Product is removed from your cart!</div>";
      }
      if(empty($_SESSION["shopping_cart"]))
      unset($_SESSION["shopping_cart"]);
      }		
}
}

if (isset($_POST['action']) && $_POST['action']=="change"){
  foreach($_SESSION["shopping_cart"] as &$value){
    if($value['code'] === $_POST["code"]){
        
        break; // Stop the loop after we've found the product
    }
}
  	
}

if(!empty($_SESSION["shopping_cart"])) {

    $cart_count = count(array_keys($_SESSION["shopping_cart"]));
    }
    else{
    $cart_count = 0;
    }
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- stylesheet -->
    <link rel="stylesheet" href="./assets/css/cartui.css"> 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="shortcut icon" href="./assets/images/favicon.png" type="image/svg+xml">

  

    <!-- script -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="./assets/js/cart.js"></script>



    
    <title>Pritish Purav</title>
</head>
<body>
        <div class="card">
        <div class="row">
            <div class="col-md-8 cart">
                <div class="title">
                    <div class="row">
                        <div class="col"><h4><b>Shopping Cart</b></h4></div>
                        <div class="col align-self-center text-right text-muted"><?php echo $cart_count ?> items</div>
                    </div>
                </div>    
                <?php
                    if(isset($_SESSION["shopping_cart"])){
                        $total_price = 0;
                    
                ?>	
                <?php		
                foreach ($_SESSION["shopping_cart"] as $product){
                            ?>
                    <div class="row border-top border-bottom">
                            <div class="row main align-items-center">
                                <div class="col-3" ><img class="img-fluid imgclass" src='./assets/images/<?php echo $product["image"]; ?>'></div>
                                <div class="col">
                                    <!-- <div class="row text-muted">Shirt</div> -->
                                    <div class="row"><?php echo $product["name"]; ?></div>
                                </div>
                            
                                <div class="col"><?php echo "₹".$product["price"]; ?>
                                
                                <form id="myForm<?php echo $product['code']?>" class='hidden' method='post' action='' >
                                    <input type='hidden' name='code' value="<?php echo $product["code"]; ?>" />
                                    <input type='hidden' name='action' value="remove" />
                                </form>
                                <span id="submitBtn<?php echo $product['code']?>" class="close cursor-pointer">&#10005;</span>
                                <script>
                                   $(document).ready(function(){
                                    $('#submitBtn<?php echo $product['code']?>').click(function(){
                                        $("#myForm<?php echo $product['code']?>").submit();
                                       });
                                    })
                                </script>


                                </div>
                            </div>
                        </div>


        
                <?php

                
                $total_price += ($product["price"]);
                }
                ?>
                
                <?php
                }else{
                    $total_price = 0;
                    echo "<h3>Your cart is empty!</h3>";
                    }
                ?>
                <div class="back-to-shop"><a href="./index.php">&leftarrow; <span class="text-primary">Back to shop</span></a></div>
            </div>
            <div class="col-md-4 summary">
                <div><h5><b>Summary</b></h5></div>
                <hr>
                <?php
                    if($total_price == 0){
                        $codeclass = 'hidden';
                    }else{
                        $codeclass = '';
                    }
                ?>
                <form class=<?php echo $codeclass ?>>
                    <p>Discount Code</p>
                    <input id="code" placeholder="Enter your code">
                </form>
                <div class="row" style="border-top: 1px solid rgba(0,0,0,.1); padding: 2vh 0;">
                    <div class="col">TOTAL PRICE</div>
                    <div class="col text-right"><strong><?php echo "₹".$total_price; ?></strong></div>
                </div>
                <?php
                    // assume $totalprice is defined and contains the total price

                    if ($total_price == 0) {
                    $classbtn = 'hidden"';
                    } else {
                    $classbtn = 'btn'; 
                    }
                    ?>

                <form action="./checkout.php" method="post">
                    <button class="<?php echo $classbtn; ?>">CHECKOUT</button>
                </form>

            </div>
        </div>
        
    </div>



</body>
</html>