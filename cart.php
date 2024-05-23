<?php

session_start();

//include("Data-Objects/fileManipulationFunctions.php")
;
include("Data-Objects\databaseManipulationFunctions.php");
$conn = null;
include("databaseConnection.php");


 include("includes/header.php");

$users = arrayUsersFromDatabase($conn);
$products = arrayProductsFromDatabase($conn);



//Produket dhe useri duhen marr nga sessioni
$product1 = $products[0];


$currentUser = new User(1,"Guest","","","","","");

if(isset($_SESSION['logged_in']) && ($_SESSION['logged_in']==true)){
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];
$contact_number = $_SESSION['contact_number'];
$email = $_SESSION['email'];


$currentUser = new User($user_id,$username,$password,$first_name,$last_name,$contact_number,$email);

}

//echo '<script>'.var_dump($currentUser).'</script>';


//Quantity duhet mu marr nga sessioni ne Single Product


$quantity = 10;


if(isset($_POST['remove'])){
  $idCart = $_POST['idRemove'];
   
  removeItemCart($conn, $idCart);
}

//addProductToShoppingCart($db, Product $product, User $currentUser, $quantity)
if(isset($_GET['product'])){
   foreach($products as $p){
    if($_GET['product']== $p->getId()){
        addProductToShoppingCart($conn,$p, $currentUser,1);
    }
   
}
}


$allCartsItems = arrayShopingCartFromDatabase($conn);


$userCart = [];


foreach($allCartsItems as $c){

  if($c->getUser()->getId() == $currentUser->getId()){
    array_Push($userCart,$c);
  
  }
}


ksort($userCart);

$total = 0;
foreach($userCart as $c){
  $total+= (($c->getProduct()->getPrice()+($c->getProduct()->getPrice()*$c->getProduct()->getDiscount()))*$c->getQuantity());
}


define('TAX', 0.18);

$subTotal = $total - ($total*TAX);








