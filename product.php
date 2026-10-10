<?php
require_once 'includes/config.php';
$id = (int)($_GET['id'] ?? 0);
$st = $pdo->prepare("SELECT p.*, c.name AS category, b.name AS brand
                     FROM products p
                     LEFT JOIN categories c ON c.id = p.category_id
                     LEFT JOIN brands b ON b.id = p.brand_id
                     WHERE p.id = ?");
$st->execute([$id]);
$p = $st->fetch();
if (!$p) { flash('Product not found.', 'danger'); redirect('index.php'); }
$pageTitle = $p['title'] . ' - ' . SITE_NAME;
include 'includes/header.php';
?>
<div class="container my-4">
  <div class="row">
    <div class="col-md-6"><img src="<?= product_image($p) ?>" class="img-fluid" alt="<?= e($p['title']) ?>"></div>
    <div class="col-md-6">
      <h2><?= e($p['title']) ?></h2>
      <p class="text-muted">
        <?php if ($p['category']): ?>Category: <?= e($p['category']) ?><?php endif; ?>
        <?php if ($p['brand']): ?> &middot; Brand: <?= e($p['brand']) ?><?php endif; ?>
      </p>
      <p><?= nl2br(e($p['description'])) ?></p>
      <h4>Price: <?= e(number_format($p['price'], 2)) ?> /-</h4>
      <form action="cart.php" method="post" class="form-inline my-3">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
        <input type="number" name="qty" value="1" min="1" max="99" class="form-control qty-input mr-2">
        <button class="btn btn-brand">Add to cart</button>
      </form>
      <a href="index.php" class="btn btn-slate">&larr; Back to store</a>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
