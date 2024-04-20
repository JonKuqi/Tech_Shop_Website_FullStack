<?php  

include("Data-Objects/fileManipulationFunctions.php");
$products = arrayProductsFromFile();
var_dump($products);

$default = $products;
$sorted = [];

// Default sorting
$newProducts = $products;
$byName = true;
if(isset($_POST['sortimi']) && !isset($_POST['search'])){
if($_POST['sortimi'] == "default"){
    $newProducts = $products;
}
elseif($_POST['sortimi'] == "A-Z"){
    foreach($products as $p){
        array_push($sorted, $p->getName());
    } 
    sort($sorted); 
}
elseif($_POST['sortimi'] == "Z-A"){
    foreach($products as $p){
      array_push($sorted, $p->getName());
    } 
    rsort($sorted); 
}
elseif($_POST['sortimi'] == "price low-high"){
    foreach($products as $p){
        $sorted[$p->getId()] = $p->getPrice();
    } 
    asort($sorted); 
    $byName=false;
}
elseif($_POST['sortimi'] == "price high-low"){
    foreach($products as $p){
        $sorted[$p->getId()] = $p->getPrice();
    } 
    arsort($sorted); 
    $byName=false;
}
$newProducts = [];
if($byName){
  foreach($sorted as $s){
      foreach($products as $p){
        if($p->getName() == $s){
          array_push($newProducts, $p);
          break;
        }
      }
  }

}else{
foreach($sorted as $key => $value) {
    foreach($products as $p) {
        if($p->getId() == $key) {
            array_push($newProducts, $p);
            break;
        }
    }
}
}


}


//Searchi
include("Data-Objects/search.php");
if(isset($_POST['search'])){
  $search = $_POST['search'];
  $searchedProducts = searchProducts($search);
  $newProducts =[];

  foreach($searchedProducts as $key => $value) {
    foreach($products as $p) {
        if($p->getId() == $key) {
            array_push($newProducts, $p);
            break;
        }
    }
  }
}

$products=$newProducts;


//Filtrimi
$phoneCheck ="";
$watchCheck = "";
$laptopCheck = "";

if(isset($_POST['category'])){
  $category = $_POST['category'];
    $temp = [];
     foreach($category as $value){
       if($value == "phone"){
        $phoneCheck = "checked";
          foreach($products as $p){
            if($p instanceof SmartPhone){
              array_push($temp, $p);
            }
          }
       }
       if($value == "watch"){
        $watchCheck="checked";
        foreach($products as $p){
          if($p instanceof SmartWatch){
            array_push($temp, $p);
          }
        }
      }
      //if($value == "laptop"){
      //  $laptopCheck = "checked";
      //  foreach($products as $p){
       //   if($p instanceof SmartWatch){
       //     array_push($temp, $p);
       //   }
       // }
      //}

     }

   $products = $temp;
 }

?>



<?php

// Funksioni për sterilizimin e të dhënave të kërkimit
function sterilize($input) {
  // ktu mi fshi hapsirat e panevojshme
  $cleaned_input = trim($input);
  
  // karakteret e panevojshme mi fshi
  $cleaned_input = htmlspecialchars($cleaned_input);
  
  // rez
  return $cleaned_input;
}

//kqyrim a ka bo useri search
if (isset($_GET['search'])) {
  // perdorimi i funksionit sterilize
  $search_query = sterilize($_GET['search']);

  // kqyrim a ekziston cookie
  if (isset($_COOKIE['recent_searches'])) {
      $recent_searches = json_decode($_COOKIE['recent_searches'], true);

      // e shtojna searchin e fundit nliste
      array_unshift($recent_searches, $search_query);
  } else {
      // nëse nuk ekziston cookie, e rujme kerkimin per here tpare
      $recent_searches = [$search_query];
  }

  // pe konvertojna njson string per me rujt ncookie
  $recent_searches_json = json_encode($recent_searches);

  setcookie('recent_searches', $recent_searches_json, time() + (86400 * 30), '/');
}

