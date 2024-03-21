<?php
include 'connection/connect.php';

session_start();

// $customer_id = 1;
if (isset($_SESSION['customer_id'])) {
    $customer_id = intval($_SESSION['customer_id']);
} else {
    $customer_id = 0; // Set a default value or handle the missing customer ID case
}

if (isset($_POST['add_to_cart'])) {
       // Check if the user is logged in
    if (isset($_SESSION['customer_id'])) {
        // Get the selected food details from the form
        $food_id = $_POST['food_id'];
        $food_name = $_POST['food_name'];
        $net_price = $_POST['net_price'];
        $food_image = $_POST['food_image'];
        $quantity = 1; // Default quantity is 1, you can modify this based on your requirements

        // Check if the same food item already exists in the cart for the current customer
        $existingCartItemQuery = "SELECT * FROM cart WHERE customer_id = ? AND food_id = ?";
        $existingCartItemStmt = mysqli_prepare($conn, $existingCartItemQuery);
        mysqli_stmt_bind_param($existingCartItemStmt, "ii", $customer_id, $food_id);
        mysqli_stmt_execute($existingCartItemStmt);
        $existingCartItemResult = mysqli_stmt_get_result($existingCartItemStmt);

        if (mysqli_num_rows($existingCartItemResult) > 0) {
            // Food item already exists in the cart, update the quantity
            $row = mysqli_fetch_assoc($existingCartItemResult);
            $existingQuantity = $row['quantity'];
            $newQuantity = $existingQuantity + 1;
    
            $updateQuantityQuery = "UPDATE cart SET quantity = ? WHERE cart_id = ?";
            $updateQuantityStmt = mysqli_prepare($conn, $updateQuantityQuery);
            mysqli_stmt_bind_param($updateQuantityStmt, "ii", $newQuantity, $row['cart_id']);

            if (mysqli_stmt_execute($updateQuantityStmt)) {
                // Quantity updated successfully
                // You can add any additional logic or redirection here
                // ...
            } else {
                // Error occurred while updating the quantity
                echo "Error: " . mysqli_error($conn);
            }
        
        }
        else {
            // Food item doesn't exist in the cart, insert a new cart item
            $insertCartItemQuery = "INSERT INTO cart (customer_id, food_id, food_name, food_price, food_image, quantity) VALUES (?, ?, ?, ?, ?, ?)";
            $insertCartItemStmt = mysqli_prepare($conn, $insertCartItemQuery);
            mysqli_stmt_bind_param($insertCartItemStmt, "iisdsi", $customer_id, $food_id, $food_name, $net_price, $food_image, $quantity);
            if (mysqli_stmt_execute($insertCartItemStmt)) {
                // Cart item inserted successfully
                // You can add any additional logic or redirection here
                // ...
            } else {
                // Error occurred while inserting the cart item
                echo "Error: " . mysqli_error($conn);
            }
            }
    } else {
        // User is not logged in, redirect to the login page
        header("Location: login.php");
        exit();
    }
}

if (isset($_POST['add_to_wishlist'])) {
    // Sanitize and validate the input values
    $food_id = intval($_POST['food_id']);
    $food_name = mysqli_real_escape_string($conn, $_POST['food_name']);
    $net_price = floatval($_POST['net_price']);
    $food_image = mysqli_real_escape_string($conn, $_POST['food_image']);
    
    // Check if the same food item already exists in the wishlist for the current customer
    $existingWishlistItemQuery = "SELECT * FROM wishlist WHERE customer_id = ? AND food_id = ?";
    $stmt = mysqli_prepare($conn, $existingWishlistItemQuery);
    mysqli_stmt_bind_param($stmt, "ii", $customer_id, $food_id);
    mysqli_stmt_execute($stmt);
    $existingWishlistItemResult = mysqli_stmt_get_result($stmt);
 
    if (mysqli_num_rows($existingWishlistItemResult) > 0) {
       // Food item already exists in the wishlist
       // You can add any additional logic or display a message to the user
       // ...
    } else {
       // Food item doesn't exist in the wishlist, insert a new wishlist item
       $insertWishlistItemQuery = "INSERT INTO wishlist (customer_id, food_id, food_name, net_price, food_image) VALUES (?, ?, ?, ?, ?)";
       $stmt = mysqli_prepare($conn, $insertWishlistItemQuery);
       mysqli_stmt_bind_param($stmt, "iisds", $customer_id, $food_id, $food_name, $net_price, $food_image);
       if (mysqli_stmt_execute($stmt)) {
          // Wishlist item inserted successfully
          // You can add any additional logic or redirection here
          // ...
       } else {
          // Error occurred while inserting the wishlist item
          echo "Error: " . mysqli_stmt_error($stmt);
       }
    }
 }



// Pass the item count to the HTML

// Query to fetch the cart items
$sql = "SELECT SUM(quantity) AS totalQuantity FROM cart WHERE customer_id = $customer_id";
$result = $conn->query($sql);

$itemCount = 0; // Default value

