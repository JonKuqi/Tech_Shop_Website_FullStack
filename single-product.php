<?php

//Logjika e faqes
 include("includes/header.php");
include("Data-Objects/fileManipulationFunctions.php");



$products = arrayProductsFromFile();
$users = arrayUsersFromFile();

if (isset($_GET['product'])) $linkchoice=$_GET['product'];
else $linkchoice='';

$product = $products[0];

foreach($products as $p){
  if($p->getId() == $linkchoice){
      $product = $p;
  }
}
include("Data-Objects/recomendProducts.php");
addProductCookie($product);


$category = "Undefined";
if ($product instanceof SmartPhone) {
  $category = "Smart Phone";
}elseif($product instanceof SmartWatch){
  $category = "Smart Watch";
}elseif($product instanceof Laptop){
  $category = "Laptop";
}elseif($product instanceof OtherBrands){
  $category = "Other Brands";
}



//Leaving a review

$currentUser = $users[0];
$reviews = arrayReviewsFromFile();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $context = $_POST['context'];
  $rating = $_POST['rate'];
  
 $newReview = new Review($reviews[count($reviews)-1]->getId()+1,$product,$currentUser, intval($rating),$context);
 $newReview->registerReview();
 echo '<script>alert("You have succesfully added a review!");</script>';
 header("Refresh:0");
}



//Reviews


//$review = new Review(1, $product, $users[0],5.0,"Amaizing Product");



$productReviews = [];

foreach($reviews as $r){
  if($product->getId() == $r->getProduct()->getId()){
    array_push($productReviews, $r);
  }
}
$sumRating = 0;

foreach($productReviews as $r){
  $sumRating+=$r->getRating();
}
if(count($productReviews)>0){
$productRating = $sumRating/count($productReviews);
}else{$productRating = "Undefined";}




if(isset($_POST['add-to-cart'])){
   $quantity = $_POST['quantity'];
      addProductToShopingCard($product,$currentUser,$quantity);
}

?>



<!DOCTYPE html>
<html>
  
<!-- Mirrored from demo.templatesjungle.com/ministore/single-product.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 26 Mar 2024 19:59:59 GMT -->
<head>
    <title>Ministore</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="cdn.jsdelivr.net/npm/swiper%409/swiper-bundle.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500&amp;family=Lato:wght@300;400;700&amp;display=swap" rel="stylesheet">
    <!-- script
    ================================================== -->
    <script src="js/modernizr.js"></script>
    
   <style>

#SingleProduct-size{
   height: 719px;
   float:right;
   margin-right: 4vh;
 }
 </style>