/*

//me qet pjese tprintimit vec mundesh me vertetu a po ruhen ne cookie


if (isset($_COOKIE['recent_searches'])) {
  $recent_searches = json_decode($_COOKIE['recent_searches'], true);
  if (!empty($recent_searches)) {
      echo '<h2>Kërkimet e Fundit:</h2>';
      echo '<ul>';
      foreach ($recent_searches as $search) {
          echo '<li>' . htmlspecialchars($search) . '</li>';
      }
      echo '</ul>';
  }
}
*/

// shikojm a o dergu kerkesa get per me fshi 

if (isset($_GET['clearCookies'])) {
  // shikojm a ekziston cookie
  if (isset($_COOKIE['recent_searches'])) {
      // pe fshijme cookien
      setcookie('recent_searches', '', time() - 3600, '/');
      echo '<script>alert("Cookie u fshi me sukses!");</script>'; 
  } else {
      echo '<script>alert("Cookie nuk ekziston.");</script>'; 
  }

 
  echo '<script>window.location.href = window.location.pathname;</script>';
}



?>


<!DOCTYPE html>
<html>
  
<!-- Mirrored from demo.templatesjungle.com/ministore/shop.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 26 Mar 2024 19:59:48 GMT -->
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
  .checkbox-wrapper-20 {
    --slider-height: 8px !important;
    --slider-width: calc(var(--slider-height) * 4) !important;
    --switch-height: calc(var(--slider-height) * 3) !important;
    --switch-width: var(--switch-height) !important;
    --switch-shift: var(--slider-height) !important;
    --transition: all 0.2s ease !important;

    --switch-on-color: #ef0460 !important;
    --slider-on-color: #fc5d9b !important;

    --switch-off-color: #eeeeee !important;
    --slider-off-color: #c5c5c5 !important;
  }

  .checkbox-wrapper-20 .switch {
    display: block !important;
  }
    
  .checkbox-wrapper-20 .switch .slider {
    position: relative !important;
    display: inline-block !important;
    height: var(--slider-height) !important;
    width: var(--slider-width) !important;
    border-radius: var(--slider-height) !important;
    cursor: pointer !important;
    background: var(--slider-off-color) !important;
    transition: var(--transition) !important;
  }
      
  .checkbox-wrapper-20 .switch .slider:after {
    background: var(--switch-off-color) !important;
    position: absolute !important;
    left: calc(-1 * var(--switch-shift)) !important;
    top: calc((var(--slider-height) - var(--switch-height)) / 2) !important;
    display: block !important;
    width: var(--switch-height) !important;
    height: var(--switch-width) !important;
    border-radius: 50% !important;
    box-shadow: 0px 2px 2px rgba(0, 0, 0, .2) !important;
    content: '' !important;
    transition: var(--transition) !important;
  }
    
  .checkbox-wrapper-20 .switch label {
    margin-right: 7px !important;
  }
    
  .checkbox-wrapper-20 .switch .input {
    display: none !important;
  }
      
  .checkbox-wrapper-20 .switch .input ~ .label {
    margin-left: var(--slider-height) !important;
  }
         
  .checkbox-wrapper-20 .switch .input:checked ~ .slider:after {
    left: calc(var(--slider-width) - var(--switch-width) + var(--switch-shift)) !important;
  }
    
  .checkbox-wrapper-20 .switch .input:checked ~ .slider {
    background: var(--slider-on-color) !important;
  }

  .checkbox-wrapper-20 .switch .input:checked ~ .slider:after {
    background: var(--switch-on-color) !important;
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
      <svg xmlns="http://www.w3.org/2000/svg" id="chevron-left" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
      </svg>
      <symbol xmlns="http://www.w3.org/2000/svg" id="chevron-right" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="cart-outline" viewBox="0 0 16 16">
        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
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
                      <a href="single-product.php" class="dropdown-item">Single Product</a>
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
                      <a href="login-system-with-email-verification/index.php">
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
    <section class="hero-section position-relative bg-light-blue padding-medium">
      <div class="hero-content">
        <div class="container">
          <div class="row">
            <div class="text-center padding-large no-padding-bottom">
              <h1 class="display-2 text-uppercase text-dark">Shop</h1>
              <div class="breadcrumbs">
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <div class="shopify-grid padding-large">
      <div class="container">
        <div class="row">


          <!-- Paraqitja e produkteve-->
          <main class="col-md-9">
            <div class="filter-shop d-flex justify-content-between">
              <div class="showing-product">
                <p>Showing 1–9 of <?php $number ?> results</p>
              </div>
              <div class="sort-by">
      <form id="sortingForm" action="shop.php" method="post" >
                <select name="sortimi" onchange="this.form.submit()" id="input-sort" class="form-control" data-filter-sort="" data-filter-order="">
                  <option value="default" name="default">Sorting Mode</option>
                  <option value="price low-high" name="price low-high">Price (Low-High)</option>
                  <option value="price high-low" name="price high-low">Price (High-Low)</option>
                  <option value="A-Z" name="A-Z">Name (A - Z)</option>
                  <option value="Z-A" name="Z-A">Name (Z - A)</option>
                  <option value="rating-highest" name="rating-highest">Rating (Highest)</option>
                  <option value="rating-lowest" name="rating-lowest">Rating (Lowest)</option>
                  <option value="relevance" name="relevance">Relevance</option>
                
                </select>
                
  </form>
 
  
              </div>
            </div>
            <div class="product-content product-store d-flex justify-content-between flex-wrap">






<?php 

if(empty($products)){
  echo "No results found, please try different products.";
} else {
  foreach($products as $p){
      $p->showInShop();
  }
}
?>






            </div>
            <nav class="navigation paging-navigation text-center padding-medium" role="navigation">
              <div class="pagination loop-pagination d-flex justify-content-center align-items-center">
                <a href="#">
                  <svg class="chevron-left pe-3">
                    <use xlink:href="#chevron-left"></use>
                  </svg>
                </a>
                <span aria-current="page" class="page-numbers current pe-3">1</span>
                <a class="page-numbers pe-3" href="#">2</a>
                <a class="page-numbers pe-3" href="#">3</a>
                <a class="page-numbers pe-3" href="#">4</a>
                <a class="page-numbers" href="#">5</a>
                <a href="#">
                  <svg class="chevron-right ps-3">
                    <use xlink:href="#chevron-right"></use>
                  </svg>
                </a>
              </div>
            </nav>
          </main>
          <aside class="col-md-3">
            <div class="sidebar">
              <div class="widget-menu">
                <div class="widget-search-bar">


 <!--  Forma e search -->

 <form role="search" method="get" class="d-flex" action="shop.php" id="searchForm">
    <input class="search-field" placeholder="Search" type="search" name="search">
    <div class="search-icon bg-dark" id="searchIcon">
        <a href="#">
            <svg class="search text-light">
                <use xlink:href="#search"></use>
            </svg>
        </a>
        
    </div>
   
</form>
<a href="?clearCookies=1" style="color:gray;">Click here to delete history from search</a>


<!--FILTRIMI -->
<form method="post" class="d-flex" action="shop.php"> 
                </div> 
              </div>
              <div class="widget-product-categories pt-5">
              <h4 class="widget-title text-uppercase">Filter</h4>
                <h5 class="widget-title text-decoration-underline text-uppercase">Categories</h5>
                <ul class="product-categories sidebar-list list-unstyled">
                <br>
      
                <div class="checkbox-wrapper-20">
          <div class="switch">
           <input id="phone" name = "category[]" value ="phone"  class="input" type="checkbox" <?php echo $phoneCheck; ?> />
          <label for="phone" class="slider"></label>&nbsp&nbspSmart Phone
        </div> 
        <br>
        <div class="checkbox-wrapper-20">
          <div class="switch">
           <input id="watch" name = "category[]" value ="watch"  class="input" type="checkbox" <?php echo $watchCheck; ?> />
          <label for="watch" class="slider"></label>&nbsp&nbspSmart Watches
        </div> 
        <br>
        <div class="checkbox-wrapper-20">
          <div class="switch">
           <input id="laptop" name = "category[]" value ="laptop" class="input" type="checkbox" <?php echo $laptopCheck; ?> />
          <label for="laptop" class="slider"></label>&nbsp&nbspLaptop
        </div> 
        <br>
        

        <button type="submit">Filtro</button>
        
</form>

               


                </ul>
              </div>
              <div class="widget-product-tags pt-3">
                <h5 class="widget-title text-decoration-underline text-uppercase">Tags</h5>
                <ul class="product-tags sidebar-list list-unstyled">
                  <li class="tags-item">
                    <a href="#">White</a>
                  </li>
                  <li class="tags-item">
                    <a href="#">Cheap</a>
                  </li>
                  <li class="tags-item">
                    <a href="#">Mobile</a>
                  </li>
                  <li class="tags-item">
                    <a href="#">Modern</a>
                  </li>
                </ul>
              </div>
              <div class="widget-product-brands pt-3">
                <h5 class="widget-title text-decoration-underline text-uppercase">Brands</h5>
                <ul class="product-tags sidebar-list list-unstyled">
                  <li class="tags-item">
                    <a href="#">Apple</a>
                  </li>
                  <li class="tags-item">
                    <a href="#">Samsung</a>
                  </li>
                  <li class="tags-item">
                    <a href="#">Huwai</a>
                  </li>
                </ul>
              </div>
              <div class="widget-price-filter pt-3">
                <h5 class="widget-titlewidget-title text-decoration-underline text-uppercase">Filter By Price</h5>
                <ul class="product-tags sidebar-list list-unstyled">
                  <li class="tags-item">
                    <a href="#">Less than $10</a>
                  </li>
                  <li class="tags-item">
                    <a href="#">$10- $20</a>
                  </li>
                  <li class="tags-item">
                    <a href="#">$20- $30</a>
                  </li>
                  <li class="tags-item">
                    <a href="#">$30- $40</a>
                  </li>
                  <li class="tags-item">
                    <a href="#">$40- $50</a>
                  </li>
                </ul>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </div>
    <section id="subscribe" class="container-grid padding-large position-relative overflow-hidden">
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
                  <p>Nisi, purus vitae, ultrices nunc. Sit ac sit suscipit hendrerit. Gravida massa volutpat aenean odio erat nullam fringilla.</p>
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
                  <h5 class="widget-title pb-2">Quick Links</h5>
                  <ul class="menu-list list-unstyled text-uppercase">
                    <li class="menu-item pb-2">
                      <a href="index.php">Home</a>
                    </li>
                    <li class="menu-item pb-2">
                      <a href="about.html">About</a>
                    </li>
                    <li class="menu-item pb-2">
                      <a href="shop.php">Shop</a>
                    </li>
                    <li class="menu-item pb-2">
                      <a href="blog.html">Blogs</a>
                    </li>
                    <li class="menu-item pb-2">
                      <a href="contact.html">Contact</a>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 pb-3">
                <div class="footer-menu text-uppercase">
                  <h5 class="widget-title pb-2">Help & Info Help</h5>
                  <ul class="menu-list list-unstyled">
                    <li class="menu-item pb-2">
                      <a href="#">Track Your Order</a>
                    </li>
                    <li class="menu-item pb-2">
                      <a href="#">Returns Policies</a>
                    </li>
                    <li class="menu-item pb-2">
                      <a href="#">Shipping + Delivery</a>
                    </li>
                    <li class="menu-item pb-2">
                      <a href="#">Contact Us</a>
                    </li>
                    <li class="menu-item pb-2">
                      <a href="#">Faqs</a>
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
document.getElementById("sortingForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent default form submission

    var formData = new FormData(this); // Serialize form data
    var xhr = new XMLHttpRequest();

    xhr.open("POST", "shopSorting.php", true); // Specify the PHP script
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Handle the response here (if needed)
            console.log(xhr.responseText); // Log the response for debugging
        } else {
            console.error('Request failed:', xhr.status); // Log any errors
        }
    };

    xhr.send(formData); // Send the form data via AJAX
});
</script>
<script>
    // Add an event listener to the search icon
    document.getElementById('searchIcon').addEventListener('click', function(event) {
        // Prevent the default behavior of the anchor tag
        event.preventDefault();
        
        // Submit the form programmatically
        document.getElementById('searchForm').submit();
    });
</script>
  </body>

<!-- Mirrored from demo.templatesjungle.com/ministore/shop.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 26 Mar 2024 19:59:48 GMT -->
</html>
