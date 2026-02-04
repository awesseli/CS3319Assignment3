<?php
// Programmer Name: 93
// Purpose: List customers who bought more than a
// given quantity of a selected product

include 'dbconnect.php';

$message = "";
$results = null;  // will hold the query result if form is submitted
$productDescription = "";
$threshold = "";

// If the form was submitted
if (isset($_POST['prodID']) && isset($_POST['quantity'])) {

    $prodID = $_POST['prodID'];
    $threshold = $_POST['quantity'];
// Lets make sure that the quantity is a number, and is a valid one
    if (!is_numeric($threshold) || $threshold < 0) {
        $message = "Quantity must be a non-negative number.";
    } else {
        // Get product description for display
        $prodQuery = "SELECT description FROM product WHERE prodID = '$prodID'";
        $prodResult = mysqli_query($connection, $prodQuery);

        if ($prodResult && mysqli_num_rows($prodResult) > 0) {
            $prodRow = mysqli_fetch_assoc($prodResult);
            $productDescription = $prodRow['description'];
        }
        if ($prodResult) {
            mysqli_free_result($prodResult);
        }

// Query: customers who bought more than threshold of this product
// NOTE: "more than" is strict here >, so if user enters 8,
// we show quantity >= 9
        $custQuery = "
            SELECT c.cusID, c.firstname, c.lastname, p.quantity
            FROM customer c
            JOIN purchases p ON c.cusID = p.cusID
            WHERE p.prodID = '$prodID'
              AND p.quantity > $threshold
            ORDER BY c.lastname, c.firstname
        ";

        $results = mysqli_query($connection, $custQuery);

        if (!$results) {
            $message = "Error running query.";
        }
    }
}

// Get all products for the dropdown
$allProdQuery = "SELECT prodID, description FROM product ORDER BY description";
$allProdResult = mysqli_query($connection, $allProdQuery);

if (!$allProdResult) {
    die("Error fetching products.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Customers by Quantity</title>
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
    <h2>Customers Who Bought More Than X of a Product</h2>

    <?php
    if ($message !== "") {
        echo '<div class="message">' . $message . '</div>';
    }
    ?>

    <form method="post" action="customersByQuantity.php">
        <p>
<!-- This section dynamically creates options with their product labels -->
            <label for="prodID">Select a product:</label>
            <select name="prodID" id="prodID">
                <?php while ($p = mysqli_fetch_assoc($allProdResult)) { ?>
                    <option value="<?php echo $p['prodID']; ?>"
                        <?php if (isset($prodID) && $prodID == $p['prodID']) echo "selected"; ?>>
                        <?php echo $p['prodID'] . " - " . $p['description']; ?>
                    </option>
                <?php } ?>
            </select>
        </p>

        <p>
            <label for="quantity">More than this quantity:</label>
            <input type="number" name="quantity" id="quantity" min="0"
                   value="<?php echo ($threshold !== "" ? $threshold : "0"); ?>">
        </p>

        <p>
            <input type="submit" value="Show Customers">
        </p>
    </form>

    <?php if ($results && mysqli_num_rows($results) > 0) { ?>
        <h3>Results</h3>
        <p>
            Product: <strong><?php echo $productDescription; ?></strong><br>
            Threshold: more than <strong><?php echo $threshold; ?></strong> units
        </p>

        <table>
            <tr>
                <th>Customer ID</th>
                <th>Name</th>
                <th>Quantity Purchased</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($results)) { ?>
                <tr>
                    <td><?php echo $row['cusID']; ?></td>
                    <td><?php echo $row['firstname'] . " " . $row['lastname']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                </tr>
            <?php } ?>
        </table>

    <?php } else if (isset($_POST['prodID']) && $message == "") { ?>
        <div class="message">
            No customers bought more than <?php echo $threshold; ?> of this product.
        </div>
    <?php } ?>

    <p><a href="mainmenu.php" class="button secondary">Back to main menu</a></p>
</div>

</body>
</html>

<?php
if ($results) {
    mysqli_free_result($results);
}
mysqli_free_result($allProdResult);
include 'dbclose.php';
?>
