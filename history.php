<?php
session_start();

include('connection/connect.php');

if (isset($_SESSION['customer_id'])) {
    $customer_id = intval($_SESSION['customer_id']);
} else {
    $customer_id = 0; // Set a default value or handle the missing customer ID case
}

// Increase the character limit for GROUP_CONCAT
mysqli_query($conn, "SET SESSION group_concat_max_len = 10000");

// Define the initial SQL query
$sql = "SELECT o.order_id, p.payment_time, GROUP_CONCAT(f.food_name) AS food_names,
        COUNT(oi.order_items_id) AS total_order_items, o.order_status, p.total_amount
        FROM orders o
        JOIN payment p ON o.order_id = p.order_id
        JOIN orderitems oi ON o.order_id = oi.order_id
        JOIN food f ON oi.food_id = f.food_id
        WHERE o.customer_id = $customer_id";

// Get the selected date range or set a default value
$dateRange = isset($_GET['date_range']) ? $_GET['date_range'] : 'all';

// Modify the SQL query based on the selected date range
switch ($dateRange) {
    case '7':
        // Modify the query to fetch orders from the last 7 days
        $sql .= " AND DATE(payment_time) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        break;
    case '30':
        // Modify the query to fetch orders from the last 30 days
        $sql .= " AND DATE(payment_time) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
        break;
    case '60':
        // Modify the query to fetch orders from the last 60 days
        $sql .= " AND DATE(payment_time) >= DATE_SUB(CURDATE(), INTERVAL 60 DAY)";
        break;
    case '90':
        // Modify the query to fetch orders from the last 90 days
        $sql .= " AND DATE(payment_time) >= DATE_SUB(CURDATE(), INTERVAL 90 DAY)";
        break;
    default:
        // No specific date range selected, fetch all orders
        break;
}

// Add a GROUP BY clause to group the results by order ID
$sql .= " GROUP BY o.order_id";

// Add an ORDER BY clause to sort the results in descending order by order ID
$sql .= " ORDER BY o.order_id DESC";

// Execute the query
$res = mysqli_query($conn, $sql);

// Count rows to check whether the category is available or not
$count = mysqli_num_rows($res);

$message = '';

