<?php
date_default_timezone_set('Asia/Kuala_Lumpur');

$servername = "localhost"; // Server
$username = "root"; // Username
$password = ""; // Password
$dbname = "secangkir"; // Database

// Create connection
$connection = new mysqli($servername, $username, $password, $dbname);

$customer_id = "";
$customer_name = "";
$email_address = "";
$phone_number = "";
$customer_password = "";
$registration_date = "";
$updation_date = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // GET METHOD: show the data of the customer

    if (!isset($_GET["customer_id"])) {
        header("Location:customer_list.php");
        exit;
    }

    $customer_id = $_GET["customer_id"];

    // Read the row of the selected customer from the database table
    $sql = "SELECT * FROM customer WHERE customer_id = $customer_id";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location:customer_list.php");
        exit;
    }

    $customer_id = $row['customer_id'];
    $customer_name = $row['customer_name'];
    $email_address = $row['email_address'];
    $phone_number = $row['phone_number'];
    $registration_date = $row['registration_date'];
    $updation_date = $row['updation_date'];

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POST method: update the data of the customer
    $customer_id = $_POST['customer_id'];
    $customer_name = $_POST['customer_name'];
    $email_address = $_POST['email_address'];
    $phone_number = $_POST['phone_number'];
    $registration_date = $_POST['registration_date'];
    $updation_date = date('Y-m-d H:i:s');

    if (empty($customer_id) || empty($customer_name) || empty($email_address) || empty($phone_number) || empty($registration_date) || empty($updation_date)) {
        $errorMessage = "All fields are required.";
    } else {
        $sql = "UPDATE customer SET customer_name = '$customer_name', email_address = '$email_address', phone_number = '$phone_number', registration_date = '$registration_date', updation_date = '$updation_date' WHERE customer_id = $customer_id";

        $result = $connection->query($sql);

        if (!$result) {
            $errorMessage = "Invalid query: " . $connection->error;
        } else {
            $successMessage = "Customer data updated successfully.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/styless.css">

    <link rel="icon" href="image/logo-bg.png" type="image/x-icon">
    <title>Secangkir Information</title>

    <style>

        /* Form container */
        main form {
            max-width: 600px;
            margin: auto;
        }

        /* Form rows */
        .row {
            margin-bottom: 15px;
        }

        /* Form labels */
        label {
            font-weight: bold;
        }

        /* Form inputs */
        .form-control {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        /* Success message */
        .alert-success {
            margin-top: 15px;
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            margin-right: 10px;
        }

        .btn-primary {
            background-color: #783b31;
            color: #fff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }

        body.dark .btn-primary{
            background-color: #c98d83;
            cursor: pointer;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
            border: none;
            border-radius: 10px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            form {
                max-width: 100%;
            }
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
            <li><a href="order.php"><i class='bx bx-receipt'></i>Orders</a></li>
            <li class="active"><a href="customer.php"><i class='bx bx-user'></i>Customer List</a></li>
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
                    <h1>Customer Information</h1>
                    <ul class="breadcrumb">
                        <li><a href="customer.php">
                                Customer List
                            </a></li>
                        /
                        <li><a style="pointer-events: none;" href="#" class="active">Customer Information</a></li>
                    </ul>
                </div>
            </div>

            <div class="bottom-data">
                <div class="orders">
                    <div class="header">
                        <i class='bx bx-user'></i>
                        <h3>Customer List</h3>
                    </div>

        <?php
        if (!empty($errorMessage)) {
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>" . $errorMessage . "</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";
        }

        ?>

        <form method="post">
            <input type="hidden" name="customer_id" value="<?php echo $customer_id ?>">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="customer_name" value="<?php echo $customer_name; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="email_address" value="<?php echo $email_address; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Phone</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="phone_number" value="<?php echo $phone_number; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Registration Date</label>
                <div class="col-sm-6">
                    <p><?php echo $registration_date; ?></p>
                    <input type="hidden" name="registration_date" value="<?php echo $registration_date; ?>">
    
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Updation Date</label>
                <div class="col-sm-6">
                    <p><?php echo $updation_date; ?></p>
                </div>
            </div>

            <?php
            if (!empty($successMessage)) {
                echo "
                <div class='row mb-3'>
                    <div class='offset-sm-3 col-sm-6'>
                        <div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>$successMessage</strong>
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                    </div>
                </div>
                ";
            }
            ?>

            <div class="row">
                <div class="col-sm-6 offset-sm-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="customer.php" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
                </div>
            </div>
        </main>
    </div>
    <script src="index.js"></script>
</body>

</html>