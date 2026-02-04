<?php
// Programmer Name: 93
// Purpose: Show all products purchased by a selected customer

include 'dbconnect.php';

// 1. Get cusID
$cusID = $_GET['cusID'];

// 2. Get customer info for the heading
$custQuery = "SELECT firstname, lastname FROM customer WHERE cusID = '$cusID'";
$custResult = mysqli_query($connection, $custQuery);

if (!$custResult || mysqli_num_rows($custResult) == 0) {
    die("Customer not found.");
}

$customer = mysqli_fetch_assoc($custResult);

// 3. Get this customer's purchases joined with products
$purQuery = "
    SELECT p.prodID,p.description,
           p.cost,
           pur.quantity,
           (p.cost * pur.quantity) AS totalSpent
    FROM purchases pur
    JOIN product p ON pur.prodID = p.prodID
    WHERE pur.cusID = '$cusID';
";

$purResult = mysqli_query($connection, $purQuery);

if (!$purResult) {
    die("Query for purchases failed: " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Customer Purchases</title>
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
    <h2>
        Purchases for 
        <?php
        if ($customer) {
            echo $customer['firstname'] . " " . $customer['lastname'] . " (ID: " . $cusID . ")";
        } else {
            echo "Unknown Customer (ID: " . $cusID . ")";
        }
        ?>
    </h2>

    <?php if (mysqli_num_rows($purResult) == 0) { ?>
        <div class="message">
            This customer has not purchased any products yet.
        </div>
    <?php } else { ?>

        <table>
            <tr>
                <th>Product ID</th>
                <th>Description</th>
                <th>Price per Item</th>
                <th>Quantity Purchased</th>
                <th>Total Spent for Product</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($purResult)) { 
                $totalForProduct = $row['cost'] * $row['quantity'];
            ?>
                <tr>
                    <td><?php echo $row['prodID']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['cost']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $totalForProduct; ?></td>
                </tr>
            <?php } ?>
        </table>

    <?php } ?>

    <p><a href="listcustomers.php" class="button secondary">Back to customers</a></p>
    <p><a href="mainmenu.php" class="button secondary">Back to main menu</a></p>
</div>

</body>
</html>


<?php
mysqli_free_result($purResult);
if ($custResult) {
    mysqli_free_result($custResult);
}
include 'dbclose.php';
?>
