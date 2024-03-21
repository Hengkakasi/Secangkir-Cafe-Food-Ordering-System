<?php
session_start();

include('connection/connect.php');

if (isset($_SESSION['customer_id'])) {
    $customer_id = intval($_SESSION['customer_id']);
} else {
    $customer_id = 0; // Set a default value or handle the missing customer ID case
}

//Create SQL Query to Display CAtegories from Database
$sqlcheckcart = "SELECT * FROM cart WHERE customer_id = $customer_id ORDER BY cart_id";
//Execute the Query
$rescheckcart = mysqli_query($conn, $sqlcheckcart);
//Count rows to check whether the category is available or not
$countcheckcart = mysqli_num_rows($rescheckcart);

if(isset($_POST['placeOrders'] )) {
    $totalPrice = 0;
    date_default_timezone_set("Asia/Kuala_Lumpur");
    $date = date('Y-m-d H:i:s');
    $remark = $_POST['remark'];
    $takeMeal = $_POST['takeMeal'];
    $paymentMethod_id = $_POST['paymentMethod_id'];
    
    // Insert data into the orders table
    $sqlInsert1 = "INSERT INTO orders (customer_id, order_status, remark, take_meal) VALUES ('$customer_id', 'preparing', '$remark', '$takeMeal')";
    mysqli_query($conn, $sqlInsert1);

    // Retrieve the latest order ID from the orders table
    $sqlOrderId = "SELECT order_id FROM orders ORDER BY order_id DESC LIMIT 1";
    $resultOrderId = mysqli_query($conn, $sqlOrderId);

    if ($resultOrderId) {
        $row = mysqli_fetch_assoc($resultOrderId);
        $latestOrderID = $row['order_id'];

        // Retrieve the order items from the cart table
        $sqlCartItems = "SELECT * FROM cart WHERE customer_id = '$customer_id'";
        $resultCartItems = mysqli_query($conn, $sqlCartItems);

        while ($cartItem = mysqli_fetch_assoc($resultCartItems)) {
            $food_id = $cartItem['food_id'];
            $food_name = $cartItem['food_name'];
            // $quantity = $cartItem['quantity'];
            // $food_price = $cartItem['food_price'];
            $food_price = $cartItem['food_image'];
            $quantity = (int)$cartItem['quantity']; // Convert to integer
            $food_price = (float)$cartItem['food_price']; // Convert to float
            $subtotal = $food_price * $quantity;

            // Insert the order item into the orderitems table
            $sqlInsert2 = "INSERT INTO orderitems (order_id, food_id, quantity, subtotal) VALUES ('$latestOrderID', '$food_id', '$quantity', '$subtotal')";
            mysqli_query($conn, $sqlInsert2);
        }

        // Calculate the total price
        $sqlTotalPrice = "SELECT SUM(subtotal) AS total_price FROM orderitems WHERE order_id = '$latestOrderID'";
        $resultTotalPrice = mysqli_query($conn, $sqlTotalPrice);

        if ($resultTotalPrice) {
            $rowTotalPrice = mysqli_fetch_assoc($resultTotalPrice);
            $totalPrice = $rowTotalPrice['total_price'];
        }

        // Insert data into the payment table
        $sqlInsert3 = "INSERT INTO payment (order_id, total_amount, payment_time, paymentMethod_id) VALUES ('$latestOrderID', '$totalPrice', '$date', '$paymentMethod_id')";
        mysqli_query($conn, $sqlInsert3);

        // Delete the cart items for the customer
        $sqlDelete = "DELETE FROM cart WHERE customer_id = '$customer_id'";
        mysqli_query($conn, $sqlDelete);

        // Redirect to the order detail page
        header("Location: orderdetail.php");
        exit();
    } else {
        echo "Failed to retrieve the latest order ID.";
    }
} else {
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
	    <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
	    <title>Secangkir | Checkout</title>
        <link rel="icon" href="image/logo.png" type="image/png">

	    <!-- link for header -->
	    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	    <!-- link for footer -->
	    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

        <link href="css/main.css" rel="stylesheet">
	    <link href="css/checkout.css" rel="stylesheet">

    </head>

    <body>
        <header>
            <?php include('includes/main-header.php');?>
        </header>

        <div class="tablecontainer">
            <h1>checkout</h1>
            <p><a href="index.php">Home</a> / <a href="cart.php">Shopping Cart</a><span> / Checkout</span></p>
        </div>

        <div class="content-container">
            <div class="popular">
                <div class="ordersummary">
                    <h2>Order Summary</h2>
                    <?php
                        $totalPrice = 0; // Initialize total price variable
                        $formattedtotalPrice = 0; // Initialize the $formattedtotalPrice variable

                        if($countcheckcart>0)
                        {
                            $itemNo = 1; // Starting item number
                        ?>
                            <div class="OS-items">
                                <table>
                                    <tr >
                                        <th style="text-align: center;">Item No</th>
                                        <th style="text-align: center;">Food Image</th>
                                        <th style="text-align: center;">Food Name</th>
                                        <th style="text-align: center;">Quantity</th>
                                        <th style="text-align: center;">Subtotal</th>
                                    </tr>
                            <?php
                                //CAtegories Available
                                while($row=mysqli_fetch_assoc($rescheckcart))
                                {
                                    $food_name = $row['food_name'];
                                    $food_price = $row['food_price'];
                                    $quantity = $row['quantity'];
                                    $food_image = $row['food_image'];

                                    $subtotal = $food_price * $quantity; // Calculate subtotal
                                    $formattedsubtotal = sprintf("%.2f", $subtotal);
                                    $totalPrice += $subtotal; // Add subtotal to the total price
                                    $formattedtotalPrice = sprintf("%.2f", $totalPrice);
                                ?>

                                    <tr>
                                        <td style="text-align: center;"><?php echo $itemNo; ?></td>
                                        <td style="text-align: center;"><img style="height: 50px; width: 50px;" src="image/food/<?php echo $food_image; ?>" alt="Food Image"></td>
                                        <td style="text-align: center;"><?php echo $food_name; ?></td>
                                        <td style="text-align: center;"><?php echo $quantity; ?></td>
                                        <td style="text-align: center;"><?php echo $formattedsubtotal; ?></td>
                                    </tr>

                                <?php
                                    $itemNo++; // Increment the item number for the next row
                                }
                                ?>
                                </table>
                            </div>
                            <div class="total">
                                <h3><i class='fas fa-coins' style='font-size:24px'></i> Total Price: RM <?php echo $formattedtotalPrice; ?></h3> 
                            </div>
                            <?php 
                        }
                        else
                        {
                            //checkout not Available
                            echo "<div class='error'>You have no item for checkout.</div>";
                        }
                    ?>

                </div>

                <div class="placeorder">
                    <form class="form" method="post" action=" ">
                        <div class="remark">
                            <h2>Remark</h2>
                            <textarea id="remark" name="remark" rows="4" cols="50"></textarea>
                        </div>
                        <div class="takeMeal">
                            <h2>Way To Take Meal</h2>
                            <div>
                                <label>
                                    <input type="radio" name="takeMeal" value="Dine In">Dine In
                                </label>
                                <label>
                                    <input type="radio" name="takeMeal" value="Take Away">Take Away
                                </label>
                            </div>
                        </div>
                        <div class="payment">
                            <h2>Payment Method</h2>
                            <div>
                                <label>
                                    <input type="radio" name="paymentMethod_id" value="1">TNG
                                </label>
                                <label>
                                    <input type="radio" name="paymentMethod_id" value="2">Online Banking
                                </label>
                            </div>
                        </div>
                        <button name="placeOrders" type="submit">Place Order</button>
                    </form>
                </div>
            </div>
        </div>

        <footer class="footer">
            <?php include('includes/main-footer.php');?>
        </footer>
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
        

    </body>
</html>