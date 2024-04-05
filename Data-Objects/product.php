<?php

date_default_timezone_set('Europe/Belgrade');

abstract class Product {
    protected $pid;
    protected $sku;
    protected $price;
    protected $quantity;
    protected $time_added;
    protected $name;
    protected $discount; // prej 0 ne 1
    protected $tags = array();
    protected $reviews = array();
    public static $tax = 0.18;

    public function __construct(int $pid,int $sku,float $price, int $quantity, $time_added, $name,float $discount) {
        $this->pid = $pid;
        $this->sku = $sku;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->time_added = $time_added;
        $this->name = $name;
        $this->discount = $discount;
    }

    public abstract function showInShop();
    public function __destruct() {
        echo "<script>console.log('Destruktori')</script>";
    }
    public abstract function formatToFile();
    public function addTag(Tag $tag){ array_push($this->tags,$tag); }
    public function addReview(Review $review){ array_push($this->reviews, $review);}

//getters dhe setters
    public function getTags(){ return $this->tags; }
    public function getId() { return $this->pid; }
    public function getSku() { return $this->sku; }
    public function getPrice() { return $this->price; }
    public function getQuantity() { return $this->quantity; }
    public function getTimeAdded() { return $this->time_added; }
    public function getName() { return $this->name; }
    public function getDiscount() { return $this->discount; }

    public function setId($pid) { $this->pid = $pid; }
    public function setSku($sku) { $this->sku = $sku; }
    public function setPrice($price) { $this->price = $price; }
    public function setQuantity($quantity) { $this->quantity = $quantity; }
    public function setTimeAdded($time_added) { $this->time_added = $time_added; }
    public function setName($name) { $this->name = $name; }
    public function setDiscount($discount) { $this->discount = $discount; }
}

class SmartPhone extends Product {
    private $brand;
    private $images = array();
    private $short_description;
    private $long_description;

    public function __construct($pid, $sku, $price, $quantity, $time_added, $name, $discount, $brand, $short_description, $long_description) {
        parent::__construct($pid, $sku, $price, $quantity, $time_added, $name, $discount);
        $this->brand = $brand;
       // $this->images = $images;
        $this->short_description = $short_description;
        $this->long_description = $long_description;
    }

    public function __destruct() {
        parent::__destruct();
        echo "<script>console.log('Destruktori')</script>";
    }
  
    public function formatToFile(){ 
      return parent::getId().'|'.
             parent::getSku().'|'.
             parent::getPrice().'|'.
             parent::getQuantity().'|'.
             parent::getTimeAdded().'|'.
             parent::getName().'|'.
             parent::getDiscount().'|'.
            "$this->brand|$this->short_description|$this->long_description\n";
    }

    public function showInShop(){
         $finalPrice = $this->getPrice();
         
        echo '  <div class="col-lg-4 col-md-6">
        <div class="product-card position-relative pe-3 pb-3">
          <div class="image-holder">
            <img src="'.$this->images[0].'" alt="product-item" class="img-fluid">
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
          <div class="card-detail d-flex justify-content-between pt-3 pb-3">
            <h3 class="card-title text-uppercase">
              <a href="#">'.$this->getName().'</a>
            </h3>
            <span class="item-price text-primary" style="font-size:25px">';
            if($this->getDiscount() != 0.0){
                   echo '<span  style="font-size: 20px; color: rgb(189, 11, 56) !important; text-decoration: line-through;" class="item-price text-primary">'.$this->getPrice().'€</span>     '.$this->getPrice()-($this->getPrice()*$this->getDiscount()) .'€';
            }else{
              echo $this->getPrice()."€";
            }    
     echo '</span>
          </div>
        </div>                  
      </div> ';
  
      
    }
    public function addImage(String $path){
        array_push($this->images, $path);
    }


//getters dhe setters
    public function getBrand() { return $this->brand; }
    public function setBrand($brand) { $this->brand = $brand; }
    public function getImages() { return $this->images; }
    public function setImages($images) { $this->images = $images; }
    public function getShortDescription() { return $this->short_description; }
    public function setShortDescription($short_description) { $this->short_description = $short_description; }
    public function getLongDescription() { return $this->long_description; }
    public function setLongDescription($long_description) { $this->long_description = $long_description; }

    public function getId() { return parent::getId(); }
    public function setId($pid) { parent::setId($pid); }
    public function getSku() { return parent::getSku(); }
    public function setSku($sku) { parent::setSku($sku); }
    public function getPrice() { return parent::getPrice(); }
    public function setPrice($price) { parent::setPrice($price); }
    public function getQuantity() { return parent::getQuantity(); }
    public function setQuantity($quantity) { parent::setQuantity($quantity); }
    public function getTimeAdded() { return parent::getTimeAdded(); }
    public function setTimeAdded($time_added) { parent::setTimeAdded($time_added); }
    public function getName() { return parent::getName(); }
    public function setName($name) { parent::setName($name); }
    public function getDiscount() { return parent::getDiscount(); }
    public function setDiscount($discount) { parent::setDiscount($discount); }
}

