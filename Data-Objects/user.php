<?php

class User{
    private $id;
    private $username;
    private $password;
    private $address;
    private $first_name;
    private $last_name;
    private $telephone;
    private $email; 
    private $payment;
    private $cartProducts = array();

    public function __construct($id, $username, $password, $first_name, $last_name, $telephone, $email) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        //$this->address = $address;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->telephone = $telephone;
        $this->email = $email;
        //$this->payment = $payment;

    }

    public function registerUser(){

        $file = fopen("WebsiteData/users.txt",'a') or die("Error gjate hapjes...");
        fwrite($file, $this->formatToFile());
        fclose($file);

        



    }

    public function __destruct() {
        echo "<script>console.log('Destruktori')</script>";
    }

    public function addCartProduct(ShopingCart $sh_product){ array_push($this->cartProducts, $sh_product);}

    public function formatToFile() {
        return "$this->id|$this->username|$this->password|$this->first_name|$this->last_name|$this->telephone|$this->email\n";
    }
// Getters dhed setters
    public function getId(){ return $this->id; }
    public function getUsername() { return $this->username; }
    public function getPassword() { return $this->password; }
    public function getAddress() { return $this->address; }
    public function getFirstName() { return $this->first_name; }
    public function getLastName() { return $this->last_name; }
    public function getTelephone() { return $this->telephone; }
    public function getEmail() { return $this->email; }
    public function getPayment(){return $this->payment;}

    public function setId($id){ $this->id = $id; }
    public function setUsername($username) { $this->username = $username; }
    public function setPassword($password) { $this->password = $password; }
    public function setAddress($address) { $this->address = $address; }
    public function setFirstName($first_name) { $this->first_name = $first_name; }
    public function setLastName($last_name) { $this->last_name = $last_name; }
    public function setTelephone($telephone) { $this->telephone = $telephone; }
    public function setEmail($email) { $this->email = $email; }
    public function setPayment($payment) {$this->payment = $payment; }
}


class Adress{
    private $id;
    private $street;
    private $city;
    private $state;
    private $zip;

    public function __construct($id, $street, $city, $state, $zip) {
        $this->id = $id;
        $this->street = $street;
        $this->city = $city;
        $this->state = $state;
        $this->zip = $zip;
    }

    public function __destruct() {}

    public function formatToFile() {
        return "$this->id|$this->street|$this->city|$this->state|$this->zip\n";
    }

 // Getters dhe setters
    public function getId() { return $this->id; }
    public function getStreet() { return $this->street; }
    public function getCity() { return $this->city; }
    public function getState() { return $this->state; }
    public function getZip() { return $this->zip; }

    public function setId($id) { $this->id = $id; }
    public function setStreet($street) { $this->street = $street; }
    public function setCity($city) { $this->city = $city; }
    public function setState($state) { $this->state = $state; }
    public function setZip($zip) { $this->zip = $zip; }
}


class UserPayment {
    private $id;
    private $provider;
    private $account_number;
    private $expiry_date;

    public function __construct($id, $provider, $account_number, $expiry_date) {
        $this->id = $id;
        $this->provider = $provider;
        $this->account_number = $account_number;
        $this->expiry_date = $expiry_date;
    }

    public function __destruct() {}

    public function formatToFile() {
        return "$this->id|$this->provider|$this->account_number|$this->expiry_date\n";
    }

// Getters dhe setters
    public function getId() { return $this->id; }
    public function getProvider() { return $this->provider; }
    public function getAccountNumber() { return $this->account_number; }
    public function getExpiryDate() { return $this->expiry_date; }

    public function setId($id) { $this->id = $id; }
    public function setProvider($provider) { $this->provider = $provider; }
    public function setAccountNumber($account_number) { $this->account_number = $account_number; }
    public function setExpiryDate($expiry_date) { $this->expiry_date = $expiry_date; }
}

?>