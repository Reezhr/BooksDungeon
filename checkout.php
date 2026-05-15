<?php
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: booklogin.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

$items = [];
$grand_total = 0;
$savings = 0;
$cart_count = 0;

$query = "SELECT books.*, cart.cart_id, cart.quantity
          FROM cart
          JOIN books ON cart.book_id = books.book_id
          WHERE cart.user_id = '$user_id'
          ORDER BY cart.cart_id DESC";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Checkout query failed: " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($result)) {
    $row['subtotal'] = $row['price'] * $row['quantity'];
    $grand_total += $row['subtotal'];
    $savings += ($row['original_price'] - $row['price']) * $row['quantity'];
    $cart_count += $row['quantity'];
    $items[] = $row;
}

$order_placed = false;
$order_number = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order']) && !empty($items)) {
    $order_placed = true;
    $order_number = 'BD' . time();
    mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id'");
    $items = [];
    $grand_total = 0;
    $savings = 0;
    $cart_count = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Checkout – Booksdungeon</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="bookstyle.css">
  <style>
    .checkout-card {
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 16px;
      box-shadow: 0 12px 30px rgba(0,0,0,.06);
      padding: 1.5rem;
    }
    .checkout-step {
      display: inline-flex;
      align-items: center;
      gap: .75rem;
      padding: .75rem 1rem;
      border: 1px solid #e7e2da;
      border-radius: 999px;
      background:#faf5ef;
      color:#5c3a21;
      font-size:.9rem;
      margin-right:.75rem;
      margin-bottom:.75rem;
    }
    .checkout-summary {
      background:#fff;
      border:1px solid #ddd;
      border-radius:16px;
      padding:1.5rem;
    }
    .checkout-summary h5 {
      font-family:'Playfair Display',serif;
      margin-bottom:1rem;
    }
    .summary-row { display:flex; justify-content:space-between; margin-bottom:.8rem; }
    .summary-row.total { font-weight:700; font-size:1rem; border-top:1px solid #eee; padding-top:.8rem; margin-top:1rem; }
    .btn-place-order {
      width:100%;
      background:#722304;
      border:none;
      color:#fff;
      border-radius:12px;
      padding:.85rem;
      font-weight:700;
    }
    .btn-place-order:hover { background:#5a1b02; }
  </style>
</head>
<body>

<nav class="main-nav py-2">
  <div class="container-xl d-flex align-items-center gap-3">
    <a href="index.php" class="navbar-brand d-flex align-items-center gap-2 me-0">
      <img src="bookstoreimages/booklogo.png" alt="Booksdungeon Logo" width="50" height="42">
      <span class="d-none d-sm-block" style="font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:700;color:#722304;">Booksdungeon</span>
    </a>
    <div class="ms-auto d-flex align-items-center gap-3">
      <a href="bookwishlist.php" class="nav-icon d-none d-md-inline"><i class="bi bi-heart"></i></a>
      <a href="bookcart.php" class="nav-icon"><i class="bi bi-cart3"></i></a>
    </div>
  </div>
</nav>

<div class="container-xl py-5">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <a href="bookcart.php" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1 mb-2">
        <i class="bi bi-arrow-left-short" style="font-size:1.5rem;"></i>
        Back to Cart
      </a>
      <h2 class="section-heading mb-1">Checkout</h2>
      <p class="text-muted mb-0" style="font-size:.95rem;">Complete your order and select delivery details.</p>
    </div>
    <div class="checkout-step"><i class="bi bi-check-circle"></i>Secure checkout</div>
  </div>

  <?php if ($order_placed): ?>
    <div class="checkout-card text-center">
      <i class="bi bi-check2-circle text-success" style="font-size:4rem;"></i>
      <h3 class="mt-3">Order Confirmed!</h3>
      <p class="text-muted">Your order number is <strong><?php echo htmlspecialchars($order_number); ?></strong>.</p>
      <p class="text-muted">We will email you the order details shortly.</p>
      <a href="index.php" class="btn btn-outline-primary mt-3">Continue Shopping</a>
    </div>
  <?php elseif (empty($items)): ?>
    <div class="checkout-card text-center">
      <i class="bi bi-cart-x text-muted" style="font-size:4rem;"></i>
      <h3 class="mt-3">Your cart is empty</h3>
      <p class="text-muted">Add books to your cart before checking out.</p>
      <a href="index.php" class="btn btn-outline-primary mt-3">Browse Books</a>
    </div>
  <?php else: ?>
    <div class="row gy-4">
      <div class="col-lg-7">
        <div class="checkout-card">
          <h5 class="mb-4">Delivery Details</h5>
          <form method="POST" action="checkout.php">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-control" placeholder="Your name" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="tel" name="phone" class="form-control" placeholder="+91 98765 43210" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Postal Code</label>
                <input type="text" name="postal_code" class="form-control" placeholder="400001" required>
              </div>
              <div class="col-12">
                <label class="form-label">Address</label>
                <textarea name="address" rows="3" class="form-control" placeholder="House number, street, area" required></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-control" placeholder="Mumbai" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">State</label>
                <input type="text" name="state" class="form-control" placeholder="Maharashtra" required>
              </div>
            </div>

            <div class="mt-4">
              <h5 class="mb-3">Payment Method</h5>
              <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="payment_method" id="paymentCard" value="card" checked>
                <label class="form-check-label" for="paymentCard">Credit / Debit Card</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="payment_method" id="paymentUpi" value="upi">
                <label class="form-check-label" for="paymentUpi">UPI</label>
              </div>
              <div class="form-check mb-3">
                <input class="form-check-input" type="radio" name="payment_method" id="paymentCod" value="cod">
                <label class="form-check-label" for="paymentCod">Cash on Delivery</label>
              </div>
            </div>

            <button type="submit" name="place_order" class="btn-place-order">
              <i class="bi bi-lock me-1"></i>Place Order
            </button>
          </form>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="checkout-summary">
          <h5>Order Summary</h5>
          <?php foreach ($items as $item): ?>
            <div class="d-flex align-items-center gap-3 mb-3">
              <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" width="70" height="100" style="object-fit:cover;border-radius:8px;">
              <div class="flex-grow-1">
                <div style="font-weight:700;"><?php echo htmlspecialchars($item['title']); ?></div>
                <div class="text-muted" style="font-size:.85rem;">Qty <?php echo $item['quantity']; ?></div>
              </div>
              <div style="font-weight:700;color:#722304;">₹<?php echo number_format($item['subtotal'], 2); ?></div>
            </div>
          <?php endforeach; ?>

          <div class="summary-row"><span>Items (<?php echo $cart_count; ?>)</span><span>₹<?php echo number_format($grand_total + $savings, 2); ?></span></div>
          <div class="summary-row"><span>You Save</span><span>− ₹<?php echo number_format($savings, 2); ?></span></div>
          <div class="summary-row"><span>Delivery</span><span><?php echo ($grand_total >= 499) ? '<span style="color:#2e7d32;">FREE</span>' : '₹49'; ?></span></div>
          <div class="summary-row total"><span>Grand Total</span><span>₹<?php echo number_format($grand_total + ($grand_total < 499 ? 49 : 0), 2); ?></span></div>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
