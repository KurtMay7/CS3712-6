<?php
function connectDB()
{
    $config = parse_ini_file("/local/my_web_files/ambaird/classdb/db.ini");
    $dbh = new PDO($config['dsn'], $config['username'], $config['password']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}
//return number of rows matching the given user and passwd.
function authenticate($user, $passwd) {
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT count(*) FROM Customer ".
        "where username = :username and password = sha2(:passwd,256) ");
        $statement->bindParam(":username", $user);
        $statement->bindParam(":passwd", $passwd);
        $result = $statement->execute();
        $row=$statement->fetch();
        $dbh=null;
        return $row[0];
    }catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

//return number of rows matching the user (used to see if username already exists)
function user_exist($user){
    try{
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT count(*) FROM Customer ".
        "where username = :username");
        $statement->bindParam(":username", $user);
        $result = $statement->execute();
        $row=$statement->fetch();
        $dbh=null;
        return $row[0];
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function createAccount($create_username, $create_password, $create_password2, $first_name, $last_name, $address, $email){
    try{
        $dbh = connectDB();
        $exist = user_exist($create_username);
        if($exist == 0){ //No user with that username
            if($create_password == $create_password2){
                $statement = $dbh->prepare("INSERT INTO Customer (username, password, first_name, last_name, email, shipping_addr) VALUES (:create_username,sha2(:create_password,256),:first_name,:last_name,:email,:shipping_addr);");
                $statement->bindParam(":create_username", $create_username,\PDO::PARAM_STR);
                $statement->bindParam(":create_password", $create_password,\PDO::PARAM_STR);
                $statement->bindParam(":first_name", $first_name,\PDO::PARAM_STR);
                $statement->bindParam(":last_name", $last_name,\PDO::PARAM_STR);
                $statement->bindParam(":email", $email,\PDO::PARAM_STR);
                $statement->bindParam(":shipping_addr", $address,\PDO::PARAM_STR);
                $result = $statement->execute();
                if($result){ //If true
                    $dbh=null;
                    return '<p style="color:green">Account successfully created! :^)</p>
                    <form method="POST" action="main.php">
                    <input type="submit" value="Home" name="home">
                    </form>';
                
                } else{
                    $dbh=null;
                    return '<p style="color:red">Account creation failed! >:( </p>';
                }
            } else {
                $dbh=null;
                return '<p style="color:red">New passwords do not match!</p>';
        }
        } else {
            $dbh=null;
            return '<p style="color:red">Account already exists with that username!</p>';
            
        }
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function changePassword($user, $old_pass, $new_pass, $new_pass2){
    try {
        $dbh = connectDB();
        $validPassword = authenticate($user, $old_pass);
        if($validPassword == 1){
            if($new_pass == $new_pass2){
                $statement = $dbh->prepare("UPDATE Customer SET password=sha2(:password,256) WHERE username=:username");
                $statement->bindParam(":password", $new_pass, \PDO::PARAM_STR);
                $statement->bindParam(":username", $user, \PDO::PARAM_STR);
                $result = $statement->execute();
                if($result){
                    $dbh=null;
                    return '<p style="color:green">Password successfully changed! :^)</p>';
                } else {
                    '<p style="color:red">Password change failed</p>';
                }
            } else {
                $dbh=null;
                return '<p style="color:red">New passwords do not match!</p>';
            }
        } else {
            $dbh=null;
            return '<p style="color:red">Old Password is incorrect!</p>';
        }
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function getCustomerId($user){
    try{
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT cust_ID FROM Customer WHERE username = :user");
        $statement->bindParam(":user", $user, \PDO::PARAM_STR);
        $statement->execute();
        $id = $statement->fetchAll();
        $dbh = null;
        return $id[0];
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function getOrderId($user){
    try{
        $dbh = connectDB();
        $userId = getCustomerId($user);
        $statement = $dbh->prepare("SELECT order_ID FROM Order_Info WHERE cust_ID = :userid");
        $statement->bindParam(":userId", $userId[0], \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function getOrders($user){
    try{
        $dbh = connectDB();
        $id = getCustomerId($user);
        $statement = $dbh->prepare("SELECT order_ID, date, total_price FROM Order_Info where cust_ID = :id");
        $statement->bindParam(":id", $id[0], \PDO::PARAM_INT); 
        $statement->execute();
        $dbh = null;
        return $statement->fetchAll();
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function getOrderItems($orderId){
    try{
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT Product.name, has_order_Items.prod_ID, has_order_Items.quantity, Product.price
        FROM Order_Info
        INNER JOIN has_order_Items ON Order_Info.order_ID = has_order_Items.order_ID
        INNER JOIN Product ON has_order_Items.prod_ID = Product.prod_ID
        WHERE Order_Info.order_ID = :orderId");
        $statement->bindParam(":orderId", $orderId, \PDO::PARAM_INT);
        $statement->execute();
        $dbh = null;
        return $statement->fetchAll();
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

/**
 * [0] -> Category Name
 * [1] -> Name
 * [2] -> Description
 * [3] -> Price
 * [4] -> Stock Quantity
 * [5] -> Img
 */
function getProductInfo($prodId){
    try{
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT category_name, name, description, price, stock_quantity, img FROM Product WHERE prod_ID = :prodId");
        $statement->bindParam(":prodId", $prodId, \PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetchAll();
        $dbh = null;
        return $result[0];
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

/**
 * [0] = Product Id
 * [1] = Quantity
 * [2] = Price
 */
function getShoppingCart($user){
    try{
        $dbh = connectDB();
        $custId = getCustomerId($user);
        $statement = $dbh->prepare("SELECT prod_ID, quantity, price FROM has_in_shopping_cart WHERE cust_ID = :custId");
        $statement->bindParam(":custId", $custId[0], \PDO::PARAM_INT);
        $statement->execute();
        $dbh = null;
        return $statement->fetchAll();
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

/**
 * 1) Adds product with quantity to cart
 */
function addToCart($user, $prodId, $quantity){
    try{
        $dbh = connectDB();
        $custId = getCustomerId($user);
        $price = getProductInfo($prodId);
        $statement = $dbh->prepare("INSERT INTO has_in_shopping_cart VALUES(:custId, :prodId, :quantity, :price)");
        $statement->bindParam(":custId", $custId[0], \PDO::PARAM_INT);
        $statement->bindParam(":prodId", $prodId, \PDO::PARAM_INT);
        $statement->bindParam(":quantity", $quantity, \PDO::PARAM_INT);
        $statement->bindParam(":price", $price[3], \PDO::PARAM_INT);
        $result = $statement->execute();
        if($result){ //Success
            $dbh=null;
            return '<p style="color:green">Added to cart!</p>
            <form method="POST" action="main.php">
            <input type="submit" value="Home" name="home">
            </form>';
        }   else{ 
            $dbh=null;
            return '<p style="color:red">Failed to add to cart</p>
            <form method="POST" action="main.php">
            <input type="submit" value="Home" name="home">
            </form>'; 
        }
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function deleteCartItem($user, $prodId){
    try{
        $dbh = connectDB();
        $custId = getCustomerId($user);
        $statement = $dbh->prepare("DELETE FROM has_in_shopping_cart WHERE cust_ID = :custId AND prod_ID = :prodId");
        $statement->bindParam(":custId", $custId[0], \PDO::PARAM_INT);
        $statement->bindParam(":prodId", $prodId, \PDO::PARAM_INT);
        $result = $statement->execute();
        if($result){
            $dbh=null;
            return '<p style="color:green">Item successfully deleted!</p>
            <form method="POST" action="main.php">
            <input type="submit" value="Home" name="home">
            </form>';
        } else {
            return '<p style="color:red">Item deletion failed</p>
            <form method="POST" action="main.php">
            <input type="submit" value="Home" name="home">
            </form>';
        }
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function updateCartItem($user, $prodId, $quantity){
    try{
        $dbh = connectDB();
        $custId = getCustomerId($user);
        $statement = $dbh->prepare("UPDATE has_in_shopping_cart SET quantity=:quantity WHERE cust_ID = :custId AND prod_ID = :prodId");
        $statement->bindParam(":quantity", $quantity, \PDO::PARAM_INT);
        $statement->bindParam(":custId", $custId[0], \PDO::PARAM_INT);
        $statement->bindParam(":prodId", $prodId, \PDO::PARAM_INT);
        $result = $statement->execute();
        if($result){
            $dbh=null;
            return '<p style="color:green">Item successfully updated!</p>
            <form method="POST" action="main.php">
            <input type="submit" value="Home" name="home">
            </form>';
        } else {
            $dbh=null;
            return '<p style="color:red">Item update failed!</p>
            <form method="POST" action="main.php">
            <input type="submit" value="Home" name="home">
            </form>';
        }
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

/**
 * 1) Check to see how many items there are
 *      a) If insufficient, then print out message
 * 2) Attempt to checkout
 *      a) Remove all items from the user's cart
 *      b) Subtract quantity from product
 *      c) Add to Orders (Return order ID to the customer)
 *      c) Success!
 */
function checkout($user){
    try{
        $dbh = connectDB();
        $custId = getCustomerId($user);
        $cart = getShoppingCart($user); //Returns all products in the user's cart
        $i = 0; 
        $invalidProdId = 0;
        foreach ($cart as $item) { 
            $cartQuant = $item[1];
            $product = getProductInfo($item[0]); //Get product info
            $prodQuant = $product[4];
            if($cartQuant > $prodQuant){ //If trying to buy more than available
                $i = -1;
                $invalidProdId = $item[0];
            }
        }
        if($i == -1){
            $dbh=null;
            return '<p style="color:red">Order failed due to insufficient stock on Product '.$invalidProdId.'</p>
            <form method="POST" action="main.php">
            <input type="submit" value="Home" name="home">
            </form>';
        } else { //There is enough stock
            $total_price = 0;
            foreach($cart as $item){ //Removes stock from each product in the cart
                $process = $dbh->prepare("UPDATE Product SET stock_quantity = stock_quantity-:cartQuant WHERE prod_ID = :prodId");
                $process->bindParam(":cartQuant", $item[1], \PDO::PARAM_INT);
                $process->bindParam(":prodId", $item[0], \PDO::PARAM_INT);
                $result = $process->execute();
                $total_price += ($item[1]*$item[2]);

                if(!$result){ //Failed
                    $dbh=null;
                    return '<p style="color:red">Checkout Failed!</p>
                    <form method="POST" action="main.php">
                    <input type="submit" value="Home" name="home">
                    </form>';
                }
            }
            
            //Adds to Order Info
            $query = $dbh->prepare("INSERT INTO Order_Info(cust_ID, date, status, total_price) VALUES(:custId, now(), 0, :total_price)");
            $query->bindParam(":custId", $custId[0], \PDO::PARAM_INT);
            $query->bindParam(":total_price", $total_price, \PDO::PARAM_INT);
            $query->execute();

            $query = $dbh->prepare("SELECT order_ID FROM Order_Info WHERE cust_ID=:custId AND date=now()");
            $query->bindParam(":custId", $custId[0], \PDO::PARAM_INT);
            $query->execute();
            $order = $query->fetchAll();
            $orderId = $order[0];
            
            foreach($cart as $item){ //Adds items to has_order_Items
                $pro = $dbh->prepare("INSERT INTO has_order_Items values(:prodId, :orderId, :quantity, :price)");
                $pro->bindParam(":prodId", $item[0], \PDO::PARAM_INT);
                $pro->bindParam(":orderId", $orderId[0], \PDO::PARAM_INT);
                $pro->bindParam(":quantity", $item[1], \PDO::PARAM_INT);
                $pro->bindParam(":price", $item[2], \PDO::PARAM_INT);
                $result = $pro->execute();

                if(!$result){ //Failed
                    $dbh=null;
                    return '<p style="color:red">Checkout Failed!</p>
                    <form method="POST" action="main.php">
                    <input type="submit" value="Home" name="home">
                    </form>';
                }
            }

            $statement = $dbh->prepare("DELETE FROM has_in_shopping_cart WHERE cust_ID = :custId");
            $statement->bindParam(":custId", $custId[0], \PDO::PARAM_INT);
            $answer = $statement->execute();
            if($answer){
                $dbh=null;
                return '<p style="color:green">Your checkout was a success! Your order number is '.$orderId[0].'</p>
                <form method="POST" action="main.php">
                <input type="submit" value="Home" name="home">
                </form>';
            } else {
                $dbh=null;
                return '<p style="color:red">Checkout Failed!</p>
                <form method="POST" action="main.php">
                <input type="submit" value="Home" name="home">
                </form>';
            }
        }
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}