<?php
include("Data-Objects/search.php");



function addProductCookie(Product $product) {

    $oldProducts = isset($_COOKIE['productsVisited']) ? unserialize($_COOKIE['productsVisited']) : array();

    array_push($oldProducts, $product->getName());
    array_push($oldProducts, $product->getBrand());

    $category = "Undefined";
    if ($product instanceof SmartPhone) {
        $category = "smart phone";
    }
    if ($product instanceof SmartWatch) {
        $category = "smart watch";
    }
    if ($product instanceof Laptop) {
        $category = "laptop";
    }
    if ($product instanceof OtherBrands) {
        $category = "other brands";
    }
    array_push($oldProducts, $category);

    $newProducts = serialize($oldProducts);   
    setcookie('productsVisited', $newProducts, time() + (86400 * 2), '/'); //simboli "/" e ben te qasshme nga qdo file

}

function recommendProducts($products, $db){
    
//cookie zgjat dy dit
//$recomendProducts = $products;

$recomendProducts = $products;

if(isset($_COOKIE['productsVisited']) || !empty($_COOKIE['productsVisited'])) {

    $productsVisited = unserialize($_COOKIE['productsVisited']);    
  
    $stringToSearch = implode(" ", $productsVisited);
   
    $searchedProducts = searchProducts($stringToSearch, $db);
    $recomendProducts =[];
     
    

    foreach($searchedProducts as $key => $value) {
      foreach($products as $p) {
          if($p->getId() == $key) {
              array_push($recomendProducts, $p);
              break;
          }
      }
    }
}
  

return $recomendProducts;

}



?>