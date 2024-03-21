<?php

// Include the database connection file
include 'connection/connect.php';

session_start();

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


//deleting
if (isset($_POST['delete'])) {
   $wishlist_id = $_POST['wishlist_id'];

   // Delete the selected item from the cart table
   $deleteWishlistItemQuery = "DELETE FROM wishlist WHERE wishlist_id = ?";
   $stmt = mysqli_prepare($conn, $deleteWishlistItemQuery);
   mysqli_stmt_bind_param($stmt, "i", $wishlist_id);
   if (mysqli_stmt_execute($stmt)) {
     
   } else {
       // Error occurred while deleting the item
       echo "Error: " . mysqli_stmt_error($stmt);
   }
}

if (isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
   // Retrieve the necessary details from the wishlist item
   $wishlist_id = $_POST['wishlist_id'];
   $food_id = intval($_POST['food_id']);
   $food_name = mysqli_real_escape_string($conn, $_POST['food_name']);
   $net_price = floatval($_POST['net_price']);
   $food_image = mysqli_real_escape_string($conn, $_POST['food_image']);
   
   // Check if the item already exists in the cart
   $existingCartItemQuery = "SELECT quantity FROM cart WHERE customer_id = ? AND food_id = ?";
   $stmt = mysqli_prepare($conn, $existingCartItemQuery);
   mysqli_stmt_bind_param($stmt, "ii", $customer_id, $food_id);
   mysqli_stmt_execute($stmt);
   mysqli_stmt_store_result($stmt);
   $numRows = mysqli_stmt_num_rows($stmt);
   
   if ($numRows > 0) {
      // Item already exists in the cart, increment the quantity
      mysqli_stmt_bind_result($stmt, $existingQuantity);
      mysqli_stmt_fetch($stmt);
      $newQuantity = $existingQuantity + 1;
      
      // Update the quantity of the existing item in the cart
      $updateCartItemQuery = "UPDATE cart SET quantity = ? WHERE customer_id = ? AND food_id = ?";
      $updateStmt = mysqli_prepare($conn, $updateCartItemQuery);
      mysqli_stmt_bind_param($updateStmt, "iii", $newQuantity, $customer_id, $food_id);
      
      if (mysqli_stmt_execute($updateStmt)) {
         // Quantity updated successfully
         // You can add any additional logic or redirection here
         // ...
      } else {
         // Error occurred while updating the quantity
         echo "Error: " . mysqli_stmt_error($updateStmt);
      }
   } else {
      // Item does not exist in the cart, insert a new item
      $defaultQuantity = 1;
      
      $insertCartItemQuery = "INSERT INTO cart (customer_id, food_id, food_name, food_price, food_image, quantity) VALUES (?, ?, ?, ?, ?, ?)";
      $insertStmt = mysqli_prepare($conn, $insertCartItemQuery);
      mysqli_stmt_bind_param($insertStmt, "iisdsi", $customer_id, $food_id, $food_name, $net_price, $food_image, $defaultQuantity);
      
      if (mysqli_stmt_execute($insertStmt)) {
         // Item inserted successfully into the cart
         // You can add any additional logic or redirection here
         // ...
      } else {
         // Error occurred while inserting the cart item
         echo "Error: " . mysqli_stmt_error($insertStmt);
      }
   }
}

// Deleting all items
if (isset($_POST['delete_all'])) {
   // Delete all items from the wishlist table for the current customer
   $deleteAllWishlistItemsQuery = "DELETE FROM wishlist WHERE customer_id = ?";
   $stmt = mysqli_prepare($conn, $deleteAllWishlistItemsQuery);
   mysqli_stmt_bind_param($stmt, "i", $customer_id);
   if (mysqli_stmt_execute($stmt)) {
      // All wishlist items deleted successfully
      // You can add any additional logic or redirection here
      // ...
   } else {
      // Error occurred while deleting the items
      echo "Error: " . mysqli_stmt_error($stmt);
   }
}


