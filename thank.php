<?php
// Include the database connection file (connection.php)
include 'connection/connect.php';

session_start();

if (isset($_SESSION['customer_id'])) {
    $customer_id = intval($_SESSION['customer_id']);
} else {
    $customer_id = 0; // Set a default value or handle the missing customer ID case
}

$message = '';

if (isset($_POST['send'])) {
   $order_id = $_POST['order_id']; // Add the order_id value

   $score = $_POST['score'];
   $remark = $_POST['remark'];

   // Prepare and execute SQL query
   $query = "INSERT INTO rating (order_id, customer_id, score, remark) VALUES (?, ?, ?, ?)";
   $stmt = $conn->prepare($query);
   $stmt->bind_param('iiss', $order_id, $customer_id, $score, $remark);
   
   if ($stmt->execute()) {
      $message = 'Rating submitted successfully!';
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
	    <title>Secangkir | Order Review</title>
        <link rel="icon" href="image/logo.png" type="image/png">

	    <!-- link for header -->
	    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	    <!-- link for footer -->
	    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

        <link href="css/main.css" rel="stylesheet">
	    <link href="css/thank.css" rel="stylesheet">

    </head>

    <body>
        <header>
            <?php include('includes/main-header.php');?>
        </header>

        <div class="tablecontainer">
            <h1>Review</h1>
            <p><a href="index.php">Home</a> / <a href="history.php">Order History</a><span> / Review</span></p>
        </div>

        <div class="content-container">
            <div class="popup">
                <div class="popup-content">
                    <div class="imgbox">
                        <img src="image/checked.png" alt="" class="img">
                    </div>

                     <div class="title">
                        <h3>Submitted!</h3>
                    </div>
                    <p class="para">Thank you for your rating, we will hear your reviews for improvement!</p>
                    <form action="">
                        <a href="history.php" class="sbutton" id="s_button">OKAY</a>
                    </form>
                </div>
            </div>
    
            <div class="products">
                <h2>Orders Reviewed</h2>
                <div class="product-container">
                    <?php
                    // Retrieve data from the `rating` table
                    $query = "SELECT `rating_id`, `customer_id`, `order_id`, `score`, `remark`, `rating_date` 
                    FROM `rating` 
                    WHERE customer_id=$customer_id 
                    ORDER BY `rating_date` 
                    DESC LIMIT 4";
                    
                    $result = $conn->query($query);

                    // Check if there are any rows returned
                    if ($result->num_rows > 0) {
                        $counter = 0; // Initialize a counter
                        // Loop through each row and generate a product box
                        while ($row = $result->fetch_assoc()) {
                            if ($counter < 4) { // Display only four products
                                echo '<div class="product">';
                                echo '<div class="namePrice">';
                                echo '<h3>Order ID: ' . $row['order_id'] . '</h3>';
                                echo '<span>Customer ID:' . $row['customer_id'] . '</span>';
                                echo '</div>';
                                echo '<p>' . $row['remark'] . '</p>';
                                echo '<div class="stars">';
                                for ($i = 1; $i <= $row['score']; $i++) {
                                    echo '<i class="bi bi-star-fill"></i>';
                                }
                                for ($i = $row['score'] + 1; $i <= 5; $i++) {
                                    echo '<i class="bi bi-star"></i>';
                                }
                                echo '</div>';
                            
                                echo '<div class="bay">';
                                echo '<p>' . $row['rating_date'] . '</p>';
                                echo '</div>';
                                echo '</div>';
                            
                                $counter++; // Increment the counter
                            } else {
                                break; // Exit the loop after displaying four products
                            }
                        }
                    } else {
                        // Display a message if no rows are returned
                        echo '<p>No orders reviewed yet.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <footer class="footer">
            <?php include('includes/main-footer.php');?>
        </footer>
    </body>
</html>