<?php
require_once 'includes/config.php';
$errors = [];
$name = $email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';
    $pass2 = $_POST['password2'] ?? '';
    if ($name === '' || mb_strlen($name) > 100) $errors[] = 'Please enter your name.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email.';
    if (strlen($pass) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($pass !== $pass2) $errors[] = 'Passwords do not match.';
    if (!$errors) {
        $st = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $st->execute([$email]);
        if ($st->fetch()) $errors[] = 'That email is already registered.';
    }
    if (!$errors) {
        $st = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?,?,?)");
        $st->execute([$name, $email, password_hash($pass, PASSWORD_DEFAULT)]);
        flash('Registration successful. Please login.');
        redirect('login.php');
    }
}
$pageTitle = 'Register - ' . SITE_NAME;
include 'includes/header.php';
?>
<div class="container auth-box">
  <h3>Create an account</h3>
  <?php foreach ($errors as $er): ?><div class="alert alert-danger py-2"><?= e($er) ?></div><?php endforeach; ?>
  <form method="post">
    <?= csrf_field() ?>
    <div class="form-group"><label>Name</label><input class="form-control" name="name" value="<?= e($name) ?>" required></div>
    <div class="form-group"><label>Email</label><input type="email" class="form-control" name="email" value="<?= e($email) ?>" required></div>
    <div class="form-group"><label>Password</label><input type="password" class="form-control" name="password" required></div>
    <div class="form-group"><label>Confirm password</label><input type="password" class="form-control" name="password2" required></div>
    <button class="btn btn-brand btn-block">Register</button>
    <p class="mt-3 mb-0">Already have an account? <a href="login.php">Login</a></p>
  </form>
</div>
<?php include 'includes/footer.php'; ?>