class SmartWatch extends Product{   
    private $brand;
    private $images = array();
    private $short_description;
    private $long_description;

    public function __construct($pid, $sku, $price, $quantity, $time_added, $name, $discount, $brand, $short_description, $long_description) {
        parent::__construct($pid, $sku, $price, $quantity, $time_added, $name, $discount);
        $this->brand = $brand;
       // $this->images = $images;
        $this->short_description = $short_description;
        $this->long_description = $long_description;
    }

    public function __destruct() {
        parent::__destruct();
        echo "<script>console.log('Destruktori')</script>";
    }

    public function formatToFile(){ 
      return parent::getId().'|'.
             parent::getSku().'|'.
             parent::getPrice().'|'.
             parent::getQuantity().'|'.
             parent::getTimeAdded().'|'.
             parent::getName().'|'.
             parent::getDiscount().'|'.
            "$this->brand|$this->short_description|$this->long_description\n";
    }

    public function showInShop(){
      $finalPrice = $this->getPrice();
      
     echo '  <div class="col-lg-4 col-md-6">
     <div class="product-card position-relative pe-3 pb-3">
       <div class="image-holder">
         <img src="'.$this->images[0].'" alt="product-item" class="img-fluid">
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
       <div class="card-detail d-flex justify-content-between pt-3 pb-3">
         <h3 class="card-title text-uppercase">
           <a href="#">'.$this->getName().'</a>
         </h3>
         <span class="item-price text-primary" style="font-size:25px">';
         if($this->getDiscount() != 0.0){
                echo '<span  style="font-size: 20px; color: rgb(189, 11, 56) !important; text-decoration: line-through;" class="item-price text-primary">'.$this->getPrice().'€</span>     '.$this->getPrice()-($this->getPrice()*$this->getDiscount()) .'€';
         }else{
           echo $this->getPrice()."€";
         }    
  echo '</span>
       </div>
     </div>                  
   </div> ';

   
 }
    public function addImage(String $path){
        array_push($this->images,$path);
    }


    
//getters dhe setters
    public function getBrand() { return $this->brand; }
    public function setBrand($brand) { $this->brand = $brand; }
    public function getImages() { return $this->images; }
    public function setImages($images) { $this->images = $images; }
    public function getShortDescription() { return $this->short_description; }
    public function setShortDescription($short_description) { $this->short_description = $short_description; }
    public function getLongDescription() { return $this->long_description; }
    public function setLongDescription($long_description) { $this->long_description = $long_description; }

    public function getId() { return parent::getId(); }
    public function setId($pid) { parent::setId($pid); }
    public function getSku() { return parent::getSku(); }
    public function setSku($sku) { parent::setSku($sku); }
    public function getPrice() { return parent::getPrice(); }
    public function setPrice($price) { parent::setPrice($price); }
    public function getQuantity() { return parent::getQuantity(); }
    public function setQuantity($quantity) { parent::setQuantity($quantity); }
    public function getTimeAdded() { return parent::getTimeAdded(); }
    public function setTimeAdded($time_added) { parent::setTimeAdded($time_added); }
    public function getName() { return parent::getName(); }
    public function setName($name) { parent::setName($name); }
    public function getDiscount() { return parent::getDiscount(); }
    public function setDiscount($discount) { parent::setDiscount($discount); }
}


//Tagu
class Tag{
    private $id;
    private $title;
    private $products = array();

    public function __construct($id,$title){
        $this->id = $id;
        $this->title = $title;
    }
    public function addProduct(Product $product){ array_push($this->products,$product); }
    
    public function formatToFile(){
        return "$this->id|$this->title\n";
    }

//getters dhe setters
    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getProducts() {return $this->products;}

    public function setId($id){ $this->id = $id; }
    public function setTitle($title){ $this->title = $title;}

}

class ProductTag{
    private $id;
    private $tag;
    private $product;

    public function __construct($id, Product $product, Tag $tag) {
        $this->id = $id;
        $this->product = $product;
        $this->tag = $tag;
     //shton tagun ne produkt
        $product->addTag($tag);
     //shton produktin ne tag
        $tag->addProduct($product);
    }
    public function formatToFile() {
        return "{$this->product->getId()}|{$this->tag->getId()}\n";
    }

    public function getId(){ return $this->id;}
    public function getProduct() { return $this->product; }
    public function getTag() { return $this->tag; }
}

?>