<?php


function searchProducts(String $search){
$products = arrayProductsFromFile();

foreach($products as $p){
    $arrayOfNames=[];
    $category = ["UNDEFINED","UNSET"];

    if($p instanceof SmartPhone){
        $category = ["phone","smart phone"];
    }
    if($p instanceof SmartWatch){
        $category = ["watch","smart watch"];
    }
    if($p instanceof Laptop){
        $category = ["laptop","laptop"];
    }
    $partsOfName = explode(" ", $p->getName());
    
    foreach($partsOfName as $part){
        $part = trim(strtolower($part));
        array_push($arrayOfNames, $part);
    }
    array_push($arrayOfNames, strtolower($p->getBrand()));
    array_push($arrayOfNames, $category[0]);
    array_push($arrayOfNames, $category[1]);
    
    $arrayOfNames = array_unique($arrayOfNames);

    $matrix[$p->getId()] = $arrayOfNames;

}


//hek fjat e shpeshta qe perdoren
$search = " ".$search." "; //shtu space qe me i kap edhe fjalt n fund edhe n fillim

$wordsToRemove = [" a "," an "," I "," it "," is "," do "," does "," for "," from "," go "," how "," the "," etc "];

foreach ($wordsToRemove as $w) {
    $search = str_replace($w, " ", $search);
}
$search = trim($search);

$arraySearch = explode(" ", $search);

for($i=0; $i<count($arraySearch) ; $i++){
    $arraySearch[$i] = trim($arraySearch[$i]);
    $arraySearch[$i] = strtolower($arraySearch[$i]);
}

$countedProducts = [];
//Per qdo fjal ne search, qdo produkt kqyret sa her e ka qat fjal.

foreach($matrix as $pid => $arrayNames){
    $count = 0;
    foreach($arrayNames as $name){
       
       foreach($arraySearch as $searchWord){
         if(str_contains($name, $searchWord)){
            $count++;
         }
          
       }
      
    }
     if($count != 0){
       $countedProducts[$pid] = $count;
       }
}

//countedProducts eshte numeric Array

arsort($countedProducts);

return $countedProducts;
}

?>