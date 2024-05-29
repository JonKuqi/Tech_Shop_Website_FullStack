<?php
session_start();
$conn = null;
//Logjika e faqes
//include("Data-Objects/fileManipulationFunctions.php");


include("includes/header.php");
include("Data-Objects/databaseManipulationFunctions.php");
include("databaseConnection.php");

require("Website-Php-functions/errorHandler.php");
require("Website-Php-functions/GabimPersonalizuar.php");


$products = arrayProductsFromDatabase($conn);
$users = arrayUsersFromDatabase($conn);

//marrja produktit nga shop 
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




$reviews = arrayReviewsFromDatabase($conn);


if(isset($_POST['rate']) && $_SERVER["REQUEST_METHOD"] == "POST") {
  $context = $_POST['context'];
  $rating = $_POST['rate'];

  $user_id = $currentUser->getId();
  $product_id = $product->getId();

   if($conn !=null)
  $stmt = $conn->prepare("INSERT INTO tblReview (pid, tbl_user_id, rating, context) VALUES (?, ?, ?, ?)");

  $stmt->bind_param("iiis", $product_id, $user_id, $rating, $context);

  $stmt->execute();

  if ($stmt->affected_rows > 0) {
      echo '<script>alert("You have successfully added a review!");</script>';
      echo '<script>window.location.href = window.location.href;</script>';
  } else {
      echo "Error: Unable to add review.";
  }

  $stmt->close();
}


//Reviews


//$review = new Review(1, $product, $users[0],5.0,"Amaizing Product");

//$productReviews = [];

//echo ' <script>
// async function getReviews(productId) {
//            const response = await fetch(`http://localhost/reviews.php?product_id=${productId}`);
//            const data = await response.json();
//
//        }
//</script>
//
//';



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


try{
$productRating = $sumRating/count($productReviews);
}catch(ArithmeticError $e){
  $productRating = "No reviews";
}



