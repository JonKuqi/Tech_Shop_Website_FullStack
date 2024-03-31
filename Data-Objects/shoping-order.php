<?php

class ShopingCart {
    private $id;
    private $user;
    private $product;

    public function __construct($id, $user, $product) {
        $this->id = $id;
        $this->user = $user;
        $this->product = $product;
    }

    public function __destruct() {}

    public function formatToFile() {
        return "$this->id|{$this->user->getId()}|{$this->product->getId()}\n";
    }
 // Getters dhe setters
    public function getId() { return $this->id; }
    public function getUser() { return $this->user; }
    public function getProduct() { return $this->product; }

    public function setId($id) { $this->id = $id; }
    public function setUser($user) { $this->user = $user; }
    public function setProduct($product) { $this->product = $product; }
}


class Orders {
    private $id;
    private $product;
    private $user;
    private $orderDetail;

    public function __construct($id, Product $product, User $user, OrderDetail $orderDetail) {
        $this->id = $id;
        $this->product = $product;
        $this->user = $user;
        $this->orderDetail = $orderDetail;
    }

    public function __destruct() {}
    public function formatToFile() {
        return "$this->id|{$this->product->getId()}|{$this->user->getId()}|{$this->orderDetail->getId()}\n";
    }

// Getters dhe setters
    public function getId() { return $this->id; }
    public function getProduct() { return $this->product; }
    public function getUser() { return $this->user; }
    public function getOrderId() { return $this->orderDetail; }

    public function setId($id) { $this->id = $id; }
    public function setProduct($product) { $this->product = $product; }
    public function setUser($user) { $this->user = $user; }
    public function setOrderId($orderId) { $this->orderDetail = $orderId; }
}

class OrderDetail {
    private $id;
    private $payment_method;
    private $shipping_address;
    private $time_ordered;
    private $stageOfCompletion;

    public function __construct($id, $payment_method, Adress $shipping_address, $time_ordered, $stageOfCompletion) {
        $this->id = $id;
        $this->payment_method = $payment_method;
        $this->shipping_address = $shipping_address;
        $this->time_ordered = $time_ordered;
        $this->stageOfCompletion = $stageOfCompletion;
    }

    public function __destruct() {
        // Destructor
    }
    public function formatToFile() {
        return "$this->id|$this->payment_method|{$this->shipping_address->getId()}|$this->time_ordered|$this->stageOfCompletion\n";
    }

// Getters dhe setters
    public function getId() { return $this->id; }
    public function getPaymentMethod() { return $this->payment_method; }
    public function getShippingAddress() { return $this->shipping_address; }
    public function getTimeOrdered() { return $this->time_ordered; }
    public function getStageOfCompletion() { return $this->stageOfCompletion; }

    public function setId($id) { $this->id = $id; }
    public function setPaymentMethod($payment_method) { $this->payment_method = $payment_method; }
    public function setShippingAddress($shipping_address) { $this->shipping_address = $shipping_address; }
    public function setTimeOrdered($time_ordered) { $this->time_ordered = $time_ordered; }
    public function setStageOfCompletion($stageOfCompletion) { $this->stageOfCompletion = $stageOfCompletion; }
}



?>