<?php
include 'connection/connect.php';

session_start();
if (isset($_SESSION['customer_id'])) {
    $customer_id = intval($_SESSION['customer_id']);
} else {
    $customer_id = 0; // Set a default value or handle the missing customer ID case
}

// ...

if (isset($_POST['add_to_cart'])) {
    // Sanitize and validate the input values
    $food_id = intval($_POST['food_id']);
    $food_name = mysqli_real_escape_string($conn, $_POST['food_name']);
    $net_price = floatval($_POST['net_price']);
    $food_image = mysqli_real_escape_string($conn, $_POST['food_image']);
    $quantity = 1; // Default quantity is 1, you can modify this based on your requirements
 
    // Check if the same food item already exists in the cart for the current customer
    $existingCartItemQuery = "SELECT * FROM cart WHERE customer_id = ? AND food_id = ?";
    $stmt = mysqli_prepare($conn, $existingCartItemQuery);
    mysqli_stmt_bind_param($stmt, "ii", $customer_id, $food_id);
    mysqli_stmt_execute($stmt);
    $existingCartItemResult = mysqli_stmt_get_result($stmt);
 
    if (mysqli_num_rows($existingCartItemResult) > 0) {
       // Food item already exists in the cart, update the quantity on the client-side
       $row = mysqli_fetch_assoc($existingCartItemResult);
       $existingQuantity = $row['quantity'];
       $newQuantity = $existingQuantity + 1;
 
    //    // Output the updated quantity as JSON response
    //    echo json_encode(['quantity' => $newQuantity]);
    } else {
       // Food item doesn't exist in the cart, insert a new cart item
       $insertCartItemQuery = "INSERT INTO cart (customer_id, food_id, food_name, food_price, food_image, quantity) VALUES (?, ?, ?, ?, ?, ?)";
       $stmt = mysqli_prepare($conn, $insertCartItemQuery);
       mysqli_stmt_bind_param($stmt, "iisdsi", $customer_id, $food_id, $food_name, $net_price, $food_image, $quantity);
       if (mysqli_stmt_execute($stmt)) {
          // Cart item inserted successfully
          // You can add any additional logic or redirection here
          // ...
       } else {
          // Error occurred while inserting the cart item
          echo "Error: " . mysqli_stmt_error($stmt);
       }
    }
}


//deleting
if (isset($_POST['delete'])) {
    $cart_id = $_POST['cart_id'];

    // Delete the selected item from the cart table
    $deleteCartItemQuery = "DELETE FROM cart WHERE cart_id = ?";
    $stmt = mysqli_prepare($conn, $deleteCartItemQuery);
    mysqli_stmt_bind_param($stmt, "i", $cart_id);
    if (mysqli_stmt_execute($stmt)) {
      
    } else {
        // Error occurred while deleting the item
        echo "Error: " . mysqli_stmt_error($stmt);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartId = isset($_POST['cart_id']) ? $_POST['cart_id'] : null;
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : null;

    // Perform any necessary validation or sanitization of the input values

    // Update the cart table in the database
    $sql = "UPDATE cart SET quantity = ? WHERE cart_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $quantity, $cartId);

    if ($stmt->execute()) {
        // Update was successful
        
    } else {
        // Update failed
        echo "Error updating cart: " . $conn->error;
    }

    $stmt->close();
}


// Retrieve the food items from the cart for the current customer
$cartItemsQuery = "SELECT * FROM cart WHERE customer_id = ?";
$stmt = mysqli_prepare($conn, $cartItemsQuery);
mysqli_stmt_bind_param($stmt, "i", $customer_id);
mysqli_stmt_execute($stmt);
$cartItemsResult = mysqli_stmt_get_result($stmt);

// ...
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
    <title>Secangkir | Shopping Cart</title>
    <link rel="icon" href="image/logo.png" type="image/png">

    <!-- link for header -->
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- link for footer -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

    <!-- <link href="style.css" rel="stylesheet"> -->
    <link href="css/main.css" rel="stylesheet"> 
    <link href="css/cart.css" rel="stylesheet">  
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
    <header>
        <?php include('includes/main-header.php'); ?>
    </header>

    <div class="tablecontainer">
        <h1>shopping cart</h1>
        <p><a href="index.php">Home</a> <span> / Shopping cart</span></p>
    </div>

    <main>
        <div class="wrapper">
            <div class="project">
                <div class="shop">
                    <?php
                    
                    // Check if there are any items in the cart
                    if (mysqli_num_rows($cartItemsResult) > 0) {
                        while ($row = mysqli_fetch_assoc($cartItemsResult)) {
                            // Display the food item details
                            ?>
                            <div class="box">
                                <img class="product-image" src="image/food/<?php echo $row['food_image'] ?>">
                                <div class="content">
                                    <h3><?php echo $row['food_name'] ?></h3>
                                    <h4>Price: RM <span class="price"><?php echo $row['food_price'] ?></span></h4>
                                    <form method="post" action="cart.php">
                                    <p class="unit">
                                        <label for="quantityInput_<?php echo $row['cart_id'] ?>">Quantity:</label>
                                        <input type="number" id="quantityInput_<?php echo $row['cart_id'] ?>" class="qtyBox" name="quantity" value="<?php echo $row['quantity'] ?>" min="1" max="10">
                                        <button class="update" type="submit">
                                        <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </p>
                                    <input type="hidden" name="cart_id" value="<?php echo $row['cart_id'] ?>">
                                    </form>

                                    <div class="btn-area">
                                        <form action="" method="post">
                                            <input type="hidden" name="cart_id" value="<?php echo $row['cart_id'] ?>">
                                            <button type="submit" name="delete" onclick="return confirm('Are you sure you want to remove this item?')" class="btn-remove">
                                                <i class="fa fa-trash" aria-hidden="true"></i> Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "No items in the cart.";
                    }
                    ?>
                </div>

                <div class="right-bar">
                    <?php
                    $subtotal = 0;
                    $quantity = 0;
                    $cartItemsResult->data_seek(0); // Reset the result set pointer
                    while ($row = mysqli_fetch_assoc($cartItemsResult)) {
                        $subtotal += (float) $row['food_price'] * (int) $row['quantity'];
                        $quantity += $row['quantity'];
                    }
                    $tax = 0;
                    $total = $subtotal + $tax;
                    ?>
                    <p><span>Item count:</span> <span class="item-count"><?php echo $quantity; ?></span></p>
                    <hr>
                    <p><span>Subtotal:</span> <span>RM <span class="subtotal"><?php echo number_format($subtotal, 2); ?></span></span></p>
                    <hr>
                    <p><span>Tax:</span> <span>RM <span class="tax"><?php echo number_format($tax, 2); ?></span></span></p>
                    <hr>
                    <p><span>Total:</span> <span>RM <span class="grand-total"><?php echo number_format($total, 2); ?></span></span></p>
                    <a href="checkout.php"><i class="fa fa-shopping-cart"></i>Checkout</a>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <?php include('includes/main-footer.php');?>
    </footer>
</body>
</html>