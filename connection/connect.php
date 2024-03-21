<?php

//main connection file for both admin & front end
$servername = "localhost"; //server
$username = "root"; //username
$password = ""; //password
$dbname = "secangkir";  //database

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname); // connecting 
// Check connection
if (!$conn) {       //checking connection to DB	
    die("Connection failed: " . mysqli_connect_error());
}
else{
    //echo "Connected successfully";
}

?>