<style>
        .swiper-horizontal>.swiper-pagination-bullets .swiper-pagination-bullet,
        .swiper-pagination-horizontal.swiper-pagination-bullets .swiper-pagination-bullet {
            width: 16px !important;
            height: 4px !important;
            border-radius: 5px !important;
            margin: 0 6px !important;
        }

        .swiper-pagination {
            bottom: 2px !important;
        }

        .swiper-wrapper {
            height: max-content !important;
            width: max-content !important;
            padding-bottom: 64px !important;
        }

        .swiper-pagination-bullet-active {
            background: #4F46E5 !important;
        }
        
        .swiper-slide.swiper-slide-active>.slide_active\:border-indigo-600 {
            --tw-border-opacity: 1 !important;
            border-color: rgb(79 70 229 / var(--tw-border-opacity)) !important;
            padding:10px !important;
        }

        .swiper-slide.swiper-slide-active>.group .slide_active\:text-gray-800 {
            ---tw-text-opacity: 1 !important;
            color: rgb(31 41 55 / var(--tw-text-opacity)) !important;
            padding:10px !important;
        }




        .rate {
    float: left;
    height: 46px;
    padding: 0 10px;
}
.rate:not(:checked) > input {
    position:absolute;
    top:-9999px;
}
.rate:not(:checked) > label {
    float:right;
    width:1em;
    overflow:hidden;
    white-space:nowrap;
    cursor:pointer;
    font-size:30px;
    color:#ccc;
}
.rate:not(:checked) > label:before {
    content: '★ ';
}
.rate > input:checked ~ label {
    color: #ffc700;    
}
.rate:not(:checked) > label:hover,
.rate:not(:checked) > label:hover ~ label {
    color: #deb217;  
}
.rate > input:checked + label:hover,
.rate > input:checked + label:hover ~ label,
.rate > input:checked ~ label:hover,
.rate > input:checked ~ label:hover ~ label,
.rate > label:hover ~ input:checked ~ label {
    color: #c59b08;
}

    </style>


  </head>
  <body>

    <section id="selling-product" class="single-product padding-xlarge">
      <div class="container">
        <div class="row mt-5">
          <div class="col-lg-6">
            <div class="product-preview mb-3">
            <div class="product-preview mb-3">


                  <img src="<?php echo $product->getImages()[0]; ?>" alt="single-product" class="img-fluid" id="SingleProduct-size">



             </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="product-info">
              <div class="element-header">
                <h3 itemprop="name" class="display-7 text-uppercase"><?php echo $product->getName(); ?></h3>
                <div class="rating-container d-flex align-items-center">
                  <div class="rating" data-rating="1" onclick=rate(1)>
                    <svg class="star star-fill">
                      <use xlink:href="#star-fill"></use>
                    </svg>
                    <span class="rating-count ps-2"><?php echo $productRating ?></span>
                  </div>
                </div>
              </div>
              <div class="product-price pt-3 pb-3">
                <strong class="text-primary display-6 fw-bold"><?php echo ($product->getPrice()-($product->getPrice()*$product->getDiscount())); ?>€</strong>
              </div>
              <p><?php echo $product->getShortDescription(); ?></p>
              <div class="cart-wrap padding-small">
            
               <br><br><br>
                <div class="product-quantity">
                  <div class="stock-number text-dark"><?php echo $product->getQuantity(); ?>  in stock</div>
                  <div class="stock-button-wrap pt-3">
                  <form method="post" action="<?php echo "single-product.php?product=".$product->getId();?>">
                    <div class="input-group product-qty">
                        <span class="input-group-btn">
                            <button type="button" class="quantity-left-minus btn btn-number"  data-type="minus" data-field="">
                              -
                            </button>
                        </span>
                        <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1" min="1" max="10">
                        <span class="input-group-btn">
                            <button type="button" class="quantity-right-plus btn btn-number" data-type="plus" data-field="">
                                +
                            </button>
                        </span>
                    </div>
                    <div class="qty-button d-flex flex-wrap pt-3">
                 
                      <button type="submit" class="btn btn-primary btn-medium text-uppercase me-3 mt-3">Buy now</button>
                      <button type="submit" name="add-to-cart" value="1269" class="btn btn-black btn-medium text-uppercase mt-3">Add to cart</button>
                 </form>                      
                    </div>
                  </div>
                </div>
              </div>
              <br><br>
              <div class="meta-product py-2">
                <div class="meta-item d-flex align-items-baseline">
                  <h4 class="item-title no-margin pe-2">SKU:</h4>
                  <ul class="select-list list-unstyled d-flex">
                    <li data-value="S" class="select-item"><?php echo $product->getSku(); ?></li>
                  </ul>
                </div>
                <div class="meta-item d-flex align-items-baseline">
                  <h4 class="item-title no-margin pe-2">Category: </h4>
                  <ul class="select-list list-unstyled d-flex">
                    <li data-value="S" class="select-item">
                      <a href="#"><?php echo $category; ?></a>
                    </li>
                    
                  </ul>
                </div>
                

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="product-info-tabs">
      <div class="container">
        <div class="row">
          <div class="tabs-listing">
            <nav>
              <div class="nav nav-tabs d-flex flex-wrap justify-content-center" id="nav-tab" role="tablist"><button class="nav-link text-uppercase pe-5" id="nav-review-tab" data-bs-toggle="tab" data-bs-target="#nav-review" type="button" role="tab" aria-controls="nav-review" aria-selected="true">Reviews</button>
                <button class="nav-link active text-uppercase pe-5" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="false" >Description</button>
               
                
              </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
              <div class="tab-pane fade show active border-top border-bottom padding-small" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                <p>Product Description</p>
                <p><?php echo $product->getLongDescription(); ?></p>
                
              </div>
              <div class="tab-pane fade border-top border-bottom padding-small" id="nav-information" role="tabpanel" aria-labelledby="nav-information-tab">
                <p>It is Comfortable and Best</p>
                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
              </div>
              <div class="tab-pane fade border-top border-bottom padding-small" id="nav-review" role="tabpanel" aria-labelledby="nav-review-tab">

