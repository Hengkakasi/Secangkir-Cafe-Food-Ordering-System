<?php

if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo '<script>window.alert("Register successfully!");</script>';
}

if (isset($_GET['fail']) && $_GET['fail'] == 1) {
    echo '<script>window.alert("Cannot find your account. Try to register a new account.");</script>';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="icon" href="image/logo-bg.png" type="image/x-icon">
  <title>Admin Login Panel</title>
  <style>
      *{
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: poppins;
      }

      body{
        background-color: white;
      }

      div.container{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
        text-align: center;
        display: flex;
        flex-direction: row;
        align-items: center;
        background-color: #ffedeb;
        padding: 50px;
        box-shadow: 0 50px 50px -50px darkslategray;
      }

      div.container div.myform{
        width: 270px;
        margin-right: 30px;
      }

      div.container div.myform h2{
        color: #783b31;
        margin-bottom: 20px;
      }

      div.container div.myform input{
        border: none;
        outline: none;
        border-radius: 0;
        width: 100%;
        border-bottom: 2px solid #783b31;
        margin-bottom: 25px;
        padding: 7px 0;
        font-size: 20px;
        text-align: center;
        background-color: #ffedeb;
      }

      div.container div.myform button{
        color: white;
        background-color: #783b31;
        border: none;
        outline: none;
        border-radius: 2px;
        font-size: 20px;
        padding: 5px 12px;
        font-weight: 500;
      }

      div.container div.image{
        width: 500px;
        height: 500px;
      }
  </style>

</head>
<body>
  
  <div class="container">
    <div class="myform">
      <form method="post" action="config/confirmLogin.php">
        <h2>ADMIN LOGIN</h2>
        <input type="text" name="Uid" placeholder="Admin Name" required>
        <input type="password" name="Pword" placeholder="Password" required>
        <button type="submit">LOGIN</button>
      </form>
    </div>
    <div class="image">
    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script> 

<dotlottie-player src="https://lottie.host/735cadfb-9f70-439d-b4a9-f4354d7ce175/vA5bRVmzOX.json" background="transparent" speed="1" style="width: 500px; height: 500px;" loop autoplay></dotlottie-player>
    </div>
  </div>

</body>
</html>