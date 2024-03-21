<?php
session_start();

include('connection/connect.php');

include('phpqrcode/qrlib.php');

if (isset($_SESSION['customer_id'])) {
    $customer_id = intval($_SESSION['customer_id']);
} else {
    $customer_id = 0; // Set a default value or handle the missing customer ID case
}

$order_id = null; // Initialize the order ID variable

if (isset($_GET['order_id'])) {
    // If the order_id is provided in the URL, use that value
    $order_id = $_GET['order_id'];
} else if (isset($_SERVER['HTTP_REFERER'])) {
    // Check the HTTP_REFERER to determine the previous page
    $previous_page = $_SERVER['HTTP_REFERER'];

    if (strpos($previous_page, 'checkout.php') !== false) {
        // If the previous page is checkout.php, get the latest order_id from the database
        $sql_latest_order = "SELECT MAX(order_id) AS latest_order_id FROM orders WHERE customer_id = $customer_id";
        $res_latest_order = mysqli_query($conn, $sql_latest_order);
        $row_latest_order = mysqli_fetch_assoc($res_latest_order);
        $order_id = $row_latest_order['latest_order_id'];
    }
    // Add more conditions if needed for other pages
    else if (strpos($previous_page, 'history.php') !== false) {
        // If the previous page is history.php, use the provided order_id from the URL
        $order_id = $_GET['order_id'];
    }
}

// Retrieve order details
$sql = "SELECT o.order_id, c.customer_name, o.order_status, o.take_meal, o.remark, p.total_amount, p.payment_time, pm.paymentMethod_name
        FROM customer c
        JOIN orders o ON c.customer_id = o.customer_id
        JOIN payment p ON o.order_id = p.order_id
        JOIN paymentmethod pm ON pm.paymentMethod_id = p.paymentMethod_id
        WHERE c.customer_id = $customer_id AND o.order_id = $order_id";

// Execute the query to retrieve order details
$res = mysqli_query($conn, $sql);

// Retrieve food items for the order
$sql_items = "SELECT f.food_name, f.net_price, oi.quantity, oi.subtotal
              FROM orderitems oi
              JOIN food f ON oi.food_id = f.food_id
              WHERE oi.order_id = $order_id";

// Execute the query to retrieve food items
$res_items = mysqli_query($conn, $sql_items);

// Count rows to check whether the order and food items are available or not
$count = mysqli_num_rows($res);
$count_items = mysqli_num_rows($res_items);

// Count rows to check whether the order and food items are available or not
$count = mysqli_num_rows($res);
$count_items = mysqli_num_rows($res_items);

// Generate QR Code
// $qrCodeData = "http://localhost/secangkircafe/orderdetailtest.php?order_id=$order_id";
// $qrCodePath = "qrcodes/order_$order_id.png"; // Change the path as needed
?>

