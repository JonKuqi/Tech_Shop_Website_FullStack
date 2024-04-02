<?php



function arrayProductsFromFile($filePath) {
    $arrayProducts = array();
    $file = fopen($filePath, 'r');

    while (!feof($file)) {  
        $line = fgets($file);
        $parts = explode("|", $line);

        if (isset($parts[0])) {
        //    $product = new Product($parts[0], $parts[1], $parts[2]);
        //    array_push($arrayProducts, $product);
        }
    }

    fclose($file);
    return $arrayProducts;
}

function arrayTagsFromFile($filePath) {
    $arrayTags = array();
    $file = fopen($filePath, 'r');

    while (!feof($file)) {  
        $line = fgets($file);
        $parts = explode("|", $line);

        if (isset($parts[0])) {
            $tag = new Tag($parts[0], $parts[1]);
            array_push($arrayTags, $tag);
        }
    }

    fclose($file);
    return $arrayTags;
}

function arrayProductTagsFromFile($filePath) {
    $arrayProductTags = array();
    $file = fopen($filePath, 'r');

    while (!feof($file)) {  
        $line = fgets($file);
        $parts = explode("|", $line);

        if (isset($parts[0]) && isset($parts[1])) {
          //  $product = new Product($parts[0], $parts[1], $parts[2]);
            $tag = new Tag($parts[3], $parts[4]);
          //  $productTag = new ProductTag($product, $tag);
            array_push($arrayProductTags, $productTag);
        }
    }

    fclose($file);
    return $arrayProductTags;
}

function arrayUsersFromFile($filePath) {
    $arrayUsers = array();
    $file = fopen($filePath, 'r');

    while (!feof($file)) {  
        $line = fgets($file);
        $parts = explode("|", $line);

        if (isset($parts[0])) {
            $user = new User($parts[0], $parts[1], $parts[2], $parts[3], $parts[4], $parts[5], $parts[6], $parts[7]);
            array_push($arrayUsers, $user);
        }
    }

    fclose($file);
    return $arrayUsers;
}

function arrayAddressesFromFile($filePath) {
    $arrayAddresses = array();
    $file = fopen($filePath, 'r');

    while (!feof($file)) {  
        $line = fgets($file);
        $parts = explode("|", $line);

        if (isset($parts[0])) {
         //   $address = new Address($parts[0], $parts[1], $parts[2], $parts[3], $parts[4]);
            array_push($arrayAddresses, $address);
        }
    }

    fclose($file);
    return $arrayAddresses;
}

function arrayUserPaymentsFromFile($filePath) {
    $arrayUserPayments = array();
    $file = fopen($filePath, 'r');

    while (!feof($file)) {  
        $line = fgets($file);
        $parts = explode("|", $line);

        if (isset($parts[0])) {
            $userPayment = new UserPayment($parts[0], $parts[1], $parts[2], $parts[3]);
            array_push($arrayUserPayments, $userPayment);
        }
    }

    fclose($file);
    return $arrayUserPayments;
}

function arrayReviewsFromFile($filePath) {
    $arrayReviews = array();
    $file = fopen($filePath, 'r');

    while (!feof($file)) {  
        $line = fgets($file);
        $parts = explode("|", $line);

        if (isset($parts[0])) {
            $product = new Product($parts[1], $parts[2], $parts[3]);
            $user = new User($parts[4], $parts[5], $parts[6], $parts[7], $parts[8], $parts[9], $parts[10], $parts[11]);
            $review = new Review($parts[0], $product, $user, $parts[12], $parts[13]);
            array_push($arrayReviews, $review);
        }
    }

    fclose($file);
    return $arrayReviews;
}

function arrayReviewLikesFromFile($filePath) {
    $arrayReviewLikes = array();
    $file = fopen($filePath, 'r');

    while (!feof($file)) {  
        $line = fgets($file);
        $parts = explode("|", $line);

        if (isset($parts[0])) {
            $user = new User($parts[1], $parts[2], $parts[3], $parts[4], $parts[5], $parts[6], $parts[7], $parts[8]);
            $review = new Review($parts[0], null, null, null, null);
            $reviewLike = new ReviewLikes($parts[0], $user, $review);
            array_push($arrayReviewLikes, $reviewLike);
        }
    }

    fclose($file);
    return $arrayReviewLikes;
}

function arrayShoppingCartsFromFile($filePath) {
    $arrayShoppingCarts = array();
    $file = fopen($filePath, 'r');

    while (!feof($file)) {  
        $line = fgets($file);
        $parts = explode("|", $line);

        if (isset($parts[0])) {
            $user = new User($parts[1], $parts[2], $parts[3], $parts[4], $parts[5], $parts[6], $parts[7], $parts[8]);
            $product = new Product($parts[2], $parts[9], $parts[10]);
            $shoppingCart = new ShoppingCart($parts[0], $user, $product);
            array_push($arrayShoppingCarts, $shoppingCart);
        }
    }

    fclose($file);
    return $arrayShoppingCarts;
}

function arrayOrdersFromFile($filePath) {
    $arrayOrders = array();
    $file = fopen($filePath, 'r');

    while (!feof($file)) {  
        $line = fgets($file);
        $parts = explode("|", $line);

        if (isset($parts[0])) {
            $product = new Product($parts[1], $parts[2], $parts[3]);
            $user = new User($parts[2], $parts[3], $parts[4], $parts[5], $parts[6], $parts[7], $parts[8], $parts[9]);
            $order = new Orders($parts[0], $product, $user, $parts[10]);
            array_push($arrayOrders, $order);
        }
    }

    fclose($file);
    return $arrayOrders;
}

function arrayOrderDetailsFromFile($filePath) {
    $arrayOrderDetails = array();
    $file = fopen($filePath, 'r');

    while (!feof($file)) {  
        $line = fgets($file);
        $parts = explode("|", $line);

        if (isset($parts[0])) {
            $paymentMethod = $parts[1];
            $shippingAddress = new Address($parts[2], $parts[3], $parts[4], $parts[5], $parts[6]);
            $timeOrdered = $parts[7];
            $stageOfCompletion = $parts[8];
            $orderDetail = new OrderDetails($parts[0], $paymentMethod, $shippingAddress, $timeOrdered, $stageOfCompletion);
            array_push($arrayOrderDetails, $orderDetail);
        }
    }

    fclose($file);
    return $arrayOrderDetails;
}








?>