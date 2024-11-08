<?php if (session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>

    <script>
        document.getElementById("burger").addEventListener("click", function() {
            document.getElementById("dropdown").classList.toggle("active");
        });
    </script>

        <!-- <ul> -->
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?>
                <!-- <li><a>logged in as: <?php echo $_SESSION['username']; ?></a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php } else { ?>
                <li><a href="login.php" class="profile">
                        Login
                    </a></li>
                    
            <?php } ?>

        </ul> -->


<div class="nav">
    <div class="anchors">
        <a href="#top">[Home]</a>
        <a href="#anchor2">[Project]</a>
        <a href="#anchor3">[Contact]</a>
        <a href="#anchor4">[Blog]</a>
        <a href="admin.php">[Admin]</a>
    </div>
</div>