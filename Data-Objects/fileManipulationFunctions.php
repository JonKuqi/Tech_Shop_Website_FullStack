<?php
include("product.php");
$product = new SmartPhone(1,"1050","2500","10","2024","Iphone",0.2,"Iphone","Short","Long Desc.");

$pathImg = "../images/product-item1.jpg";
function registerProduct($product){
    $file = fopen("../WebsiteData/product.txt", 'a');
    fwrite($file,$product->formatToFIle());
    fclose($file);
}

//img_id, pid, path
function saveArrayImages($product,$imgiD,$path ){
    $file = fopen("../WebsiteData/img.txt", 'a');
    fwrite($file, $imgiD."|".$product->getId()."|".$path."\n");
    fclose($file);
}


function arrayProductsFromFile(){
    $arrayProducts = array();
    $file = fopen("C:/xampp/htdocs/Tech_shop_website_gr.6/WebsiteData/product.txt", 'r');

    while(!feof($file)) {  
     $line = fgets($file);
     $parts = explode("|",$line);
 
   if(isset($parts[1])){
     if($parts[1] >=1000 && $parts[1] <= 2000){
     $product = new SmartPhone($parts[0],$parts[1],$parts[2],$parts[3],$parts[4],$parts[5],$parts[6],$parts[7],$parts[8],$parts[9]);
     array_push($arrayProducts, $product);
     }else if($parts[1] > 2000 && $parts[1] <= 3000){
     $product = new SmartWatch($parts[0],$parts[1],$parts[2],$parts[3],$parts[4],$parts[5],$parts[6],$parts[7],$parts[8],$parts[9]);
     array_push($arrayProducts, $product);
     }
    }
}
return $arrayProducts;
}


function setImagesOnProducts($products){
    $file = fopen("C:/xampp/htdocs/Teste Projekti/WebsiteData/img.txt", 'r');
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




?>