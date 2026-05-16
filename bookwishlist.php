<?php
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: booklogin.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

$query = "SELECT books.*, wishlist.wishlist_id
          FROM wishlist
          JOIN books ON wishlist.book_id = books.book_id
          WHERE wishlist.user_id = '$user_id'
          ORDER BY wishlist.wishlist_id DESC";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Wishlist query failed: " . mysqli_error($conn));
}

// Cart count for navbar
$cc   = mysqli_query($conn, "SELECT SUM(quantity) as total FROM cart WHERE user_id='$user_id'");
$crow = mysqli_fetch_assoc($cc);
$cart_count = $crow['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Wishlist – Booksdungeon</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="bookstyle.css">
  <style>
    .wishlist-item {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,.06);
      padding: 1.2rem;
      display: flex;
      gap: 1.2rem;
      align-items: flex-start;
      margin-bottom: 1rem;
    }
    .wishlist-item img {
      width: 80px;
      height: 115px;
      object-fit: cover;
      border-radius: 6px;
      flex-shrink: 0;
    }
    .wishlist-info { flex: 1; }
    .wishlist-title { font-weight: 700; font-size: 1rem; color: #1a2a3a; }
    .wishlist-author { font-size: .82rem; color: #888; margin-bottom: .5rem; }
    .btn-move-cart {
      background: #722304;
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: .4rem 1rem;
      font-size: .84rem;
      font-weight: 600;
    }
    .btn-move-cart:hover { background: #5a1b02; color:#fff; }
    .btn-remove {
      background: none;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: .4rem 1rem;
      font-size: .84rem;
      color: #555;
    }
    .btn-remove:hover { background: #fee; border-color: #e53935; color: #e53935; }
    .empty-state { text-align:center; padding: 5rem 1rem; }
    .empty-state i { font-size: 4rem; color: #ddd; }
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
      <a href="bookwishlist.php" style="position:relative;">
        <i class="bi bi-heart nav-icon d-none d-md-inline"></i>
      </a>
      <a href="bookcart.php" style="position:relative;">
        <i class="bi bi-cart3 nav-icon"></i>
        <span class="cart-badge"><?php echo $cart_count; ?></span>
      </a>
    </div>
  </div>
</nav>

<div class="container-xl py-5">
  <h2 class="section-heading mb-4"><i class="bi bi-heart-fill me-2" style="color:#e53935;"></i>My Wishlist</h2>

  <?php if (mysqli_num_rows($result) === 0): ?>
    <div class="empty-state">
      <i class="bi bi-heart d-block mb-3"></i>
      <h5 class="text-muted">Your wishlist is empty</h5>
      <p class="text-muted" style="font-size:.9rem;">Browse our collection and save books you love.</p>
      <a href="index.php" class="btn mt-3" style="background:#722304;color:#fff;border-radius:8px;padding:.6rem 1.8rem;">Browse Books</a>
    </div>
  <?php else: ?>
    <div class="row">
      <div class="col-lg-8">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <div class="wishlist-item">
            <img src="<?php echo htmlspecialchars($row['image']); ?>"
                 alt="<?php echo htmlspecialchars($row['title']); ?>"
                 onerror="this.src='https://placehold.co/80x115/f0ede8/1a3c5e?text=Book'">
            <div class="wishlist-info">
              <div class="wishlist-title"><?php echo htmlspecialchars($row['title']); ?></div>
              <div class="wishlist-author"><?php echo htmlspecialchars($row['author']); ?></div>
              <div class="d-flex align-items-center gap-2 mb-3">
                <span style="font-size:1.1rem;font-weight:700;color:#722304;">₹<?php echo $row['price']; ?></span>
                <span style="font-size:.85rem;color:#aaa;text-decoration:line-through;">₹<?php echo $row['original_price']; ?></span>
              </div>
              <div class="d-flex gap-2 flex-wrap">
                <form action="addtocart.php" method="POST" style="display:inline;">
                  <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                  <button type="submit" class="btn-move-cart">
                    <i class="bi bi-cart-plus me-1"></i>Move to Cart
                  </button>
                </form>
                <a href="removewishlist.php?id=<?php echo $row['book_id']; ?>" class="btn-remove">
                  <i class="bi bi-trash me-1"></i>Remove
                </a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
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
<script>
document.addEventListener('DOMContentLoaded', function () {
  const cartBadge = document.querySelector('.cart-badge');
  const itemsContainer = document.querySelector('.col-lg-8');

  function showEmptyState() {
    itemsContainer.innerHTML = `
      <div class="empty-state">
        <i class="bi bi-heart d-block mb-3"></i>
        <h5 class="text-muted">Your wishlist is empty</h5>
        <p class="text-muted" style="font-size:.9rem;">Browse our collection and save books you love.</p>
        <a href="index.php" class="btn mt-3" style="background:#722304;color:#fff;border-radius:8px;padding:.6rem 1.8rem;">Browse Books</a>
      </div>
    `;
  }

  // Handle Move to Cart forms
  document.querySelectorAll('form[action="addtocart.php"]').forEach(function(form){
    form.addEventListener('submit', function(e){
      e.preventDefault();
      const formData = new FormData(form);
      // indicate wishlist removal after adding to cart
      formData.append('remove_wishlist', '1');

      fetch('addtocart.php', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
      }).then(r => r.json()).then(data => {
        if (!data) return;
        if (data.status === 'login_required') {
          window.location = 'booklogin.php';
          return;
        }
        if (data.status === 'ok') {
          // update cart badge
          if (cartBadge && typeof data.cart_count !== 'undefined') {
            cartBadge.textContent = data.cart_count;
          }
          // remove the wishlist item from DOM
          const item = form.closest('.wishlist-item');
          if (item) item.remove();
          // if no items remain, show empty state
          if (!document.querySelector('.wishlist-item')) {
            showEmptyState();
          }
        }
      }).catch(err => {
        console.error('Move to cart failed', err);
      });
    });
  });

  // Handle Remove links
  document.querySelectorAll('a[href^="removewishlist.php"]').forEach(function(link){
    link.addEventListener('click', function(e){
      e.preventDefault();
      const url = new URL(link.href, window.location.href);
      fetch(url.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(data => {
          if (!data) return;
          if (data.status === 'login_required') {
            window.location = 'booklogin.php';
            return;
          }
          if (data.status === 'ok') {
            const item = link.closest('.wishlist-item');
            if (item) item.remove();
            if (!document.querySelector('.wishlist-item')) {
              showEmptyState();
            }
          }
        }).catch(err => console.error('Remove wishlist failed', err));
    });
  });
});
</script>
</body>
</html>
