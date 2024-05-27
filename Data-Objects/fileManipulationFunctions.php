<?php

include("review.php"); 
include("shoping-order.php");

function registerProduct($product){
    $file = fopen("WebsiteData/product.txt", 'a');
    fwrite($file,$product->formatToFIle());
    fclose($file);
}

//img_id, pid, path
function saveArrayImages($product,$imgiD,$path ){
    $file = fopen("WebsiteData/img.txt", 'a');
    fwrite($file, $imgiD."|".$product->getId()."|".$path."\n");
    fclose($file);
}


function arrayProductsFromFile(){
    $arrayProducts = array();

    $file = fopen("WebsiteData/product.txt", 'r');

    while(!feof($file)) {  
     $line = fgets($file);
     $parts = explode("|",$line);
 
     if(isset($parts[1])) {
        switch(true) {
            case ($parts[1] >= 1000 && $parts[1] <= 2000):
                $product = new SmartPhone($parts[0],$parts[1],$parts[2],$parts[3],$parts[4],$parts[5],$parts[6],$parts[7],$parts[8],$parts[9]);
                break;
            case ($parts[1] > 2000 && $parts[1] <= 3000):
                $product = new SmartWatch($parts[0],$parts[1],$parts[2],$parts[3],$parts[4],$parts[5],$parts[6],$parts[7],$parts[8],$parts[9]);
                break;
            case ($parts[1] > 3000 && $parts[1] <= 4000):
                $product = new Laptop($parts[0],$parts[1],$parts[2],$parts[3],$parts[4],$parts[5],$parts[6],$parts[7],$parts[8],$parts[9]);
                break;
            case ($parts[1] > 4000 && $parts[1] <= 5000):
                $product = new OtherBrands($parts[0],$parts[1],$parts[2],$parts[3],$parts[4],$parts[5],$parts[6],$parts[7],$parts[8],$parts[9]);
                break;
        }
        array_push($arrayProducts, $product);
    }
    
    
}
fclose($file);
setImagesOnProducts($arrayProducts);
return $arrayProducts;
}



function setImagesOnProducts($products){
    $file = fopen("WebsiteData/img.txt", 'r');
    while(!feof($file)) {  
        $line = fgets($file);
        $parts = explode("|",$line);
        if(isset($parts[1])){
        foreach($products as $p){

        if((int)$parts[1] == $p->getId()){
             $p->addImage($parts[2]);
           }
         }
      }
    }
}


function arrayUsersFromFile(){
  $arrayUsers = array();

  $file = fopen("WebsiteData/users.txt", 'r');

  while(!feof($file)) {  
   $line = fgets($file);
   $parts = explode("|",$line);
   if(isset($parts[1])){
   $user = new User($parts[0],$parts[1],$parts[2],$parts[3],$parts[4],$parts[5],$parts[6]);
   }
  array_push($arrayUsers,$user);
  
}
fclose($file);
return $arrayUsers;
}

function arrayReviewsFromFile(){
  $arrayReviews = array();

  $file = fopen("WebsiteData/review.txt", 'r');
  $products = arrayProductsFromFile();
  $users = arrayUsersFromFile();

  while(!feof($file)) {  
      $line = fgets($file);
      $parts = explode("|",$line);
      if(isset($parts[0]) && isset($parts[1])&& isset($parts[2])){
          $product = null;
          $user = null;

          foreach($products as $p){
              if($p->getId() == $parts[1]){
                  $product = $p;
                  break; 
              }
          }

          foreach($users as $u){
              if($u->getId() == $parts[2]){
                  $user = $u;
                  break; 
              }
          }

          if($product && $user) {
              $review = new Review($parts[0], $product, $user, $parts[3], $parts[4]);
              array_push($arrayReviews, $review);
          }
      }
  }

  fclose($file);
  return $arrayReviews;
}



function arrayShopingCartFromFile(){
    $arrayShopingCart = array();

    $file = fopen("WebsiteData/shoping_cart.txt", 'r');
    $products = arrayProductsFromFile();
    $users = arrayUsersFromFile();
  
    while(!feof($file)) {  
        $line = fgets($file);
        $parts = explode("|",$line);
        if(isset($parts[0]) && isset($parts[1])&& isset($parts[2])){
            $product = null;
            $user = null;
  
            foreach($products as $p){
                if($p->getId() == $parts[2]){
                    $product = $p;
                    break; 
                }
            }
  
            foreach($users as $u){
                if($u->getId() == $parts[1]){
                    $user = $u;
                    break; 
                }
            }
  
            if($product && $user) {
                $item = new ShopingCart($parts[0], $user, $product, $parts[3]);
                array_push($arrayShopingCart, $item);
            }
        }
    }
  
    fclose($file);
    return $arrayShopingCart;
  }
  


  
function addProductToShopingCard(Product $product, User $currentUser, $quantity){
    $arrayShopingCarts = arrayShopingCartFromFile();
    $id = intval($arrayShopingCarts[count($arrayShopingCarts)-1]->getId());
    $shopingCardItem = new ShopingCart($id+1,$currentUser,$product,$quantity);
    $file = fopen("WebsiteData/shoping_cart.txt",'a') or die("Error gjate hapjes...");
    fwrite($file, $shopingCardItem->formatToFile());
    fclose($file);
}
  


function removeItemCart(int $id){
     
    $items = arrayShopingCartFromFile();

    $newItems = [];

    foreach($items as $i){
        if($i->getId() != $id){
          array_push($newItems, $i);
          
        }
    }
    $file = fopen("WebsiteData/shoping_cart.txt",'w') or die("Error gjate hapjes...");
    
    foreach($newItems as $i){
        fwrite($file,$i->formatTofile());
    }
    fclose($file);

}

  
function setAddressAndPayment($user) {
    $newUser = $user;
    $address = null; 
    $payment = null;

    $file1 = fopen("WebsiteData/adress.txt", 'r');
    while (!feof($file1)) {
        $line = fgets($file1);
        $parts = explode("|", $line);
        if (isset($parts[0])) {
            if ($parts[0] == $user->getId()) {
                $address = new Adress($parts[0], $parts[1], $parts[2], $parts[3], $parts[4]);
                $newUser->setAddress($address);
            }
        }
    }
    fclose($file1);

    $file2 = fopen("WebsiteData/userPayment.txt", 'r');
    while (!feof($file2)) {
        $line = fgets($file2);
        $parts = explode("|", $line);
        if (isset($parts[0])) {
            if ($parts[0] == $user->getId()) {
                $payment = new UserPayment($parts[0], $parts[1], $parts[2], $parts[3]);
                $newUser->setPayment($payment);
            }
        }
    }
    fclose($file2);

    return $newUser;
}


function readEntireFile($file) {
        $filesize = filesize($file);
        $handle = fopen($file, 'r');
        if ($handle) {
        
            $content = fread($handle, $filesize);
            fclose($handle);
            return $content;
        } else {
            return "Nuk mund të hapet file.";
        }
}


?>