if (isset($_POST['send'])) {
    $order_id = $_POST['order_id'];

    $score = $_POST['score'];
    $remark = $_POST['remark'];

    // Prepare and execute SQL query
    $query = "INSERT INTO rating (order_id, customer_id, score, remark) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiss', $order_id, $customer_id, $score, $remark);

    if ($stmt->execute()) {
        $message = 'Rating submitted successfully!';
        echo "<script>window.location.href = 'thank.php';</script>";
    } else {
        $message = 'Failed to submit rating.';
    }

    // Close statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
	    <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
	    <title>Secangkir | Order History</title>
        <link rel="icon" href="image/logo.png" type="image/png">

	    <!-- link for header -->
	    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	    <!-- link for footer -->
	    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

        <link href="css/main.css" rel="stylesheet">
	    <link href="css/history.css" rel="stylesheet">
        <link href="css/bootstrap.min.css" rel="stylesheet">

    </head>

    <body>
        <header>
            <?php include('includes/main-header.php');?>
        </header>

        <div class="tablecontainer">
            <h1>Order History</h1>
            <p><a href="index.php">Home</a><span> / Order History</span></p>
        </div>

        <div class="content-container">
            <section class="popular">
                <!-- Date Picker for select a date start-->
                <div class="row">
                    <div class="col-md-6">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Select Date Range</button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="?date_range=all">All</a>
                                <a class="dropdown-item" href="?date_range=7">Last 7 Days</a>
                                <a class="dropdown-item" href="?date_range=30">Last 30 Days</a>
                                <a class="dropdown-item" href="?date_range=60">Last 60 Days</a>
                                <a class="dropdown-item" href="?date_range=90">Last 90 Days</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Date Picker for select a date end-->

                <!-- Order History Start -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="box-container">

                            <?php

                            if($count>0){
                                while($row=mysqli_fetch_assoc($res)){
                                    $order_id = $row['order_id'];
                                    $payment_time = $row['payment_time'];
                                    $food_names = $row['food_names'];
                                    $total_order_items = $row['total_order_items'];
                                    $order_status = $row['order_status'];
                                    $total_amount = $row['total_amount'];

                                    $payment_date = date('d-m-Y', strtotime($payment_time));
                                    $payment_time = date('h:i A', strtotime($payment_time));

                                ?>
                                    <div class="order-box">
                                        <div class="box">

                                        <?php
                                            // Assume $orderStatus contains the current order status
                                            $color = '';

                                            switch ($order_status) {
                                                case 'preparing':
                                                    $color = 'red';
                                                    break;
                                                case 'ready':
                                                    $color = 'orange';
                                                    break;
                                                case 'in queue':
                                                    $color = 'blue';
                                                    break;
                                                case 'complete':
                                                    $color = 'green';
                                                    break;
                                                default:
                                                    $color = 'gray';
                                                    break;
                                            }
                                        ?>

                                        <div class="order-status-container" style="background-color: <?php echo $color; ?>">
                                            <p><span><?php echo $order_status; ?></span></p>
                                        </div>
                                            <h3> Order Information</h3>
                                            <p> Order id : <span><?php echo $order_id; ?></span> </p>
                                            <p> Order date : <span><?php echo $payment_date; ?></span> </p>
                                            <p> Order time : <span><?php echo $payment_time; ?></span> </p>
                                            <p> Order items : <span><?php echo $food_names; ?></span> </p>
                                            <p> Total order items : <span><?php echo $total_order_items; ?></span> </p>
                                            <p> Total amount : RM <span><?php echo $total_amount; ?></span> </p>
                                            <div class="flex-btn">
                                                <a href="orderdetail.php?order_id=<?php echo $order_id; ?>" class="delete-btn">view details</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                            }else{
                                echo '<p class="empty">you have no order history</p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <!-- Order History End -->
                <div id="reviewPopup" class="popup">
                    <div class="popup-content">
                        <h2>Write a Review</h2>
                        <span id="closeReviewBtn" class="close">&times;</span>

                        <div id="reviewResult"><?php echo $message; ?></div>

                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <div class="form-group">
                                <label for="order_id">Order ID:</label>
                                <input type="text" name="order_id" id="order_id" value="">
                            </div>

                            <label for="score">Score:</label>
                            <div class="rating">
                                <input type="radio" name="score" id="star5" value="5">
                                <label for="star5"></label>
                                <input type="radio" name="score" id="star4" value="4">
                                <label for="star4"></label>
                                <input type="radio" name="score" id="star3" value="3">
                                <label for="star3"></label>
                                <input type="radio" name="score" id="star2" value="2">
                                <label for="star2"></label>
                                <input type="radio" name="score" id="star1" value="1">
                                <label for="star1"></label>
                            </div>

                            <label for="remark">Remark:</label>
                            <textarea name="remark" id="remark" cols="30" rows="5"></textarea>

                            <button type="submit" name="send" class="review">Submit Review</button>
                        </form>
                    </div>
    </div>
            </section>
        </div>
        
        <footer class="footer">
            <?php include('includes/main-footer.php');?>
        </footer>

        <script src="js/jquery.min.js"></script>
        <script src="js/animsition.min.js"></script>
        <script src="js/foodpicky.min.js"></script>
        <script src="js/bootstrap.min.js"></script>

        

    <script>
        // Get all the "rate" buttons
        var rateButtons = document.querySelectorAll('[id^="openReviewBtn_"]');

        // Attach a click event listener to each "rate" button
        rateButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                // Extract the order ID from the button ID
                var orderID = this.id.split('_')[1];

                // Display the review popup
                document.getElementById("reviewPopup").style.display = "block";

                // Set the order ID value in the form field
                document.getElementById("order_id").value = orderID;
            });
        });

        document.getElementById("closeReviewBtn").addEventListener("click", function() {
            document.getElementById("reviewPopup").style.display = "none";
        });
    </script>

    <script>
        document.querySelector('.review').addEventListener('click', function() {
            // Redirect to the thank.php page
            window.location.href = 'thank.php';
        });
    </script>

    </body>
</html>