<!DOCTYPE html>
<html lang="en">
    <head>
	    <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
	    <title>Secangkir | Order Details</title>
        <link rel="icon" href="image/logo.png" type="image/png">

	    <!-- link for header -->
	    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	    <!-- link for footer -->
	    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

        <link href="css/main.css" rel="stylesheet">
	    <link href="css/orderdetail.css" rel="stylesheet">

    </head>

    <body>
        <header>
            <?php include('includes/main-header.php');?>
        </header>

        <div class="tablecontainer">
            <h1>order details</h1>
            <p><a href="index.php">Home</a> / <a href="history.php">Order History</a><span> / Order Details</span></p>
        </div>

        <div class="content-container">
            <div class="receipt">
                <?php   
                if($count > 0 && $count_items > 0){
                    $row = mysqli_fetch_assoc($res);
                    $order_id = $row['order_id'];
                    $customer_name = $row['customer_name'];
                    $payment_time = $row['payment_time'];
                    $order_status = $row['order_status'];
                    $total_amount = $row['total_amount'];
                    $paymentMethod_name = $row['paymentMethod_name'];
                    $take_meal = $row['take_meal'];
                    $remark = $row['remark'];   
                    $payment_date = date('d-m-Y', strtotime($payment_time));
                    $payment_time = date('h:i A', strtotime($payment_time));
                    
                    
                    // Generate QR Code
                    $qrCodeData = "Order ID: $order_id\n";
                    $qrCodeData .= "Customer: $customer_name\n";
                    $qrCodeData .= "Date: $payment_date\n";
                    $qrCodeData .= "Time: $payment_time\n";
                    $qrCodeData .= "Status: $order_status\n";
                    $qrCodeData .= "Total Items: $count_items\n";
                    $qrCodeData .= "Total Amount: RM $total_amount\n";
                    $qrCodePath = "qrcodes/order_$order_id.png";    
                    QRcode::png($qrCodeData, $qrCodePath, 'L', 4, 2);
                    $qrCodeImagePath = $qrCodePath; 

                ?>

                <div class="receipt-header">
                    <div class="Rheader" style="display:flex; align-items: center; justify-content:space-between;">
                        <div style="margin-right: 20px; text-align:left">
                            <h1>Secangkir Cafe</h1>
                            <p>Bangunan F3, <br>Universiti Tun Hussein Onn Malaysia, <br>86400 Parit Raja, Johor<br><br></p>
                        </div>
                        <img src="<?php echo $qrCodeImagePath; ?>" alt="QR Code" height="100px" width="100px"> 
                    </div>
                    
                    <h2>Receipt</h2>
                </div>
                <div class="receipt-body">
                    <div class="left">
                        <p><strong>Order ID: <?php echo $order_id; ?></p>
                        <p><strong>Date: <?php echo $payment_date; ?></p>
                        <p><strong>Payment Method: <?php echo $paymentMethod_name; ?></p>
                        <p><strong>Customer Name: <?php echo $customer_name; ?></p>

                        <!-- Display QR Code image -->
                        
                    </div>
                    <div class="right">
                        <p><strong><?php echo $take_meal; ?></p>
                        <p><strong>Time: <?php echo $payment_time; ?></p>
                        <p><strong><?php echo $order_status; ?></p>
                        <p><strong><?php echo $count_items; ?> items</p>
                    </div>
                </div>
                <div class="receipt-items">
                    <table>
                        <tr>
                            <th style="text-align: center;">Item Name</th>
                            <th style="text-align: center;">Quantity</th>
                            <th style="text-align: center;">Price per item</th>
                            <th style="text-align: center;">Price</th>
                        </tr>

                        <?php
                        while ($row_items = mysqli_fetch_assoc($res_items)) {
                            $food_name = $row_items['food_name'];
                            $net_price = $row_items['net_price'];
                            $quantity = $row_items['quantity'];
                            $subtotal = $row_items['subtotal'];
                        ?>
                            <tr>
                                <td style="text-align: center;"><?php echo $food_name; ?></td>
                                <td style="text-align: center;"><?php echo $quantity; ?></td>
                                <td style="text-align: center;"><?php echo $net_price; ?></td>
                                <td style="text-align: center;"><?php echo $subtotal; ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>
                </div>
                <div class="receipt-remark">
                    <p>Remark: <?php echo $remark; ?></p>
                </div>
                <div class="receipt-total">
                    <p><strong>Total:</strong> RM <?php echo $total_amount; ?></p>
                </div>
            <?php
            } else {
                echo '<p class="empty">Sorry, there was an error in generating the receipt.</p>';
            }
            ?>
                <div class="receipt-footer">
                    <p>Thank you for your purchase!</p>
                    <p>Looking forward to seeing you again soon!</p>
                </div>
            </div>
            <!-- Print button -->
            <div class="flex-btn">
                <a href="history.php" class="option-btn">Back</a>
                <button onclick="window.print()">Print Receipt</button>
            </div>
        </div>

        <footer class="footer">
            <?php include('includes/main-footer.php');?>
        </footer>
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

    </body>
</html>