function shfaq(ShopingCart $c){
  $singlePrice = $c->getProduct()->getPrice()+($c->getProduct()->getPrice()*$c->getProduct()->getDiscount());
  $subTotal = $singlePrice*$c->getQuantity();
 echo '  
<div class="cart-item border-top border-bottom padding-small">
<div class="row align-items-center">
<div class="col-lg-4 col-md-3">
 <div class="cart-info d-flex flex-wrap align-items-center mb-4">
   <div class="col-lg-5">
     <div class="card-image">
       <img src="'.($c->getProduct()->getImages())[0].'" alt="cloth" class="img-fluid">
     </div>
   </div>
   <div class="col-lg-4">
     <div class="card-detail">
       <h3 class="card-title text-uppercase">
         <a href="#">'.$c->getProduct()->getName().'</a>
       </h3>
       <div class="card-price">
         <span class="money text-primary" data-currency-usd="$1200.00">$'.$singlePrice.'</span>
       </div>
     </div>
   </div>
 </div>
</div>
<div class="col-lg-6 col-md-7">
 <div class="row d-flex">
   <div class="col-lg-6">
     <div class="qty-field">
       <div class="qty-number d-flex">
         <div class="quntity-button incriment-button">+</div>
         <input class="spin-number-output bg-light no-margin" type="text" value="'.$c->getQuantity().'">
         <div class="quntity-button decriment-button">-</div>
       </div>
       <div class="regular-price"></div>
       <div class="quantity-output text-center bg-primary"></div>
     </div>
   </div>
   <div class="col-lg-4">
     <div class="total-price">
       <span class="money text-primary">$'.$subTotal.'</span>
     </div>
   </div>   
 </div>             
</div>
<div class="col-lg-1 col-md-2">
 <div class="cart-remove">
 <form method="post" action="cart.php">
 <button type="submit" name="remove" style="color: transparent; background-color: transparent; border-color: transparent; cursor: default;">
  <input type="hidden" name="idRemove" value="'.$c->getId().'"/>
     <svg class="close" width="38px">
       <use xlink:href="#close"></use>
     </svg>
   
   </button>
   </form>

 </div>
</div>
</div>
</div>
';}



?>




<!DOCTYPE html>
<html>
  

    <section class="hero-section position-relative bg-light-blue padding-medium">
      <div class="hero-content">
        <div class="container">
          <div class="row">
            <div class="text-center padding-large no-padding-bottom">
              <h1 class="display-2 text-uppercase text-dark">Cart</h1>
              <div class="breadcrumbs">
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="shopify-cart padding-large">
      <div class="container">
        <div class="row">
          <div class="cart-table">
            <div class="cart-header">
              <div class="row d-flex text-uppercase">
                <h3 class="cart-title col-lg-4 pb-3">Product</h3>
                <h3 class="cart-title col-lg-3 pb-3">Quantity</h3>
                <h3 class="cart-title col-lg-4 pb-3">Subtotal</h3>
              </div>
            </div>








<?php 

$total = 0;
foreach($userCart as $c){
  shfaq($c);
  $total+= (($c->getProduct()->getPrice()+($c->getProduct()->getPrice()*$c->getProduct()->getDiscount()))*$c->getQuantity());


}

?>



            
          </div>
          <div class="cart-totals bg-grey padding-medium">
            <h2 class="display-7 text-uppercase text-dark pb-4">Cart Totals</h2>
            <div class="total-price pb-5">
              <table cellspacing="0" class="table text-uppercase">
                <tbody>
                  <tr class="subtotal pt-2 pb-2 border-top border-bottom">
                    <th>Total without tax</th>
                    <td data-title="Subtotal">
                      <span class="price-amount amount text-primary ps-5">
                        <bdi>
                          <span class="price-currency-symbol">$</span><?php echo $subTotal; ?>
                        </bdi>
                      </span>
                    </td>
                  </tr>
                  <tr class="order-total pt-2 pb-2 border-bottom">
                    <th>Total</th>
                    <td data-title="Total">
                      <span class="price-amount amount text-primary ps-5">
                        <bdi>
                          <span class="price-currency-symbol">$</span><?php echo $total; ?></bdi>
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="button-wrap">
              <button class="btn btn-black btn-medium text-uppercase me-2 mb-3 btn-rounded-none">Update Cart</button>
              <a href="shop.php" class="btn btn-black btn-medium text-uppercase me-2 mb-3 btn-rounded-none">Continue Shopping</a>
              <a href="checkout.php" class="btn btn-black btn-medium text-uppercase mb-3 btn-rounded-none">Proceed to checkout</a>

            </div>
          </div>
        </div>
      </div>
    </section> 
    <section id="subscribe" class="container-grid position-relative overflow-hidden">
      <div class="container">
        <div class="row">
          <div class="subscribe-content bg-dark d-flex flex-wrap justify-content-center align-items-center padding-medium">
            <div class="col-md-6 col-sm-12">
              <div class="display-header pe-3">
                <h2 class="display-7 text-uppercase text-light">Subscribe Us Now</h2>
                <p>Get latest news, updates and deals directly mailed to your inbox.</p>
              </div>
            </div>
            <div class="col-md-5 col-sm-12">
              <form class="subscription-form validate">
                <div class="input-group flex-wrap">
                  <input class="form-control btn-rounded-none" type="email" name="EMAIL" placeholder="Your email address here" required="">
                  <button class="btn btn-medium btn-primary text-uppercase btn-rounded-none" type="submit" name="subscribe">Subscribe</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php include("includes/footer.php")?>
    
    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="js/plugins.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
    <script>
document.addEventListener("DOMContentLoaded", function() {
    var updateCart = function(inputField, increase) {
        var value = parseInt(inputField.value);
        var stockQuantity = 10; // Assuming stock quantity
        var priceElement = inputField.closest('.cart-item').querySelector('.card-price .money');
        var totalPriceElement = inputField.closest('.cart-item').querySelector('.total-price .money');
        var price = parseFloat(priceElement.textContent.replace(/[^0-9.]/g, ''));

        if (increase) {
            if (value < stockQuantity) {
                value++;
            }
        } else {
            if (value > 1) {
                value--;
            }
        }

        inputField.value = value;
        totalPriceElement.textContent = '$' + (price * value).toFixed(2);
    };

 
    var quantityFields = document.querySelectorAll('.spin-number-output');
    quantityFields.forEach(function(inputField) {
        inputField.addEventListener('change', function() {
            updateCart(inputField, true); // Always increasing the quantity when manually changed
        });
    });

 
    var incrementButtons = document.querySelectorAll('.incriment-button');
    incrementButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var inputField = button.parentElement.querySelector('.spin-number-output');
            updateCart(inputField, true);
        });
    });

  
    var decrementButtons = document.querySelectorAll('.decriment-button');
    decrementButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var inputField = button.parentElement.querySelector('.spin-number-output');
            updateCart(inputField, false);
        });
    });
});
</script>
  </body>

<!-- Mirrored from demo.templatesjungle.com/ministore/cart.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 26 Mar 2024 19:59:50 GMT -->
</html>