<!--review section -->
              <section class="py-24 ">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-16 ">
                <span class="text-sm text-gray-500 font-medium text-center block mb-2"></span>
                <h2 class="text-4xl text-center font-bold text-gray-900 ">Product Reviews</h2>
            </div>
            <!--Slider wrapper-->


            <div class="swiper mySwiper">
                <div class="swiper-wrapper w-max">





                <?php 
          //Ketu behen show reviews

          foreach($productReviews as $pr){
           $pr->shfaq();
          }
          
          
          
          ?>

                    




                <div class="swiper-pagination"></div>
            </div>
        </div>


    </section>
    <br>
<br>

    <h2 class="text-4xl text-center font-bold text-gray-900 "style="text-align:left;">Leave a Review</h2>

    <form method="post" class="form-group padding-small">
                        <p style="text-align:center;">You need to be logged in to leave a review *</p>
                          <div class="row">
                            <div class="col-lg-12 mb-3">
                              
                            </div>
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                            <textarea class="form-control ps-3 pt-3" id="comment" name="context" placeholder="Write your review here *"></textarea>
                            <div class="container1">
                              <!-- Stars -->
  
                              <div class="rate">
    <input type="radio" id="star5" name="rate" value="5" />
    <label for="star5" title="text">5 stars</label>
    <input type="radio" id="star4" name="rate" value="4" />
    <label for="star4" title="text">4 stars</label>
    <input type="radio" id="star3" name="rate" value="3" />
    <label for="star3" title="text">3 stars</label>
    <input type="radio" id="star2" name="rate" value="2" />
    <label for="star2" title="text">2 stars</label>
    <input type="radio" id="star1" name="rate" value="1" />
    <label for="star1" title="text">1 star</label>
  </div>

  <script>
  // Get all labels inside .rate
  var labels = document.querySelectorAll('.rate label');

  // Add click event listener to each label
  labels.forEach(function(label) {
    label.addEventListener('click', function(event) {
      // Prevent the default action (form submission)
      event.preventDefault();
      
      // Get the associated radio button
      var associatedInputId = label.getAttribute('for');
      var associatedInput = document.getElementById(associatedInputId);
      
      // Check the associated radio button
      if (associatedInput) {
        associatedInput.checked = true;
      }
    });
  });
</script>


                            </div>

