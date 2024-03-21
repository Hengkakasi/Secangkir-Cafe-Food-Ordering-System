<?php 
//embed PHP code from another file
require_once 'config/connect.php';
require_once 'config/confirmLogin.php';

//before access admin page need to login first 
if(isset($_SESSION["Uid"])){
   $admin_name = $_SESSION["Uid"];
}else{
   header('location:login.php');
}

// Handle food deletion
if (isset($_POST['action']) && $_POST['action'] === 'delete_food') {
    $food_name = $_POST['food_name'];
    $success = deleteFood($con, $food_name);
    if ($success) {
        // Redirect to prevent form resubmission on page refresh
        header('Location: menulist.php');
        exit;
    } else {
        echo "Failed to delete food.";
    }
}

// Function to delete food
function deleteFood($con, $food_name)
{
    $food_name = mysqli_real_escape_string($con, $food_name);
    $sql = "DELETE FROM food WHERE food_name = '$food_name'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return true;
    } else {
        // Display the error message
        echo "Error: " . mysqli_error($con);
        return false;
    }
}


// Handle food update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'update_food') {
    $food_name = mysqli_real_escape_string($con, $_POST['food_name']);
    $category_id = mysqli_real_escape_string($con, $_POST['category']);
    $net_price = mysqli_real_escape_string($con, $_POST['netprice']);

    $success = updateFood($con, $food_name, $category_id, $net_price);
    if ($success) {
        // Redirect to prevent form resubmission on page refresh
        header('Location: menulist.php');
        exit;
    } else {
        echo "Failed to update food.";
    }
}


