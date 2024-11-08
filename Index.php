<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio</title>
    <link rel="stylesheet" href="css/1.css">
    <style>
        /* Optional styling to space out the sections */
        section {
            margin: 50px;
            scroll-margin-top: 90px;
        }
        a {
            color: inherit;
            text-decoration: none;
            cursor: pointer;
        }
        a:hover {
            color: #555;
        }
    </style>
</head>
<body>


<?php include "includes/nav.php" ?>

<div class="box1">
<div class="introductie">
<h1>Welcome</h1>                     
<br>
<br>                                                                           
<p>  Hallo ik ben Davy Koopman                         
<p>  Ik ben een software Developer in opleiding en ik hou ervan om dingen te maken.                           
<p>Ik Woon in julianadorp en ik spreek Nederlands en Engels</p>
</div>
<div class="image">
    <br>
    <br>
<img src="uploads/mijn-foto.png" alt="Mijn Foto" class="profile-image">
</div>
</div>
<div class="line">l</div>


<section id="anchor2">
    <div class="anchor2t">
    <div class="con2">
        <h2>Project</h2>
        <p>Hier kunt u wat projecten zien waar ik aan heb gewerkt.</p>
    </div>
 <br>     
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
                <a href="view_post.php?id=<?php echo $post['id']; ?>" class="read-more">Lees meer</a>
            </div> <!-- End of post-content -->
        </div> <!-- End of post -->
    <?php endwhile; ?>

    <?php $conn->close(); ?>
</div>
    </div>
</section>
<div class="line">l</div>

<section id="anchor3">
  <div class="contact-blok">
     <div class="con1">
        <h2>Contact</h2>
        <br>
        <br>
        <p>Natuurlijk als u contact met mij wilt opnemen kunt u hier een bericht achter laten dan ga ik u so snel mogelijk een antwoord terug sturen.</p>
     </div>
    <div class="contact-form-container">
     <h2>Contact Me</h2>
        <form action="submit_form.php" method="POST">
         <label for="name">Name:</label>
         <input type="text" id="name" name="name" required placeholder="Uw Naam">
        
         <label for="email">Email:</label>
         <input type="email" id="email" name="email" required placeholder="Uw Email">
        
         <label for="message">Message:</label>
         <textarea id="message" name="message" rows="5" required placeholder="Uw Bericht"></textarea>
        
         <button type="submit">Send Message</button>
        </form>
    </div>
  </div>
  </div>
</section>

<div class="line">l</div>

<section id="anchor4">
<div class="anchor2t">
    <h2>Blog</h2>
    <p class="blog">Hier ga ik meer informatie over mezelf geven zodat U mij beter kan leren kennen</p>
 



<div class="post-container">
    <?php
    // Include the database connection
    include 'includes/connect.php';

    try {
        // Haal alle berichten op
        $sql = "SELECT * FROM blog ORDER BY created_at DESC";
        $result = $conn->query($sql);

        // Loop door de berichten en toon ze
        while ($row = $result->fetch_assoc()) {
            echo "<div class='post'>";
            echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
            echo "<p>" . htmlspecialchars_decode($row['content']) . "</p>";
            echo "<time>" . date('d-m-Y H:i', strtotime($row['created_at'])) . "</time>";
            echo "</div>"; // End of post
        }
    } catch (Exception $e) {
        echo "Fout: " . $e->getMessage();
    } finally {
        // Sluit de database verbinding
        $conn->close();
    }
    ?>
</div>
</div>
</section>

















<?php include "includes/footer.php" ?>
</body>
</html>