<?php
require_once 'includes/config.php';
require_login();
$items = cart_items();
if (!$items) { flash('Your cart is empty.', 'warning'); redirect('cart.php'); }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $address = trim($_POST['address'] ?? '');
    if (mb_strlen($address) < 10) {
        $error = 'Please enter a full delivery address.';
    } else {
        try {
            $pdo->beginTransaction();
            $total = 0;
            foreach ($items as $i) $total += $i['subtotal'];
            $pdo->prepare("INSERT INTO orders (user_id, total, address) VALUES (?,?,?)")
                ->execute([$_SESSION['user']['id'], $total, $address]);
            $orderId = $pdo->lastInsertId();
            $ins = $pdo->prepare("INSERT INTO order_items (order_id, product_id, title, price, quantity) VALUES (?,?,?,?,?)");
            foreach ($items as $i) $ins->execute([$orderId, $i['id'], $i['title'], $i['price'], $i['qty']]);
            $pdo->commit();
            $_SESSION['cart'] = [];
            flash("Order #$orderId placed successfully! (Payment: Cash on Delivery)");
            redirect('orders.php');
        } catch (Exception $ex) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = 'Could not place the order. Please try again.';
        }
    }
}
$pageTitle = 'Checkout - ' . SITE_NAME;
include 'includes/header.php';
?>
<div class="container my-4">
  <h3>Checkout</h3>
  <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
  <div class="row">
    <div class="col-md-7">
      <form method="post">
        <?= csrf_field() ?>
        <div class="form-group"><label>Delivery address</label><textarea name="address" class="form-control" rows="4" required></textarea></div>
        <p class="text-muted">Payment method: Cash on Delivery</p>
        <button class="btn btn-brand">Place order</button>
        <a href="cart.php" class="btn btn-outline-secondary">Back to cart</a>
      </form>
    </div>
    <div class="col-md-5">
      <ul class="list-group">
        <?php foreach ($items as $i): ?>
          <li class="list-group-item d-flex justify-content-between"><span><?= e($i['title']) ?> &times; <?= (int)$i['qty'] ?></span><span><?= e(money($i['subtotal'])) ?></span></li>
        <?php endforeach; ?>
        <li class="list-group-item d-flex justify-content-between font-weight-bold"><span>Total</span><span><?= e(money(cart_total())) ?></span></li>
      </ul>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
