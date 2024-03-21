<?php
session_start();

include('connection/connect.php');

if (isset($_SESSION['customer_id'])) {
    $customer_id = intval($_SESSION['customer_id']);
} else {
    $customer_id = 0; // Set a default value or handle the missing customer ID case
}

//==================== SQL FOR LOGIN ====================
if(isset($_POST['login'])){
    $Email = $_POST['email'];
    $password = $_POST['password'];

    $select = mysqli_query($conn, "SELECT * FROM customer WHERE email_address = '$Email' AND password = '$password' ");
    $row = mysqli_fetch_array($select);
    $num = mysqli_num_rows($select);

    if($num==1){
        $_SESSION["email_address"] = $row['email'];
        $_SESSION["customer_id"] = $row['customer_id'];
        header("Location: animation/coffeeFilling.php?final=page1");
        // header("Location: index.php");
        exit(); // Add this line to stop executing the rest of the code after redirecting
    }
    else{
        echo '<script type="text/javascript">';
        echo 'alert("Invalid Username or Password");';
        echo 'window.location.href = "login.php";';
        echo '</script>';
        exit();
    }
}

//==================== SQL FOR REGISTER ====================
if(isset($_POST['register'] )) 
{

    date_default_timezone_set("Asia/Kuala_Lumpur");
    $date = date('Y-m-d H:i:s');
    $Username = $_POST['username'];
    $Email = $_POST['email'];
    $password = $_POST['password'];
    $Cpassword = $_POST['cpassword'];
    $Phone = $_POST['phone'];

    $check_username= mysqli_query($conn, "SELECT customer_name FROM customer where customer_name = '$Username' ");
	$check_email = mysqli_query($conn, "SELECT email_address FROM customer where email_address = '$Email' ");
	
	if($password != $Cpassword){  
       	
        echo "<script>alert('Password not match');</script>";
        echo "<script>window.location.href = 'login.php';</script>";
        exit(); 
    }
	elseif(strlen($password) < 6)  
	{
        echo "<script>alert('Password Must be >=6');</script>";
        echo "<script>window.location.href = 'login.php';</script>";
        exit();  
	}
	elseif(strlen($Phone) < 10 || strlen($Phone) > 11)  
	{
      echo "<script>alert('Invalid phone number!');</script>";
      echo "<script>window.location.href = 'login.php';</script>";
      exit();  
	}

    elseif (!filter_var($Email, FILTER_VALIDATE_EMAIL)) 
    {
        echo "<script>alert('Invalid email address please type a valid email!');</script>";
        echo "<script>window.location.href = 'login.php';</script>";
        exit();  
    }
	elseif(mysqli_num_rows($check_username) > 0) 
    {
       echo "<script>alert('Username Already exists!');</script>";
       echo "<script>window.location.href = 'login.php';</script>";
       exit();  
    }
	elseif(mysqli_num_rows($check_email) > 0) 
    {
       echo "<script>alert('Email Already exists!');</script>";
       echo "<script>window.location.href = 'login.php';</script>";
       exit();  
    }
	else{
	    $mql = "INSERT INTO customer(customer_name,email_address,phone_number,password,registration_date) VALUES('$Username','$Email','$Phone','$password','$date')";
	    mysqli_query($conn, $mql);
	    header("Location: login.php");
        exit(); // Add this line to stop executing the rest of the code after redirecting
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
	    <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
	    <title>Secangkir | Login</title>
        <link rel="icon" href="image/logo.png" type="image/png">

	    <!-- link for header -->
	    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	    <!-- link for footer -->
	    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

	    <link href="css/main.css" rel="stylesheet">
        <link href="css/login.css" rel="stylesheet">
    </head>

    <body>
        <header>
            <div id="mySidenav" class="sidenav">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                <div class="menu">
                <?php
                if(isset($_SESSION['customer_id'])){
                ?>
                    <div class="item"><a href="index.php"><i class="bi bi-house"></i>Home</a></div>
                    <div class="item">
                        <a class="sub-btn" onclick="toggleSubMenu()"><i class="bi bi-journal-text"></i>Menu<i class="fas fa-angle-right dropdown"></i></a>
                        <div class="sub-menu">
                        <a href="category-foods.php?category_id=1" class="submenu1">Food</a>
                        <a href="category-foods.php?category_id=2" class="submenu1">Snack</a>
                        <a href="category-foods.php?category_id=3" class="submenu1">Desserts</a>
                        <a href="category-foods.php?category_id=4" class="submenu1">Beverages</a>
                        </div>
                    </div>
                    <div class="item"><a href="history.php"><i class="bi bi-journal-check"></i>My Orders</a></div>
                    <div class="item"><a href="wishlist.php"><i class="bi bi-heart"></i>Wishlist</a></div>
                    <div class="item"><a href="cart.php"><i class="bi bi-cart3"></i>Shopping Cart</a></div>
                    <div class="item"><a href="profile.php"><i class="bi bi-person-lines-fill"></i>Profile Settings</a></div>
                    <div class="item"><a href="logout.php"><i class="bi bi-box-arrow-right"></i>Logout</a></div>
                <?php
                }else{
                ?>
                    <div class="item"><a href="index.php"><i class="bi bi-house"></i>Home</a></div>
                    <div class="item">
                        <a class="sub-btn" onclick="toggleSubMenu()"><i class="bi bi-journal-text"></i>Menu<i class="fas fa-angle-right dropdown"></i></a>
                        <div class="sub-menu">
                        <a href="category-foods.php?category_id=1" class="submenu1">Food</a>
                        <a href="category-foods.php?category_id=2" class="submenu1">Snack</a>
                        <a href="category-foods.php?category_id=3" class="submenu1">Desserts</a>
                        <a href="category-foods.php?category_id=4" class="submenu1">Beverages</a>
                        </div>
                    </div>
                    <div class="item"><a href="login.php"><i class="bi bi-journal-check"></i>My Orders</a></div>
                    <div class="item"><a href="login.php"><i class="bi bi-heart"></i>Wishlist</a></div>
                    <div class="item"><a href="login.php"><i class="bi bi-cart3"></i>Shopping Cart</a></div>
                    <div class="item"><a href="login.php"><i class="bi bi-person-lines-fill"></i>Profile Settings</a></div>
                <?php
                }?>
                </div>
            </div>

            <div class="logo">

	            <h1>Secangkir<span>Cafe</span></h1>

            </div>

            <nav>
            	<span><i class="bi menu bi-list" onclick="openNav()"></i></span>
            	<div class="right_menu">
            		<ul>
            			<li><a href="index.php">Home</a></li>
            			<li class="dropdown">
            				<a href="menu.php">Menu</a>
            				<div class="dropdown-content">
            					<a href="category-foods.php?category_id=1">Food</a>
            					<a href="category-foods.php?category_id=2">Snack</a>
            					<a href="category-foods.php?category_id=3">Desserts</a>
            					<a href="category-foods.php?category_id=4">Beverages</a>
            				</div>
            			</li>
            			<li><a href="contact.php">Contact</a></li>
            		</ul>

                    <div class="user_cart">
            			<?php
                        if(isset($_SESSION['customer_id'])){
                        ?>  
                        <ul>
                        	<li>
                            <!-- <span class="material-icons-outlined"> favorite_border </span> -->
                            	<a href="wishlist.php" class="cart_action"><span class="material-icons-outlined"><i class="bi bi-heart" style="width: 28px; height: 28px;"></i></span></a>
                            </li>
                            <li>
                                <a href="cart.php" class="cart_action">
                                	<span class="material-icons-outlined"><i class="bi bi-cart3" style="width: 28px; height: 28px;"></i></i></span>
                                </a>
                            </li>

                            <li class="icon">
                                <span class="material-icons-outlined"> <i class="bi bi-person-circle" style="width: 28px; height: 28px;"></i></span>
                                <ul>
                                    <li class="sub-item">
                                        <span class="material-icons-outlined">
				                			<i class="bi bi-journal-check"></i>
                                        </span>
                                        <p><a href="history.php">My Orders</a></p>
                                    </li>
                                    <li class="sub-item">
                                        <span class="material-icons-outlined"><i class="bi bi-person-badge"></i></span>
                                        <p><a href="update-profile.php">Update Profile</a></p>
                                    </li>
                                    <li class="sub-item">
                                        <span class="material-icons-outlined"><i class="bi bi-box-arrow-right"></i></span>
                                        <p><a href="logout.php">Logout</a></p>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <?php
                        }else{
                        ?>
                        <ul>
                        	<li>
                            <!-- <span class="material-icons-outlined"> favorite_border </span> -->
                            	<a href="login.php" class="cart_action"><span class="material-icons-outlined"><i class="bi bi-heart" style="width: 28px; height: 28px;"></i></span></a>
                            </li>
                            <li>
                                <a href="login.php" class="cart_action">
                                	<span class="material-icons-outlined"><i class="bi bi-cart3" style="width: 28px; height: 28px;"></i></i></span>
                                </a>
                            </li>

                            <li class="icon">
                                <span class="material-icons-outlined"> <i class="bi bi-person-circle" style="width: 28px; height: 28px;"></i></span>
                                <ul>
                                    <li class="sub-item">
                                    <span class="material-icons-outlined"><i class="bi bi-box-arrow-in-left"></i></span>
                                        <p><a href="login.php">Login</a></p>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <?php
                        }?>
            		</div>
            	</div>
            </nav>
        </header>

        <div class="content-container">
            <div class="wrapper">
                <!-- CODE FOR LOGIN -->
                <div class="form-box login">
                    <h2>Login</h2>
                    <form action="" method="post">
                        <div class="input-box">
                            <span class="icon">
                                <ion-icon name="mail"></ion-icon>
                            </span>
                            <input type="email" name="email" required>
                            <label>Email</label>
                        </div>
                        <div class="input-box">
                            <span class="icon">
                                <ion-icon name="lock-closed"></ion-icon>
                            </span>
                            <input type="password" name="password" required>
                            <label>Password</label>
                        </div>
                        <div class="remember-forgot">
                            <label><input type="checkbox">Remember me</label>
                            <a href="resetPassword.php">Forgot Password?</a>
                        </div>
                        <button type="submit" class="btn" name="login">Login</button>
                        <div class="login-register">
                            <p>Don't have an account? <a href="#" class="register-link">Register</a></p>
                        </div>
                    </form>
                </div>

                <!-- CODE FOR REGISTER -->
                <div class="form-box register">
                    <h2>Registration</h2>
                    <form action="" method="post">
                        <div class="input-box" id="long">
                            <span class="icon">
                                <ion-icon name="person-circle"></ion-icon>
                            </span>
                            <input type="text" name="username" required>
                            <label>Username</label>
                        </div>

                        <div class="input-box" id="short">
                            <span class="icon">
                                <ion-icon name="mail"></ion-icon>
                            </span>
                            <input type="email" name="email" required>
                            <label>Email</label>
                        </div>

                        <div class="input-box" id="short">
                            <span class="icon">
                                <ion-icon name="call"></ion-icon>
                            </span>
                            <input type="tel" pattern="^(\d{10}|\d{11})$" name=phone required>
                            <label>Phone Number</label>
                        </div>

                        <div class="input-box" id="short">
                            <span class="icon">
                                <ion-icon name="lock-closed"></ion-icon>
                            </span>
                            <input type="password" name="password" required>
                            <label>Password</label>
                        </div>

                        <div class="input-box" id="short">
                            <span class="icon">
                                <ion-icon name="lock-closed"></ion-icon>
                            </span>
                            <input type="password" name="cpassword" required>
                            <label>Confirmed Password</label>
                        </div>

                        <div class="remember-forgot">
                            <label><input type="checkbox">Agree to the terms & conditions</label>
                        </div>
                        <button type="submit" class="btn" name="register">Register</button>
                        <div class="login-register">
                            <p>Already have an account? <a href="#" class="login-link">Login</a></p>
                        </div>
                    </form>
                </div>
            </div>

            <div class="vapour">
                <span style="--i:25;"></span>
                <span style="--i:1;"></span>
                <span style="--i:24;"></span>
                <span style="--i:3;"></span>
                <span style="--i:16;"></span>
                <span style="--i:5;"></span>
                <span style="--i:13;"></span>
                <span style="--i:20;"></span>
                <span style="--i:22;"></span>
                <span style="--i:6;"></span>
                <span style="--i:7;"></span>
                <span style="--i:10"></span>
                <span style="--i:8;"></span>
                <span style="--i:17;"></span>
                <span style="--i:11;"></span>
                <span style="--i:12;"></span>
                <span style="--i:14;"></span>
                <span style="--i:2;"></span>
                <span style="--i:9;"></span>
                <span style="--i:15;"></span>
                <span style="--i:4;"></span>
                <span style="--i:19;"></span>
                <span style="--i:18;"></span>
                <span style="--i:21;"></span>
                <span style="--i:23;"></span>
            </div>
        </div>

        <footer class="footer">
            <?php include('includes/main-footer.php');?>
        </footer>

        <script src="js/login.js"></script>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

        <script>

            function openNav() {
                document.getElementById("mySidenav").style.width = "350px";
            }
        
            function closeNav() {
                document.getElementById("mySidenav").style.width = "0";
            }
        
            function toggleSubMenu() {
                var subMenu = document.querySelector('.sub-menu');
                subMenu.classList.toggle('show');
            }

        </script>
    </body>
</html>