<?php
include("Data-Objects/search.php");


function recommendProducts($products){

//cookie zgjat nje dit

if(isset($_COOKIE['productsVisited'])) {
    $productsVisited = unserialize($_COOKIE['productsVisited']);      
    $stringToSearch = implode(" ", $productsVisited);

   $recomendProducts = searchProducts($stringToSearch);
}

return $recomendProducts;
}

?>