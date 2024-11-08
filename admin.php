<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="css/1.css">
</head>
<body>

<?php
session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['recht']) || $_SESSION['recht'] != 1) {
    // Stuur de gebruiker door naar een andere pagina
    header("Location: index.php");
    exit(); // Zorg ervoor dat het script stopt na het doorsturen
}

// De rest van je index.php-code hieronder
?>
  <?php include 'includes/nav.php'; ?>


  <body>
    <h2>Nieuw Bericht Maken</h2>
    <form action="upload_post.php" method="POST" enctype="multipart/form-data">
        <label for="title">Titel:</label>
        <input type="text" id="title" name="title" required><br><br>
        
        <label for="content">Inhoud:</label><br>
        <textarea id="content" name="content" rows="5" required></textarea><br><br>
        
        <label for="image">Afbeelding:</label>
        <input type="file" id="image" name="image" accept="image/*"><br><br>
        
        <button type="submit">Opslaan</button>
    </form>
</body>

<hr>

<?php 
// Include the database connection
include 'includes/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve input values
    $title = htmlspecialchars(trim($_POST['title']));
    $content = htmlspecialchars(trim($_POST['content']));
    $imageName = null;

    // Process image if uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $targetFilePath = 'uploads/' . $imageName;

        // Move uploaded file to "uploads" directory
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            echo "Error uploading the image.";
            exit;
        }
    }

    // Insert the post data into the database
    $sql = "INSERT INTO posts (title, content, image) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $title, $content, $imageName);

    if ($stmt->execute()) {
        // Get the ID of the newly inserted post
        $postId = $stmt->insert_id;
        // Redirect to the post's unique page
        header("Location: view_post.php?id=" . $postId);
        exit;
    } else {
        echo "An error occurred while saving the post.";
    }

    $stmt->close();
}
$conn->close();
?>
<!-- /// -->
<?php
// Include the database connection
include 'includes/connect.php';

// Fetch all posts from the database
$sql = "SELECT id, title, content, image FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<div class="post-container">
    <h2>Berichten</h2>

    <?php while ($post = $result->fetch_assoc()): ?>
        <div class="post">
            <?php if ($post['image']): ?>
                <img src="uploads/<?php echo htmlspecialchars($post['image']); ?>" alt="Afbeelding">
            <?php endif; ?>
            <div class="post-content">
                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars(substr($post['content'], 0, 100))) . '...'; ?></p>
                
                <!-- Link to view the full post -->
                <a href="view_post.php?id=<?php echo $post['id']; ?>" class="read-more">Lees meer</a>

                <!-- Edit button linking to edit_post.php with the post's ID -->
                <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="edit-button">Bewerk</a>
            </div> <!-- End of post-content -->
        </div> <!-- End of post -->
    <?php endwhile; ?>

    <?php $conn->close(); ?>
</div>

<!-- voor blogs -->



<h1>Nieuwe Blog Post</h1>
<form action="add_post.php" method="post">
    <label for="title">Titel:</label>
    <input type="text" id="title" name="title" required>

    <label for="content">Inhoud:</label>
    <textarea id="content" name="content" rows="5" required></textarea>

    <input type="submit" value="Post Toevoegen">
</form>

