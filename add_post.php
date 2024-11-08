<?php
include 'includes/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Prepare and execute the insert query
    $stmt = $conn->prepare("INSERT INTO blog (title, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $content);

    if ($stmt->execute()) {
        echo "<p>Blogpost succesvol opgeslagen! Je wordt binnen 4 seconden doorgestuurd naar de hoofdpagina</p>";
    } else {
        echo "Fout: " . $conn->error;
    }

    // Sluit de statement en de verbinding
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
