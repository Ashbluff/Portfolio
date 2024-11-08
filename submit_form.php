<?php
include 'includes/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message']));

    // Check if all fields are filled correctly
    if ($name && $email && $message) {
        // Prepare an SQL statement
        $stmt = $conn->prepare("INSERT INTO contact (name, email, message) VALUES (?, ?, ?)");
        
        // Bind the parameters and execute the statement
        $stmt->bind_param("sss", $name, $email, $message);

        if ($stmt->execute()) {
            echo "Thank you for your message!";
        } else {
            echo "Sorry, something went wrong. Please try again.";
        }

        // Close the statement and connection
        $stmt->close();
    } else {
        echo "Please fill in all fields correctly.";
    }
}

// Close the database connection
$conn->close();
?>
