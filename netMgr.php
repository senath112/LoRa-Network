<?php
// Check Balance Endpoint
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['uid'])) {
    $uid = $_GET['uid'];
    // Connect to MySQL Database
    $servername = "localhost";
    $username = "username";
    $password = "password";
    $dbname = "database";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch credit balance
    $sql = "SELECT CreditLimit FROM credit_balance WHERE UID = '$uid'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $creditLimit = $row["CreditLimit"];
        if ($creditLimit >= 1) {
            // Deduct credit and respond with '1'
            $sqlUpdate = "UPDATE credit_balance SET CreditLimit = CreditLimit - 1 WHERE UID = '$uid'";
            if ($conn->query($sqlUpdate) === TRUE) {
                echo "1";
            } else {
                echo "Error updating record: " . $conn->error;
            }
        } else {
            // Insufficient balance, respond with '2'
            echo "2";
        }
    } else {
        echo "UID not found";
    }

    $conn->close();
}

// Network Activity Endpoint
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['uid']) && isset($_GET['response'])) {
    $uid = $_GET['uid'];
    $response = $_GET['response'];
    $date = date("Y-m-d");
    $time = date("H:i:s");

    // Connect to MySQL Database
    $servername = "localhost";
    $username = "username";
    $password = "password";
    $dbname = "database";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert activity into database
    $sql = "INSERT INTO activity (date, time, UID, Response) VALUES ('$date', '$time', '$uid', '$response')";

    if ($conn->query($sql) === TRUE) {
        echo "Activity logged successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
