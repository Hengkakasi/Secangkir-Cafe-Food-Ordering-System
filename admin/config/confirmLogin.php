<?php

//embed PHP code from another file
require_once 'connect.php';

session_start();

//php code for login page
if (isset($_POST['Uid'])){
	$uname=$_POST['Uid'];
	$password=$_POST['Pword'];
	
	//select all from table login with column id and password
	//limit 1 ensures that only one row is returned
	$sql = "select * from staff where staff_name='".$uname."' AND staff_password= '".$password."' limit 1";
	
	$result=mysqli_query($con,$sql);
	if(mysqli_num_rows($result)==1){
		//login successfully
		$row = mysqli_fetch_assoc($result);
		$Uid = $row['staff_name'];
		$_SESSION["Uid"] = $Uid;
	 	header("Location: ../index.php");
		exit;
	}
	else{
		echo '<script>alert("Login failed. Please check your username and password.");</script>';
        echo '<script>window.location.href = "../login.php";</script>';
	}
}
?>