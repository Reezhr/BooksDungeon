<?php
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: booklogin.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

$query = "SELECT books.*, cart.cart_id, cart.quantity
          FROM cart
          JOIN books ON cart.book_id = books.book_id
          WHERE cart.user_id = '$user_id'
          ORDER BY cart.cart_id DESC";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Cart query failed: " . mysqli_error($conn));
}

$grand_total = 0;
$items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['subtotal'] = $row['price'] * $row['quantity'];
    $grand_total += $row['subtotal'];
    $items[] = $row;
}

$savings = 0;
foreach ($items as $item) {
    $savings += ($item['original_price'] - $item['price']) * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Cart – Booksdungeon</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="bookstyle.css">
  <style>
    .cart-item {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,.06);
      padding: 1.2rem;
      display: flex;
      gap: 1.2rem;
      align-items: flex-start;
      margin-bottom: 1rem;
    }
    .cart-item img {
      width: 80px;
      height: 115px;
      object-fit: cover;
      border-radius: 6px;
      flex-shrink: 0;
    }
    .cart-info { flex: 1; }
    .cart-title { font-weight: 700; font-size: 1rem; color: #1a2a3a; }
    .cart-author { font-size: .82rem; color: #888; }
    .qty-btn {
      width: 30px; height: 30px;
      border: 1px solid #ddd;
      background: #f8f8f8;
      border-radius: 6px;
      font-size: .9rem;
      font-weight: 700;
      cursor: pointer;
      line-height: 1;
    }
    .qty-btn:hover { background: #722304; color: #fff; border-color: #722304; }
    .qty-display {
      min-width: 36px;
      text-align: center;
      font-weight: 700;
      font-size: .95rem;
    }
    .btn-remove-item {
      background: none;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: .35rem .9rem;
      font-size: .83rem;
      color: #777;
      cursor: pointer;
    }
    .btn-remove-item:hover { background:#fee; border-color:#e53935; color:#e53935; }
    .order-summary {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,.06);
      padding: 1.5rem;
      position: sticky;
      top: 1rem;
    }
    .summary-row { display:flex; justify-content:space-between; margin-bottom:.6rem; font-size:.9rem; }
    .summary-row.total { font-size:1.1rem; font-weight:700; border-top:1px solid #eee; padding-top:.8rem; margin-top:.5rem; }
    .btn-checkout {
      background: #722304;
      color: #fff;
      border: none;
      border-radius: 10px;
      padding: .75rem;
      font-size:1rem;
      font-weight: 700;
      width: 100%;
      margin-top: 1rem;
    }
    .btn-checkout:hover { background: #5a1b02; color:#fff; }
    .empty-state { text-align:center; padding: 5rem 1rem; }
    .empty-state i { font-size: 4rem; color: #ddd; }
    .savings-badge {
      display: inline-block;
      background: #e8f5e9;
      color: #2e7d32;
      border-radius: 6px;
      padding: .2rem .6rem;
      font-size: .78rem;
      font-weight: 600;
    }
  </style>
</head>
<body>

<!-- Top bar -->
<div class="top-bar py-1 text-center">
  <span>***Free delivery on orders above ₹499***</span>
</div>

<!-- Navbar -->
<nav class="main-nav py-2">
  <div class="container-xl d-flex align-items-center gap-3">
    <a href="index.php" class="navbar-brand d-flex align-items-center gap-2">
      <img src="bookstoreimages/booklogo.png" alt="Logo" width="50" height="42">
      <span class="d-none d-sm-block" style="font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:700;color:#722304;">Booksdungeon</span>
    </a>
    <div class="ms-auto d-flex align-items-center gap-3">
      <a href="booklogin.php"><i class="bi bi-person-circle nav-icon d-none d-md-inline"></i></a>
      <a href="bookwishlist.php"><i class="bi bi-heart nav-icon d-none d-md-inline"></i></a>
      <a href="bookcart.php" style="position:relative;">
        <i class="bi bi-cart3 nav-icon"></i>
        <?php if (count($items) > 0): ?>
          <span class="cart-badge"><?php echo count($items); ?></span>
        <?php endif; ?>
      </a>
    </div>
  </div>
</nav>

<div class="container-xl py-5">
  <div class="d-flex flex-column flex-md-row align-items-start justify-content-between gap-3 mb-4">
    <a href="index.php" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1" style="font-size:1.1rem;">
      <i class="bi bi-arrow-left-short" style="font-size:1.6rem;"></i>
    </a>
    <h2 class="section-heading mb-0"><i class="bi bi-cart3 me-2"></i>My Cart</h2>
  </div>

  <?php if (empty($items)): ?>
    <div class="empty-state">
      <i class="bi bi-cart-x d-block mb-3"></i>
      <h5 class="text-muted">Your cart is empty</h5>
      <p class="text-muted" style="font-size:.9rem;">Add some books and come back here!</p>
      <a href="index.php" class="btn mt-3" style="background:#722304;color:#fff;border-radius:8px;padding:.6rem 1.8rem;">Browse Books</a>
    </div>
  <?php else: ?>
    <div class="row g-4">

      <!-- Cart Items -->
      <div class="col-lg-8">
        <?php foreach ($items as $item): ?>
          <div class="cart-item">
            <img src="<?php echo htmlspecialchars($item['image']); ?>"
                 alt="<?php echo htmlspecialchars($item['title']); ?>"
                 onerror="this.src='https://placehold.co/80x115/f0ede8/1a3c5e?text=Book'">
            <div class="cart-info w-100">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="cart-title"><?php echo htmlspecialchars($item['title']); ?></div>
                  <div class="cart-author mb-2"><?php echo htmlspecialchars($item['author']); ?></div>
                </div>
                <form action="removecart.php" method="GET" style="display:inline;">
                  <input type="hidden" name="id" value="<?php echo $item['book_id']; ?>">
                  <button type="submit" class="btn-remove-item"><i class="bi bi-trash"></i></button>
                </form>
              </div>

              <div class="d-flex align-items-center gap-2 mb-2">
                <span style="font-size:1.05rem;font-weight:700;color:#722304;">₹<?php echo $item['price']; ?></span>
                <span style="font-size:.82rem;color:#aaa;text-decoration:line-through;">₹<?php echo $item['original_price']; ?></span>
                <span class="savings-badge">Save ₹<?php echo ($item['original_price'] - $item['price']); ?> each</span>
              </div>

              <!-- Quantity Controls -->
              <div class="d-flex align-items-center gap-2 mb-2">
                <form action="updatecart.php" method="POST" style="display:inline;">
                  <input type="hidden" name="book_id" value="<?php echo $item['book_id']; ?>">
                  <input type="hidden" name="action" value="decrease">
                  <button type="submit" class="qty-btn">−</button>
                </form>
                <span class="qty-display"><?php echo $item['quantity']; ?></span>
                <form action="updatecart.php" method="POST" style="display:inline;">
                  <input type="hidden" name="book_id" value="<?php echo $item['book_id']; ?>">
                  <input type="hidden" name="action" value="increase">
                  <button type="submit" class="qty-btn">+</button>
                </form>
              </div>

              <div style="font-size:.88rem;color:#555;">
                Subtotal: <strong>₹<?php echo number_format($item['subtotal'], 2); ?></strong>
              </div>
            </div>
          </div>
        <?php endforeach; ?>

        <a href="index.php" class="d-inline-flex align-items-center gap-1 mt-2" style="color:#722304;font-size:.88rem;text-decoration:none;">
          <i class="bi bi-arrow-left-short"></i> Continue Shopping
        </a>
      </div>

      <!-- Order Summary -->
      <div class="col-lg-4">
        <div class="order-summary">
          <h5 class="mb-3" style="font-family:'Playfair Display',serif;">Order Summary</h5>

          <div class="summary-row">
            <span>Items (<?php echo count($items); ?>)</span>
            <span>₹<?php echo number_format($grand_total + $savings, 2); ?></span>
          </div>
          <div class="summary-row" style="color:#2e7d32;">
            <span>You Save</span>
            <span>− ₹<?php echo number_format($savings, 2); ?></span>
          </div>
          <div class="summary-row">
            <span>Delivery</span>
            <span><?php echo ($grand_total >= 499) ? '<span style="color:#2e7d32;">FREE</span>' : '₹49'; ?></span>
          </div>
          <?php if ($grand_total < 499): ?>
            <p style="font-size:.78rem;color:#e8612c;">Add ₹<?php echo 499 - $grand_total; ?> more for free delivery!</p>
          <?php endif; ?>

          <div class="summary-row total">
            <span>Grand Total</span>
            <span style="color:#722304;">₹<?php echo number_format($grand_total + ($grand_total < 499 ? 49 : 0), 2); ?></span>
          </div>

          <a href="checkout.php" class="btn-checkout d-inline-flex align-items-center justify-content-center">
            <i class="bi bi-lock me-1"></i>Proceed to Checkout
          </a>

          <div class="text-center mt-3" style="font-size:.75rem;color:#aaa;">
            <i class="bi bi-shield-check me-1"></i>100% Secure Payment
          </div>
        </div>
      </div>

    </div>
  <?php endif; ?>
</div>

<!-- Footer -->
<footer class="pt-4 pb-3">
  <div class="container-xl">
    <div class="footer-bottom pt-3 text-center">
      © 2026 Booksdungeon. All rights reserved.
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