// Retrieve wishlist items from the database
$wishlistItemsQuery = "SELECT * FROM wishlist WHERE customer_id = ?";
$stmt = mysqli_prepare($conn, $wishlistItemsQuery);
mysqli_stmt_bind_param($stmt, "i", $customer_id);
mysqli_stmt_execute($stmt);
$wishlistItemsResult = mysqli_stmt_get_result($stmt);



// Retrieve wishlist items from the database
// $sql = "SELECT * FROM wishlist";
// $result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>
   <title>Secangkir | Wishlist</title>
   <link rel="icon" href="image/logo.png" type="image/png">
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" rel="stylesheet" />
   <link href="css/wishlist.css" rel="stylesheet">
   <link href="css/main.css" rel="stylesheet">
</head>

<body>

<header>
    <?php include('includes/main-header.php'); ?>
</header>

   <div class="tablecontainer">
      <h1>Wishlist</h1>
      <p><a href="index.php">Home</a> <span> / Wishlist</span></p>
   </div>
   <section>
      <div class="container">
         <div class="cart">
            <div class="col-md-12 col-lg-10 mx-auto">

               <?php
               // Check if the item was successfully removed
               if (isset($_GET['removed']) && $_GET['removed'] === 'true') {
                  echo '<div class="alert alert-success" role="alert">Item removed from the wishlist.</div>';
               }

               // Loop through wishlist items
               if (mysqli_num_rows($wishlistItemsResult) > 0) {
                  while ($row = mysqli_fetch_assoc($wishlistItemsResult)) {
               ?>
                     <div class="cart-item">
                        <div class="row">
                           <div class="col-md-7 center-item">
                              <img src="image/food/<?php echo $row['food_image'] ?>">
                              <h5><?php echo $row['food_name']; ?><P>RM <?php echo $row['net_price']; ?></P></h5>
                           </div>

                           <div class="col-md-5 center-item">
                              <div class="input-group number-spinner">
                              <form action="wishlist.php" method="post">
                                 <input type="hidden" name="wishlist_id" value="<?php echo $row['wishlist_id']; ?>">
                                 <input type="hidden" name="food_id" value="<?php echo $row['food_id']; ?>">
                                 <input type="hidden" name="food_name" value="<?php echo $row['food_name']; ?>">
                                 <input type="hidden" name="net_price" value="<?php echo $row['net_price']; ?>">
                                 <input type="hidden" name="food_image" value="<?php echo $row['food_image']; ?>">
                                 <button type="submit" name="action" value="add_to_cart" class="btn-upper btn btn-primary custom-button" onclick="return confirmMessage()">Add to Cart</button>
                              </form>

                              </div>
                              <h5>

                                 <form action="wishlist.php" method="post">
                                    <input type="hidden" name="wishlist_id" value="<?php echo $row['wishlist_id']; ?>">
                                    <button type="submit" name="delete" onclick="return confirm('Are you sure you want to remove this item from the wishlist?')" class="selected-button">
                                       <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                 </form>
                              </h5>
                           </div>

                        </div>
                     </div>
               <?php
                  }
               } else {
                  echo "No wishlist items found.";
               }

               // Close the database connection
               $conn->close();
               ?>

               <!-- <div class="col-md-12 pt-4 pb-4">
                  <a href="menu.php" class="btn btn-success check-out">Proceed to shopping</a>
               </div> -->
               <div class="col-md-12 pt-4 pb-4">
                  <div class="button-container">
                  <form action="wishlist.php" method="post">
                     <button type="submit" class="btn btn-success check-out" name="delete_all" onclick="return confirm('Are you sure you want to remove all items from the wishlist?')" class="Selected-button">Clear All Items</button>
                  </form>

                     <a href="menu.php" class="btn btn-secondary">Continue to Shopping</a>
                  </div>
               </div>

               
            </div>
         </div>
      </div>
   </section>

   <footer class="footer">
        <?php include('includes/main-footer.php'); ?>
    </footer>

    <script>
      function confirmMessage() {
      // Display the information message
      alert("Added to cart successfully");

      // Allow the form submission to proceed
      return true;
      }
   </script>

</body>

</html>
