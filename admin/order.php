<?php 
//embed PHP code from another file
require_once 'config/connect.php';
require_once 'config/confirmLogin.php';

//before access admin page need to login first 
if(isset($_SESSION["Uid"])){
   $admin_name = $_SESSION["Uid"];
}else{
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/styless.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <link rel="icon" href="image/logo-bg.png" type="image/x-icon">
    <title>Secangkir Orders</title>

    <style>
        .total-payment {
            position: relative;
            cursor: pointer;
        }

        .tooltip-popup {
            display: none;
            position: fixed;
            z-index: 1;
            opacity: 0;
            transition: opacity 0.3s;
            background-color: #ffffff;
            color: #000000;
            padding: 8px;
            border-radius: 4px;
            white-space: nowrap;
            text-align: center;
        }

    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="index.php" class="logo">
            <img style="height: 36px; width: 36px;" src="image/logo-bg.png">
            <div class="logo-name"><span>Secangkir</span>Cafe</div>
        </a>
        <ul class="side-menu">
            <li><a href="index.php"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li><a href="menulist.php"><i class='bx bx-coffee'></i>Menu</a></li>
            <li class="active"><a href="order.php"><i class='bx bx-receipt'></i>Orders</a></li>
            <li><a href="customer.php"><i class='bx bx-user'></i>Customer List</a></li>
            <li><a href="reports.php"><i class='bx bx-bar-chart-alt-2'></i>Report</a></li>
            <li><a href="profile.php"><i class='bx bx-id-card'></i>Profile Setting</a></li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="logout.php" class="logout">
                    <i class='bx bx-log-out-circle'></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
    <!-- End of Sidebar -->

    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i>
            <form id="search-form">
                <div class="form-input">
                    <input id="search-input" type="search" placeholder="Search..." oninput="searchCustomers()">
                    <button class="search-btn" type="submit"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <input type="checkbox" id="theme-toggle" hidden>
            <label for="theme-toggle" class="theme-toggle"></label>
            <a href="index.php" class="notif">
                <i class='bx bx-bell'></i>
            </a>
            <a href="profile.php" class="profile">
                <img src="image/logo-bg.png">
            </a>
        </nav>

        <!-- End of Navbar -->

        <main>
            <div class="header">
                <div class="left">
                    <h1>Order List</h1>
                </div>
            </div>

            <div class="bottom-data">
                <div class="orders">
                    <div class="header">
                        <i class='bx bx-receipt'></i>
                        <h3>Order List</h3>
                    </div>
                    <table>
                    <thead>
                    <tr class="center-text">
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Food Name + Quantity</th>
                        <th>Total Quantity</th>
                        <th>Remark</th>
                        <th>Take Note</th>
                        <th>Total Payment</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $database = "secangkir";

                    // Create connection
                    $connection = new mysqli($servername, $username, $password, $database);

                    // Check connection
                    if ($connection->connect_error) {
                        die("Connection failed: " . $connection->connect_error);
                    }

                    // Update order status
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
                        $order_id = $_POST['order_id'];
                        $order_status = $_POST['order_status'];
                    
                        $updateQuery = "UPDATE ORDERS SET order_status='$order_status' WHERE order_id='$order_id'";
                        $result = $connection->query($updateQuery);
                    
                        if (!$result) {
                            echo "Failed to update order status: " . $connection->error;
                        }
                    }

                    $query = "SELECT 
                        o.order_id,
                        c.customer_name,
                        GROUP_CONCAT(CONCAT(f.food_name, ' x', oi.quantity) SEPARATOR '<br> ') AS food_and_quantity,
                        SUM(oi.quantity) AS total_quantity,
                        o.remark,
                        o.take_meal,
                        p.total_amount AS total_payment,
                        p.payment_time,
                        pm.paymentMethod_name AS payment_method,
                        o.order_status
                    FROM 
                        orders o
                        JOIN customer c ON o.customer_id = c.customer_id
                        JOIN orderitems oi ON o.order_id = oi.order_id
                        JOIN food f ON oi.food_id = f.food_id
                        LEFT JOIN payment p ON o.order_id = p.order_id
                        LEFT JOIN paymentmethod pm ON p.paymentMethod_id = pm.paymentMethod_id
                    GROUP BY 
                        o.order_id";

                    $result = $connection->query($query);

                    if (!$result) {
                        die("Invalid query: " . $connection->error);
                    }

                    // Loop through the rows of the result
                    while ($row = $result->fetch_assoc()) {
                        $order_id = $row["order_id"];
                        $customer_name = $row["customer_name"];
                        $food_and_quantity = $row["food_and_quantity"];
                        $total_quantity = $row["total_quantity"];
                        $remark = $row["remark"];
                        $take_meal = $row["take_meal"];
                        $total_payment = $row["total_payment"];
                        $order_status = $row["order_status"];
                        $payment_time = $row["payment_time"];
                        $payment_method = $row["payment_method"];

                        echo "<tr>";
                        echo "<td>$order_id</td>";
                        echo "<td>$customer_name</td>";
                        echo "<td>$food_and_quantity</td>";
                        echo "<td>$total_quantity</td>";
                        echo "<td>$remark</td>";
                        echo "<td>$take_meal</td>";
                        echo "<td class='total-payment' onmouseover='showPaymentTooltip(this)' onmouseout='hidePaymentTooltip()' data-payment-time='$payment_time' data-payment-method='$payment_method'>$total_payment</td>";

                        echo "<td class='status-cell' data-order-id='$order_id' data-status='$order_status'>";
                        echo "<span class='status $order_status'>$order_status</span>";

                        // <!-- Dropdown list -->
                        echo"<div class='status-dropdown' style='display: none;'>";
                        echo"<form method='post' action=''>";
                        echo"<select name='order_status'>";
                        echo"<option value='in queue' ".($order_status == 'in queue' ? 'selected' : '').">In Queue</option>";
                        echo"<option value='preparing' " .($order_status == 'preparing' ? 'selected ' : ''). "> Preparing</option>";
                        echo"<option value='complete' ".($order_status == 'complete' ? 'selected' : '').">Complete</option>";
                        echo"</select>";
                        echo"<input type='hidden' name='order_id' value='$order_id'>";
                        echo"<button type='submit' name='update_status' class='update-status-btn'>Update</button>";
                        echo"</form>";
                        echo"</div>";
                    }
                    // Close connection
                    $connection->close();
                    ?>
                    </tbody>
                </table>
                </div>
            </div>
        </main>
    </div>

    <div class="tooltip-popup" id="paymentTooltip"></div>



