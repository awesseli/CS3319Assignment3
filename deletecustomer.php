<?php
// Programmer Name: 93
// Purpose: Delete a customer if they have no purchases

include 'dbconnect.php';

$message = "";

// 1. If the form was submitted, process the deletion
if (isset($_POST['cusID'])) {

    $cusID = $_POST['cusID'];

    // Check if this customer has any purchases
    $checkQuery = "SELECT * FROM purchases WHERE cusID = '$cusID'";
    $checkResult = mysqli_query($connection, $checkQuery);

    if (!$checkResult) {
        $message = "Error checking purchases for this customer.";
    } else if (mysqli_num_rows($checkResult) > 0) {
        // Customer has purchases => do NOT delete
        $message = "Cannot delete customer $cusID because they have existing purchases.";
    } else {
        // No purchases => safe to delete
        $deleteQuery = "DELETE FROM customer WHERE cusID = '$cusID'";
        $deleteResult = mysqli_query($connection, $deleteQuery);

        if ($deleteResult) {
            $message = "Customer $cusID was deleted successfully.";
        } else {
            $message = "Error deleting customer $cusID.";
        }
    }

    if ($checkResult) {
        mysqli_free_result($checkResult);
    }
}

// 2. Get all customers for the dropdown
$custQuery = "SELECT cusID, firstname, lastname FROM customer ORDER BY lastname, firstname";
$custResult = mysqli_query($connection, $custQuery);

if (!$custResult) {
    die("Error fetching customers.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Delete Customer</title>
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
    <h2>Delete a Customer</h2>

<?php
    if ($message !== "") {
 
            echo '<div class="message">' . $message . '</div>';
        }
    ?>


    <form method="post" action="deletecustomer.php">
        <p>
            <label for="cusID">Select customer to delete:</label>
            <select name="cusID" id="cusID">
                <?php while ($c = mysqli_fetch_assoc($custResult)) { ?>
                    <option value="<?php echo $c['cusID']; ?>">
                        <?php echo $c['cusID'] . " - " . $c['firstname'] . " " . $c['lastname']; ?>
                    </option>
                <?php } ?>
            </select>
        </p>

        <p>
            <input type="submit" value="Delete Customer">
        </p>
    </form>

    <p><a href="mainmenu.php" class="button secondary">Back to main menu</a></p>
</div>

</body>
</html>

<?php
mysqli_free_result($custResult);
include 'dbclose.php';
?>
