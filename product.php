<?php
date_default_timezone_set('Europe/Belgrade');

abstract class Product{
    private $pid;
    private $price;
    private $quantity;
    private $time_added;

    function __construct($pid, $price,$quantity,$time_added) {
        $this->pid = intval($pid);
        $this->price = intval($price);
        $this->quantity = intval($quantity);
       // $this->time_added = date('Y-m-d H:i:s');
       $this ->time_added = $time_added;

      }  
  //getters   
    public function getPid(){return $this->pid;}
    public function getPrice(){return $this->price;}
    public function getQuantity(){return $this->quantity;}
    public function getTime(){return $this->time_added;}
  //seters
    public function setPid($pid){$this->pid = $pid;}
    public function setPrice($price){ $this->price = $price;}
    public function setQuantity($quantity){$this->quantity = $quantity;}
    public function setTime($time){$this->time_added=$time;}

    public abstract function shfaq();
    public abstract function imagesToString();
    public abstract function formatForFile();
}



class SmartPhone extends Product{
   private $name;
   private $brand;
   private $images;
   
    function __construct($pid, $price,$quantity, $name, $brand,$time, $images) {
        parent::__construct($pid,$price,$quantity,$time);
        $this->name = $name;
        $this->images = $images;
        $this->brand = $brand;
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
    $string = parent::getPid()."|".$this->brand."|".$this->name."|".parent::getQuantity()."|".parent::getPrice()."|".parent::getTime()."|".$this->imagesToString()."\n";
      return $string;
   }
  //geters
   public function getName(){return $this->name;}
   public function getImages(){return $this->images;}
   public function getPid(){return parent::getPid();}
  public function getPrice(){return parent::getPrice();}
  public function getBrand(){return $this->brand;}
  public function getQuantity(){return parent::getQuantity();}
  public function getTime(){return parent::getTime();}

  public function printTest(){ echo parent::getPrice()."<br>".$this->name."<br>";}
  
}

function saveProductsToFile($array){
  $myfile = fopen("WebsiteData/products.txt", "w") or die("Unable to open file!");
  foreach($array as $product){
       fwrite($myfile, $product->formatForFile());   
  }
  fclose($myfile);
}
  
function productsFromfile(){
  $arrayProducts = array();
  $myfile = fopen("WebsiteData/products.txt", "r") or die("Unable to open file!");

  while(!feof($myfile)) {  
  $line = fgets($myfile);

  $parts = explode("|",$line);



   if(!empty($line) && count($parts) >= 7 && $parts[0] < 1000) {
            $arrayImages = explode(">", $parts[6]);
            $smartPhone = new Smartphone($parts[0], $parts[4], $parts[3], $parts[2], $parts[1],$parts[5], $arrayImages);
            array_push($arrayProducts, $smartPhone);
        }
   }

  fclose($myfile);

 return $arrayProducts;
}



//testing
$images = ['images/product-item1.jpg','images/product-item2.jpg'];

$phone1 = new SmartPhone(1,1500,10,"Iphone 11","Apple",date('Y-m-d H:i:s'), $images);
$phone2 = new SmartPhone(2,3000,6,"Iphone 10","Apple",date('Y-m-d H:i:s'), $images);

$arrayPhone = [$phone1,$phone2];

saveProductsToFile($arrayPhone);

$products = productsFromfile();
foreach($products as $p){
 $p->printTest();
}
var_dump($products[1]);



?>