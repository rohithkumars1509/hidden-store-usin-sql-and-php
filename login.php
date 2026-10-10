<?php
require_once 'includes/config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $email = trim($_POST['email'] ?? '');
    $st = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $st->execute([$email]);
    $u = $st->fetch();
    if ($u && password_verify($_POST['password'] ?? '', $u['password'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => (int)$u['id'], 'name' => $u['name'], 'email' => $u['email'], 'is_admin' => (int)$u['is_admin']];
        flash('Welcome back, ' . $u['name'] . '!');
        redirect(!empty($_SESSION['cart']) ? 'cart.php' : 'index.php');
    }
    $error = 'Invalid email or password.';
}
$pageTitle = 'Login - ' . SITE_NAME;
include 'includes/header.php';
?>
<div class="container auth-box">
  <h3>Login</h3>
  <?php if ($error): ?><div class="alert alert-danger py-2"><?= e($error) ?></div><?php endif; ?>
  <form method="post">
    <?= csrf_field() ?>
    <div class="form-group"><label>Email</label><input type="email" class="form-control" name="email" required></div>
    <div class="form-group"><label>Password</label><input type="password" class="form-control" name="password" required></div>
    <button class="btn btn-brand btn-block">Login</button>
    <p class="mt-3 mb-0">No account? <a href="register.php">Register</a></p>
  </form>
</div>
<?php include 'includes/footer.php'; ?>
