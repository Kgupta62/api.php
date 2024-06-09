<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests for CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP
$dbname = "contact_form_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$method = $_SERVER['REQUEST_METHOD'];

// Function to get JSON input
function getJsonInput() {
    return json_decode(file_get_contents('php://input'), true);
}

switch ($method) {
    case 'POST':
        // Insert a new contact
        $data = getJsonInput();
        $name = $data['name'];
        $email = $data['email'];
        $contact_number = $data['contact_number'];
        $message = $data['message'];
        $website = $data['website'];

        $sql = "INSERT INTO contacts (name, email, contact_number, message, website) VALUES ('$name', '$email', '$contact_number', '$message', '$website')";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "New record created successfully"]);
        } else {
            echo json_encode(["error" => "Error: " . $sql . "<br>" . $conn->error]);
        }
        break;
    case 'GET':
        // Select contacts
        $sql = "SELECT * FROM contacts";
        $result = $conn->query($sql);

        $contacts = [];
        while($row = $result->fetch_assoc()) {
            $contacts[] = $row;
        }
        echo json_encode($contacts);
        break;
    case 'PUT':
        // Update a contact
        $data = getJsonInput();
        $id = $data['id'];
        $name = $data['name'];
        $email = $data['email'];
        $contact_number = $data['contact_number'];
        $message = $data['message'];
        $website = $data['website'];

        $sql = "UPDATE contacts SET name='$name', email='$email', contact_number='$contact_number', message='$message', website='$website' WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Record updated successfully"]);
        } else {
            echo json_encode(["error" => "Error: " . $sql . "<br>" . $conn->error]);
        }
        break;
    case 'DELETE':
        // Delete a contact
        $data = getJsonInput();
        $id = $data['id'];

        $sql = "DELETE FROM contacts WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Record deleted successfully"]);
        } else {
            echo json_encode(["error" => "Error: " . $sql . "<br>" . $conn->error]);
        }
        break;
    default:
        echo json_encode(["error" => "Invalid request method"]);
        break;
}

$conn->close();
?>
