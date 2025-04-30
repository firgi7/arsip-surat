<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css"> <!-- pastikan file CSS terhubung -->
</head>
<body>
  <div class="container">
    <h2 class="login-title">Login Admin</h2>
    <form class="login-form" method="post" action="login.php">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>

      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
<?php
if ($_POST) {
    $u = $_POST['username'];
    $p = md5($_POST['password']);
    $query = $conn->query("SELECT * FROM users WHERE username='$u' AND password='$p'");
    if ($query->num_rows > 0) {
        $_SESSION['login'] = true;
        header("Location: index.php");
    } else {
        echo "Login gagal!";
    }
}
?>
</body>
</html>
