<?php

class ShopingCart {
    private $id;
    private $user;
    private $product;
    private $quantity;

    public function __construct($id, $user, $product, $quantity) {
        $this->id = $id;
        $this->user = $user;
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function __destruct() {}

    public function formatToFile() {
        return "$this->id|{$this->user->getId()}|{$this->product->getId()}|$this->quantity\n";
    }
    public function formatForOrder() {
      return "$this->id|{$this->user->getId()}|{$this->product->getId()}|$this->quantity";
  }

public function getTotalPrice(){
  return ($this->product->getPrice()+($this->product->getPrice()*$this->product->getDiscount()))*$this->quantity;
}


    public function shfaq(){
        $singlePrice = $this->product->getPrice()+($this->product->getPrice()*$this->product->getDiscount());
       echo '  
 <div class="cart-item border-top border-bottom padding-small">
   <div class="row align-items-center">
     <div class="col-lg-4 col-md-3">
       <div class="cart-info d-flex flex-wrap align-items-center mb-4">
         <div class="col-lg-5">
           <div class="card-image">
             <img src="'.($this->product->getImages())[0].'" alt="cloth" class="img-fluid">
           </div>
         </div>
         <div class="col-lg-4">
           <div class="card-detail">
             <h3 class="card-title text-uppercase">
               <a href="#">'.$this->product->getName().'</a>
             </h3>
             <div class="card-price">
               <span class="money text-primary" data-currency-usd="$1200.00">'.$singlePrice.'</span>
             </div>
           </div>
         </div>
       </div>
     </div>
     <div class="col-lg-6 col-md-7">
       <div class="row d-flex">
         <div class="col-lg-6">
           <div class="qty-field">
             <div class="qty-number d-flex">
               <div class="quntity-button incriment-button">+</div>
               <input class="spin-number-output bg-light no-margin" type="text" value="'.$this->quantity.'">
               <div class="quntity-button decriment-button">-</div>
             </div>
             <div class="regular-price"></div>
             <div class="quantity-output text-center bg-primary"></div>
           </div>
         </div>
         <div class="col-lg-4">
           <div class="total-price">
             <span class="money text-primary">'.($singlePrice*$this->quantity).'</span>
           </div>
         </div>   
       </div>             
     </div>
     <div class="col-lg-1 col-md-2">
       <div class="cart-remove">
         <a href="">
           <svg class="close" width="38px">
             <use xlink:href="#close"></use>
           </svg>
         </a>
       </div>
     </div>
   </div>
 </div>

';


    }


 // Getters dhe setters
    public function getId() { return $this->id; }
    public function getUser() { return $this->user; }
    public function getProduct() { return $this->product; }
    public function getQuantity() {return $this->quantity;}

    public function setId($id) { $this->id = $id; }
    public function setUser($user) { $this->user = $user; }
    public function setProduct($product) { $this->product = $product; }
}






?>