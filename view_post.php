<?php
include 'includes/connect.php';

// Check if 'id' parameter is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "No post ID specified.";
    exit;
}

// Retrieve the post ID from the URL
$postId = (int) $_GET['id'];

// Fetch the post from the database
$sql = "SELECT * FROM posts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $postId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $post = $result->fetch_assoc();
} else {
    echo "Post not found.";
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
        .post {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        img {
            max-width: 100%;
            height: auto;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="post">
    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
    
    <?php if ($post['image']): ?>
        <img src="uploads/<?php echo htmlspecialchars($post['image']); ?>" alt="Afbeelding">
    <?php endif; ?>
</div>

</body>
</html>
