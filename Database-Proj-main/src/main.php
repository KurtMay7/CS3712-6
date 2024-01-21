<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .top-bar {
            height: 10vh;
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: center;
            background: grey;
            position: sticky;
            z-index: 100;
            top: 0;
        }

        .title-section {
            flex-grow: 1; /* Allows the title section to grow and take available space */
            flex-basis: 15%; /* Starts off taking up 50% of the parent's width */
            padding: 0 10px; /* Optional padding for aesthetic spacing */
            padding-left: 25%;
        }
        .top-bar form {
            margin-right: 10px; /* Spacing between buttons */
        }

        button {
            padding: 5px 15px; /* Adjust padding as needed */
            font-size: 16px; /* Adjust font size as needed */
        }

        .container {
            width: 100%; /* Adjust the width as needed */
            height: 50px; /* Adjust the height as needed */
            overflow: auto; /* This makes the div scrollable */
            border: 1px solid black; /* Optional, just for visibility */
        }
        .item {
            padding-top: 30px;
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            height: auto;
            align-items: center;
        }

        .item img {
            width: 400px;
            height: 500px;
            object-fit: cover;
        }

        .item h3 {
            margin-left: 20px;
        }

        .count {
            margin-left: 30px;
            border: 1px solid black;
            padding-left: 30px;
            height: 30px;
            width: 20px;
        }
        .category_name {
            text-align: center;
        }
        .category_div {
            background-color: #e39050;
        }

        .category_tab{
            height: 5vh;
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: space-around;
            align-items: center;
            background: lightblue;
            position: sticky;
            z-index: 100;
            top: 10vh;
        }

        .price{
            font-size: larger;
            margin-left: 15px;
        }

        .description{
            margin-left: 15px;
        }
    </style>
</head>