// Function to update food
function updateFood($con, $food_name, $category_id, $net_price)
{
    $food_name = mysqli_real_escape_string($con, $food_name);
    $category_id = mysqli_real_escape_string($con, $category_id);
    $net_price = mysqli_real_escape_string($con, $net_price);

    $sql = "UPDATE food SET category_id = '$category_id', net_price = '$net_price' WHERE food_name = '$food_name'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        return true;
    } else {
        // Display the error message
        echo "Error: " . mysqli_error($con);
        return false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/styless.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <link rel="icon" href="image/logo-bg.png" type="image/x-icon">
    <title>Secangkir Menu</title>

    <style>

        .modal {
            display: none;
            position: fixed;
            z-index: 5000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .formbg {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
            
        .modal-content {
            background-color: #783b31;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            overflow-y: auto; /* Add this line for vertical scroll if needed */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        form {
            text-align: center;
        }

        label {
            font-size: 18px;
            margin-bottom: 8px;
        }

        /* Style text and file inputs as boxes */
        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 14px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 1px solid #000000;
            border-radius: 8px;
            font-size: 16px;
        }

        .radio-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .radio-option {
            flex: 1;
        }

        input[type="submit"] {
            background-color: #783b31;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            box-sizing: border-box;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Responsiveness */
        @media only screen and (max-width: 600px) {
            .modal-content {
                width: 90%;
            }
        }
    </style>

    <script>
        // Function to close the modal
        function closeModal() {
            document.getElementById("addMenuModal").style.display = "none";
            document.getElementById("editMenuModal").style.display = "none";
        }
    </script>

</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="index.php" class="logo">
            <img style="height: 36px; width: 36px;" src="image/logo-bg.png">
            <div class="logo-name"><span>Secangkir</span>Cafe</div>
        </a>
        <ul class="side-menu">
            <li><a href="index.php"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li class="active"><a href="menulist.php"><i class='bx bx-coffee'></i>Menu</a></li>
            <li><a href="order.php"><i class='bx bx-receipt'></i>Orders</a></li>
            <li><a href="customer.php"><i class='bx bx-user'></i>Customer List</a></li>
            <li><a href="reports.php"><i class='bx bx-bar-chart-alt-2'></i>Report</a></li>
            <li><a href="profile.php"><i class='bx bx-id-card'></i>Profile Setting</a></li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="logout.php" class="logout">
                    <i class='bx bx-log-out-circle'></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
    <!-- End of Sidebar -->

    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i>
            <form action="#">
                <div class="form-input">
                <input type="text" id="search-input" oninput="searchMenu()" placeholder="Search...">

                    <button class="search-btn" type="submit"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <input type="checkbox" id="theme-toggle" hidden>
            <label for="theme-toggle" class="theme-toggle"></label>
            <a href="index.php" class="notif">
                <i class='bx bx-bell'></i>
            </a>
            <a href="profile.php" class="profile">
                <img src="image/logo-bg.png">
            </a>
        </nav>

        <!-- End of Navbar -->

        <main>
            <div class="header">
                <div class="left">
                    <h1>Menu List</h1>
                </div>
                <a id="addMenuLink" class="report" href="#">
                    <i class='bx bx-plus'></i>
                    <span>Add Menu</span>
                </a>
            </div>

            <!-- Modal for adding food -->
            <div id="addMenuModal" class="modal">
            <div class="formbg">
                <span class="close" onclick="closeModal()" style="position: fixed; right:5%; top:-2%; cursor: pointer; font-size: 50px;"><i class='bx bx-x'></i></span>
                    <div class="formbg-inner padding-horizontal--48">
                    <span class="padding-bottom--15" style=" text-align: center; margin: 20px 0; color: #000; font-size: 22px">Add Menu</span>
                    <form id="addmenuForm" method="post" action="config/addmenu.php" enctype="multipart/form-data" style="text-align: left;">
                            <div class="field padding-bottom--24">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" class="form-control validate" required style="width: 100%; margin: 5px 0%; padding: 10px 0; border: 1px solid #000; /* Add a black border */"  >
                            </div>
                            <div class="field padding-bottom--24" style="position: relative; width: 100%;">
                                <label for="category">Category:</label>
                                <select id="category" name="category" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid #000; border-radius: 4px; appearance: none; -webkit-appearance: none; background-color: #fff; cursor: pointer;">
                                <option value="1" style="color: #000; background-color: #fff; padding: 100px; margin: 10px 0; border: 1px solid #000;">Food</option>
                                <option value="2" style="color: #000; background-color: #fff; padding: 10px; margin: 10px 0; border: 1px solid #000;">Snack</option>
                                <option value="3" style="color: #000; background-color: #fff; padding: 10px; margin: 10px 0; border: 1px solid #000;">Dessert</option>
                                <option value="4" style="color: #000; background-color: #fff; padding: 10px; margin: 10px 0; border: 1px solid #000;">Beverages</option>
                            </select>
                            <span style="position: absolute; top: 70%; right: 10px; transform: translateY(-50%); font-size: 18px; color: #000;">▼</span>

            </div>
                            <div class="field padding-bottom--24">
                                <label for="netprice">Net Price:</label>
                                <input type="text" id="netprice" name="netprice" class="form-control validate" required style="width: 100%; margin: 5px 0%; padding: 10px 0; border: 1px solid #000; /* Add a black border */">
                            </div>

                            <div class="field padding-bottom--24">
                                <label for="image">Image:</label>
                                <div class="custom-file mt-3 mb-3">
                                    <input type="file" id="image" name="image" class="custom-file-input" required onchange="previewImage(this)">
                                </div>
                                <img id="previewImage" src="#" alt="Preview" style="max-width: 100%; max-height: 200px; display: none;">
                            </div>
                            <div class="col-12" style=" text-align:left">
                            <input type="submit" value="Add" class="button-model" style="width: 100%; margin: 30px 0%; padding: 20px 0; background-color:#783b31;" onclick="closeModal()">

                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Duplicate the existing modal for adding food and change the id -->
            <div id="editMenuModal" class="modal">
                <div class="formbg">
                    <span class="close" onclick="closeModal()" style="position: fixed; right:5%; top:-2%; cursor: pointer; font-size: 50px;"><i class='bx bx-x'></i></span>
                    <div class="formbg-inner padding-horizontal--48">
                        <span class="padding-bottom--15" style=" text-align: center; margin: 20px 0; color: #000; font-size: 22px">Edit Menu</span>
                        <form id="editmenuForm" method="post" action="" enctype="multipart/form-data" style="text-align: left;">
                            <div class="field padding-bottom--24">
                                <label for="edit_name">Name:</label>
                                <input type="text" id="edit_name" name="food_name" class="form-control validate" required readonly>
                            </div>
                            <div class="field padding-bottom--24" style="position: relative; width: 100%;">
                                <label for="edit_category">Category:</label>
                                <select id="edit_category" name="category" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid #000; border-radius: 4px; appearance: none; -webkit-appearance: none; background-color: #fff; cursor: pointer;">
                                    <option value="1" style="color: #000; background-color: #fff; padding: 100px; margin: 10px 0; border: 1px solid #000;">Food</option>
                                    <option value="2" style="color: #000; background-color: #fff; padding: 10px; margin: 10px 0; border: 1px solid #000;">Snack</option>
                                    <option value="3" style="color: #000; background-color: #fff; padding: 10px; margin: 10px 0; border: 1px solid #000;">Dessert</option>
                                    <option value="4" style="color: #000; background-color: #fff; padding: 10px; margin: 10px 0; border: 1px solid #000;">Beverages</option>
                                </select>
                                <span style="position: absolute; top: 70%; right: 10px; transform: translateY(-50%); font-size: 18px; color: #000;">▼</span>
                            </div>
                            <div class="field padding-bottom--24">
                                <label for="edit_netprice">Net Price:</label>
                                <input type="text" id="edit_netprice" name="netprice" class="form-control validate" required>
                            </div>
                            <input type="submit" value="Update" class="button-model" style="width: 100%; margin: 30px 0%; padding: 20px 0; background-color:#783b31;">
                            <input type="hidden" name="action" value="update_food">
                        </form>
                    </div>
                </div>
            </div>

            <div class="bottom-data">
                <div class="orders">
                    <div class="header">
                        <i class='bx bx-coffee'></i>
                        <h3>Menu List</h3>
                    </div>
                    <table class="menu-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Food Name</th>
                                <th>Category</th>
                                <th>Net Price</th>
                                <th>Updated On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sql = "SELECT * FROM food f
                                        JOIN category c
                                        ON f.category_id = c.category_id;";
                                $result = mysqli_query($con, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $imageData = $row["food_image"];
                                        ?>
                                        <tr>

                                            <td style="padding: 20px;"><img src='image/<?php echo $imageData; ?>' alt='Food Image' style='width: 100px; height: 100px;'></td>
                                            <td><?php echo $row["food_name"]; ?></td>
                                            <td><?php echo $row["category_name"]; ?></td>
                                            <td><?php echo $row["net_price"]; ?></td>
                                            <td><?php echo $row["updation_date"]; ?></td>
                                            <td>
                                                <!-- <a class='bx bx-edit-alt' href='#'></a> -->
                                                <a class='bx bx-edit-alt' href='javascript:void(0);' onclick='openEditModal("<?php echo $row["food_name"]; ?>", "<?php echo $row["category_id"]; ?>", "<?php echo $row["net_price"]; ?>")'></a>
											    <a class='bx bx-trash' href='javascript:void(0);' onclick='confirmDelete("<?php echo $row["food_name"]; ?>")'></a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>0 results</td></tr>";
                                }
                                mysqli_close($con);
                                ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        function confirmDelete(food_name) {
            var confirmation = confirm("Are you sure you want to delete?");
            if (confirmation) {
                // Set the value of the hidden input field
                $('#food_name_input').val(food_name);
                // Submit the form
                $('#delete_food_form').submit();
            }
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var addMenuLink = document.getElementById("addMenuLink");
            var addMenuModal = document.getElementById("addMenuModal");

            // Add click event listener to the "Add Menu" link
            addMenuLink.addEventListener("click", function (event) {
                event.preventDefault(); // Prevent the default behavior of the link

                // Show the modal
                addMenuModal.style.display = "block";
            });

            // Function to close the modal
            function closeModal() {
                addMenuModal.style.display = "none";
            }
        });
    </script>

    <script>
        document.getElementById('image').addEventListener('change', function (event) {
            var input = event.target;
            var previewImage = document.getElementById('previewImage');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        });

        function searchMenu() {
            var searchValue = $('#search-input').val().toLowerCase();

            $('tbody tr').each(function () {
                var foodName = $(this).find('td:eq(1)').text().toLowerCase();

                if (foodName.includes(searchValue)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    </script>
    <script>
        function openEditModal(foodName, category, netPrice) {
    var editModal = document.getElementById("editMenuModal");
    var editNameInput = document.getElementById("edit_name");
    var editCategoryInput = document.getElementById("edit_category");
    var editNetPriceInput = document.getElementById("edit_netprice");

    // Set values in the edit modal
    editNameInput.value = foodName;
    editCategoryInput.value = category;
    editNetPriceInput.value = netPrice;

    // Show the edit modal
    editModal.style.display = "block";
}
    </script>

    <!-- Add this form to the HTML -->
    <form id="delete_food_form" method="post">
        <input type="hidden" name="action" value="delete_food">
        <input type="hidden" id="food_name_input" name="food_name">
    </form>

    <script src="index.js"></script>
</body>

</html>