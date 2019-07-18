<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bayesian";

ini_set('memory_limit', '-1');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * from dataset";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo $row;
    }
} else {
    echo "0 results";
}
$conn->close();
?>
