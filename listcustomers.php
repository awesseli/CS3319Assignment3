<?php

// Programmer Name: 93
// Purpose: List all customers, then link to show their purchases

include 'dbconnect.php';

// Get all customers sorted by last name, then their first name

$query = "SELECT * FROM customer ORDER BY lastname, firstname";
$result =  mysqli_query($connection, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>All Customers</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

<div class="header">
    <h1>Assignment 3 - Store Database</h1>
    <nav>
        <a href="mainmenu.php">Home</a>
        <a href="listcustomers.php">Customers</a>
        <a href="listproducts.php">Products</a>
    </nav>
</div>

<div class="container">
    <h2>All Customers</h2>

<!-- Citation of Table resource
https://www.w3schools.com/html/html_tables.asp -->

    <table>
        <tr>
            <th>Customer ID</th>
            <th>Name</th>
            <th>City</th>
            <th>Phone</th>
            <th>View Purchases</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['cusID']; ?></td>
                <td><?php echo $row['firstname'] . " " . $row['lastname']; ?></td>
                <td><?php echo $row['city']; ?></td>
                <td><?php echo $row['phonenumber']; ?></td>
                <td>
                    <a class="button" href="showcustomerpurchases.php?cusID=<?php echo $row['cusID']; ?>">
                        View
                    </a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <p><a href="mainmenu.php" class="button secondary">Back to main menu</a></p>
</div>

</body>
</html>


<?php
mysqli_free_result($result);
include 'dbclose.php';
?>
