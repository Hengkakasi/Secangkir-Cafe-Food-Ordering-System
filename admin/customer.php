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

if (isset($_POST['action']) && $_POST['action'] === 'delete_customer') {
    // Assume you have a function to delete the customer by ID
    $customer_id = $_POST['customer_id'];
    $success = deleteCustomer($customer_id);

    // Output a simple response
    echo $success ? 'success' : 'error';
    exit;
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
    <title>Secangkir Customer List</title>
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
                    <h1>Customer List</h1>
                </div>
            </div>

            <div class="bottom-data">
                <div class="orders">
                    <div class="header">
                        <i class='bx bx-user'></i>
                        <h3>Customer List</h3>
                    </div>
                    <table>
						<thead>
							<tr>
								<th>Customer ID</th>
								<th>Name</th>
								<th>Email</th>
								<th>phone number</th>
								<th>Reg_date</th>
								<th>Upd_date</th>
								<th>Action</th>
							</tr>
						</thead>

						<tbody>
							<?php
							$servername = "localhost";
							$username = "root";
							$password = "";
							$database = "secangkir";

							//create connection
							$connection = new mysqli($servername, $username, $password, $database);

							//check connection
							if($connection -> connect_error){
								die("Connection failed: " . $connection -> connect_error);
							}

							// Read all rows from the database table
							
							$sql = "SELECT * FROM `customer`;";
							$result = $connection->query($sql);

							if (!$result) {
								die("Invalid query: " . $connection->error);
							}
							// Read data of each row
							while ($row = $result->fetch_assoc()) {
								$customer_id = $row['customer_id'];
								$customer_name = $row['customer_name'];
								$email_address = $row['email_address'];
								$phone_number = $row['phone_number'];
								$registration_date = $row['registration_date'];
								$updation_date = $row['updation_date'];

								echo "<tr>
										<td>$customer_id</td>
										<td>$customer_name</td>
										<td>$email_address</td>
										<td>$phone_number</td>
										<td>$registration_date</td>
										<td>$updation_date</td>
										<td>
											<a class='bx bx-edit-alt' href='customerEdit.php?customer_id=$customer_id'></a>
											<a class='bx bx-trash' href='javascript:void(0);' onclick='confirmDelete($customer_id)'></a>
										</td>
									</tr>";
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

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        function confirmDelete(customer_id) {
            var confirmation = confirm("Are you sure you want to delete?");
            if (confirmation) {
                // If the user clicks "OK" in the confirmation dialog, proceed with deletion
                $.ajax({
                    type: "POST",
                    url: "customer.php",
                    data: {
                        action: 'delete_customer',
                        customer_id: customer_id
                    },
                    success: function (response) {
                        if (response === 'success') {
                            alert("Customer account has been deleted!");
                            location.reload();
                        } else {
                            alert("Failed to delete customer account.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error: " + status + "\n" + error);
                        alert("Error in AJAX request. Check the console for details.");
                    }
                });
            }
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

    <?php
        function deleteCustomer($customer_id) {
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
        
            // delete query here
            $sql = "DELETE FROM customer WHERE customer_id = $customer_id";
            $result = $connection->query($sql);
        
            // Close connection
            $connection->close();
        
            // Return true if the query was successful, false otherwise
            return $result ? true : false;
        }
    ?>
    <script src="index.js"></script>
</body>

</html>