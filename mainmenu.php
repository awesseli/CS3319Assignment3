<?php
// Programmer Name: 93
// Purpose: Main menu for Assignment 3
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Assignment 3 - Main Menu</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

<!-- Top header bar -->
<div class="header">
    <h1>Assignment 3 - Store Database</h1>
    <nav>
        <a href="mainmenu.php">Home</a>
        <a href="listcustomers.php">Customers</a>
        <a href="listproducts.php">Products</a>
    </nav>
</div>

<!-- Main content card -->
<div class="container">
    <h2>Main Menu</h2>
    <p>Welcome to 93's Store Database!</p>
    <p>Select an option below:</p>

    <ul class="menu-list">
        <li>
            <a class="button" href="listcustomers.php">
                List all customers &amp; view their purchases
            </a>
        </li>
        <li>
            <a class="button" href="listproducts.php">
                List all products with sorting
            </a>
        </li>
        <li>
            <a class="button" href="insertpurchase.php">
                Insert a new purchase
            </a>
        </li>
        <li>
            <a class="button" href="insertcustomer.php">
                Insert a new customer
            </a>
        </li>
        <li>
            <a class="button" href="deletecustomer.php">
                Delete a customer
            </a>
        </li>
        <li>
            <a class="button" href="customersByQuantity.php">
                Customers who bought more than X of a product
            </a>
        </li>
        <li>
            <a class="button" href="neverpurchased.php">
                Products that have never been purchased
            </a>
        </li>
    </ul>
</div>

</body>
</html>
