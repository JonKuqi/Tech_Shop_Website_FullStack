<?php
session_start();
include("includes/header.php");

//include("Data-Objects/fileManipulationFunctions.php");

include("Data-Objects\databaseManipulationFunctions.php");
include("databaseConnection.php");

$products = arrayProductsFromDatabase($conn);
$smartPhones  = []; $smartWatches = []; $laptop = []; $otherBrands = [];
foreach($products as $p){
  if($p instanceof SmartPhone){
     array_push($smartPhones,$p);
  }
  if($p instanceof SmartWatch){
    array_push($smartWatches,$p);
 }
 if($p instanceof Laptop){
  array_push($laptop,$p);
}
if($p instanceof OtherBrands){
  array_push($otherBrands,$p);
}
}

krsort($smartPhones);
krsort($smartWatches);
krsort($laptop);
krsort($otherBrands);

include("Data-Objects/recomendProducts.php");

$recommendProducts = recommendProducts($products, $conn);


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


?>




<!DOCTYPE html>
<html>
  
<!-- Mirrored from demo.templatesjungle.com/ministore/ by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 26 Mar 2024 19:58:07 GMT -->
 
    <section id="billboard" class="position-relative overflow-hidden bg-light-blue">
      <div class="swiper main-swiper">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <div class="container">
              <div class="row d-flex align-items-center">
                <div class="col-md-6">
                  <div class="banner-content">
                    <h1 class="display-2 text-uppercase text-dark pb-5">Connecting Dreams, One Call at a Time.</h1>
                    <br>
                    <a href="shop.php" class="btn btn-medium btn-dark text-uppercase btn-rounded-none">Shop Product</a>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="image-holder">
                    <img src="images/banner-image.png" alt="banner">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="container">
              <div class="row d-flex flex-wrap align-items-center">
                <div class="col-md-6">
                  <div class="banner-content">
                    <h1 class="display-2 text-uppercase text-dark pb-5">Enhancing Lives Through Technology!</h1>
                    <a href="shop.php" class="btn btn-medium btn-dark text-uppercase btn-rounded-none">Shop Product</a>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="image-holder">
                    <img src="images/banner-image.png" alt="banner">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="swiper-icon swiper-arrow swiper-arrow-next">
        <svg class="chevron-right">
          <use xlink:href="#chevron-right" />
        </svg>
      </div>
      <div class="swiper-icon swiper-arrow swiper-arrow-prev">
        <svg class="chevron-left">
          <use xlink:href="#chevron-left" />
        </svg>
      </div>
   
    </section>
    <section id="company-services" class="padding-large">
      <div class="container">
        <div class="row">
          <div class="col-lg-3 col-md-6 pb-3">
            <div class="icon-box d-flex">
              <div class="icon-box-icon pe-3 pb-3">
                <svg class="cart-outline">
                  <use xlink:href="#cart-outline" />
                </svg>
              </div>
              <div class="icon-box-content">
                <h3 class="card-title text-uppercase text-dark">Free delivery</h3>
                <p>Elevate your shopping experience with our unbeatable offer of free delivery."</p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 pb-3">
            <div class="icon-box d-flex">
              <div class="icon-box-icon pe-3 pb-3">
                <svg class="quality">
                  <use xlink:href="#quality" />
                </svg>
              </div>
              <div class="icon-box-content">
                <h3 class="card-title text-uppercase text-dark">Quality guarantee</h3>
                <p>Quality Assured, Satisfaction Guaranteed!</p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 pb-3">
            <div class="icon-box d-flex">
              <div class="icon-box-icon pe-3 pb-3">
                <svg class="price-tag">
                  <use xlink:href="#price-tag" />
                </svg>
              </div>
              <div class="icon-box-content">
                <h3 class="card-title text-uppercase text-dark">Daily offers</h3>
                <p>Every Day, Unbeatable Deals Await!</p>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 pb-3">
            <div class="icon-box d-flex">
              <div class="icon-box-icon pe-3 pb-3">
                <svg class="shield-plus">
                  <use xlink:href="#shield-plus" />
                </svg>
              </div>
              <div class="icon-box-content">
                <h3 class="card-title text-uppercase text-dark">100% secure payment</h3>
                <p>Secure Payment, Your Peace of Mind Guaranteed!</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section id="mobile-products" class="product-store position-relative padding-large no-padding-top">
      <div class="container">
        <div class="row">
          <div class="display-header d-flex justify-content-between pb-3">
            <h2 class="display-7 text-dark text-uppercase">Recomended Products</h2>
            <div class="btn-right">
              <a href="shop.php" class="btn btn-medium btn-normal text-uppercase">Go to Shop</a>
            </div>
          </div>
          <div class="swiper product-swiper">
            <div class="swiper-wrapper">
             <!-- Php Rekomandimet  -->
             
              <?php
                foreach($recommendProducts as $r){
                  $r->showInIndex();
                }
              ?>
              

            </div>
          </div>
        </div>
      </div>
      <div class="swiper-pagination position-absolute text-center"></div>
    </section>
    <section id="mobile-products" class="product-store position-relative padding-large no-padding-top">
      <div class="container">
        <div class="row">
          <div class="display-header d-flex justify-content-between pb-3">
            <h2 class="display-7 text-dark text-uppercase">Mobile Products</h2>
            <div class="btn-right">
              <a href="shop.php" class="btn btn-medium btn-normal text-uppercase">Go to Shop</a>
            </div>
          </div>
          <div class="swiper product-swiper">
            <div class="swiper-wrapper">
             <!-- Php Rekomandimet  -->
             
              <?php
                foreach($smartPhones as $s){
                  $s->showInIndex();
                }
              ?>


             
              
              

            </div>
          </div>
        </div>
      </div>
      <div class="swiper-pagination position-absolute text-center"></div>
    </section>
    <section id="smart-watches" class="product-store padding-large position-relative">
      <div class="container">
        <div class="row">
          <div class="display-header d-flex justify-content-between pb-3">
            <h2 class="display-7 text-dark text-uppercase">Smart Watches</h2>
            <div class="btn-right">
              <a href="shop.php" class="btn btn-medium btn-normal text-uppercase">Go to Shop</a>
            </div>
          </div>
          <div class="swiper product-watch-swiper">
            <div class="swiper-wrapper">
              
            <?php

                foreach($smartWatches as $s){
                  $s->showInIndex();
                }
              ?>


            </div>
          </div>
        </div>
      </div>
      <div class="swiper-pagination position-absolute text-center"></div>
    </section>
    <section id="laptop" class="product-store padding-large position-relative">
      <div class="container">
        <div class="row">
          <div class="display-header d-flex justify-content-between pb-3">
            <h2 class="display-7 text-dark text-uppercase">Laptop</h2>
            <div class="btn-right">
              <a href="shop.php" class="btn btn-medium btn-normal text-uppercase">Go to Shop</a>
            </div>
          </div>
          <div class="swiper product-watch-swiper">
            <div class="swiper-wrapper">
              
            <?php
            
            foreach($laptop as $s){
              $s->showInIndex();
            }
               ?>

            </div>
          </div>
        </div>
      </div>
      <div class="swiper-pagination position-absolute text-center"></div>
    </section>
    <section id="other-brands" class="product-store padding-large position-relative">
      <div class="container">
        <div class="row">
          <div class="display-header d-flex justify-content-between pb-3">
            <h2 class="display-7 text-dark text-uppercase">Other Brands</h2>
            <div class="btn-right">
              <a href="shop.php" class="btn btn-medium btn-normal text-uppercase">Go to Shop</a>
            </div>
          </div>
          <div class="swiper product-watch-swiper">
            <div class="swiper-wrapper">
              
            <?php
            
            foreach($otherBrands as $s){
              $s->showInIndex();
            }
               ?>

            </div>
          </div>
        </div>
      </div>
      <div class="swiper-pagination position-absolute text-center"></div>
    </section>
    <section id="yearly-sale" class="bg-light-blue overflow-hidden mt-5 padding-xlarge" style="background-image: url('images/single-image1.png');background-position: right; background-repeat: no-repeat;">
      <div class="row d-flex flex-wrap align-items-center">
        <div class="col-md-6 col-sm-12">
          <div class="text-content offset-4 padding-medium">
            <h3>10% off</h3>
            <h2 class="display-2 pb-5 text-uppercase text-dark">New year sale</h2>
            <a href="shop.php" class="btn btn-medium btn-dark text-uppercase btn-rounded-none">Shop Sale</a>
          </div>
        </div>
        <div class="col-md-6 col-sm-12">
          
        </div>
      </div>
    </section>
    <section id="testimonials" class="position-relative">
      <div class="container">
        <div class="row">
          <div class="review-content position-relative">
            <div class="swiper-icon swiper-arrow swiper-arrow-prev position-absolute d-flex align-items-center">
              <svg class="chevron-left">
                <use xlink:href="#chevron-left" />
              </svg>
            </div>
            <div class="swiper testimonial-swiper">
              <div class="quotation text-center">
                <svg class="quote">
                  <use xlink:href="#quote" />
                </svg>
              </div>
              <div class="swiper-wrapper">
                <div class="swiper-slide text-center d-flex justify-content-center">
                  <div class="review-item col-md-10">
                    <i class="icon icon-review"></i>
                    <blockquote>“Couldn't be happier with my recent purchase from Ministore. The quality of the product exceeded my expectations, and the customer service was top-notch. Thank you!”</blockquote>
                    <div class="rating">
                      <svg class="star star-fill">
                        <use xlink:href="#star-fill"></use>
                      </svg>
                      <svg class="star star-fill">
                        <use xlink:href="#star-fill"></use>
                      </svg>
                      <svg class="star star-fill">
                        <use xlink:href="#star-fill"></use>
                      </svg>
                      <svg class="star star-half">
                        <use xlink:href="#star-half"></use>
                      </svg>
                      <svg class="star star-empty">
                        <use xlink:href="#star-empty"></use>
                      </svg>
                    </div>
                    <div class="author-detail">
                      <div class="name text-dark text-uppercase pt-2">Emma Chamberlin</div>
                    </div>
                  </div>
                </div>
                <div class="swiper-slide text-center d-flex justify-content-center">
                  <div class="review-item col-md-10">
                    <i class="icon icon-review"></i>
                    <blockquote>“A blog is a digital publication that can complement a website or exist independently. A blog may include articles, short posts, listicles, infographics, videos, and other digital content.”</blockquote>
                    <div class="rating">
                      <svg class="star star-fill">
                        <use xlink:href="#star-fill"></use>
                      </svg>
                      <svg class="star star-fill">
                        <use xlink:href="#star-fill"></use>
                      </svg>
                      <svg class="star star-fill">
                        <use xlink:href="#star-fill"></use>
                      </svg>
                      <svg class="star star-half">
                        <use xlink:href="#star-half"></use>
                      </svg>
                      <svg class="star star-empty">
                        <use xlink:href="#star-empty"></use>
                      </svg>
                    </div>
                    <div class="author-detail">
                      <div class="name text-dark text-uppercase pt-2">Jennie Rose</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="swiper-icon swiper-arrow swiper-arrow-next position-absolute d-flex align-items-center">
              <svg class="chevron-right">
                <use xlink:href="#chevron-right" />
              </svg>
            </div>
          </div>
        </div>
      </div>
      <div class="swiper-pagination"></div>
    </section>
   <br>
   <br>
   <?php include("includes/footer.php")?>
    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="js/plugins.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    var swiper = new Swiper('.main-swiper', {
      direction: 'horizontal', // Ensure swiper swipes horizontally
      loop: true, // Enable loop
      navigation: {
        nextEl: '.swiper-arrow-next', // Specify the next arrow element
        prevEl: '.swiper-arrow-prev', // Specify the previous arrow element
      },
    });
  });
</script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
 
  <script type="text/javascript">
  $(document).ready(function() {

    // Send product details in the server
    $(".addItem").click(function(e) {
      e.preventDefault();
      

     
      var $form = $(this).closest(".form-submit");
      var pid = $form.find(".pid").val();
      var userid = $form.find(".user").val();
      var pqty = $form.find(".quantity").val();

     console.log(userid);

     $.ajax({
        url: 'addcart.php',
        method: 'post',
        data: {
            pid: pid,
            userid: userid,
            pqty: pqty
        },
        dataType: 'json',
        success: function(response) {
            alert(response.message); // Shfaq mesazhin nga përgjigja e serverit
        }
        
    });
      console.log("hej");
    });
    

    // Load total no.of items added in the cart and display in the navbar
   
  });
  </script>

  </body>

<!-- Mirrored from demo.templatesjungle.com/ministore/ by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 26 Mar 2024 19:59:35 GMT -->
</html>
