<?php
include 'connection/connect.php';

$sql = "SELECT SUM(quantity) AS totalQuantity FROM cart WHERE customer_id = $customer_id";
$result = $conn->query($sql);

$itemCount = 0; // Default value

// Check if the query was successful and retrieve the item count
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $itemCount = $row['totalQuantity'];
}

$itemCountDisplay = ($itemCount > 0) ? $itemCount : 0;
?>


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
            <div class="item"><a href="update-profile.php"><i class="bi bi-person-lines-fill"></i>Profile Settings</a></div>
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
                    	<span class="cart_item_count"><?php echo $itemCountDisplay; ?></span>
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