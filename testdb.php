<?php
// Programmer Name: 93
// Purpose: Simple connection test to make sure everything works

include 'dbconnect.php';

echo "<h1>DB Test</h1>";

$query = "SELECT cusID, firstname, lastname FROM customer LIMIT 5;";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}

echo "<ul>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<li>" . $row['cusID'] . " - " .
         $row['firstname'] . " " . $row['lastname'] . "</li>";
}
echo "</ul>";

mysqli_free_result($result);
include 'dbclose.php';
?>