<body>

    <script>
        
    </script>

    <?php
    session_start();
    echo '<div class="top-bar">';
    echo '<h1 class="title-section">Chap Haven</h1>';
    
    if (isset($_SESSION["username"])) {
        
        echo '<p style="margin-right:15px; font-size:large;">Welcome '. $_SESSION["username"].'</p>';
        echo '<form action="customerOperations.php" method="post"><button name="changePswd" type="submit">Change Password</button></form>';
        echo '<form action="customerOperations.php" method="post"><button name="orders" type="submit">Orders</button></form>';
        echo '<form action="customerOperations.php" method="post"><button name="cart" type="submit">Shopping Cart</button></form>';
        echo '<form action="main.php" method="post"><button name="logout" type="submit">Logout</button></form>';
        $_SESSION["isLoggedIn"] = true;
    } else {
        echo '<form action="registration.php" method="post"><button name="register" type="submit">Register</button></form>';
        echo '<form action="index.php" method="post"><button name="login" type="submit">Login</button></form>';
    }
    echo '</div>';

    echo '<div class="category_tab">';
    echo '<a href="#chaps">Chaps</a>';
    echo '<a href="#boots">Cowboy Boots</a>';
    echo '<a href="#hats">Cowboy Hats</a>';
    echo '<a href="#ponies">Ponies</a>';
    echo '</div>';

    // Check if the logout button was clicked
    if (isset($_POST['logout'])) {
        // Unset all of the session variables.
        $_SESSION = array();
        session_destroy();
        header("Location: main.php");
        exit();
    }

    ?>
    <div>
        <div class="category_div">
            <h2 class="category_name" id="chaps" >Chaps</h2>
        </div>
        <div class="item">
            <img src="../pics/chaps.jpeg" alt="Chaps"></img> 
            <h3>Black Leather Chaps</h3>
            <a class="price">$55</a>
            <?php
            if(isset($_SESSION["username"])){
                ?>
            <form method="POST" action="customerOperations.php">
                <input class="count" type="number" name="quantity" min="1">
                <input type ="hidden" name="product" value="1">
                <button type="submit">Add to Cart</button>
            </form>
            <?php
            }
            ?>
            <p class="description">Seatless pants for a casual movie date</p>
        </div>
        <div class="item">
            <img src="../pics/brown_chaps.jpeg" alt="Brown Chaps"></img> 
            <h3>Brown Leather Chaps</h3>
            <a class="price">$300</a>
            <?php
            if(isset($_SESSION["username"])){
                ?>
            <<form method="POST" action="customerOperations.php">
                <input class="count" type="number" name="quantity" min="1">
                <input type ="hidden" name="product" value="2">
                <button type="submit">Add to Cart</button>
            </form>
            <?php
            }
            ?>
            <p class="description">Seatless bottoms that present a fashion statement to everyone around</p>
        </div>
        

        <div class="category_div">
            <h2 class="category_name" id="boots">Cowboy Boots</h2>
        </div>
        <div class="item">
            <img src="../pics/brown_boots_spurs.jpeg" alt="Brown Boots With Spurs"></img> 
            <h3>Brown Leather Boots With Spurs</h3>
            <a class="price">$200</a>
            <?php
            if(isset($_SESSION["username"])){
                ?>
            <form method="POST" action="customerOperations.php">
                <input class="count" type="number" name="quantity" min="1">
                <input type ="hidden" name="product" value="6">
                <button type="submit">Add to Cart</button>
            </form>
            <?php
            }
            ?>
            <p class="description">Fashionable boots come with spurs for when it's time for work</p>
        </div>
        <div class="item">
            <img src="../pics/hotrider_boots.jpeg" alt="Hotrider Boots"></img> 
            <h3>Hotrider Boots </h3>
            <a class="price">$175</a>
            <?php
            if(isset($_SESSION["username"])){
                ?>
            <form method="POST" action="customerOperations.php">
                <input class="count" type="number" name="quantity" min="1">
                <input type ="hidden" name="product" value="7">
                <button type="submit">Add to Cart</button>
            </form>
            <?php
            }
            ?>
            <p class="description">Hot like the people who wear them</p>
        </div>


        <div class="category_div">
            <h2 class="category_name" id="hats">Cowboy hats</h2>
        </div>
        <div class="item">
            <img src="../pics/white_cowboy_hat.jpeg" alt="White Cowboy Hat"></img> 
            <h3>White Cowboy Hat</h3>
            <a class="price">$75</a>
            <?php
            if(isset($_SESSION["username"])){
                ?>
            <form method="POST" action="customerOperations.php">
                <input class="count" type="number" name="quantity" min="1">
                <input type ="hidden" name="product" value="3">
                <button type="submit">Add to Cart</button>
            </form>
            <?php
            }
            ?>
            <p class="description">The perfect cowboy hat for weddings or special occasions</p>
        </div>
        <div class="item">
            <img src="../pics/black_cowboy_hat" alt="Black Cowboy Hat"></img> 
            <h3>Black Cowboy Hat</h3>
            <a class="price">$100</a>
            <?php
            if(isset($_SESSION["username"])){
                ?>
            <form method="POST" action="customerOperations.php">
                <input class="count" type="number" name="quantity" min="1">
                <input type ="hidden" name="product" value="4">
                <button type="submit">Add to Cart</button>
            </form>
            <?php
            }
            ?>
            <p class="description">Perfect for rough conditions and messy jobs.</p>
        </div>
        <div class="item">
            <img src="../pics/grey_cowboy_hat.jpeg" alt="Grey Cowboy Hat"></img> 
            <h3>Grey Cowboy Hat</h3>
            <a class="price">$50</a>
            <?php
            if(isset($_SESSION["username"])){
                ?>
            <form method="POST" action="customerOperations.php">
                <input class="count" type="number" name="quantity" min="1">
                <input type ="hidden" name="product" value="5">
                <button type="submit">Add to Cart</button>
            </form>
            <?php
            }
            ?>
            <p class="description">Good for a night out with you friends.</p>
        </div>


        <div class="category_div">
            <h2 class="category_name" id="ponies">Ponies</h2>
        </div>
        <div class="item">
            <img src="../pics/fast_pony.jpeg" alt="Fast Pony"></img> 
            <h3>Fast Pony</h3>
            <a class="price">$1000</a>
            <?php
            if(isset($_SESSION["username"])){
                ?>
            <form method="POST" action="customerOperations.php">
                <input class="count" type="number" name="quantity" min="1">
                <input type ="hidden" name="product" value="9">
                <button type="submit">Add to Cart</button>
            </form>
            <?php
            }
            ?>
            <p class="description">Bred by previous world fastest ponies so they can perform well in shows or races</p>
        </div>
        <div class="item">
            <img src="../pics/party_pony.jpeg" alt="Party Pony"></img> 
            <h3>Party Pony</h3>
            <a class="price">$75</a>
            <?php
            if(isset($_SESSION["username"])){
                ?>
            <form method="POST" action="customerOperations.php">
                <input class="count" type="number" name="quantity" min="1">
                <input type ="hidden" name="product" value="10">
                <button type="submit">Add to Cart</button>
            </form>
            <?php
            }
            ?>
            <p class="description">Rental, Trained to party. Will be dressed up and ready for your kids next birthday.</p>
        </div>
        <div class="item">
            <img src="../pics/chubby_pony.jpeg" alt="Chubby Pony"></img> 
            <h3>Chubby Pony</h3>
            <a class="price">$3000</a>
            <?php
            if(isset($_SESSION["username"])){
                ?>
            <form method="POST" action="customerOperations.php">
                <input class="count" type="number" name="quantity" min="1">
                <input type ="hidden" name="product" value="8">
                <button type="submit">Add to Cart</button>
            </form>
            <?php
            }
            ?>
            <p class="description">Graded to lift heavier weight compared normal standards. 9.6/10 on the weight to carry ratio</p>
        </div>
    </div>
</body>
</html>
