<?php
require_once 'includes/config.php';
require_login();
$st = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
$st->execute([$_SESSION['user']['id']]);
$orders = $st->fetchAll();
$itemSt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$pageTitle = 'My Orders - ' . SITE_NAME;
include 'includes/header.php';
?>
<div class="container my-4">
  <h3>My Orders</h3>
  <?php if (!$orders): ?><p class="text-muted">No orders yet.</p><?php endif; ?>
  <?php foreach ($orders as $o): $itemSt->execute([$o['id']]); ?>
    <div class="card mb-3">
      <div class="card-header d-flex justify-content-between">
        <span>Order #<?= (int)$o['id'] ?> &middot; <?= e($o['created_at']) ?></span>
        <span class="badge badge-info align-self-center"><?= e($o['status']) ?></span>
      </div>
      <ul class="list-group list-group-flush">
        <?php foreach ($itemSt->fetchAll() as $it): ?>
          <li class="list-group-item d-flex justify-content-between"><span><?= e($it['title']) ?> &times; <?= (int)$it['quantity'] ?></span><span><?= e(money($it['price'] * $it['quantity'])) ?></span></li>
        <?php endforeach; ?>
      </ul>
      <div class="card-footer font-weight-bold">Total: <?= e(money($o['total'])) ?></div>
    </div>
  <?php endforeach; ?>
</div>
<?php include 'includes/footer.php'; ?>
