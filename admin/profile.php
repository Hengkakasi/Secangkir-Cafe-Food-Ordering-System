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

    <link rel="icon" href="image/logo-bg.png" type="image/x-icon">
    <title>Secangkir Profile</title>

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
            <li><a href="customer.php"><i class='bx bx-user'></i>Customer List</a></li>
            <li><a href="reports.php"><i class='bx bx-bar-chart-alt-2'></i>Report</a></li>
            <li class="active"><a href="profile.php"><i class='bx bx-id-card'></i>Profile Setting</a></li>
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
                    <h1>Profile Setting</h1>
                </div>
            </div>

            <div class="bottom-data">
                <div class="orders">
                    <div class="header">
                        <i class='bx bx-id-card'></i>
                        <h3>Profile Setting</h3>
                    </div>

                    <?php
                    // Include database connection code (adjust the connection details as per your setup)
                    $servername = "localhost"; // Server
                    $username = "root"; // Username
                    $password = ""; // Password
                    $dbname = "secangkir"; // Database

                    // Create connection
                    $connection = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                    if ($connection->connect_error) {
                        die("Connection failed: " . $connection->connect_error);
                    }

                    // Initialize variables
                    $staff_id = 1; // Assuming you have a staff_id, update it accordingly
                    $staff_name = "";
                    $staff_contact = "";
                    $staff_password = "";
                    $confirm_password = "";
                    $update_success = "";

                    // Fetch staff details based on staff_id
                    $selectQuery = "SELECT * FROM staff WHERE staff_id = $staff_id";
                    $result = $connection->query($selectQuery);

                    if ($result && $result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $staff_name = $row["staff_name"];
                        $staff_contact = $row["staff_contact"];
                    }

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $staff_name = $_POST['staff_name'];
                        $staff_contact = $_POST['staff_contact'];
                        $new_password = $_POST['staff_password'];
                        $confirm_password = $_POST['confirm_password'];
                    
                        // Check if any update checkbox is selected
                        if (!empty($staff_name)) {
                            $updateQuery = "UPDATE staff SET staff_name='$staff_name' WHERE staff_id=$staff_id";
                        
                            if ($connection->query($updateQuery) === TRUE) {
                                $update_success = "Profile updated successfully!";
                            } else {
                                $update_success = "Error updating profile: " . $connection->error;
                            }
                        } else {
                            $update_success = "Profile updated unsuccessfully!";
                        }
                    
                    
                        if (!empty($staff_contact)) {
                            $updateQuery = "UPDATE staff SET staff_contact='$staff_contact' WHERE staff_id=$staff_id";
                        
                            if ($connection->query($updateQuery) === TRUE) {
                                $update_success = "Profile updated successfully!";
                            } else {
                                $update_success = "Error updating profile: " . $connection->error;
                            }
                        } else {
                            $update_success = "Profile updated unsuccessfully!";
                        }
                    
                    
                        // Update password if both password and confirm password are not empty and match
                        if (!empty($new_password) && $new_password == $confirm_password) {
                            $updateQuery = "UPDATE staff SET staff_password='$new_password' WHERE staff_id=$staff_id";
                        
                            if ($connection->query($updateQuery) === TRUE) {
                                $update_success = "Profile updated successfully!";
                            } else {
                                $update_success = "Error updating profile: " . $connection->error;
                            }
                        } elseif (!empty($new_password) && $new_password != $confirm_password) {
                            $update_success = "Password and Confirm Password do not match!";
                        }
                    
                        // Fetch updated staff details
                        $selectQuery = "SELECT * FROM staff WHERE staff_id = $staff_id";
                        $result = $connection->query($selectQuery);
                    
                        if ($result && $result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $staff_name = $row["staff_name"];
                            $staff_contact = $row["staff_contact"];
                        }
                    
                        // Close the result set
                        $result->close();
                    }
                
                    // Close connection
                    $connection->close();
                    ?>

                    <form method="post">
                        <input type="hidden" name="staff_name" value="">
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Admin Name</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="staff_name" placeholder="<?php echo htmlspecialchars($staff_name); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Admin Phone</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="staff_contact" placeholder="<?php echo htmlspecialchars($staff_contact); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Password</label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" name="staff_password" placeholder="">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Confirm Password</label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" name="confirm_password" placeholder="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 offset-sm-3">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="index.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>

                    <!-- Display success message -->
                    <?php
                    if (!empty($update_success)) {
                        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>$update_success</strong>
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";
                    }
                    ?>

                </div>
            </div>
        </main>
    </div>
    <script src="index.js"></script>
</body>

</html>