if(isset($_POST['add-to-cart'])){
   $quantity = $_POST['quantity'];
    try{
          if($quantity > $product->getQuantity()){
            throw new GabimSasie($product->getQuantity());
          } 
         else if (isProductInUserCart($conn, $currentUser->getId(), $product->getId())) {
            echo'<script>alert("This product is in your cart!");</script>';
           
        }else{
          addProductToShoppingCart($conn, $product,$currentUser,$quantity);
          echo '<script>alert("You have succesfully added to Cart!");</script>';  }
       
    }catch(GabimSasie $e){
      echo '<script>alert("'.$e->getMessage().'");</script>'; 
    }
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
                <strong class="text-primary display-6 fw-bold" style="color:red !important;"><?php if($product->getQuantity() == 0) echo "&nbsp&nbsp&nbspSOLD"; ?></strong>
              </div>
              <p><?php echo $product->getShortDescription(); ?></p>
              <div class="cart-wrap padding-small">
            
               <br><br><br>
                <div class="product-quantity">
                  <div class="stock-number text-dark"><?php echo $product->getQuantity(); ?>  in stock</div>
                  <div class="stock-button-wrap pt-3">
                  <form class="form-submit" method="post" action="<?php echo "single-product.php?product=".$product->getId();?>" >
                    <div class="input-group product-qty">
                        <span class="input-group-btn">
                            <button type="button" class="quantity-left-minus btn btn-number"  data-type="minus" data-field="">
                              -
                            </button>
                        </span>
                        <input type="hidden" class="pid" value="<?php echo $product->getId();?>">
                        <input type="text" id="quantity" name="quantity" class="form-control input-number quantity" value="1" min="1" max="10">
                        <span class="input-group-btn">
                            <button type="button" class="quantity-right-plus btn btn-number" data-type="plus" data-field="">
                                +
                            </button>
                        </span>
                    </div>
                  <?php  if(isset($_SESSION['logged_in'])){
                     echo ' <input type="hidden" class="user" value="'.$_SESSION['user_id'].'">';
                        }else{
                        echo ' <input type="hidden" class="user" value="1">';
                      }  ?>
                    <div class="qty-button d-flex flex-wrap pt-3">
                 
                      <button type="submit" class="btn btn-primary btn-medium text-uppercase me-3 mt-3">Buy now</button>
                      <button type="submit" name="add-to-cart" value="1269" class="btn btn-black btn-medium text-uppercase mt-3 addItem" <?php if($product->getQuantity() == 0){ echo "disabled";}else {} ?>>Add to cart</button>
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
                <div class="swiper-wrapper w-max" id='reviews-container'>





                

                <script>
    // Showing all the reviews for specific product


    getReviews(<?php echo $product->getId(); ?>); 

//http://localhost/Tech_Shop_Website_Gr.6//Data-Objects/API%20-%20review.php?product_id=3
    async function getReviews(productId) {
        const response = await fetch(`http://localhost/Tech_Shop_Website_Gr.6//Data-Objects/API%20-%20review.php?product_id=${productId}`);

        const data = await response.json();
   
        const reviewsContainer = document.getElementById('reviews-container');
        reviewsContainer.innerHTML = ''; 

        data.forEach(review => {
            const reviewDiv = document.createElement('div');
            reviewDiv.className = 'swiper-slide';
            reviewDiv.innerHTML = `
                <div class="group bg-white border border-solid border-gray-300 flex justify-between flex-col rounded-xl p-6 transition-all duration-500 w-full mx-auto slide_active:border-indigo-600 hover:border-indigo-600 hover:shadow-sm">
                    <div>
                        <div class="flex items-center mb-7 gap-2 text-amber-500 transition-all duration-500">
                            <svg class="w-5 h-5" viewBox="0 0 18 17" fill="none" height="15px" width="15px" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.10326 1.31699C8.47008 0.57374 9.52992 0.57374 9.89674 1.31699L11.7063 4.98347C11.8519 5.27862 12.1335 5.48319 12.4592 5.53051L16.5054 6.11846C17.3256 6.23765 17.6531 7.24562 17.0596 7.82416L14.1318 10.6781C13.8961 10.9079 13.7885 11.2389 13.8442 11.5632L14.5353 15.5931C14.6754 16.41 13.818 17.033 13.0844 16.6473L9.46534 14.7446C9.17402 14.5915 8.82598 14.5915 8.53466 14.7446L4.91562 16.6473C4.18199 17.033 3.32456 16.41 3.46467 15.5931L4.15585 11.5632C4.21148 11.2389 4.10393 10.9079 3.86825 10.6781L0.940384 7.82416C0.346867 7.24562 0.674378 6.23765 1.4946 6.11846L5.54081 5.53051C5.86652 5.48319 6.14808 5.27862 6.29374 4.98347L8.10326 1.31699Z" style="fill: yellow;" stroke="silver"/>
                            </svg>
                            <span class="text-base font-semibold text-indigo-600">${review.rating}</span>
                        </div>
                        <p class="text-base text-gray-600 leading-6 transition-all duration-500 pb-8 group-hover:text-gray-800 slide_active:text-gray-800">${review.context}</p>
                    </div>
                    <div class="flex items-center gap-5 pt-5 border-t border-solid border-gray-200">
                        <div class="block">
                            <h5 class="text-gray-900 font-medium transition-all duration-500 mb-1">${review.first_name} ${review.last_name}</h5>
                            <span class="text-sm leading-4 text-gray-500"></span>
                        </div>
                    </div>
                </div>
            `;
            reviewsContainer.appendChild(reviewDiv);
        });
     }
</script>
                <?php 

//          foreach($productReviews as $pr){
//           $pr->shfaq();
          ?>














                <div class="swiper-pagination"></div>
            </div>
        </div>


    </section>
    <br>
<br>

    <h2 class="text-4xl text-center font-bold text-gray-900 "style="text-align:left;">Leave a Review</h2>

    
    <form id="reviewForm" class="form-group padding-small" action="http://localhost/Tech_Shop_Website_Gr.6/Data-Objects/API%20-%20review.php" method="post">
    <p style="text-align:center;">You need to be logged in to leave a review *</p>
    <div class="row">
        <div class="col-lg-12 mb-3"></div>
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <textarea class="form-control ps-3 pt-3" id="context" name="context" placeholder="Write your review here *"></textarea>
            <div class="container1">
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
            <button class="btn btn-medium btn-black text-uppercase btn-rounded-none" style="color:white" type="submit">Post Review</button>
        </div>
        <div class="col-lg-2"></div>
    </div>
    <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>">
    <input type="hidden" name="user_id" value="<?php echo $currentUser->getId(); ?>">
</form>





           <script>

//SHTIMI I NJE REVIEW ME API

document.getElementById('reviewForm').addEventListener('submit', async function(event) {
    event.preventDefault(); // Prevent the default form submission

    const context = document.getElementById('context').value;
    const rating = document.querySelector('input[name="rate"]:checked').value;
    const productId = <?php echo $product->getId(); ?>; // Ensure this PHP variable is set
    const userId = <?php echo $currentUser->getId(); ?>; // Ensure this PHP variable is set

    const data = {
        product_id: productId,
        user_id: userId,
        rating: parseInt(rating),
        context: context
    };

    try {
        const response = await fetch('http://localhost/Tech_Shop_Website_Gr.6/Data-Objects/API%20-%20review.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        const result = await response.json();

        if (response.ok) {
            alert(result.message);
            window.location.reload();
        } else {
            alert(result.error);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while adding the review.');
    }
});
</script>

          
     









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
<script type="text/javascript">
 
  </script>
  </body>

<!-- Mirrored from demo.templatesjungle.com/ministore/single-product.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 26 Mar 2024 20:00:01 GMT -->
</html>
