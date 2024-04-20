<?php

//Logjika e faqes

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
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
      <symbol id="search" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
        <path fill="currentColor" d="M19 3C13.488 3 9 7.488 9 13c0 2.395.84 4.59 2.25 6.313L3.281 27.28l1.439 1.44l7.968-7.969A9.922 9.922 0 0 0 19 23c5.512 0 10-4.488 10-10S24.512 3 19 3zm0 2c4.43 0 8 3.57 8 8s-3.57 8-8 8s-8-3.57-8-8s3.57-8 8-8z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="user" viewBox="0 0 16 16">
        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="cart" viewBox="0 0 16 16">
        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="star-fill" viewBox="0 0 16 16">
        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="facebook" viewBox="0 0 24 24">
        <path fill="currentColor" d="M9.198 21.5h4v-8.01h3.604l.396-3.98h-4V7.5a1 1 0 0 1 1-1h3v-4h-3a5 5 0 0 0-5 5v2.01h-2l-.396 3.98h2.396v8.01Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="youtube" viewBox="0 0 32 32">
        <path fill="currentColor" d="M29.41 9.26a3.5 3.5 0 0 0-2.47-2.47C24.76 6.2 16 6.2 16 6.2s-8.76 0-10.94.59a3.5 3.5 0 0 0-2.47 2.47A36.13 36.13 0 0 0 2 16a36.13 36.13 0 0 0 .59 6.74a3.5 3.5 0 0 0 2.47 2.47c2.18.59 10.94.59 10.94.59s8.76 0 10.94-.59a3.5 3.5 0 0 0 2.47-2.47A36.13 36.13 0 0 0 30 16a36.13 36.13 0 0 0-.59-6.74ZM13.2 20.2v-8.4l7.27 4.2Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="twitter" viewBox="0 0 256 256">
        <path fill="currentColor" d="m245.66 77.66l-29.9 29.9C209.72 177.58 150.67 232 80 232c-14.52 0-26.49-2.3-35.58-6.84c-7.33-3.67-10.33-7.6-11.08-8.72a8 8 0 0 1 3.85-11.93c.26-.1 24.24-9.31 39.47-26.84a110.93 110.93 0 0 1-21.88-24.2c-12.4-18.41-26.28-50.39-22-98.18a8 8 0 0 1 13.65-4.92c.35.35 33.28 33.1 73.54 43.72V88a47.87 47.87 0 0 1 14.36-34.3A46.87 46.87 0 0 1 168.1 40a48.66 48.66 0 0 1 41.47 24H240a8 8 0 0 1 5.66 13.66Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="instagram" viewBox="0 0 256 256">
        <path fill="currentColor" d="M128 80a48 48 0 1 0 48 48a48.05 48.05 0 0 0-48-48Zm0 80a32 32 0 1 1 32-32a32 32 0 0 1-32 32Zm48-136H80a56.06 56.06 0 0 0-56 56v96a56.06 56.06 0 0 0 56 56h96a56.06 56.06 0 0 0 56-56V80a56.06 56.06 0 0 0-56-56Zm40 152a40 40 0 0 1-40 40H80a40 40 0 0 1-40-40V80a40 40 0 0 1 40-40h96a40 40 0 0 1 40 40ZM192 76a12 12 0 1 1-12-12a12 12 0 0 1 12 12Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="linkedin" viewBox="0 0 24 24">
        <path fill="currentColor" d="M6.94 5a2 2 0 1 1-4-.002a2 2 0 0 1 4 .002zM7 8.48H3V21h4V8.48zm6.32 0H9.34V21h3.94v-6.57c0-3.66 4.77-4 4.77 0V21H22v-7.93c0-6.17-7.06-5.94-8.72-2.91l.04-1.68z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="nav-icon" viewBox="0 0 16 16">
        <path d="M14 10.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 .5-.5zm0-3a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 0 0 1h7a.5.5 0 0 0 .5-.5zm0-3a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0 0 1h11a.5.5 0 0 0 .5-.5z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="close" viewBox="0 0 16 16">
        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="navbar-icon" viewBox="0 0 16 16">
        <path d="M14 10.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 .5-.5zm0-3a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 0 0 1h7a.5.5 0 0 0 .5-.5zm0-3a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0 0 1h11a.5.5 0 0 0 .5-.5z" />
      </symbol>
    </svg>

    <div class="search-popup">
        <div class="search-popup-container">

          <form role="search" method="get" class="search-form" action="#">
            <input type="search" id="search-form" class="search-field" placeholder="Type and press enter" value="" name="s" />
            <button type="submit" class="search-submit"><svg class="search"><use xlink:href="#search"></use></svg></button>
          </form>

          <h5 class="cat-list-title">Browse Categories</h5>
          
          <ul class="cat-list">
            <li class="cat-list-item">
              <a href="#" title="Mobile Phones">Mobile Phones</a>
            </li>
            <li class="cat-list-item">
              <a href="#" title="Smart Watches">Smart Watches</a>
            </li>
            <li class="cat-list-item">
              <a href="#" title="Headphones">Headphones</a>
            </li>
            <li class="cat-list-item">
              <a href="#" title="Accessories">Accessories</a>
            </li>
            <li class="cat-list-item">
              <a href="#" title="Monitors">Monitors</a>
            </li>
            <li class="cat-list-item">
              <a href="#" title="Speakers">Speakers</a>
            </li>
            <li class="cat-list-item">
              <a href="#" title="Memory Cards">Memory Cards</a>
            </li>
          </ul>

        </div>
    </div>
    
    <header id="header" class="site-header header-scrolled position-fixed text-black bg-light">
      <nav id="header-nav" class="navbar navbar-expand-lg px-3 mb-3">
        <div class="container-fluid">
          <a class="navbar-brand" href="index.php">
            <img src="images/main-logo.png" class="logo">
          </a>
          <button class="navbar-toggler d-flex d-lg-none order-3 p-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#bdNavbar" aria-controls="bdNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <svg class="navbar-icon">
              <use xlink:href="#navbar-icon"></use>
            </svg>
          </button>
          <div class="offcanvas offcanvas-end" tabindex="-1" id="bdNavbar" aria-labelledby="bdNavbarOffcanvasLabel">
            <div class="offcanvas-header px-4 pb-0">
              <a class="navbar-brand" href="index.php">
                <img src="images/main-logo.png" class="logo">
              </a>
              <button type="button" class="btn-close btn-close-black" data-bs-dismiss="offcanvas" aria-label="Close" data-bs-target="#bdNavbar"></button>
            </div>
            <div class="offcanvas-body">
              <ul id="navbar" class="navbar-nav text-uppercase justify-content-end align-items-center flex-grow-1 pe-3">
                <li class="nav-item">
                  <a class="nav-link me-4 active" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link me-4" href="about.html">About Us</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link me-4" href="blog.html">Blog</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link me-4" href="shop.php">Shop</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link me-4" href="contact.html">Contact</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link me-4" href="checkout.html">Checkout</a>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link me-4 dropdown-toggle link-dark" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Pages</a>
                  <ul class="dropdown-menu">
                    <li>
                      <a href="about.html" class="dropdown-item">About</a>
                    </li>
                    <li>
                      <a href="blog.html" class="dropdown-item">Blog</a>
                    </li>
                    <li>
                      <a href="shop.php" class="dropdown-item">Shop</a>
                    </li>
                    <li>
                      <a href="cart.php" class="dropdown-item">Cart</a>
                    </li>
                    <li>
                      <a href="checkout.html" class="dropdown-item">Checkout</a>
                    </li>
                    <li>
                      <a href="single-post.html" class="dropdown-item">Single Post</a>
                    </li>
                    <li>
                      <a href="single-product.html" class="dropdown-item">Single Product</a>
                    </li>
                    <li>
                      <a href="contact.html" class="dropdown-item">Contact</a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item">
                  <div class="user-items ps-5">
                    <ul class="d-flex justify-content-end list-unstyled">
                      <li class="search-item pe-3">
                        <a href="#" class="search-button">
                          <svg class="search">
                            <use xlink:href="#search"></use>
                          </svg>
                        </a>
                      </li>
                      <li class="pe-3">
                        <a href="#">
                          <svg class="user">
                            <use xlink:href="#user"></use>
                          </svg>
                        </a>
                      </li>
                      <li>
                        <a href="cart.html">
                          <svg class="cart">
                            <use xlink:href="#cart"></use>
                          </svg>
                        </a>
                      </li>
                    </ul>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </nav>
    </header>


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
                <div class="color-options product-select">
                  <div class="color-toggle" data-option-index="0">
                    <h4 class="item-title text-uppercase text-dark text-decoration-underline">Color:</h4>
                    <ul class="select-list list-unstyled d-flex">
                      <li class="select-item pe-3" data-val="Green" title="Green">
                        <a href="#">Green</a>
                      </li>
                      <li class="select-item pe-3" data-val="Orange" title="Orange">
                        <a href="#">Orange</a>
                      </li>
                      <li class="select-item pe-3" data-val="Red" title="Red">
                        <a href="#">Red</a>
                      </li>
                      <li class="select-item" data-val="Black" title="Black">
                        <a href="#">Black</a>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="swatch product-select" data-option-index="1">
                  <h4 class="item-title text-uppercase text-dark text-decoration-underline">Size:</h4>
                  <ul class="select-list list-unstyled d-flex">
                    <li data-value="S" class="select-item pe-3">
                      <a href="#">XL</a>
                    </li>
                    <li data-value="M" class="select-item pe-3">
                      <a href="#">L</a>
                    </li>
                    <li data-value="L" class="select-item pe-3">
                      <a href="#">M</a>
                    </li>
                    <li data-value="L" class="select-item">
                      <a href="#">S</a>
                    </li>
                  </ul>
                </div>
                <div class="product-quantity">
                  <div class="stock-number text-dark"><?php echo $product->getQuantity(); ?>  in stock</div>
                  <div class="stock-button-wrap pt-3">

                    <div class="input-group product-qty">
                        <span class="input-group-btn">
                            <button type="button" class="quantity-left-minus btn btn-number"  data-type="minus" data-field="">
                              -
                            </button>
                        </span>
                        <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1" min="1" max="10">
                        <span class="input-group-btn">
                            <button type="button" onclick="incrementQuantity()" class="quantity-right-plus btn btn-number" data-type="plus" data-field="">
                                +
                            </button>
                        </span>
                    </div>
                    <div class="qty-button d-flex flex-wrap pt-3">
                      <button type="submit" class="btn btn-primary btn-medium text-uppercase me-3 mt-3">Buy now</button>
                      <button type="submit" name="add-to-cart" value="1269" class="btn btn-black btn-medium text-uppercase mt-3">Add to cart</button>                      
                    </div>
                  </div>
                </div>
              </div>
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
                <div class="meta-item d-flex align-items-baseline">
                  <h4 class="item-title no-margin pe-2">Tags:</h4>
                  <ul class="select-list list-unstyled d-flex">
                    <li data-value="S" class="select-item">
                      <a href="#">Classic</a>,
                    </li>
                    <li data-value="S" class="select-item">
                      <a href="#"> Modern</a>
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
    <section id="instagram" class="padding-large overflow-hidden no-padding-top">
      <div class="container">
        <div class="row">
          <div class="display-header text-uppercase text-dark text-center pb-3">
            <h2 class="display-7">Shop Our Insta</h2>
          </div>
          <div class="d-flex flex-wrap">
            <figure class="instagram-item pe-2">
              <a href="https://www.instagram.com/" class="image-link position-relative">
                <img src="images/insta-item1.jpg" alt="instagram" class="insta-image">
                <div class="icon-overlay position-absolute d-flex justify-content-center">
                  <svg class="instagram">
                    <use xlink:href="#instagram"></use>
                  </svg>
                </div>
              </a>
            </figure>
            <figure class="instagram-item pe-2">
              <a href="https://www.instagram.com/" class="image-link position-relative">
                <img src="images/insta-item2.jpg" alt="instagram" class="insta-image">
                <div class="icon-overlay position-absolute d-flex justify-content-center">
                  <svg class="instagram">
                    <use xlink:href="#instagram"></use>
                  </svg>
                </div>
              </a>
            </figure>
            <figure class="instagram-item pe-2">
              <a href="https://www.instagram.com/" class="image-link position-relative">
                <img src="images/insta-item3.jpg" alt="instagram" class="insta-image">
                <div class="icon-overlay position-absolute d-flex justify-content-center">
                  <svg class="instagram">
                    <use xlink:href="#instagram"></use>
                  </svg>
                </div>
              </a>
            </figure>
            <figure class="instagram-item pe-2">
              <a href="https://www.instagram.com/" class="image-link position-relative">
                <img src="images/insta-item4.jpg" alt="instagram" class="insta-image">
                <div class="icon-overlay position-absolute d-flex justify-content-center">
                  <svg class="instagram">
                    <use xlink:href="#instagram"></use>
                  </svg>
                </div>
              </a>
            </figure>
            <figure class="instagram-item pe-2">
              <a href="https://www.instagram.com/" class="image-link position-relative">
                <img src="images/insta-item5.jpg" alt="instagram" class="insta-image">
                <div class="icon-overlay position-absolute d-flex justify-content-center">
                  <svg class="instagram">
                    <use xlink:href="#instagram"></use>
                  </svg>
                </div>
              </a>
            </figure>
          </div>
        </div>
      </div>
    </section>
    <footer id="footer" class="overflow-hidden">
      <div class="container">
        <div class="row">
          <div class="footer-top-area">
            <div class="row d-flex flex-wrap justify-content-between">
              <div class="col-lg-3 col-sm-6 pb-3">
                <div class="footer-menu">
                  <img src="images/main-logo.png" alt="logo">
                  <p>"Unlock Boundless Possibilities with Cutting-Edge Tech: Your Destination for Innovation and Excellence!"</p>
                  <div class="social-links">
                    <ul class="d-flex list-unstyled">
                      <li>
                      <a href="https://www.facebook.com/">
                          <svg class="facebook">
                            <use xlink:href="#facebook" />
                          </svg>
                        </a>
                      </li>
                      <li>
                        <a href="https://www.instagram.com/">
                          <svg class="instagram">
                            <use xlink:href="#instagram" />
                          </svg>
                        </a>
                      </li>
                      <li>
                        <a href="https://twitter.com/?lang=en">
                          <svg class="twitter">
                            <use xlink:href="#twitter" />
                          </svg>
                        </a>
                      </li>
                      <li>
                        <a href="https://www.linkedin.com/">
                          <svg class="linkedin">
                            <use xlink:href="#linkedin" />
                          </svg>
                        </a>
                      </li>
                      <li>
                        <a href="https://www.youtube.com/">
                          <svg class="youtube">
                            <use xlink:href="#youtube" />
                          </svg>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-lg-2 col-sm-6 pb-3">
                <div class="footer-menu text-uppercase">
                  <h5 class="widget-title pb-2">Speedy Buttons</h5>
                  <ul class="menu-list list-unstyled text-uppercase">
                    <li class="menu-item pb-2">
                      <a href="index.php">Go to Home</a>
                    </li>
                    <li class="menu-item pb-2">
                      <a href="about.html">About our shop</a>
                    </li>
                    <li class="menu-item pb-2">
                      <a href="shop.php">Visit the shop</a>
                    </li>
                    <li class="menu-item pb-2">
                      <a href="blog.html">View our Blogs</a>
                    </li>
                    <li class="menu-item pb-2">
                      <a href="contact.html">Contact us</a>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 pb-3">
                <div class="footer-menu contact-item">
                  <h5 class="widget-title text-uppercase pb-2">Contact Us</h5>
                  <p>Do you have any queries or suggestions? <a href="mailto:">yourinfo@gmail.com</a>
                  </p>
                  <p>If you need support? Just give us a call. <a href="#">+55 111 222 333 44</a>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
    </footer>
    <div id="footer-bottom">
      <div class="container">
        <div class="row d-flex flex-wrap justify-content-between">
          <div class="col-md-4 col-sm-6">
            <div class="Shipping d-flex">
              <p>We ship with:</p>
              <div class="card-wrap ps-2">
                <img src="images/dhl.png" alt="visa">
                <img src="images/shippingcard.png" alt="mastercard">
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-6">
            <div class="payment-method d-flex">
              <p>Payment options:</p>
              <div class="card-wrap ps-2">
                <img src="images/visa.jpg" alt="visa">
                <img src="images/mastercard.jpg" alt="mastercard">
                <img src="images/paypal.jpg" alt="paypal">
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-6">
            <div class="copyright">
              <p>© Copyright 2023 MiniStore. Design by <a href="https://templatesjungle.com/">TemplatesJungle</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
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
