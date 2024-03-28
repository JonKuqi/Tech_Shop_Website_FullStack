<?php

abstract class Product{
    private $pid;
    private $price;
    function __construct($pid, $price) {
        $this->pid = $pid;
        $this->price = $price;

      }  
    public function getPid(){
      return $this->pid;
    }
    public function getPrice(){
      return $this->price;
    }
    public function setPid($pid){
      $this->pid = $pid;
    }
    public function setPrice($price){
      $this->price = $price;
    }

    public abstract function shfaq();
    public abstract function imagesToString();
    public abstract function formatForFile();
}

class Phone extends Product{
   private $name;
   private $images;
   
    function __construct($pid, $price, $name, $images) {
        parent::__construct($pid,$price);
        $this->name = $name;
        $this->images = $images;
      }

   public function shfaq(){

   }
   public function imagesToString(){
    $stringu = "";
    for($i=0;$i<(count($this->images)-1);$i++){
         $stringu = $stringu.$this->images[$i].">";
    }
    $stringu= $stringu.$this->images[count($this->images)-1];
    return $stringu;

   }

   public function formatForFile(){
    $string = parent::getPid()."|".$this->name."|".parent::getPrice()."|".$this->imagesToString()."\n";
      return $string;
   }

   public function getName(){return $this->name;}
   public function getImages(){return $this->images;}
   public function getPid(){return parent::getPid();}
  public function getPrice(){return parent::getPrice();}

}

function saveProductsToFile($array){
  $myfile = fopen("WebsiteData/products.txt", "a") or die("Unable to open file!");
  foreach($array as $product){
       fwrite($myfile, $product->formatForFile());   
  }
  fclose($myfile);
}
  








//testing
$images = ['images/product-item1.jpg','images/product-item2.jpg'];

$phone1 = new Phone(1,1500,"Iphone 11", $images);
$phone2 = new Phone(2,3000,"Iphone 10", $images);

$arrayPhone = [$phone1,$phone2];

saveProductsToFile($arrayPhone);



?>