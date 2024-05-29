<?php  

session_start();

include("includes/header.php");

//include("Data-Objects/fileManipulationFunctions.php");
//$conn = null;

include("Data-Objects\databaseManipulationFunctions.php");
include("databaseConnection.php");
require("Website-Php-functions/errorHandler.php");


//Guest Mode
$currentUser = new User(1,"Guest","","","","","");


if(isset($_SESSION['logged_in']) && ($_SESSION['logged_in'] == true)){
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];
$contact_number = $_SESSION['contact_number'];
$email = $_SESSION['email'];


$currentUser = new User($user_id,$username,$password,$first_name,$last_name,$contact_number,$email);
}



$currentUser = setAddressAndPayment($conn, $currentUser);

//echo $currentUser->getAddress()->getStreet();

  
if ($currentUser->getAddress() != null) {
  $userCountry = $currentUser->getAddress()->getState();
  $userStreet = $currentUser->getAddress()->getStreet();
  $userCity = $currentUser->getAddress()->getCity();
  $userZip = $currentUser->getAddress()->getZip();
} else {
  $userCountry = "";
  $userStreet = "";
  $userCity = "";
  $userZip = "";
}

if ($currentUser->getPayment() != null) {
  $userProvider = $currentUser->getPayment()->getProvider();
  $userAccountNumber = $currentUser->getPayment()->getAccountNumber();

  $userExpiryDate = $currentUser->getPayment()->getExpiryDate();
} else {
  $userProvider = "";
  $userAccountNumber = "";
  $userExpiryDate = "";
}



$allCartsItems = arrayShopingCartFromDatabase($conn);
$userCart = [];

foreach($allCartsItems as $c){
  if($c->getUser()->getId() == $currentUser->getId()){
    array_Push($userCart,$c);
  }
}




$total = 0;
foreach($userCart as $c){
  $total+= (($c->getProduct()->getPrice()+($c->getProduct()->getPrice()*$c->getProduct()->getDiscount()))*$c->getQuantity());
}


const TAX = 0.18;

$subTotal = $total - ($total*TAX);





//Order dhe ruajtja ne file

if(isset($_POST['placeOrder'])){
  $country = $_POST['country'];
  $adress = $_POST['adress'];
  $notes = $_POST['notes'];
  $zip = $_POST['zip'];
  $city = $_POST['city'];
   
  if($_POST['listGroupRadios'] == 'bank'){
    $payWithBank = true;
    $provider = $_POST['provider'];
    $acc_number = $_POST['acc_number'];
    $expiryDate = $_POST['expiry_date'];
  }else{
    $payWithBank = false;
  }

  
  $file= fopen("WebsiteData/order.txt","a") or die("Error gjate gjetjes se file...");
  foreach($userCart as $c){
    if($payWithBank){
      $string = $c->formatForOrder();
      $string = substr($string, 0, -1);
      $toWrite = "$string|$country|$city|$adress|$zip|$notes|bank|$provider|$acc_number|$expiryDate\n";  
      fwrite($file, $toWrite);
    }
    if(!$payWithBank){
      $string = $c->formatForOrder();
      $string = substr($string, 0, -1);
      $toWrite ="$string|$country|$city|$adress|$zip|$notes|cashOnDelivery\n";  
      fwrite($file, $toWrite);
    }
  }
  fclose($file);

}




//Order ne databaze

