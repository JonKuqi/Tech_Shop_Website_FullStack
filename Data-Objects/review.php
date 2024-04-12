<?php
date_default_timezone_set('Europe/Belgrade');

include("product.php");
include("user.php");

class Review {
    private $id;
    private $product;
    private $user;
    private $rating;
    private $context;
    private $date;
    private $likedFromUsers = array();

    public function __construct($id, Product $product, User $user, $rating, $context) {
        $this->id = $id;
        $this->product = $product;
        $this->user = $user;
        $this->rating = $rating;
        $this->context = $context;
        $this->date  = date('Y-m-d');
    }

    public function __destruct() {}
    public function LikedFromUser(User $user){ array_push($this->likedFromUsers, $user);}

    public function formatToFile() {
        return "$this->id|{$this->product->getId()}|{$this->user->getId()}|$this->rating|$this->context\n";
    }

    public function shfaq(){
        echo '    <div class="swiper-slide">
        <div
            class="group bg-white border border-solid border-gray-300 flex justify-between flex-col rounded-xl p-6 transition-all duration-500  w-full mx-auto slide_active:border-indigo-600 hover:border-indigo-600 hover:shadow-sm ">
            <div class="">
                <div class="flex items-center mb-7 gap-2 text-amber-500 transition-all duration-500  ">
                    <svg class="w-5 h-5" viewBox="0 0 18 17" fill="none" height="15px" width = "15px"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8.10326 1.31699C8.47008 0.57374 9.52992 0.57374 9.89674 1.31699L11.7063 4.98347C11.8519 5.27862 12.1335 5.48319 12.4592 5.53051L16.5054 6.11846C17.3256 6.23765 17.6531 7.24562 17.0596 7.82416L14.1318 10.6781C13.8961 10.9079 13.7885 11.2389 13.8442 11.5632L14.5353 15.5931C14.6754 16.41 13.818 17.033 13.0844 16.6473L9.46534 14.7446C9.17402 14.5915 8.82598 14.5915 8.53466 14.7446L4.91562 16.6473C4.18199 17.033 3.32456 16.41 3.46467 15.5931L4.15585 11.5632C4.21148 11.2389 4.10393 10.9079 3.86825 10.6781L0.940384 7.82416C0.346867 7.24562 0.674378 6.23765 1.4946 6.11846L5.54081 5.53051C5.86652 5.48319 6.14808 5.27862 6.29374 4.98347L8.10326 1.31699Z"
                            style="fill: yellow;"   stroke="silver"/>
                    </svg>
                    <span class="text-base font-semibold text-indigo-600">'.$this->rating.'</span>
                </div>
                <p
                    class="text-base text-gray-600 leading-6  transition-all duration-500 pb-8 group-hover:text-gray-800 slide_active:text-gray-800">
                  '.$this->context.'
                </p>
            </div>
            <div class="flex items-center gap-5 pt-5 border-t border-solid border-gray-200">
              
                <div class="block">
                    <h5 class="text-gray-900 font-medium transition-all duration-500  mb-1"> '.$this->user->getFirstName()." ".$this->user->getLastName().'
                    </h5>
                    <span class="text-sm leading-4 text-gray-500"></span>
                </div>
            </div>
        </div>
    </div>


';
    }


  public function registerReview(){
    $file = fopen("WebsiteData/review.txt",'a') or die("Error gjate hapjes...");
    fwrite($file, $this->formatToFile());
    fclose($file);
  }


// Getters dhe setters
    public function getId() { return $this->id; }
    public function getProduct() { return $this->product; }
    public function getUser() { return $this->user; }
    public function getRating() { return $this->rating; }
    public function getContext() { return $this->context; }

    public function setId($id) { $this->id = $id; }
    public function setProduct($product) { $this->product = $product; }
    public function setUser($user) { $this->user = $user; }
    public function setRating($rating) { $this->rating = $rating; }
    public function setContext($context) { $this->context = $context; }
}

class ReviewLikes {
    private $id;
    private $user;
    private $review;

    public function __construct($id, User $user, Review $review) {
        $this->id = $id;
        $this->user = $user;
        $this->review = $review;
        $this->review->LikedFromUser($user);
    }

    public function __destruct() {}

    public function formatToFile() {
        return "$this->id|{$this->user->getId()}|{$this->review->getId()}\n";
    }
 // Getters dhe setters
    public function getId() { return $this->id; }
    public function getUser() { return $this->user; }
    public function getReview() { return $this->review; }

    public function setId($id) { $this->id = $id; }
    public function setUser($user) { $this->user = $user; }
    public function setReview($review)  { $this->review = $review; }
}

?>