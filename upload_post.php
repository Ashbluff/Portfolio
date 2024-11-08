<?php
include 'includes/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars(trim($_POST['title']));
    $content = htmlspecialchars(trim($_POST['content']));
    $imageName = null;

    // Process the uploaded image if it exists
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $targetFilePath = 'uploads/' . $imageName;

        // Move the uploaded file to the "uploads" folder
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            echo "Er is iets fout gegaan bij het uploaden van de afbeelding.";
            exit;
        }
    }

    // Prepare and execute the insert query
    $stmt = $conn->prepare("INSERT INTO posts (title, content, image) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $content, $imageName);

    if ($stmt->execute()) {
        echo "Bericht succesvol opgeslagen!";
    } else {
        echo "Er is iets misgegaan: " . $conn->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="4;url=index.php">
    <title>Redirecting...</title>
</head>
<body>
    <p>Je wordt binnen 4 seconden doorgestuurd naar de hoofdpagina.</p>
</body>
</html>
