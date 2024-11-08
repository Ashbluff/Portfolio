<?php
include 'includes/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $id = $_POST['id'];
    $title = htmlspecialchars(trim($_POST['title']));
    $content = htmlspecialchars(trim($_POST['content']));
    $imageName = $_POST['current_image']; // Default to the current image if no new image is uploaded

    // Check if a new image has been uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $targetFilePath = 'uploads/' . $imageName;

        // Move the uploaded file to the "uploads" directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            // Optionally delete the old image file if a new image is uploaded
            if ($_POST['current_image'] && file_exists('uploads/' . $_POST['current_image'])) {
                unlink('uploads/' . $_POST['current_image']);
            }
        } else {
            echo "Failed to upload new image.";
            exit;
        }
    }

    // Update the post in the database
    $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, image = ? WHERE id = ?");
    $stmt->bind_param("sssi", $title, $content, $imageName, $id);

    if ($stmt->execute()) {
        echo "Post successfully updated! <a href='index.php'>Go back to home page</a>";
    } else {
        echo "Something went wrong. Please try again.";
    }

    // Close statement
    $stmt->close();
}

// Retrieve the post data to edit if an ID is provided in the URL
elseif (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($post = $result->fetch_assoc()) {
        // Display the edit form with the existing post data
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Post</title>
        </head>
        <body>
            <h2>Edit Post</h2>
            <form action="edit_post.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($post['title']); ?>" required><br><br>
                
                <label for="content">Content:</label><br>
                <textarea name="content" id="content" rows="5" required><?php echo htmlspecialchars($post['content']); ?></textarea><br><br>
                
                <label for="image">Image:</label><br>
                <?php if ($post['image']) { ?>
                    <img src="uploads/<?php echo htmlspecialchars($post['image']); ?>" alt="Current Image" style="max-width: 200px;"><br>
                    <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($post['image']); ?>">
                <?php } ?>
                <input type="file" name="image" id="image" accept="image/*"><br><br>
                
                <button type="submit">Save Changes</button>
            </form>
        </body>
        </html>
        <?php
    } else {
        echo "Post not found.";
    }

    $stmt->close();
} else {
    echo "No post ID provided.";
}

$conn->close();
?>
