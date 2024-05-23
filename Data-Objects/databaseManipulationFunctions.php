<?php

include("review.php"); 
include("shoping-order.php");


function registerProduct($db, $product) {
    $stmt = $db->prepare("INSERT INTO tblProduct (sku, price, quantity, time_added, name, discount, brand, short_description, long_description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdissdsss", 
        $product->sku, 
        $product->price, 
        $product->quantity, 
        $product->time_added, 
        $product->name, 
        $product->discount, 
        $product->brand, 
        $product->short_description, 
        $product->long_description
    );
    $stmt->execute();
    
}

function saveArrayImages($db, $product, $imgId, $path) {
    $stmt = $db->prepare("INSERT INTO tblImages (imgId, pid, path) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $imgId, $product->getId(), $path);
    $stmt->execute();
 
}

function arrayProductsFromDatabase($db) {
    $query = "SELECT * FROM tblProduct";
    $result = $db->query($query);

    $arrayProducts = array();

    if (!$result) {
        echo "Error: " . $db->error;
        return [];
    }

    while ($row = $result->fetch_assoc()) {
        $product = null;
        switch (true) {
            case ($row['sku'] >= 1000 && $row['sku'] <= 2000):
                $product = new SmartPhone($row['pid'], $row['sku'], $row['price'], $row['quantity'], $row['time_added'], $row['name'], $row['discount'], $row['brand'], $row['short_description'], $row['long_description']);
                break;
            case ($row['sku'] > 2000 && $row['sku'] <= 3000):
                $product = new SmartWatch($row['pid'], $row['sku'], $row['price'], $row['quantity'], $row['time_added'], $row['name'], $row['discount'], $row['brand'], $row['short_description'], $row['long_description']);
                break;
            case ($row['sku'] > 3000 && $row['sku'] <= 4000):
                $product = new Laptop($row['pid'], $row['sku'], $row['price'], $row['quantity'], $row['time_added'], $row['name'], $row['discount'], $row['brand'], $row['short_description'], $row['long_description']);
                break;
            case ($row['sku'] > 4000 && $row['sku'] <= 5000):
                $product = new OtherBrands($row['pid'], $row['sku'], $row['price'], $row['quantity'], $row['time_added'], $row['name'], $row['discount'], $row['brand'], $row['short_description'], $row['long_description']);
                break;
        }
        array_push($arrayProducts, $product);
    }

    $result->free();

    // foreach($arrayProducts as $p){
    //     echo $p->getName()."<br>" ;
    // }

    setImagesOnProducts($db, $arrayProducts);
    return $arrayProducts;
}


function setImagesOnProducts($db, $products) {
    $query = "SELECT * FROM tblImages";
    $result = $db->query($query);

    while ($row = $result->fetch_assoc()) {
        foreach ($products as $p) {
            if ($row['pid'] == $p->getId()) {
                $p->addImage($row['path']);
            }
        }
    }

    $result->free();
}



function arrayUsersFromDatabase($db) {
    $query = "SELECT * FROM tbl_user";
    $result = $db->query($query);

    $arrayUsers = array();

    while ($row = $result->fetch_assoc()) {
        $user = new User($row['tbl_user_id'], $row['first_name'], $row['last_name'], $row['contact_number'], $row['email'], $row['username'], $row['password']);
        array_push($arrayUsers, $user);
    }

    $result->free();
    return $arrayUsers;
}







function arrayReviewsFromDatabase($db) {
    $query = "SELECT * FROM tblReview";
    $result = $db->query($query);

    $arrayReviews = array();
    $products = arrayProductsFromDatabase($db);
    $users = arrayUsersFromDatabase($db);

    while ($row = $result->fetch_assoc()) {
        $product = null;
        $user = null;

        foreach ($products as $p) {
            if ($p->getId() == $row['pid']) {
                $product = $p;
                break;
            }
        }

        foreach ($users as $u) {
            if ($u->getId() == $row['tbl_user_id']) {
                $user = $u;
                break;
            }
        }

        if ($product && $user) {
            $review = new Review($row['reviewId'], $product, $user, $row['rating'], $row['context']);
            array_push($arrayReviews, $review);
        }
    }

    $result->free();
    return $arrayReviews;
}










function arrayShopingCartFromDatabase($db) {
    $query = "SELECT * FROM tblShopingCart";
    $result = $db->query($query);

    $arrayShopingCart = array();
    $products = arrayProductsFromDatabase($db);
    $users = arrayUsersFromDatabase($db);

    while ($row = $result->fetch_assoc()) {
        $product = null;
        $user = null;

        foreach ($products as $p) {
            if ($p->getId() == $row['pid']) {
                $product = $p;
                break;
            }
        }

        foreach ($users as $u) {
            if ($u->getId() == $row['tbl_user_id']) {
                $user = $u;
                break;
            }
        }

        if ($product && $user) {
            $item = new ShopingCart($row['shid'], $user, $product, $row['quantity']);
            array_push($arrayShopingCart, $item);
        }
    }

    $result->free();
    return $arrayShopingCart;
}

function addProductToShoppingCart($db, Product $product, User $currentUser, $quantity) {
    $userId = $currentUser->getId();
    $productId = $product->getId();
    echo $userId;
    echo $productId;

    $stmt = $db->prepare("INSERT INTO tblshopingcart (tbl_user_id, pid, quantity) VALUES (?, ?, ?)");

    $stmt->bind_param("iii", $userId, $productId, $quantity);

    $stmt->execute();

    if ($stmt->error) {
        echo "Error: " . $stmt->error;
    } else {
        echo "Product added to shopping cart successfully.";
    }

}

function removeItemCart($db, int $id) {
    $stmt = $db->prepare("DELETE FROM tblShopingCart WHERE shid = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}


function setAddressAndPayment($db, $user) {
    $stmt = $db->prepare("SELECT * FROM tblAdress WHERE tbl_user_id = ?");
    $stmt->bind_param("i", $user->getId());
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $address = new Adress($row['tbl_user_id'], $row['street'], $row['city'], $row['state'], $row['zip']);
        $user->setAddress($address);
    }
  
    $stmt = $db->prepare("SELECT * FROM tbluserPayment WHERE tbl_user_id = ?");
    $stmt->bind_param("i", $user->getId());
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $payment = new UserPayment($row['tbl_user_id'], $row['provider'], $row['accountNumber'], $row['expiryDate']);
        $user->setPayment($payment);
    }
    return $user;
}


?>