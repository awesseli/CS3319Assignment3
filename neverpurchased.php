<?php
// Programmer Name: 93
// Purpose: List products that have never been purchased

include 'dbconnect.php';

// LEFT JOIN all products with purchases,
// then keep only those where there is no matching purchase row
$query = "
    SELECT pr.description, pr.quantityonhand
    FROM product pr
    LEFT JOIN purchases pu ON pr.prodID = pu.prodID
    WHERE pu.prodID IS NULL
    ORDER BY pr.description
";

$result = mysqli_query($connection, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Products Never Purchased</title>
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
    <h2>Products That Have Never Been Purchased</h2>

    <?php if (mysqli_num_rows($result) == 0) { ?>
        <div class="message">
            No products were found that have never been purchased.
        </div>
    <?php } else { ?>

        <table>
            <tr>
                <th>Description</th>
                <th>Quantity On Hand</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['quantityonhand']; ?></td>
                </tr>
            <?php } ?>
        </table>

    <?php } ?>

    <p><a href="mainmenu.php" class="button secondary">Back to main menu</a></p>
</div>

</body>
</html>


<?php
mysqli_free_result($result);
include 'dbclose.php';
?>