if(isset($_POST['placeOrder'])){
  $country = $_POST['country'];
  $address = $_POST['adress'];
  $notes = $_POST['notes'];
  $zip = $_POST['zip'];
  $city = $_POST['city'];
  $payWithBank = ($_POST['listGroupRadios'] == 'bank');
  $provider = ($payWithBank) ? $_POST['provider'] : null;
  $acc_number = ($payWithBank) ? $_POST['acc_number'] : null;
  $expiryDate = ($payWithBank) ? $_POST['expiry_date'] : null;

  // Prepare and execute SQL query to insert order data into tblOrder
  $stmt = $conn->prepare("INSERT INTO tblOrder (shid, tbl_user_id, country, city, adress, zip, notes, provider, accNumber, expiryDate, pid, quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    foreach($userCart as $c){
        $shid = $c->getId();
        $productId = $c->getProduct()->getId();
        $quantity = $c->getQuantity();
        $user_id = $currentUser->getId();

        $stmt->bind_param("iisssissssii", $shid, $user_id, $country, $city, $address, $zip, $notes, $provider, $acc_number, $expiryDate, $productId, $quantity);
        $stmt->execute();
    }



  if ($currentUser->getAddress() == null && ($currentUser->getId() != 1)) {
    $country = $_POST['country'];
    $street = $_POST['adress'];
     $notes = $_POST['notes'];
    $zip = $_POST['zip'];
    $city = $_POST['city'];

    
    $user_id = $currentUser->getId();
   


    $stmt = $conn->prepare("INSERT INTO tblAdress (tbl_user_id, street, city, state, zip) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $street, $city, $country, $zip);
    $stmt->execute();

}else if($currentUser->getAddress() != null && $currentUser->getId() != 1){
  $country = $_POST['country'];
  $street = $_POST['adress'];
   $notes = $_POST['notes'];
  $zip = $_POST['zip'];
  $city = $_POST['city'];

  echo "Ka mberri ketu";

  $user_id = $currentUser->getId();
 


  $stmt = $conn->prepare("UPDATE tblAdress SET street=?, city=?,state=?,zip=? WHERE tbl_user_id=?");
  $stmt->bind_param("issss", $street, $city, $country, $zip, $user_id,);
  $stmt->execute();

}

if ($payWithBank && ($currentUser->getPayment() == null) && ($currentUser->getId() != 1)) {
    $provider = $_POST['provider'];
    $acc_number = $_POST['acc_number'];
    $expiryDate = $_POST['expiryDate'];

    $user_id = $currentUser->getId();

    $stmt = $conn->prepare("INSERT INTO tblUserPayment (tbl_user_id, provider, accountNumber, expiryDate) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $provider, $acc_number, $expiryDate);
    $stmt->execute();

}else if($payWithBank && ($currentUser->getPayment() == null) && ($currentUser->getId() != 1)){
  $provider = $_POST['provider'];
  $acc_number = $_POST['acc_number'];
  $expiryDate = $_POST['expiryDate'];

  $user_id = $currentUser->getId();

  $stmt = $conn->prepare("UPDATE tblUserPayment SET provider = ?, accountNumber = ?, expiryDate = ? WHERE tbl_user_id = ?");
  $stmt->bind_param("isss", $provider, $acc_number, $expiryDate, $user_id);
  $stmt->execute();

}

  echo '<script>alert("You have successfully ordered!");</script>';
  echo '<script>window.location.href = window.location.href;</script>';
}



?>






<!DOCTYPE html>
<html>
  
<!-- Mirrored from demo.templatesjungle.com/ministore/checkout.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 26 Mar 2024 19:59:50 GMT -->

    <section class="hero-section position-relative bg-light-blue padding-medium">
      <div class="hero-content">
        <div class="container">
          <div class="row">
            <div class="text-center padding-large no-padding-bottom">
              <h1 class="display-2 text-uppercase text-dark">Checkout</h1>
              <div class="breadcrumbs">
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="shopify-cart checkout-wrap padding-large">
      <div class="container">
        <form  method="post" action="checkout.php">
          <div class="row d-flex flex-wrap">
            <div class="col-lg-6">
              <h2 class="display-7 text-uppercase text-dark pb-4">Billing Details</h2>
              <div class="billing-details">
                <label for="fname">First Name*</label>
                <input type="text" id="fname" name="firstname" class="form-control mt-2 mb-4 ps-3" value="<?php echo $currentUser->getFirstName(); ?>">
                <label for="lname">Last Name*</label>
                <input type="text" id="lname" name="lastname" class="form-control mt-2 mb-4 ps-3" value="<?php echo $currentUser->getLastName(); ?>">
                <label for="cname">Country / Region*</label>
                <input type="text" id="lname" name="country" class="form-control mt-2 mb-4 ps-3"value="<?php echo $userCountry; ?>">

                <label for="city">Town / City *</label>
                <input type="text" id="city" name="city" class="form-control mt-3 ps-3 mb-4" value="<?php echo $userCity; ?>">
                <label for="address">Address*</label>
                <input type="text" id="adr" name="adress" placeholder="House number and street name" class="form-control mt-3 ps-3 mb-3" value="<?php echo $userStreet; ?>">
                <label for="address">Zip*</label>
                <input type="text" id="adr" name="zip" class="form-control mt-3 ps-3 mb-3" value="<?php echo $userZip; ?>">
               
                <label for="email">Phone *</label>
                <input type="text" id="phone" name="phone" class="form-control mt-2 mb-4 ps-3" value="<?php echo $currentUser->getTelephone(); ?>">
                <label for="email">Email address *</label>
                <input type="text" id="email" name="email" class="form-control mt-2 mb-4 ps-3" value="<?php echo $currentUser->getEmail(); ?>">
              </div>
            </div>
            <div class="col-lg-6">
              <h2 class="display-7 text-uppercase text-dark pb-4">Additional Information</h2>
              <div class="billing-details">
                <label for="fname">Order notes (optional)</label>
                <textarea class="form-control pt-3 pb-3 ps-3 mt-2" placeholder="Notes about your order. Like special notes for delivery." name="notes"></textarea>
              </div>
              <div class="your-order mt-5">
                <h2 class="display-7 text-uppercase text-dark pb-4">Cart Totals</h2>
                <div class="total-price">
                  <table cellspacing="0" class="table">
                    <tbody>
                      <tr class="subtotal border-top border-bottom pt-2 pb-2 text-uppercase">
                        <th>Total before tax</th>
                        <td data-title="Subtotal">
                          <span class="price-amount amount text-primary ps-5">
                            <bdi>
                              <span class="price-currency-symbol">$</span><?php echo $subTotal; ?></bdi>
                          </span>
                        </td>
                      </tr>
                      <tr class="order-total border-bottom pt-2 pb-2 text-uppercase">
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
                  <div class="list-group mt-5 mb-3">
                    <label class="list-group-item d-flex gap-2 border-0">
                      <input class="form-check-input flex-shrink-0" type="radio" name="listGroupRadios" id="listGroupRadios1" value="bank" checked>
                      <span>
                        <strong class="text-uppercase">Direct bank transfer</strong>
                        <small class="d-block text-body-secondary">Make your payment directly into our bank account. Please use your Order ID. Your order will shipped after funds have cleared in our account.</small>
                      </span>
                    </label>
                    <div id="bank-details">
                      <input type="text" name="provider" id="provider" placeholder="Provider" value="<?php echo $userProvider; ?>">
                      <input type="text" name="acc_number" id="account_number" placeholder="Account Number" value="<?php echo $userAccountNumber; ?>">
                      <input type="text" name="expiryDate" id="expiry_date" placeholder="Expiry Date" value="<?php echo $userExpiryDate; ?>">
                    </div>
                    <script>
                      document.addEventListener("DOMContentLoaded", function() {
                        var radios = document.getElementsByName('listGroupRadios');
                        var bankDetails = document.getElementById('bank-details');
                    
                        for (var i = 0; i < radios.length; i++) {
                          radios[i].addEventListener('change', function() {
                            if (this.id === 'listGroupRadios1') {
                              bankDetails.style.display = 'block';
                            } else {
                              bankDetails.style.display = 'none';
                            }
                          });
                        }
                      });
                    </script>
                    <label class="list-group-item d-flex gap-2 border-0">
                      <input class="form-check-input flex-shrink-0" type="radio" name="listGroupRadios" id="listGroupRadios3" value="cash">
                      <span>
                        <strong class="text-uppercase">Cash on delivery</strong>
                        <small class="d-block text-body-secondary">Pay with cash upon delivery.</small>
                      </span>
                    </label>
                  </div>
                  <div style="display: flex; align-items: center;">
    <a href="cart.php" style="margin-left:10px;">
        <button name="goTOCart" class="btn btn-dark btn-medium text-uppercase btn-rounded-none">Go to cart</button>
    </a>
    <button type="submit" name="placeOrder" class="btn btn-dark btn-medium text-uppercase btn-rounded-none" style="margin-left: 10px;">Place an order</button>
</div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </section>
    <br>
    <br>
    <?php include("includes/footer.php")?>
    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="js/plugins.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
  </body>

<!-- Mirrored from demo.templatesjungle.com/ministore/checkout.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 26 Mar 2024 19:59:50 GMT -->
</html>
