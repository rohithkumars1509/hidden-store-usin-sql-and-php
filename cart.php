<?php
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);

    if ($action === 'add' && $id) {
        $st = $pdo->prepare("SELECT id FROM products WHERE id = ?");
        $st->execute([$id]);
        if ($st->fetch()) {
            $qty = max(1, min(99, (int)($_POST['qty'] ?? 1)));
            $_SESSION['cart'][$id] = min(99, ($_SESSION['cart'][$id] ?? 0) + $qty);
            flash('Item added to cart.');
        }
        redirect($_SERVER['HTTP_REFERER'] ?? 'index.php');
    }
    if ($action === 'update') {
        foreach (($_POST['qty'] ?? []) as $pid => $q) {
            $pid = (int)$pid; $q = (int)$q;
            if ($q <= 0) unset($_SESSION['cart'][$pid]);
            else $_SESSION['cart'][$pid] = min(99, $q);
        }
        flash('Cart updated.');
        redirect('cart.php');
    }
    if ($action === 'remove' && $id) {
        unset($_SESSION['cart'][$id]);
        flash('Item removed.');
        redirect('cart.php');
    }
}

$items = cart_items();
$pageTitle = 'Your Cart - ' . SITE_NAME;
include 'includes/header.php';
?>
<div class="container my-4">
  <h3>Your Cart</h3>
  <?php if (!$items): ?>
    <p class="text-muted">Your cart is empty. <a href="index.php">Continue shopping</a></p>
  <?php else: ?>
    <form action="cart.php" method="post">
      <?= csrf_field() ?><input type="hidden" name="action" value="update">
      <div class="table-responsive">
      <table class="table table-bordered">
        <thead class="thead-light"><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($items as $i): ?>
          <tr>
            <td><img src="<?= product_image($i) ?>" width="50" class="mr-2" alt=""><?= e($i['title']) ?></td>
            <td><?= e(money($i['price'])) ?></td>
            <td><input type="number" class="form-control qty-input" name="qty[<?= (int)$i['id'] ?>]" value="<?= (int)$i['qty'] ?>" min="0" max="99"></td>
            <td><?= e(money($i['subtotal'])) ?></td>
            <td><button type="submit" form="rm<?= (int)$i['id'] ?>" class="btn btn-sm btn-danger">Remove</button></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot><tr><th colspan="3" class="text-right">Total</th><th colspan="2"><?= e(money(cart_total())) ?></th></tr></tfoot>
      </table>
      </div>
      <button class="btn btn-slate">Update cart</button>
      <a href="index.php" class="btn btn-outline-secondary">Continue shopping</a>
      <a href="checkout.php" class="btn btn-brand float-right">Proceed to checkout</a>
    </form>
    <?php foreach ($items as $i): ?>
      <form id="rm<?= (int)$i['id'] ?>" action="cart.php" method="post" class="d-none">
        <?= csrf_field() ?><input type="hidden" name="action" value="remove"><input type="hidden" name="id" value="<?= (int)$i['id'] ?>">
      </form>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>
