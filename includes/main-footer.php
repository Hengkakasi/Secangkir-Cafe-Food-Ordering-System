<div class="footer_container">
    <div class="row">
		<div class="footer-col">
			<h4>Opening hours</h4>
			<ul>
				<li><a href="#">SUNDAY TO FRIDAY:</a></li>
				<li><a href="#">09.00 - 18.00</a></li>
			</ul>
		</div>

		<div class="footer-col">
			<h4>Useful Links</h4>
			<?php
            if(isset($_SESSION['customer_id'])){
            ?>
			<ul>
				<li><a href="menu.php">Menu</a></li>
				<li><a href="history.php">History</a></li>
				<li><a href="cart.php">Cart</a></li>
			</ul>
			<?php
			}else{
			?>
			<ul>
				<li><a href="menu.php">Menu</a></li>
				<li><a href="login.php">History</a></li>
				<li><a href="login.php">Cart</a></li>
			</ul>
			<?php
			}?>
		</div>
					
        <div class="footer-col">
			<h4>Menu</h4>
			<ul>
				<li><a href="category-foods.php?category_id=1">Food</a></li>
				<li><a href="category-foods.php?category_id=2">Snack</a></li>
				<li><a href="category-foods.php?category_id=3">Desserts</a></li>
				<li><a href="category-foods.php?category_id=4">Beverages</a></li>
			</ul>
		</div>

	    <div class="footer-col">
			<h4>Scan For Mobile Menu</h4>
			<div>
				<img src="image/secangkirQR.png" alt="QR" height="150px" width="150px">
			</div>
		</div>
	</div>
</div>	

<div class="credit">
	Copyright © 2023 <span>Secangkir Cafe</span>.All Rights Reserved.
</div>


