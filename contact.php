<?php
require_once 'includes/config.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $msg = trim($_POST['message'] ?? '');
    if ($name === '') $errors[] = 'Name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if ($msg === '') $errors[] = 'Message is required.';
    if (!$errors) {
        $pdo->prepare("INSERT INTO contact_messages (name,email,message) VALUES (?,?,?)")->execute([$name, $email, $msg]);
        flash('Thanks! We received your message.');
        redirect('contact.php');
    }
}
$pageTitle = 'Contact - ' . SITE_NAME;
include 'includes/header.php';
?>
<div class="container auth-box">
  <h3>Contact us</h3>
  <?php foreach ($errors as $er): ?><div class="alert alert-danger py-2"><?= e($er) ?></div><?php endforeach; ?>
  <form method="post">
    <?= csrf_field() ?>
    <div class="form-group"><label>Name</label><input class="form-control" name="name" required></div>
    <div class="form-group"><label>Email</label><input type="email" class="form-control" name="email" required></div>
    <div class="form-group"><label>Message</label><textarea class="form-control" name="message" rows="4" required></textarea></div>
    <button class="btn btn-brand btn-block">Send</button>
  </form>
</div>
<?php include 'includes/footer.php'; ?>