</div>
<div class="col-lg-2"></div>
<div class="col-lg-2"></div>
                            <div class="col-lg-8 mt-3">
                              <button class="btn btn-medium btn-black text-uppercase btn-rounded-none" style="color:white" type="submit">Post Review </button>
                            </div>
                            <div class="col-lg-2"></div>
                          </div>
           </form>


     









              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section id="related-products" class="product-store position-relative padding-large">
      <div class="container">
        <div class="row">
          <div class="display-header d-flex justify-content-between pb-3">
            <h2 class="display-7 text-dark text-uppercase">Related Products</h2>
            <div class="btn-right">
              <a href="shop.php" class="btn btn-medium btn-normal text-uppercase">Go to Shop</a>
            </div>
          </div>
          <div class="swiper product-swiper">
            <div class="swiper-wrapper">
              <div class="swiper-slide">
                <div class="product-card position-relative">
                  <div class="image-holder">
                    <img src="images/product-item1.jpg" alt="product-item" class="img-fluid">
                  </div>
                  <div class="cart-concern position-absolute">
                    <div class="cart-button d-flex">
                      <div class="btn-left">
                        <a href="#" class="btn btn-medium btn-black">Add to Cart</a>
                        <svg class="cart-outline position-absolute">
                          <use xlink:href="#cart-outline"></use>
                        </svg>
                      </div>
                    </div>
                  </div>
                  <div class="card-detail d-flex justify-content-between align-items-baseline pt-3">
                    <h3 class="card-title text-uppercase">
                      <a href="#">Iphone 10</a>
                    </h3>
                    <span class="item-price text-primary">$980</span>
                  </div>
                </div>
              </div>
              <div class="swiper-slide">
                <div class="product-card position-relative">
                  <div class="image-holder">
                    <img src="images/product-item2.jpg" alt="product-item" class="img-fluid">
                  </div>
                  <div class="cart-concern position-absolute">
                    <div class="cart-button d-flex">
                      <div class="btn-left">
                        <a href="#" class="btn btn-medium btn-black">Add to Cart</a>
                        <svg class="cart-outline position-absolute">
                          <use xlink:href="#cart-outline"></use>
                        </svg>
                      </div>
                    </div>
                  </div>
                  <div class="card-detail d-flex justify-content-between align-items-baseline pt-3">
                    <h3 class="card-title text-uppercase">
                      <a href="#">Iphone 11</a>
                    </h3>
                    <span class="item-price text-primary">$1100</span>
                  </div>
                </div>
              </div>
              <div class="swiper-slide">
                <div class="product-card position-relative">
                  <div class="image-holder">
                    <img src="images/product-item3.jpg" alt="product-item" class="img-fluid">
                  </div>
                  <div class="cart-concern position-absolute">
                    <div class="cart-button d-flex">
                      <div class="btn-left">
                        <a href="#" class="btn btn-medium btn-black">Add to Cart</a>
                        <svg class="cart-outline position-absolute">
                          <use xlink:href="#cart-outline"></use>
                        </svg>
                      </div>
                    </div>
                  </div>
                  <div class="card-detail d-flex justify-content-between align-items-baseline pt-3">
                    <h3 class="card-title text-uppercase">
                      <a href="#">Iphone 8</a>
                    </h3>
                    <span class="item-price text-primary">$780</span>
                  </div>
                </div>
              </div>
              <div class="swiper-slide">
                <div class="product-card position-relative">
                  <div class="image-holder">
                    <img src="images/product-item4.jpg" alt="product-item" class="product-image">
                  </div>
                  <div class="cart-concern position-absolute">
                    <div class="cart-button d-flex">
                      <div class="btn-left">
                        <a href="#" class="btn btn-medium btn-black">Add to Cart</a>
                        <svg class="cart-outline position-absolute">
                          <use xlink:href="#cart-outline"></use>
                        </svg>
                      </div>
                    </div>
                  </div>
                  <div class="card-detail d-flex justify-content-between align-items-baseline pt-3">
                    <h3 class="card-title text-uppercase">
                      <a href="#">Iphone 13</a>
                    </h3>
                    <span class="item-price text-primary">$1500</span>
                  </div>
                </div>
              </div>
              <div class="swiper-slide">
                <div class="product-card position-relative">
                  <div class="image-holder">
                    <img src="images/product-item5.jpg" alt="product-item" class="product-image">
                  </div>
                  <div class="cart-concern position-absolute">
                    <div class="cart-button d-flex">
                      <div class="btn-left">
                        <a href="#" class="btn btn-medium btn-black">Add to Cart</a>
                        <svg class="cart-outline position-absolute">
                          <use xlink:href="#cart-outline"></use>
                        </svg>
                      </div>
                    </div>
                  </div>
                  <div class="card-detail d-flex justify-content-between align-items-baseline pt-3">
                    <h3 class="card-title text-uppercase">
                      <a href="#">Iphone 12</a>
                    </h3>
                    <span class="item-price text-primary">$1300</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="swiper-pagination position-absolute text-center"></div>
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
function incrementQuantity() {
    var quantityInput = document.getElementById("quantity");
    var max = parseInt(quantityInput.max);
    var currentValue = parseInt(quantityInput.value);
    if (currentValue < max) {
        quantityInput.value = currentValue + 1;
    }
}
</script>

<script>
    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 1,
        spaceBetween: 32,
        loop: true,
        centeredSlides: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,

        },
        autoplay: {
            delay: 6000,
            disableOnInteraction: false,
        },
        breakpoints: {
        640: {
          slidesPerView: 1,
          spaceBetween: 32,
        },
        768: {
          slidesPerView: 2,
          spaceBetween: 32,
        },
        1024: {
          slidesPerView: 3,
          spaceBetween: 32,
        },
      },
    });
</script>
  </body>

<!-- Mirrored from demo.templatesjungle.com/ministore/single-product.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 26 Mar 2024 20:00:01 GMT -->
</html>
