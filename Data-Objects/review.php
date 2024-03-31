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