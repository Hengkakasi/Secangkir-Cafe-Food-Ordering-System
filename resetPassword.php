<?php
session_start();

include('connection/connect.php');

if (isset($_SESSION['customer_id'])) {
    $customer_id = intval($_SESSION['customer_id']);
} else {
    $customer_id = 0; // Set a default value or handle the missing customer ID case
}

if(isset($_POST['submit'])){
    $Email = $_POST['email'];
    $Username = $_POST['username'];
    $password = $_POST['password'];
    $Cpassword = $_POST['cpassword'];

    $check_username= mysqli_query($conn, "SELECT customer_name FROM customer where customer_name = '$Username' ");
	$check_email = mysqli_query($conn, "SELECT email_address FROM customer where email_address = '$Email' ");

    if($password != $Cpassword){  
       	
        echo "<script>alert('Password not match');</script>";
        echo "<script>window.location.href = 'resetPassword.php';</script>";
        exit(); 
    }
	elseif(strlen($password) < 6)  
	{
        echo "<script>alert('Password Must be >=6');</script>";
        echo "<script>window.location.href = 'resetPassword.php';</script>";
        exit();  
	}
    elseif (!filter_var($Email, FILTER_VALIDATE_EMAIL)) 
    {
        echo "<script>alert('Invalid email address please type a valid email!');</script>";
        echo "<script>window.location.href = 'resetPassword.php';</script>";
        exit();  
    }
    elseif(mysqli_num_rows($check_username) == 0) 
    {
       echo "<script>alert('Username does not exists! Please enter the correct username.');</script>";
       echo "<script>window.location.href = 'resetPassword.php';</script>";
       exit();  
    }
	elseif(mysqli_num_rows($check_email) == 0) 
    {
       echo "<script>alert('Email does not exists! Please enter the correct email.');</script>";
       echo "<script>window.location.href = 'resetPassword.php';</script>";
       exit();  
    }
    else{
	    $mql = "UPDATE customer SET password = '$password' WHERE email_address = '$Email' AND customer_name = '$Username'";
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
	    <title>Secangkir | Reset Password</title>

	    <!-- link for header -->
	    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	    <!-- link for footer -->
	    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

	    <!-- <link href="cart.css" rel="stylesheet"> -->
	    <link href="css/main.css" rel="stylesheet">
        <link href="css/resetPassword.css" rel="stylesheet">
    </head>

    <body>
        <header>
            <?php include('includes/main-header.php');?>
        </header>

        <div class="content-container">
            <div class="wrapper">
                <div class="form-box login">
                    <h2>Reset Password</h2>
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
                                <ion-icon name="person-circle"></ion-icon>
                            </span>
                            <input type="text" name="username" required>
                            <label>Username</label>
                        </div>
                        <div class="input-box">
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
                        <button type="submit" class="btn" name="submit" >Submit</button>
                    </form>
                </div>
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

        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    </body>
</html>