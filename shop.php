<?php  


session_start();
 include("includes/header.php");
//include("Data-Objects/fileManipulationFunctions.php");
include("Data-Objects\databaseManipulationFunctions.php");

require("Website-Php-functions/errorHandler.php");

//testim i error_handler
echo $undefined_variable;



$conn = null;
include("databaseConnection.php");



$products = arrayProductsFromDatabase($conn);


include("databaseConnection.php");



$default = $products;
$sorted = [];

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
if(isset($_GET['search'])){
  $search = $_GET['search'];
  $searchedProducts = searchProducts($search, $conn);
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
      if($value == "laptop"){
        $laptopCheck = "checked";
        foreach($products as $p){
         if($p instanceof Laptop){
            array_push($temp, $p);
         }
        }
      }

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



<?php



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['category']) && is_array($_POST['category'])) {
        foreach ($_POST['category'] as $category) {
            if (!isset($_SESSION['category_visits'][$category])) {
                $_SESSION['category_visits'][$category] = 1; 
            } else {
                $_SESSION['category_visits'][$category]++; 
            }
        }
    }
    
    session_write_close();
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
                <p>Showing 1–9 of 10 results</p>
              </div>
              <div class="sort-by">
      <form id="sortingForm" action="shop.php" method="post" >
                <select name="sortimi" onchange="this.form.submit()" id="input-sort" class="form-control" data-filter-sort="" data-filter-order="">
                  <option value="default" name="default">Default</option>
                  <option value="price low-high" name="price low-high">Price (Low-High)</option>
                  <option value="price high-low" name="price high-low">Price (High-Low)</option>
                  <option value="A-Z" name="A-Z">Name (A - Z)</option>
                  <option value="Z-A" name="Z-A">Name (Z - A)</option>
                 
                
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
        </div>




<form method="post" class="d-flex" action="shop.php"> 
    <!-- Shfaq numrin e vizitave për kategoritë e zgjedhura -->
    <?php
    if (isset($_SESSION['category_visits'])) {
        foreach ($_SESSION['category_visits'] as $category => $visits) {
            echo ' <p>Number of visits for ' . $category . ' : ' . $visits . '</p><br>';
        }
    }
    ?> 
</form> 


                </ul>
              </div>
             
              <div class="widget-product-brands pt-3">
                <h5 class="widget-title text-decoration-underline text-uppercase">Brands</h5>
                <ul class="product-tags sidebar-list list-unstyled">
                  <li class="tags-item">
                    <a href="shop.php?search=apple">Apple</a>
                  </li>
                  <li class="tags-item">
                    <a href="shop.php?search=samsung">Samsung</a>
                  </li>
                  <li class="tags-item">
                    <a href="shop.php?search=Huawei">Huwei</a>
                  </li>
                </ul>
              </div>
              
            </div>
          </aside>
        </div>
      </div>
    </div>
    <br>
    <?php include("includes/footer.php")?>
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
