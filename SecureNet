<?php
// Load environment variables from .env file
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get database connection details from environment variables
$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check Balance Endpoint
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uid']) && isset($_POST['api_key'])) {
    $uid = $_POST['uid'];
    $api_key = $_POST['api_key'];

    // Check if API key matches
    $apiKey = getenv('API_KEY');
    if ($api_key !== $apiKey) {
        echo "Invalid API Key";
        exit;
    }

    // Proceed with fetching credit balance
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
}

// Network Activity Endpoint
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uid']) && isset($_POST['response']) && isset($_POST['api_key'])) {
    $uid = $_POST['uid'];
    $response = $_POST['response'];
    $api_key = $_POST['api_key'];

    // Check if API key matches
    $apiKey = getenv('API_KEY');
    if ($api_key !== $apiKey) {
        echo "Invalid API Key";
        exit;
    }

    // Proceed with logging network activity
    $date = date("Y-m-d");
    $time = date("H:i:s");

    // Insert activity into database
    $sql = "INSERT INTO activity (date, time, UID, Response) VALUES ('$date', '$time', '$uid', '$response')";

    if ($conn->query($sql) === TRUE) {
        echo "Activity logged successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
