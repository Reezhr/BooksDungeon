<?php
include "config.php";

$error = "";
$success = "";

// Handle Registration
if (isset($_POST['register'])) {
    $fullname = trim($conn->real_escape_string($_POST['fullname']));
    $email    = trim($conn->real_escape_string($_POST['email']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT id FROM bookstorelogin WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "An account with this email already exists.";
    } else {
        $sql = "INSERT INTO bookstorelogin (fullname, email, password) VALUES ('$fullname','$email','$password')";
        if (mysqli_query($conn, $sql)) {
            $success = "Account created! You can now log in.";
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}

// Handle Login
if (isset($_POST['login'])) {
    $email    = trim($conn->real_escape_string($_POST['email']));
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM bookstorelogin WHERE email='$email'");
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id']  = $row['id'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['flash']    = "Welcome back, " . htmlspecialchars($row['fullname']) . "!";
            header("Location: index.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No account found with that email.";
    }
}

// Already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login – Booksdungeon</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="bookstyle.css">
  <style>
    body { background: var(--bd-bg, #f7f4f0); }
    .auth-card {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 24px rgba(0,0,0,.08);
      padding: 2.5rem 2rem;
      max-width: 440px;
      width: 100%;
    }
    .auth-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.7rem;
      color: #722304;
      font-weight: 700;
    }
    .nav-tabs .nav-link { color: #555; font-weight: 500; }
    .nav-tabs .nav-link.active { color: #722304; border-bottom: 2px solid #722304; }
    .btn-auth {
      background: #722304;
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: .6rem 1.5rem;
      font-weight: 600;
      width: 100%;
    }
    .btn-auth:hover { background: #5a1b02; color: #fff; }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="main-nav py-2">
  <div class="container-xl d-flex align-items-center gap-3">
    <a href="index.php" class="navbar-brand d-flex align-items-center gap-2">
      <img src="bookstoreimages/booklogo.png" alt="Logo" width="50" height="42">
      <span class="d-none d-sm-block" style="font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:700;color:#722304;">Booksdungeon</span>
    </a>
  </div>
</nav>

<div class="d-flex justify-content-center align-items-center py-5 px-3">
  <div class="auth-card">
    <div class="text-center mb-4">
      <i class="bi bi-journals fs-1" style="color:#e8612c;"></i>
      <div class="auth-title mt-2">Booksdungeon</div>
      <p class="text-muted" style="font-size:.88rem;">Your reading adventure starts here</p>
    </div>

    <?php if ($error): ?>
      <div class="alert alert-danger py-2" style="font-size:.88rem;"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="alert alert-success py-2" style="font-size:.88rem;"><?php echo $success; ?></div>
    <?php endif; ?>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" id="authTab">
      <li class="nav-item">
        <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#loginPane">Login</button>
      </li>
      <li class="nav-item">
        <button class="nav-link" id="reg-tab" data-bs-toggle="tab" data-bs-target="#regPane">Register</button>
      </li>
    </ul>

    <div class="tab-content">

      <!-- LOGIN -->
      <div class="tab-pane fade show active" id="loginPane">
        <form method="POST">
          <div class="mb-3">
            <label class="form-label fw-500">Email</label>
            <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
          </div>
          <div class="mb-4">
            <label class="form-label fw-500">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
          </div>
          <button type="submit" name="login" class="btn-auth">Login</button>
        </form>
      </div>

      <!-- REGISTER -->
      <div class="tab-pane fade" id="regPane">
        <form method="POST">
          <div class="mb-3">
            <label class="form-label fw-500">Full Name</label>
            <input type="text" name="fullname" class="form-control" placeholder="Your full name" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-500">Email</label>
            <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
          </div>
          <div class="mb-4">
            <label class="form-label fw-500">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter your password" minlength="6" required>
          </div>
          <button type="submit" name="register" class="btn-auth">Create Account</button>
        </form>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php if ($success): ?>
<script>
  // Switch to login tab after successful registration
  document.getElementById('login-tab').click();
</script>
<?php endif; ?>
</body>
</html>
