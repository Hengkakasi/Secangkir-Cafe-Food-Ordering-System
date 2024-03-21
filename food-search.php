<?php
session_start();

include('connection/connect.php');

if (isset($_SESSION['customer_id'])) {
    $customer_id = intval($_SESSION['customer_id']);
} else {
    $customer_id = 0; // Set a default value or handle the missing customer ID case
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
        echo "Item already exists in the wishlist.";
    } else {
        // Food item doesn't exist in the wishlist, insert a new wishlist item
        $insertWishlistItemQuery = "INSERT INTO wishlist (customer_id, food_id, food_name, net_price, food_image) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertWishlistItemQuery);
        mysqli_stmt_bind_param($stmt, "iisds", $customer_id, $food_id, $food_name, $net_price, $food_image);
        if (mysqli_stmt_execute($stmt)) {
            // Wishlist item inserted successfully
            echo "Item added to the wishlist.";
            // You can add any additional logic or redirection here
            // ...
        } else {
            // Error occurred while inserting the wishlist item
            echo "Error: " . mysqli_stmt_error($stmt);
        }
    }
}

if (isset($_POST['add_to_cart'])) {
    // Get the selected food details from the form
    $food_id = $_POST['food_id'];
    $food_name = $_POST['food_name'];
    $net_price = $_POST['net_price'];
    $food_image = $_POST['food_image'];
    $quantity = 1; // Default quantity is 1, you can modify this based on your requirements
 
    // Check if the user is logged in
    if (isset($_SESSION['customer_id'])) {
       $customer_id = $_SESSION['customer_id'];
 
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
       // User is not logged in, handle the case as per your requirement (e.g., redirect to login page)
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
	    <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
	    <title>Secangkir | Food Search</title>
        <link rel="icon" href="image/logo.png" type="image/png">

	    <!-- link for header -->
	    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	    <!-- link for footer -->
	    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

        <link href="css/main.css" rel="stylesheet">
        <link href="css/menu.css" rel="stylesheet">

        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/font-awesome.min.css" rel="stylesheet">

    </head>

    <body>
        <header>
            <?php include('includes/main-header.php');?>
        </header>

        <div class="tablecontainer">
            <h1>food search</h1>
            <p><a href="index.php">Home</a> / <a href="menu.php">Menu</a> <span> / Food Search</span></p>
        </div>

        <div class="content-container">
            <!-- FOOD SEARCH START -->
            <?php include('includes/search-bar.php');?>
            <!-- FOOD SEARCH END -->

            <!-- RESULT SEARCH START-->
            <section class="popular">
                <div class="container">
                    <div class="container1">
                        <?php 
                            //Get the Search Keyword
                            $search = $_POST['search'];
                        ?>
                        <h2 class="text-center">You have searched for: " <?php echo $search; ?> "</h2>
                    </div>

                    <div class="row">                                                                                              <!-- Need to be modify -->
					    <?php 					
					        //SQL Query to Get foods based on search keyword
                            $sql = "SELECT * FROM food WHERE food_name LIKE '%$search%' OR net_price LIKE '%$search%'";

                            //Execute the Query
                            $res = mysqli_query($conn, $sql);
            
                            //Count Rows
                            $count = mysqli_num_rows($res);

                            //Check whether food available of not
                            if($count>0)
                            {
                                //Food Available
                                while($row=mysqli_fetch_assoc($res))
                                {
                                    $food_id = $row['food_id'];
                                    $food_name = $row['food_name'];
                                    $net_price = $row['net_price'];
                                    $food_image = $row['food_image'];
                                    ?>

                                    <div class="col-xs-6 col-sm-4 col-md-3 food-item">                                           
                                        <div class="food-item-wrap">
                                            <?php
                                                // Check whether image name is available or not
                                                if($food_image=="")
                                                {
                                                    //Image not Available
                                                    echo "<div class='error'>Image not Available.</div>";
                                                }
                                                else
                                                {
                                                    //Image Available
                                                    ?>
                                                    <div class="figure-wrap bg-image" data-image-src="image/food/<?php echo $food_image; ?>"></div>
                                                    <?php 
                                                }
                                            ?>
                                            <div class="content">
                                                <h5><a><?php echo $food_name; ?></a></h5>
                                                <div class="price-btn-block"> <span class="price">RM <?php echo $net_price; ?></span></div>
                                                <div class="button-container">
                                                <?php
                                                if (isset($_SESSION['customer_id'])) {
                                                ?>
                                                    <form action="wishlist.php" method="post">
                                                        <input type="hidden" name="food_id" value="<?php echo $food_id; ?>">
                                                        <input type="hidden" name="food_name" value="<?php echo $food_name; ?>">
                                                        <input type="hidden" name="net_price" value="<?php echo $net_price; ?>">
                                                        <input type="hidden" name="food_image" value="<?php echo $food_image; ?>">
                                                        <button type="submit" name="add_to_wishlist" class="cart-button"><i class="bi bi-heart"></i></button>
                                                    </form>
                                                    <form action="cart.php" method="post">
                                                        <input type="hidden" name="food_id" value="<?php echo $food_id; ?>">
                                                        <input type="hidden" name="food_name" value="<?php echo $food_name; ?>">
                                                        <input type="hidden" name="net_price" value="<?php echo $net_price; ?>">
                                                        <input type="hidden" name="food_image" value="<?php echo $food_image; ?>">
                                                        <button type="submit" name="add_to_cart" class="btn theme-btn-dash pull-right">Add To Cart</button>
                                                    </form>
                                                <?php
                                                }else{
                                                    ?>
                                                    <form action="login.php" method="post">
                                                        <input type="hidden" name="food_id" value="<?php echo $food_id; ?>">
                                                        <input type="hidden" name="food_name" value="<?php echo $food_name; ?>">
                                                        <input type="hidden" name="net_price" value="<?php echo $net_price; ?>">
                                                        <input type="hidden" name="food_image" value="<?php echo $food_image; ?>">
                                                        <button type="submit" name="add_to_wishlist" class="cart-button"><i class="bi bi-heart"></i></button>
                                                    </form>
                                                    <form action="login.php" method="post">
                                                        <input type="hidden" name="food_id" value="<?php echo $food_id; ?>">
                                                        <input type="hidden" name="food_name" value="<?php echo $food_name; ?>">
                                                        <input type="hidden" name="net_price" value="<?php echo $net_price; ?>">
                                                        <input type="hidden" name="food_image" value="<?php echo $food_image; ?>">
                                                        <button type="submit" name="add_to_cart" class="btn theme-btn-dash pull-right">Add To Cart</button>
                                                    </form>
                                                    <?php
                                                }?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php                                            
                                }
                            }
                            else
                            {
                                //Food Not Available
                                echo "<div class='error text-center'>Food not found.</div>";
                            }	
					    ?>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </section>
            <!-- RESULT SEARCH END-->

        </div>

        <footer class="footer">
            <?php include('includes/main-footer.php');?>
        </footer>

        <script src="js/jquery.min.js"></script>
        <script src="js/animsition.min.js"></script>
        <script src="js/foodpicky.min.js"></script>

    </body>
</html>