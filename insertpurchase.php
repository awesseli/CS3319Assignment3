<?php
// Programmer Name: 93
// Purpose: Insert a new purchase, or increase quantity if it already exists

include 'dbconnect.php';

$message = "";

// 1. If the form was submitted, process
if (isset($_POST['cusID']) && isset($_POST['prodID']) && isset($_POST['quantity'])) {

    $cusID = $_POST['cusID'];
    $prodID = $_POST['prodID'];
    $quantity = $_POST['quantity'];

    // Quantity check
    if (!is_numeric($quantity) || $quantity <= 0) {
        $message = "Quantity must be a positive number.";
    } else {

        // Check if this customer already purchased this product
        $checkQuery = "SELECT quantity FROM purchases WHERE cusID='$cusID' AND prodID='$prodID'";
        $checkResult = mysqli_query($connection, $checkQuery);

        if (!$checkResult) {
            $message = "Error checking existing purchase.";
        } else if (mysqli_num_rows($checkResult) > 0) {
            // Purchase exists -> update quantity
            $updateQuery = "UPDATE purchases
                            SET quantity = quantity + $quantity
                            WHERE cusID='$cusID' AND prodID='$prodID'";
            $updateResult = mysqli_query($connection, $updateQuery);

            if ($updateResult) {
                $message = "Existing purchase updated (quantity increased by $quantity).";
            } else {
                $message = "Error updating purchase.";
            }
        } else {
            // No purchase yet -> insert new row
            $insertQuery = "INSERT INTO purchases (cusID, prodID, quantity)
                            VALUES ('$cusID', '$prodID', $quantity)";
            $insertResult = mysqli_query($connection, $insertQuery);

            if ($insertResult) {
                $message = "New purchase inserted successfully.";
            } else {
                $message = "Error inserting purchase.";
            }
        }
    }
}

// Get customers and products for the dropdown lists
$custQuery = "SELECT cusID, firstname, lastname FROM customer ORDER BY lastname, firstname";
$custResult = mysqli_query($connection, $custQuery);
if (!$custResult) {
    die("Error fetching customers.");
}

$prodQuery = "SELECT prodID, description FROM product ORDER BY description";
$prodResult = mysqli_query($connection, $prodQuery);
if (!$prodResult) {
    die("Error fetching products.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Insert Purchase</title>
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
    <h2>Insert a New Purchase</h2>

    <?php
    if ($message !== "") {
        echo '<div class="message">' . $message . '</div>';
    }
    ?>

    <form method="post" action="insertpurchase.php">
        <p>
            <label for="cusID">Customer:</label>
            <select name="cusID" id="cusID">
                <?php while ($c = mysqli_fetch_assoc($custResult)) { ?>
                    <option value="<?php echo $c['cusID']; ?>">
                        <?php echo $c['cusID'] . " - " . $c['firstname'] . " " . $c['lastname']; ?>
                    </option>
                <?php } ?>
            </select>
        </p>

        <p>
            <label for="prodID">Product:</label>
            <select name="prodID" id="prodID">
                <?php while ($p = mysqli_fetch_assoc($prodResult)) { ?>
                    <option value="<?php echo $p['prodID']; ?>">
                        <?php echo $p['prodID'] . " - " . $p['description']; ?>
                    </option>
                <?php } ?>
            </select>
        </p>

        <p>
            <label for="quantity">Quantity to purchase:</label>
            <input type="number" name="quantity" id="quantity" min="1">
        </p>

        <p>
            <input type="submit" value="Submit Purchase">
        </p>
    </form>

    <p><a href="mainmenu.php" class="button secondary">Back to main menu</a></p>
</div>

</body>
</html>
<?php
mysqli_free_result($custResult);
mysqli_free_result($prodResult);
include 'dbclose.php';
?>