// Check if the query was successful and retrieve the item count
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $itemCount = $row['totalQuantity'];
}

$itemCountDisplay = ($itemCount > 0) ? $itemCount : 0;



?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>Secangkir | Home </title>
    <link rel="icon" href="image/logo.png" type="image/png">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/index2.css">
    <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <header>
        <?php include('includes/main-header.php'); ?>
    </header>
    <div class="tablecontainer">
        <section>
            <div class="circle"></div>
            <div class="hcontainer">
                <div class="text-box">
                    <h2>Welcome to the<br><span>Secangkir&nbspCafe</span></h2>
                    <p>Immerse yourself in the warm ambiance, impeccable service, and a menu that reflects our dedication to culinary excellence. Whether you're here for a quick snack, a leisurely meal, or a sweet treat, Secangkir Kopitiam welcomes you to a world where every moment is a celebration of taste and indulgence.</p>
                    <a href="menu.php">SHOP NOW</a>
                </div>
                <div class="img-box">
                    <img src="image/home.png" class="starbucks" alt="" style="width: 800px">
                </div>
            </div>

            
        </section>

     
    </div>
            <!---category start-->
            <div class="cards">
                <?php
                $query = "SELECT * FROM `food` ORDER BY updation_date DESC LIMIT 8";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($fetch_products = mysqli_fetch_assoc($result)) {
                        // Generate dynamic HTML for each card
                        ?>
                        <div class="card">
                            <img src="image/food/<?php echo $fetch_products['food_image']; ?>" alt="Food Image" class="dis">
                            <h4>RM <?php echo $fetch_products['net_price']; ?></h4>
                            <h5><?php echo $fetch_products['food_name']; ?></h5>
                            <p>Per Serving</p>
                            <div class="cart">
                                <?php
                                if (isset($_SESSION['customer_id'])) {
                                ?>
                                <form action="wishlist.php" method="post">
                                    <input type="hidden" name="food_id" value="<?php echo $fetch_products['food_id']; ?>">
                                    <input type="hidden" name="food_name" value="<?php echo $fetch_products['food_name']; ?>">
                                    <input type="hidden" name="net_price" value="<?php echo $fetch_products['net_price']; ?>">
                                    <input type="hidden" name="food_image" value="<?php echo $fetch_products['food_image']; ?>">
                                    <button type="submit" name="add_to_wishlist" class="cart-button"><i class="bi bi-heart"></i></button>
                                </form>
                                <form action="cart.php" method="post">
                                    <input type="hidden" name="food_id" value="<?php echo $fetch_products['food_id']; ?>">
                                    <input type="hidden" name="food_name" value="<?php echo $fetch_products['food_name']; ?>">
                                    <input type="hidden" name="net_price" value="<?php echo $fetch_products['net_price']; ?>">
                                    <input type="hidden" name="food_image" value="<?php echo $fetch_products['food_image']; ?>">
                                    <button type="submit" name="add_to_cart" class="cart-button"><i class="bi bi-cart-check"></i></button>
                                </form>
                                <?php
                                }else{
                                ?>
                                <form action="login.php" method="post">
                                    <input type="hidden" name="food_id" value="<?php echo $fetch_products['food_id']; ?>">
                                    <input type="hidden" name="food_name" value="<?php echo $fetch_products['food_name']; ?>">
                                    <input type="hidden" name="net_price" value="<?php echo $fetch_products['net_price']; ?>">
                                    <input type="hidden" name="food_image" value="<?php echo $fetch_products['food_image']; ?>">
                                    <button type="submit" name="add_to_wishlist" class="cart-button"><i class="bi bi-heart"></i></button>
                                </form>
                                <form action="login.php" method="post">
                                    <input type="hidden" name="food_id" value="<?php echo $fetch_products['food_id']; ?>">
                                    <input type="hidden" name="food_name" value="<?php echo $fetch_products['food_name']; ?>">
                                    <input type="hidden" name="net_price" value="<?php echo $fetch_products['net_price']; ?>">
                                    <input type="hidden" name="food_image" value="<?php echo $fetch_products['food_image']; ?>">
                                    <button type="submit" name="add_to_cart" class="cart-button"><i class="bi bi-cart-check"></i></button>
                                </form>
                                <?php
                                }?>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>

            <div class="social">
                <div class="sign">
                    <i class="bi bi-caret-left-fill"></i>
                    <i class="bi bi-caret-right-fill"></i>
                </div>
            </div>


    <script>
        let left = document.getElementsByClassName('bi-caret-left-fill')[0];
        let right = document.getElementsByClassName('bi-caret-right-fill')[0];
        let cards = document.getElementsByClassName('cards')[0];

        left.addEventListener('click', () => {
            cards.scrollLeft -= 140;
        });

        right.addEventListener('click', () => {
            cards.scrollLeft += 140;
        });

        function openNav() {
            document.getElementById("mySidenav").style.width = "350px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }

        function toggleSubMenu() {
            var subMenu = document.querySelector('.sub-menu');
            subMenu.classList.toggle('show');
        }

    </script>


</body>

</html>
