<html>
<?php
require "db.php";
session_start();

if (!isset($_SESSION['username'])) {
    header("LOCATION:login.php");
}


if(isset($_POST['changePswd'])){
    ?>
    <div class="container">
        <h1 style="text-align: center;">Change Password</h1>
        <form action="customerOperations.php" method="POST" type="submit" style="justify-content: center; align-items: center; display: flex; flex-direction: column; height: 100%;">
            <div style="outline: 2px solid black; padding: 5px;">
            
                <div style="padding-bottom: 2px; margin-bottom: 2px;">
                    <label for="old_pass">Old Password: </label>
                    <input type="password" name="old_pass" style="width: 200px;">
                </div>

                <div style="padding-bottom: 2px; margin-bottom: 2px;"> 
                    <label for="new_pass">New Password: </label>
                    <input type="password" name="new_pass" autocomplete="off" autocorrect="off" spellcheck="off" style="width: 200px; left: 2px;">
                </div>

                <div style="padding-bottom: 2px; margin-bottom: 2px;"> 
                    <label for="new_pass2">New Password Again: </label>
                    <input type="password" name="new_pass2" autocomplete="off" autocorrect="off" spellcheck="off" style="width: 200px; left: 2px;">
                </div>

                <button type="submit"  value="submit" name="change_pass_submit">
                    Submit
                </button>


            </div>
        </form>
    </div>
    <form method="POST" action="customerOperations.php">
        <input type="submit" value="Home" name="home">
    </form>
    <?php
} 
else if(isset($_POST['orders'])){
?>
    <h1>Orders</h1>
    <?php
    $user = $_SESSION["username"];
    $order = getOrders($user);
    foreach ($order as $row) {
        $i = 0;
        echo "<h4>Order: $row[0]</h4>";
        echo "<h4>Date: $row[1] | Total Price: $row[2]</h4>";
        $current = getOrderItems($row[0]);
        foreach( $current as $item) {
            echo "<p> <b>Name:</b> ".$current[$i][0]."
             <b>Product Id:</b> ".$current[$i][1]." <b>Quantity:</b> ".$current[$i][2].
             " <b>Price</b> $".$current[$i][3]."</p>";
            $i++;
        }
    }
    ?>
    <form method="POST" action="customerOperations.php">
        <input type="submit" value="Home" name="home">
    </form>
    <?php
}
else if(isset($_POST['cart'])){
    ?>
    <h1>Shopping Cart</h1>
    <?php
    $user = $_SESSION["username"];
    $cart = getShoppingCart($user);
    foreach ($cart as $row) { 
        echo '<div>';
        echo "<h3>Product: $row[0]</h3>";
        echo "<p><b>Amount:</b> $row[1]"."<b> Price:</b> $row[2]</p>";
        $product = getProductInfo($row[0]);
        echo "<img src='../pics/".htmlspecialchars($product[5])."'>";
        echo "<h3>$product[1]</h3>";
        echo '<form method="post" action="customerOperations.php">'; 
        echo '<label for="quantity">Quantity: </label>';
        echo '<input type="number" id="quantity" name="quantity" value="'.$row['quantity'].'" min="1">';
        echo '<input type="hidden" name="update" value="' . $row[0] . '">';
        echo '<button type="submit">Update</button>';
        echo '</form>';
        echo '<form method="post" action="customerOperations.php">'; 
        echo '<input type="hidden" name="delete" value="' . $row[0] . '">';
        echo '<button type="submit">Delete</button>';
        echo '</form>';
        echo '</div>';
    }
    ?>
    <form method="POST" action="customerOperations.php">
        <input type="submit" value="Checkout" name="checkout">
    </form>
    <form method="POST" action="customerOperations.php">
        <input type="submit" value="Home" name="home">
    </form>
    <?php
}

if(isset($_POST['update']) && isset($_POST['quantity'])){
    //Sanitize Input (to prevent SQL injection)
    $prodId = filter_var($_POST['update'], FILTER_SANITIZE_NUMBER_INT);
    $quantity = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT);
    $user = $_SESSION['username'];
    $result = updateCartItem($user, $prodId, $quantity);
    echo $result;
}
if(isset($_POST['delete'])){
    //Sanitize Input (to prevent SQL injection)
    $prodId = filter_var($_POST['delete'], FILTER_SANITIZE_NUMBER_INT);
    $user = $_SESSION["username"];
    $result = deleteCartItem($user, $prodId);
    echo $result;
}

if(isset($_POST['checkout'])){
    $user = $_SESSION["username"];
    $result = checkout($user);
    echo $result;
}

//Add to cart is pressed
if(isset($_POST["product"])){
    $prodId = filter_var($_POST["product"], FILTER_SANITIZE_NUMBER_INT);
    $quantity = $_POST["quantity"];
    $user = $_SESSION["username"];
    $result = addToCart($user, $prodId, $quantity);
    echo $result;
}

if (isset($_POST["change_pass_submit"])) {
    $old_pass = $_POST["old_pass"];
    $new_pass = $_POST["new_pass"];
    $new_pass2 = $_POST["new_pass2"];
    $user = $_SESSION["username"];
    $response = changePassword($user, $old_pass, $new_pass, $new_pass2);
    echo $response;
    ?>
    <form method="POST" action="customerOperations.php">
        <input type="submit" value="Home" name="home">
    </form>
    <?php
}
if(isset($_POST["home"])){
    header("LOCATION:main.php");
}
?>
</html>