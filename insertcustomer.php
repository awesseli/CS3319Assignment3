<?php
// Programmer Name: 93
// Purpose: Insert a new customer with an auto-generated random ID

include 'dbconnect.php';

$message = "";

// Function to generate a random 2-digit customer ID with no duplicates

// https://www.w3schools.com/php/func_math_rand.asp (Used to create
// random function)
// Basically we generate a code, using a do while loop
// Then we check to see if the code is empty, if it is, we can use it
// https://www.w3schools.com/php/php_functions.asp (this website is peak)

function generateCustomerID($connection) {
    do {
        // Generate a random 2-digit number (10–99)
        $num = rand(10, 99);
        $newID = strval($num);

        // Check if this ID already exists
        $query = "SELECT cusID FROM customer WHERE cusID = '$newID'";
        $result = mysqli_query($connection, $query);

        $exists = mysqli_num_rows($result) > 0;

        mysqli_free_result($result);

    } while ($exists);  // try again if ID exists

    return $newID;
}

// If the form was submitted
if (isset($_POST['firstname'])) {

    $firstname  = $_POST['firstname'];
    $lastname   = $_POST['lastname'];
    $city       = $_POST['city'];
    $phone      = $_POST['phonenumber'];

    // basic validation
    if ($firstname == "" || $lastname == "") {
        $message = "First name and last name are required.";
    } else {

        // Generate a unique random ID
        $newID = generateCustomerID($connection);

        // Insert the customer (agentID = NULL)
        $insertQuery = "
            INSERT INTO customer (cusID, firstname, lastname, city, phonenumber, agentID)
            VALUES ('$newID', '$firstname', '$lastname', '$city', '$phone', NULL)
        ";

        $insertResult = mysqli_query($connection, $insertQuery);

        if ($insertResult) {
            $message = "New customer inserted successfully with ID: $newID";
        } else {
            $message = "Error inserting customer.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Insert Customer</title>
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
    <h2>Insert a New Customer</h2>

    <?php
    if ($message !== "") {
        echo '<div class="message">' . $message . '</div>';
    }
    ?>

    <form method="post" action="insertcustomer.php">
        <p>
            <label for="firstname">First name:</label>
            <input type="text" name="firstname" id="firstname" required>
        </p>
        <p>
            <label for="lastname">Last name:</label>
            <input type="text" name="lastname" id="lastname" required>
        </p>
        <p>
            <label for="city">City:</label>
            <input type="text" name="city" id="city">
        </p>
        <p>
            <label for="phonenumber">Phone number:</label>
            <input type="text" name="phonenumber" id="phonenumber">
        </p>

        <p>
            <input type="submit" value="Insert Customer">
        </p>
    </form>

    <p><a href="mainmenu.php" class="button secondary">Back to main menu</a></p>
</div>

</body>
</html>

<?php
include 'dbclose.php';
?>
