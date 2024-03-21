<!-- use below for linking -->
<!-- header("Location: animation/coffeeFilling.php?final=page1"); --> 

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Loading......</title>

    <?php
        $finalPage = ""; // Default final page

        // Check if the parameter exists in the URL
        if (isset($_GET['final'])) {
            $finalPage = $_GET['final']; // Get the value of the parameter
        }

        // Set the redirect URL based on the parameter
        $redirectUrl = "finalPage.php"; // Default redirect URL

        if ($finalPage === "page1") {
            $redirectUrl = "login.php";
        } elseif ($finalPage === "page2") {
            $redirectUrl = "menu.php";
        } elseif ($finalPage === "page3") {
            $redirectUrl = "history.php";
        }
    ?>
    <meta http-equiv="refresh" content="1;url=<?php echo $redirectUrl; ?>">

    <!-- CSS File -->
    <link rel="stylesheet" href="coffeeFilling.css">

</head>

<body>

    <div class="cup">
        <span class="steam"></span>
        <span class="steam"></span>
        <span class="steam"></span>
        <div class="cup-handle"></div>
    </div>

    <script>
        setTimeout(function() {
            window.location.href = "../index.php";
        }, 1000); // Redirect to welcome.php after 1 second
    </script>

</body>

</html>