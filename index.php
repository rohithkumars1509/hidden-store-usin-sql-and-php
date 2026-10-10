<?php
require_once 'includes/config.php';
$pageTitle = 'My Store';

$q = trim($_GET['q'] ?? '');
$cat = (int)($_GET['category'] ?? 0);
$brand = (int)($_GET['brand'] ?? 0);

$sql = "SELECT * FROM products WHERE 1=1";
$params = [];
if ($q !== '')  { $sql .= " AND (title LIKE ? OR keywords LIKE ? OR description LIKE ?)"; $like = "%$q%"; array_push($params, $like, $like, $like); }
if ($cat)       { $sql .= " AND category_id = ?"; $params[] = $cat; }
if ($brand)     { $sql .= " AND brand_id = ?";    $params[] = $brand; }
$sql .= " ORDER BY id ASC";
$st = $pdo->prepare($sql); $st->execute($params);
$products = $st->fetchAll();

$brands = $pdo->query("SELECT * FROM brands ORDER BY id")->fetchAll();
$cats = $pdo->query("SELECT * FROM categories ORDER BY id")->fetchAll();

include 'includes/header.php';
?>
<h2 class="page-title"><?= e(SITE_NAME) ?></h2>
<p class="page-sub">Communications is at the heart of e-commerce and community</p>

<div class="container-fluid" id="products">
  <div class="row">
    <div class="col-md-10">
      <div class="row">
        <?php if (!$products): ?>
          <div class="col-12"><p class="text-center text-muted">No products found.</p></div>
        <?php endif; ?>
        <?php foreach ($products as $p): ?>
          <div class="col-lg-4 col-md-6">
            <div class="card product-card">
              <img src="<?= product_image($p) ?>" class="card-img-top" alt="<?= e($p['title']) ?>">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?= e($p['title']) ?></h5>
                <p class="card-text"><?= e(mb_strimwidth($p['description'], 0, 90, '…')) ?></p>
                <p class="card-text mt-auto">Price: <?= e(number_format($p['price'], 0)) ?> /-</p>
                <div class="d-flex">
                  <form action="cart.php" method="post" class="mr-2">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                    <button class="btn btn-brand">Add to cart</button>
                  </form>
                  <a href="product.php?id=<?= (int)$p['id'] ?>" class="btn btn-slate">View more</a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="col-md-2 px-0">
      <h4 class="side-head">Delivery Brands</h4>
      <ul class="side-list">
        <?php foreach ($brands as $b): ?>
          <li><a href="index.php?brand=<?= (int)$b['id'] ?>" class="<?= $brand == $b['id'] ? 'active' : '' ?>"><?= e($b['name']) ?></a></li>
        <?php endforeach; ?>
      </ul>
      <h4 class="side-head">Categories</h4>
      <ul class="side-list">
        <?php foreach ($cats as $c): ?>
          <li><a href="index.php?category=<?= (int)$c['id'] ?>" class="<?= $cat == $c['id'] ? 'active' : '' ?>"><?= e($c['name']) ?></a></li>
        <?php endforeach; ?>
        <li><a href="index.php">All products</a></li>
      </ul>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