<script>
        document.addEventListener("DOMContentLoaded", function () {
            var statusCells = document.querySelectorAll('.status-cell');

            statusCells.forEach(function (statusCell) {
                statusCell.addEventListener('click', function (event) {
                    event.stopPropagation(); // Prevent the click event from propagating

                    // Hide all other dropdowns
                    hideAllDropdowns();

                    // Show the dropdown for the clicked row
                    var dropdown = statusCell.querySelector('.status-dropdown');
                    dropdown.style.display = 'block';
                });
            });

            document.addEventListener('click', function (event) {
                // Hide dropdowns when clicking outside of them
                hideAllDropdowns();
            });

            function hideAllDropdowns() {
                var dropdowns = document.querySelectorAll('.status-dropdown');
                dropdowns.forEach(function (dropdown) {
                    dropdown.style.display = 'none';
                });
            }
        });


        document.addEventListener("DOMContentLoaded", function () {
            var totalPaymentCells = document.querySelectorAll('.total-payment');

            totalPaymentCells.forEach(function (totalPaymentCell) {
                totalPaymentCell.addEventListener('mouseover', function () {
                    showPaymentTooltip(totalPaymentCell);
                });

                totalPaymentCell.addEventListener('mouseout', function () {
                    hidePaymentTooltip();
                });
            });
        });

        function showPaymentTooltip(element) {
            var paymentTime = element.getAttribute('data-payment-time');
            var paymentMethod = element.getAttribute('data-payment-method');
            var tooltipContent = "Payment Date: " + paymentTime + "<br>Payment Method: " + paymentMethod;

            var tooltipPopup = document.getElementById('paymentTooltip');
            tooltipPopup.innerHTML = tooltipContent;

            var rect = element.getBoundingClientRect();
            var tooltipWidth = tooltipPopup.offsetWidth;
            var tooltipHeight = tooltipPopup.offsetHeight;

            var leftOffset = rect.left + (rect.width / 2) - (tooltipWidth / 2);
            var topOffset = rect.bottom;

            tooltipPopup.style.top = topOffset + 'px';
            tooltipPopup.style.left = leftOffset + 'px';

            tooltipPopup.style.display = 'block';
            tooltipPopup.style.opacity = 1;
        }

        function hidePaymentTooltip() {
            var tooltipPopup = document.getElementById('paymentTooltip');
            tooltipPopup.style.display = 'none';
            tooltipPopup.style.opacity = 0;
        }

        function searchCustomers() {
            var searchValue = $('#search-input').val().toLowerCase();

            $('tbody tr').each(function () {
                var customerName = $(this).find('td:eq(1)').text().toLowerCase();

                if (customerName.includes(searchValue)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    </script>

    <script src="index.js"></script>
</body>

</html>