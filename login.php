<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
    header('location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="css/login.css">

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=0" />
  <title>Admin</title>
  <meta name="description" content="Admin" />

  <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

</head>

<body>
<?php include 'includes/nav.php'; ?>

    <div class="login-container">
        <!-- <img src="imgs/loginLogo.svg"> -->
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <input id="email" name="Email" placeholder="Email" type="text" class="input" required>
            <input id="pass" name="Password" placeholder="Password" type="password" class="input" data-type="Password" required>
            <input type="submit" class="button" name="login_submit" value="Sign In">
        </form>


    </div>


  <?php
  include 'includes/connect.php';

  // Check if form is submitted
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_submit'])) {
      // Function for data validation to prevent SQL injection
      function validateData($data){
          $data = trim($data);
          $data = stripslashes($data);
          $data = htmlspecialchars($data);
          return $data;
      }

      // Validate and sanitize user input
      $email = validateData($_POST['Email']);
      $_pwd = validateData($_POST['Password']);

      // SQL query to fetch user data
      $sql = "SELECT userEmail, userpassWD, userName, userID FROM users WHERE userEmail = ?";
      if ($stmt = $conn->prepare($sql)) {
          $stmt->bind_param("s", $param_email);
          $param_email = $email;
          $stmt->execute();
          $stmt->bind_result($b_email, $b_pwd, $b_usrname, $b_userID);
          $stmt->store_result();
          $stmt->fetch();

          // Check if user exists
          if ($stmt->num_rows > 0) {
              // Verify password
              if (password_verify($_pwd, $b_pwd)) {
                  session_start();
                  // Set session variables
                  $_SESSION["loggedin"] = true;
                  $_SESSION["id"] = intval($b_userID);
                  $_SESSION["email"] = $b_email;
                  $_SESSION["username"] = $b_usrname;

                  // Query to fetch 'recht' from the database based on user's email
                    $sql_recht = "SELECT recht FROM users WHERE userEmail = ?";
                    if ($stmt_recht = $conn->prepare($sql_recht)) {
                        $stmt_recht->bind_param("s", $param_email);
                        $param_email = $email;
                        $stmt_recht->execute();
                        $stmt_recht->bind_result($b_recht);
                        $stmt_recht->fetch();

                        // Store 'recht' in session variable
                        $_SESSION['recht'] = $b_recht;

                        $stmt_recht->close();
                    }

                  header('Location: index.php'); // Redirect to index.php
                  exit(); // Stop further execution
              } else {
                  echo '<center><div class="error" id="error-popup"><p>Password invalid</p></div><center>';
              }
          } else {
              echo '<center><div class="error" id="error-popup"><p>Email invalid</p></div><center>';
          }
      }
      $stmt->close();
  }

  ?>
   
<!-- <?php include 'includes/footer.php'; ?> -->
</body>

</html>