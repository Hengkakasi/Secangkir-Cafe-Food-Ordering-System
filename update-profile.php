<?php

session_start();

include('connection/connect.php');

if (isset($_SESSION['customer_id'])) {
    $customer_id = intval($_SESSION['customer_id']);
} else {
    $customer_id = 0; // Set a default value or handle the missing customer ID case
}

//Create SQL Query to Display CAtegories from Database
$sql = "SELECT * FROM customer WHERE customer_id = $customer_id";
//Execute the Query
$res = mysqli_query($conn, $sql);
//Count rows to check whether the category is available or not
$count = mysqli_num_rows($res);

// Fetch user profile data
$select_customer = "SELECT * FROM customer WHERE customer_id = $customer_id";
$result_customer = mysqli_query($conn, $select_customer);
$fetch_customer = mysqli_fetch_assoc($result_customer);

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);

    if (!empty($name)) {
        $name = mysqli_real_escape_string($conn, $name);
        $update_name = "UPDATE customer SET customer_name = '$name' WHERE customer_id = $customer_id";
        mysqli_query($conn, $update_name);
    }

    if (!empty($email)) {
        $email = mysqli_real_escape_string($conn, $email);
        $select_email = "SELECT * FROM customer WHERE email_address = '$email'";
        $result_email = mysqli_query($conn, $select_email);
        if (mysqli_num_rows($result_email) > 0) {
            $message[] = 'email already taken!';
        } else {
            $update_email = "UPDATE customer SET email_address = '$email' WHERE customer_id = $customer_id";
            mysqli_query($conn, $update_email);
        }
    }

    if (!empty($number)) {
        $number = mysqli_real_escape_string($conn, $number);
        $select_number = "SELECT * FROM customer WHERE phone_number = '$number'";
        $result_number = mysqli_query($conn, $select_number);
        if (mysqli_num_rows($result_number) > 0) {
            $message[] = 'number already taken!';
        } else {
            $update_number = "UPDATE customer SET phone_number = '$number' WHERE customer_id = $customer_id";
            mysqli_query($conn, $update_number);
        }
    }

    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];
    
    if (!empty($old_pass) && !empty($new_pass) && !empty($confirm_pass)) {
        if ($old_pass == $fetch_customer['password']) {
            if ($new_pass == $confirm_pass) {
                $update_pass = "UPDATE customer SET password = '$confirm_pass' WHERE customer_id = $customer_id";
                mysqli_query($conn, $update_pass);
                $message[] = 'Password updated successfully!';
            } else {
                $message[] = 'Confirm password does not match!';
            }
        } else {
            $message[] = 'Old password is incorrect!';
        }
    } else {
        $message[] = 'Please fill in all password fields!';
    }
}


?>

<!DOCTYPE html>
<html lang="en">
    <head>
	    <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
	    <title>Secangkir | Update Profile</title>
        <link rel="icon" href="image/logo.png" type="image/png">

	    <!-- link for header -->
	    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- font awesome cdn link  -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

	    <!-- link for footer -->
	    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

	    <link href="css/main.css" rel="stylesheet">
        <link href="css/update-profile.css" rel="stylesheet">
    </head>

    <body>
        <header>
            <?php include('includes/main-header.php');?>
        </header>

        <div class="tablecontainer">
            <h1>Update Profile</h1>
            <p><a href="index.php">Home</a> / <a href="profile.php">Profile</a><span> / Update profile</span></p>
        </div>

        <section class="form-container update-form">

            <form action="" method="post">
                <h3>update profile</h3>
                <input type="text" name="name" placeholder="<?= $fetch_customer['customer_name']; ?>" class="box" maxlength="50">
                <input type="email" name="email" placeholder="<?= $fetch_customer['email_address']; ?>" class="box" maxlength="50"
                oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="number" name="number" placeholder="<?= $fetch_customer['password']; ?>" class=" box" min="0"
                max="9999999999" maxlength="10">
                <input type="password" name="old_pass" placeholder="enter your old password" class="box" maxlength="50"
                oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="password" name="new_pass" placeholder="enter your new password" class="box" maxlength="50"
                oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="password" name="confirm_pass" placeholder="confirm your new password" class="box"
                maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="submit" value="update now" name="submit" class="btn">
                <?php if (!empty($message)) : ?>
                <div class="error-messages">
                    <?php foreach ($message as $msg) : ?>
                        <p><?= $msg; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            </form>

        </section>

        <footer class="footer">
            <?php include('includes/main-footer.php');?>
        </footer>

    </body>
</html>