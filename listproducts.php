<?php
// Programmer Name: 93
// Purpose: List all products with user sorting

include 'dbconnect.php';

// 1. Decide how we are sorting: by description or cost, ASC or DESC

$orderby   = isset($_GET['orderby']) ? $_GET['orderby'] : 'description';
$direction = isset($_GET['direction']) ? $_GET['direction'] : 'ASC';


// Build and run the query
$query = "SELECT * FROM product ORDER BY $orderby $direction";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>All Products</title>
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
    <h2>All Products</h2>

    <!-- Sorting form -->
    <form method="get" action="listproducts.php">
        <p>
            <label for="orderby">Order by:</label>
            <select name="orderby" id="orderby">
                <option value="description" <?php if ($orderby == 'description') echo 'selected'; ?>>
                    Description
                </option>
                <option value="cost" <?php if ($orderby == 'cost') echo 'selected'; ?>>
                    Price
                </option>
            </select>
        </p>

        <p>
            <label for="direction">Direction:</label>
            <select name="direction" id="direction">
                <option value="ASC" <?php if ($direction == 'ASC') echo 'selected'; ?>>
                    Ascending
                </option>
                <option value="DESC" <?php if ($direction == 'DESC') echo 'selected'; ?>>
                    Descending
                </option>
            </select>
        </p>

        <p>
            <input type="submit" value="Sort Products">
        </p>
    </form>

    <!-- Products table -->
    <table>
        <tr>
            <th>Product ID</th>
            <th>Description</th>
            <th>Price</th>
            <th>Quantity On Hand</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['prodID']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo $row['cost']; ?></td>
                <td><?php echo $row['quantityonhand']; ?></td>
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
