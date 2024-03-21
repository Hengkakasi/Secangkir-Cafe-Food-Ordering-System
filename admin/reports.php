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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="icon" href="image/logo-bg.png" type="image/x-icon">
    <title>Secangkir Reports</title>
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
            <li><a href="order.php"><i class='bx bx-receipt'></i>Orders</a></li>
            <li><a href="customer.php"><i class='bx bx-user'></i>Customer List</a></li>
            <li class="active"><a href="reports.php"><i class='bx bx-bar-chart-alt-2'></i>Report</a></li>
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
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
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
                    <h1>Reports</h1>
                </div>
            </div>

            <div class="bottom-data">
                <div class="orders">
                    <div class="header">
                        <i class='bx bx-bar-chart'></i>
                        <h3>Payment Method Use By Customers</h3>
                    </div>
                    <canvas id="paymentChart" width="50px" height="50px"></canvas>

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

                    // Query to get payment method data from the database
                    $query = "SELECT paymentMethod_name AS method, COUNT(*) AS count FROM payment
                              JOIN paymentmethod ON payment.paymentMethod_id = paymentmethod.paymentMethod_id
                              GROUP BY paymentMethod_name";

                    $result = $connection->query($query);

                    if (!$result) {
                        die("Invalid query: " . $connection->error);
                    }

                    // Fetch data and prepare for JavaScript
                    $data = array();
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                    ?>

                    <script>
                        // Use PHP data in JavaScript
                        const labels = <?php echo json_encode(array_column($data, 'method')); ?>;
                        const counts = <?php echo json_encode(array_column($data, 'count')); ?>;

                        const pmc = document.getElementById('paymentChart').getContext('2d');

                        new Chart(pmc, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Payment Methods',
                                    data: counts,
                                    backgroundColor: ['#3498db', '#2ecc71'], // Set colors as needed
                                }],
                            },
                        });
                    </script>
                    
                </div>

                <div class="reminders">
                    <div class="header">
                        <i class='bx bxs-pie-chart-alt-2'></i>
                        <h3>Number Of Food In Each Categories</h3>
                    </div>

                    <?php 
                    //embed PHP code from another file
                    require_once 'config/connect.php';

                    // Fetch food category data
                    $query = "SELECT c.category_name, COUNT(f.food_id) AS food_count
                              FROM category c
                              LEFT JOIN food f ON c.category_id = f.category_id
                              GROUP BY c.category_name";

                    $result = $connection->query($query);

                    if (!$result) {
                        die("Invalid query: " . $connection->error);
                    }

                    // Fetch data and prepare for JavaScript
                    $data = array();
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                    ?>

                    <!-- Pie chart canvas -->
                    <canvas id="foodCategoryChart" width="400px" height="400px"></canvas>

                    <script>
                        // Use PHP data in JavaScript
                        const categoryLabels = <?php echo json_encode(array_column($data, 'category_name')); ?>;
                        const foodCounts = <?php echo json_encode(array_column($data, 'food_count')); ?>;
                                    
                        const foodCategoryChartCanvas = document.getElementById('foodCategoryChart').getContext('2d');
                                    
                        new Chart(foodCategoryChartCanvas, {
                            type: 'pie',
                            data: {
                                labels: categoryLabels,
                                datasets: [{
                                    data: foodCounts,
                                    backgroundColor: ['#3498db', '#2ecc71', '#e74c3c', '#f39c12'], // Add more colors if needed
                                }],
                            },
                        });
                    </script>
                </div>

                <div class="orders">
                    <div class="header">
                        <i class='bx bxs-pie-chart-alt-2'></i>
                        <h3>Order Types</h3>
                    </div>

                    <?php
                    // Query to get the total number of orders for each order type
                    $query = "SELECT take_meal, COUNT(*) AS order_count FROM orders GROUP BY take_meal";

                    $result = $connection->query($query);

                    if (!$result) {
                        die("Invalid query: " . $connection->error);
                    }
                
                    // Fetch data and prepare for JavaScript
                    $data = array();
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                    ?>

                    <!-- Pie chart canvas -->
                    <canvas id="orderTypeChart" width="400px" height="400px"></canvas>
                
                    <script>
                        // Use PHP data in JavaScript
                        const orderTypes = <?php echo json_encode(array_column($data, 'take_meal')); ?>;
                        const orderCounts = <?php echo json_encode(array_column($data, 'order_count')); ?>;
                
                        const orderTypeChartCanvas = document.getElementById('orderTypeChart').getContext('2d');
                
                        new Chart(orderTypeChartCanvas, {
                            type: 'pie',
                            data: {
                                labels: orderTypes,
                                datasets: [{
                                    data: orderCounts,
                                    backgroundColor: ['#3498db', '#e74c3c'], // Add more colors if needed
                                }],
                            },
                        });
                    </script>

                </div>

                <div class="reminders">
                    <div class="header">
                        <i class='bx bx-line-chart'></i>
                        <h3>Total Income</h3>
                    </div>
                    
                    <canvas id="incomeLineChart" width="400px" height="400px"></canvas>

                    <?php
                    // Query to get the total income for each date
                    $query = "SELECT DATE(payment_time) AS payment_date, 
                                     SUM(total_amount) AS total_income
                              FROM payment
                              GROUP BY DATE(payment_time)";

                    $result = $connection->query($query);

                    if (!$result) {
                        die("Invalid query: " . $connection->error);
                    }
                
                    // Fetch data and prepare for JavaScript
                    $data = array();
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                    ?>

                    <script>
                        // Use PHP data in JavaScript
                        const paymentDates = <?php echo json_encode(array_column($data, 'payment_date')); ?>;
                        const totalIncome = <?php echo json_encode(array_column($data, 'total_income')); ?>;
                
                        const incomeLineChartCanvas = document.getElementById('incomeLineChart').getContext('2d');
                
                        new Chart(incomeLineChartCanvas, {
                            type: 'line',
                            data: {
                                labels: paymentDates,
                                datasets: [{
                                    label: 'Total Income',
                                    data: totalIncome,
                                    borderColor: '#3498db', // Line color for Total Income
                                    fill: false,
                                }],
                            },
                        });
                    </script>

                    <?php
                    // Close connection
                    $connection->close();
                    ?>
                </div>
            </div>

        </main>
    </div>
    <script src="index.js"></script>
</body>

</html>