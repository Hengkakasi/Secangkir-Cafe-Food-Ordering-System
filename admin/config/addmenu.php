<?php

require_once 'connect.php';

$name = $_POST["name"];
$category = $_POST["category"];
$netprice = $_POST["netprice"];
$image = $_FILES['image']['name'];

$result = mysqli_query($con, "SELECT food_id FROM food ORDER BY food_id DESC LIMIT 1");
$row = mysqli_fetch_assoc($result);
$lastId = $row['food_id'];

$newId = $lastId + 1;

$sql = "INSERT INTO food(food_id, category_id, food_name, net_price, food_image)
VALUES (?,?,?,?,?);
";

$stmt = mysqli_prepare($con, $sql);

// Bind the parameters to the prepared statement
mysqli_stmt_bind_param($stmt, "iisds", $newId, $category, $name, $netprice, $image);

// Execute the prepared statement
if (mysqli_stmt_execute($stmt)) {
    // Close the prepared statement
    mysqli_stmt_close($stmt);

    // Close the database connection
    mysqli_close($con);

    // Redirect back to the same page
    header("Location: ../menulist.php");
    exit();
} else {
    // Close the prepared statement
    mysqli_stmt_close($stmt);

    // Close the database connection
    mysqli_close($con);

    die("Error updating menu: " . mysqli_error($con));
}
?>