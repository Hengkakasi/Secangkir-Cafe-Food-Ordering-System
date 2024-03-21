<?php

$db="secangkir";

//connect database
$con=mysqli_connect("localhost","root","");
mysqli_select_db($con,$db);

if(!$con){
	die("Connection Error");
}

?>