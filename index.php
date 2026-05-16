<?php
include "config.php";

// Get cart count for logged-in user
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $cc = mysqli_query($conn, "SELECT SUM(quantity) as total FROM cart WHERE user_id='$uid'");
    $crow = mysqli_fetch_assoc($cc);
    $cart_count = $crow['total'] ?? 0;
}

// Check which books are in wishlist for logged-in user
$wishlisted = [];
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $wq = mysqli_query($conn, "SELECT book_id FROM wishlist WHERE user_id='$uid'");
    while ($wr = mysqli_fetch_assoc($wq)) {
        $wishlisted[] = $wr['book_id'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Booksdungeon</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="bookstyle.css">
</head>
<body>

<!-- Top utility bar -->
<div class="top-bar py-1">
  <div class="top-bar-marquee">
    <div class="top-bar-marquee-content">
      <div class="top-bar-marquee-item">
        <span class="me-2">***Free delivery on orders above ₹499***</span>
        <span class="d-none d-md-inline me-2">|</span>
        <a href="#" class="d-none d-md-inline me-2">Download the App</a>
        <span class="d-none d-md-inline me-2">|</span>
        Use code <strong class="ms-1">BOOKS10</strong> for 10% off
      </div>
      <div class="top-bar-marquee-item">
        <span class="me-2">***Free delivery on orders above ₹499***</span>
        <span class="d-none d-md-inline me-2">|</span>
        <a href="#" class="d-none d-md-inline me-2">Download the App</a>
        <span class="d-none d-md-inline me-2">|</span>
        Use code <strong class="ms-1">BOOKS10</strong> for 10% off
      </div>
      <div class="top-bar-marquee-item">
        <span class="me-2">***Free delivery on orders above ₹499***</span>
        <span class="d-none d-md-inline me-2">|</span>
        <a href="#" class="d-none d-md-inline me-2">Download the App</a>
        <span class="d-none d-md-inline me-2">|</span>
        Use code <strong class="ms-1">BOOKS10</strong> for 10% off
      </div>
    </div>
  </div>
</div>

<!-- Navbar -->
<nav class="main-nav py-2">
  <div class="container-xl d-flex align-items-center gap-3">
    <a href="index.php" class="navbar-brand d-flex align-items-center gap-2 me-0">
      <img src="bookstoreimages/booklogo.png" alt="Booksdungeon Logo" width="50" height="42">
      <span class="d-none d-sm-block" style="font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:700;color:#722304;">Booksdungeon</span>
    </a>
    <div class="input-group flex-grow-1" style="max-width:480px;">
      <input type="search" class="form-control search-input" placeholder="Search books, authors, ISBN…" />
      <button class="btn search-btn text-white px-3" type="button"><i class="bi bi-search"></i></button>
    </div>
    <div class="d-flex align-items-center gap-3 ms-auto">
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="logout.php" title="Logout" class="d-none d-md-flex align-items-center gap-1" style="color:inherit;text-decoration:none;">
          <i class="bi bi-person-circle nav-icon"></i>
          <span style="font-size:.8rem;"><?php echo htmlspecialchars($_SESSION['fullname'] ?? ''); ?></span>
        </a>
      <?php else: ?>
        <a href="booklogin.php">
          <i class="bi bi-person-circle nav-icon d-none d-md-inline" title="Login / Register"></i>
        </a>
      <?php endif; ?>

      <a href="bookwishlist.php" style="position:relative;">
        <i class="bi bi-heart nav-icon d-none d-md-inline" title="Wishlist"></i>
        <span class="cart-badge wishlist-badge" style="<?php echo count($wishlisted) > 0 ? '' : 'display:none;'; ?>"><?php echo count($wishlisted); ?></span>
      </a>

      <a href="bookcart.php" style="position:relative;">
        <i class="bi bi-cart3 nav-icon" title="Cart"></i>
        <span class="cart-badge"><?php echo $cart_count; ?></span>
      </a>
    </div>
  </div>
</nav>

<!-- Category bar -->
<div class="cat-bar d-flex align-items-center gap-2 px-3 py-2">
  <a class="btn cat-pill active" href="#">All</a>
  <a class="btn cat-pill" href="#">Fiction</a>
  <a class="btn cat-pill" href="#">Non-Fiction</a>
  <a class="btn cat-pill" href="#">Children's</a>
  <a class="btn cat-pill" href="#">Self-Help</a>
  <a class="btn cat-pill" href="#">History</a>
  <a class="btn cat-pill" href="#">Science</a>
  <a class="btn cat-pill" href="#">Business</a>
  <a class="btn cat-pill" href="#">Romance</a>
  <a class="btn cat-pill" href="#">Classics</a>
  <a class="btn cat-pill" href="#">Comics</a>
  <a class="btn cat-pill" href="#">Biographies</a>
  <a class="btn cat-pill" href="#">Philosophy</a>
</div>

<!-- Hero Carousel -->
<div id="heroCarousel" class="carousel slide carousel-fade hero-carousel" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
  </div>
  <div class="carousel-inner position-relative">
    <div class="carousel-item active">
      <img src="bookstoreimages/car1.png" alt="Promo 1">
      <div class="carousel-caption-custom">
        <a href="#" class="btn">Shop Now</a>
      </div>
    </div>
    <div class="carousel-item">
      <img src="bookstoreimages/car2.png" alt="Promo 2">
      <div class="carousel-caption-custom">
        <a href="#" class="btn">Browse Deals</a>
      </div>
    </div>
    <div class="carousel-item">
      <img src="bookstoreimages/car3.png" alt="Promo 3">
      <div class="carousel-caption-custom">
        <a href="#" class="btn">See New Arrivals</a>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

<!-- Trust Strip -->
<div class="trust-strip py-3">
  <div class="container-xl">
    <div class="row row-cols-2 row-cols-md-4 g-3 text-center">
      <div class="col trust-item">
        <i class="bi bi-truck d-block mb-1"></i>
        <span class="d-block">Free Delivery</span>
        <small>On orders above ₹499</small>
      </div>
      <div class="col trust-item">
        <i class="bi bi-arrow-counterclockwise d-block mb-1"></i>
        <span class="d-block">Easy Returns</span>
        <small>30-day return policy</small>
      </div>
      <div class="col trust-item">
        <i class="bi bi-shield-check d-block mb-1"></i>
        <span class="d-block">100% Genuine</span>
        <small>Authentic books only</small>
      </div>
      <div class="col trust-item">
        <i class="bi bi-headset d-block mb-1"></i>
        <span class="d-block">24/7 Support</span>
        <small>Here to help anytime</small>
      </div>
    </div>
  </div>
</div>

<!-- Flash message -->
<?php if (isset($_SESSION['flash'])): ?>
  <div class="container-xl mt-3">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?php echo $_SESSION['flash']; unset($_SESSION['flash']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  </div>
<?php endif; ?>

<!-- Main Content -->
<div class="container-xl py-4">

  <!-- ===== FEATURED BOOKS ===== -->
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="section-heading mb-0">Featured Books</h2>
    <a href="#" class="view-all">View All <i class="bi bi-arrow-right-short"></i></a>
  </div>
  <div class="row g-3 mb-5">

    <!-- The Tattooist of Auschwitz (id=1) -->
    <div class="col-12 col-md-6 col-xl-4">
      <div class="feat-card">
        <div class="feat-img-wrap">
          <img src="https://covers.openlibrary.org/b/isbn/9781250301697-M.jpg"
               alt="The Tattooist of Auschwitz"
               onerror="this.src='https://placehold.co/86x130/f0ede8/1a3c5e?text=Book'">
        </div>
        <div class="feat-body">
          <div class="feat-title mb-1">The Tattooist of Auschwitz</div>
          <div class="text-muted mb-1" style="font-size:.78rem;">Heather Morris</div>
          <div class="stars mb-2">★★★★★ <span class="text-muted ms-1" style="font-size:.75rem;">(4,821)</span></div>
          <p class="feat-desc mb-2">Based on the true story of Lale and Gita Sokolov — two Slovakian Jews who survived Auschwitz and built a new life in Australia.</p>
          <div class="d-flex align-items-center gap-2 mb-3">
            <span class="feat-price">₹285</span>
            <span class="feat-old">₹599</span>
            <span class="badge-save">Save 52%</span>
          </div>
          <form action="addtocart.php" method="POST">
            <input type="hidden" name="book_id" value="1">
            <button type="submit" class="add-cart-btn">
              <i class="bi bi-cart-plus me-1"></i>Add to Cart
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Atomic Habits (id=2) -->
    <div class="col-12 col-md-6 col-xl-4">
      <div class="feat-card">
        <div class="feat-img-wrap">
          <img src="https://covers.openlibrary.org/b/isbn/9780735211292-M.jpg"
               alt="Atomic Habits"
               onerror="this.src='https://placehold.co/86x130/f0ede8/1a3c5e?text=Book'">
        </div>
        <div class="feat-body">
          <div class="feat-title mb-1">Atomic Habits</div>
          <div class="text-muted mb-1" style="font-size:.78rem;">James Clear</div>
          <div class="stars mb-2">★★★★★ <span class="text-muted ms-1" style="font-size:.75rem;">(9,314)</span></div>
          <p class="feat-desc mb-2">An easy and proven way to build good habits and break bad ones. Tiny changes, remarkable results.</p>
          <div class="d-flex align-items-center gap-2 mb-3">
            <span class="feat-price">₹349</span>
            <span class="feat-old">₹699</span>
            <span class="badge-save">Save 50%</span>
          </div>
          <form action="addtocart.php" method="POST">
            <input type="hidden" name="book_id" value="2">
            <button type="submit" class="add-cart-btn">
              <i class="bi bi-cart-plus me-1"></i>Add to Cart
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- The Alchemist (id=3) -->
    <div class="col-12 col-md-6 col-xl-4">
      <div class="feat-card">
        <div class="feat-img-wrap">
          <img src="https://covers.openlibrary.org/b/isbn/9780062315007-M.jpg"
               alt="The Alchemist"
               onerror="this.src='https://placehold.co/86x130/f0ede8/1a3c5e?text=Book'">
        </div>
        <div class="feat-body">
          <div class="feat-title mb-1">The Alchemist</div>
          <div class="text-muted mb-1" style="font-size:.78rem;">Paulo Coelho</div>
          <div class="stars mb-2">★★★★☆ <span class="text-muted ms-1" style="font-size:.75rem;">(6,208)</span></div>
          <p class="feat-desc mb-2">A magical fable about following your dream. A young shepherd travels from Spain to Egypt in search of treasure buried near the Pyramids.</p>
          <div class="d-flex align-items-center gap-2 mb-3">
            <span class="feat-price">₹199</span>
            <span class="feat-old">₹399</span>
            <span class="badge-save">Save 50%</span>
          </div>
          <form action="addtocart.php" method="POST">
            <input type="hidden" name="book_id" value="3">
            <button type="submit" class="add-cart-btn">
              <i class="bi bi-cart-plus me-1"></i>Add to Cart
            </button>
          </form>
        </div>
      </div>
    </div>

  </div>

  <!-- ===== BESTSELLERS ===== -->
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="section-heading mb-0">Bestsellers</h2>
    <a href="#" class="view-all">View All <i class="bi bi-arrow-right-short"></i></a>
  </div>
  <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3 mb-5">

    <!-- Tattooist (id=1) -->
    <div class="col">
      <div class="book-card position-relative">
        <form id="wishlist-form-1" name="wishlist-form-1" action="addtowishlist.php" method="POST" class="wishlist-form">
          <input id="wishlist-book-id-1" type="hidden" name="book_id" value="1">
          <button id="wishlist-btn-1" type="submit" class="wishlist-btn <?php echo in_array(1,$wishlisted)?'active':''; ?>">
            <i class="bi <?php echo in_array(1,$wishlisted)?'bi-heart-fill':'bi-heart'; ?>"></i>
          </button>
        </form>
        <div class="book-img-wrap">
          <img src="https://covers.openlibrary.org/b/isbn/9781250301697-M.jpg"
               alt="The Tattooist of Auschwitz"
               onerror="this.src='https://placehold.co/120x172/f0ede8/1a3c5e?text=Book'">
        </div>
        <div class="p-3">
          <div class="book-title mb-1">The Tattooist of Auschwitz</div>
          <div class="book-author mb-1">Heather Morris</div>
          <div class="stars mb-2">★★★★★</div>
          <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
            <span class="price-now">₹285</span>
            <span class="price-old">₹599</span>
            <span class="badge-save">52% off</span>
          </div>
          <form action="addtocart.php" method="POST">
            <input type="hidden" name="book_id" value="1">
            <button type="submit" class="add-cart-btn">
              <i class="bi bi-cart-plus me-1"></i>Add to Cart
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Atomic Habits (id=2) -->
    <div class="col">
      <div class="book-card position-relative">
        <form id="wishlist-form-2" name="wishlist-form-2" action="addtowishlist.php" method="POST" class="wishlist-form">
          <input id="wishlist-book-id-2" type="hidden" name="book_id" value="2">
          <button id="wishlist-btn-2" type="submit" class="wishlist-btn <?php echo in_array(2,$wishlisted)?'active':''; ?>">
            <i class="bi <?php echo in_array(2,$wishlisted)?'bi-heart-fill':'bi-heart'; ?>"></i>
          </button>
        </form>
        <div class="book-img-wrap">
          <img src="https://covers.openlibrary.org/b/isbn/9780735211292-M.jpg"
               alt="Atomic Habits"
               onerror="this.src='https://placehold.co/120x172/f0ede8/1a3c5e?text=Book'">
        </div>
        <div class="p-3">
          <div class="book-title mb-1">Atomic Habits</div>
          <div class="book-author mb-1">James Clear</div>
          <div class="stars mb-2">★★★★★</div>
          <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
            <span class="price-now">₹349</span>
            <span class="price-old">₹699</span>
            <span class="badge-save">50% off</span>
          </div>
          <form action="addtocart.php" method="POST">
            <input type="hidden" name="book_id" value="2">
            <button type="submit" class="add-cart-btn">
              <i class="bi bi-cart-plus me-1"></i>Add to Cart
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- The Alchemist (id=3) -->
    <div class="col">
      <div class="book-card position-relative">
        <form id="wishlist-form-3" name="wishlist-form-3" action="addtowishlist.php" method="POST" class="wishlist-form">
          <input id="wishlist-book-id-3" type="hidden" name="book_id" value="3">
          <button id="wishlist-btn-3" type="submit" class="wishlist-btn <?php echo in_array(3,$wishlisted)?'active':''; ?>">
            <i class="bi <?php echo in_array(3,$wishlisted)?'bi-heart-fill':'bi-heart'; ?>"></i>
          </button> qw
        </form>
        <div class="book-img-wrap">
          <img src="https://covers.openlibrary.org/b/isbn/9780062315007-M.jpg"
               alt="The Alchemist"
               onerror="this.src='https://placehold.co/120x172/f0ede8/1a3c5e?text=Book'">
        </div>
        <div class="p-3">
          <div class="book-title mb-1">The Alchemist</div>
          <div class="book-author mb-1">Paulo Coelho</div>
          <div class="stars mb-2">★★★★☆</div>
          <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
            <span class="price-now">₹199</span>
            <span class="price-old">₹399</span>
            <span class="badge-save">50% off</span>
          </div>
          <form action="addtocart.php" method="POST">
            <input type="hidden" name="book_id" value="3">
            <button type="submit" class="add-cart-btn">
              <i class="bi bi-cart-plus me-1"></i>Add to Cart
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Rich Dad Poor Dad (id=4) -->
    <div class="col">
      <div class="book-card position-relative">
        <form id="wishlist-form-4" name="wishlist-form-4" action="addtowishlist.php" method="POST" class="wishlist-form">
          <input id="wishlist-book-id-4" type="hidden" name="book_id" value="4">
          <button id="wishlist-btn-4" type="submit" class="wishlist-btn <?php echo in_array(4,$wishlisted)?'active':''; ?>">
            <i class="bi <?php echo in_array(4,$wishlisted)?'bi-heart-fill':'bi-heart'; ?>"></i>
          </button>
        </form>
        <div class="book-img-wrap">
          <img src="https://covers.openlibrary.org/b/isbn/9781612680194-M.jpg"
               alt="Rich Dad Poor Dad"
               onerror="this.src='https://placehold.co/120x172/f0ede8/1a3c5e?text=Book'">
        </div>
        <div class="p-3">
          <div class="book-title mb-1">Rich Dad Poor Dad</div>
          <div class="book-author mb-1">Robert T. Kiyosaki</div>
          <div class="stars mb-2">★★★★☆</div>
          <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
            <span class="price-now">₹249</span>
            <span class="price-old">₹499</span>
            <span class="badge-save">50% off</span>
          </div>
          <form action="addtocart.php" method="POST">
            <input type="hidden" name="book_id" value="4">
            <button type="submit" class="add-cart-btn">
              <i class="bi bi-cart-plus me-1"></i>Add to Cart
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- The Power of Now (id=5) -->
    <div class="col">
      <div class="book-card position-relative">
        <form id="wishlist-form-5" name="wishlist-form-5" action="addtowishlist.php" method="POST" class="wishlist-form">
          <input id="wishlist-book-id-5" type="hidden" name="book_id" value="5">
          <button id="wishlist-btn-5" type="submit" class="wishlist-btn <?php echo in_array(5,$wishlisted)?'active':''; ?>">
            <i class="bi <?php echo in_array(5,$wishlisted)?'bi-heart-fill':'bi-heart'; ?>"></i>
          </button>
        </form>
        <div class="book-img-wrap">
          <img src="https://covers.openlibrary.org/b/isbn/9781577314806-M.jpg"
               alt="The Power of Now"
               onerror="this.src='https://placehold.co/120x172/f0ede8/1a3c5e?text=Book'">
        </div>
        <div class="p-3">
          <div class="book-title mb-1">The Power of Now</div>
          <div class="book-author mb-1">Eckhart Tolle</div>
          <div class="stars mb-2">★★★★★</div>
          <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
            <span class="price-now">₹299</span>
            <span class="price-old">₹550</span>
            <span class="badge-save">46% off</span>
          </div>
          <form action="addtocart.php" method="POST">
            <input type="hidden" name="book_id" value="5">
            <button type="submit" class="add-cart-btn">
              <i class="bi bi-cart-plus me-1"></i>Add to Cart
            </button>
          </form>
        </div>
      </div>
    </div>

  </div>

  <!-- Featured Cards -->
  <div class="row g-3 mb-5">
    <div class="col-12 col-md-4">
      <div class="card text-bg-dark overflow-hidden h-100 featured-img-card">
        <img src="bookstoreimages/featuredcard1.png" class="card-img" alt="Featured card">
        <div class="card-img-overlay d-flex flex-column justify-content-center text-center p-4"></div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card text-bg-dark overflow-hidden h-100 featured-img-card">
        <img src="bookstoreimages/featuredcard2.png" class="card-img" alt="Featured card">
        <div class="card-img-overlay d-flex flex-column justify-content-center text-center p-4"></div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card text-bg-dark overflow-hidden h-100 featured-img-card">
        <img src="bookstoreimages/featuredcard3.png" class="card-img" alt="Featured card">
        <div class="card-img-overlay d-flex flex-column justify-content-center text-center p-4"></div>
      </div>
    </div>
  </div>

  <!-- Promo Banner -->
  <div class="promo-banner p-4 p-md-5 mb-5 d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
    <div>
      <h2 class="mb-2">End of Season Sale</h2>
      <p class="mb-0" style="opacity:.85;font-size:.95rem;">Up to 70% off on select titles. Limited time only — grab your favourites before they're gone.</p>
    </div>
    <a href="#" class="btn promo-btn flex-shrink-0">Shop the Sale</a>
  </div>

  <!-- ===== NEW ARRIVALS ===== -->
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="section-heading mb-0">New Arrivals</h2>
    <a href="#" class="view-all">View All <i class="bi bi-arrow-right-short"></i></a>
  </div>
  <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3 mb-5">

    <!-- Ikigai (id=6) -->
    <div class="col">
      <div class="book-card position-relative">
        <span class="badge bg-danger position-absolute top-0 end-0 m-2" style="font-size:.68rem;border-radius:4px;">New</span>
        <form id="wishlist-form-6" name="wishlist-form-6" action="addtowishlist.php" method="POST" class="wishlist-form">
          <input id="wishlist-book-id-6" type="hidden" name="book_id" value="6">
          <button id="wishlist-btn-6" type="submit" class="wishlist-btn <?php echo in_array(6,$wishlisted)?'active':''; ?>">
            <i class="bi <?php echo in_array(6,$wishlisted)?'bi-heart-fill':'bi-heart'; ?>"></i>
          </button>
        </form>
        <div class="book-img-wrap">
          <img src="https://covers.openlibrary.org/b/isbn/9780143130727-M.jpg"
               alt="Ikigai"
               onerror="this.src='https://placehold.co/120x172/f0ede8/1a3c5e?text=Book'">
        </div>
        <div class="p-3">
          <div class="book-title mb-1">Ikigai</div>
          <div class="book-author mb-1">Héctor García</div>
          <div class="stars mb-2">★★★★★</div>
          <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
            <span class="price-now">₹225</span>
            <span class="price-old">₹450</span>
            <span class="badge-save">50% off</span>
          </div>
          <form action="addtocart.php" method="POST">
            <input type="hidden" name="book_id" value="6">
            <button type="submit" class="add-cart-btn">
              <i class="bi bi-cart-plus me-1"></i>Add to Cart
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Sapiens (id=7) -->
    <div class="col">
      <div class="book-card position-relative">
        <span class="badge bg-danger position-absolute top-0 end-0 m-2" style="font-size:.68rem;border-radius:4px;">New</span>
        <form id="wishlist-form-7" name="wishlist-form-7" action="addtowishlist.php" method="POST" class="wishlist-form">
          <input id="wishlist-book-id-7" type="hidden" name="book_id" value="7">
          <button id="wishlist-btn-7" type="submit" class="wishlist-btn <?php echo in_array(7,$wishlisted)?'active':''; ?>">
            <i class="bi <?php echo in_array(7,$wishlisted)?'bi-heart-fill':'bi-heart'; ?>"></i>
          </button>
        </form>
        <div class="book-img-wrap">
          <img src="https://covers.openlibrary.org/b/isbn/9780062316097-M.jpg"
               alt="Sapiens"
               onerror="this.src='https://placehold.co/120x172/f0ede8/1a3c5e?text=Book'">
        </div>
        <div class="p-3">
          <div class="book-title mb-1">Sapiens: A Brief History</div>
          <div class="book-author mb-1">Yuval Noah Harari</div>
          <div class="stars mb-2">★★★★★</div>
          <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
            <span class="price-now">₹399</span>
            <span class="price-old">₹799</span>
            <span class="badge-save">50% off</span>
          </div>
          <form action="addtocart.php" method="POST">
            <input type="hidden" name="book_id" value="7">
            <button type="submit" class="add-cart-btn">
              <i class="bi bi-cart-plus me-1"></i>Add to Cart
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- The Midnight Library (id=8) -->
    <div class="col">
      <div class="book-card position-relative">
        <span class="badge bg-danger position-absolute top-0 end-0 m-2" style="font-size:.68rem;border-radius:4px;">New</span>
        <form id="wishlist-form-8" name="wishlist-form-8" action="addtowishlist.php" method="POST" class="wishlist-form">
          <input id="wishlist-book-id-8" type="hidden" name="book_id" value="8">
          <button id="wishlist-btn-8" type="submit" class="wishlist-btn <?php echo in_array(8,$wishlisted)?'active':''; ?>">
            <i class="bi <?php echo in_array(8,$wishlisted)?'bi-heart-fill':'bi-heart'; ?>"></i>
          </button>
        </form>
        <div class="book-img-wrap">
          <img src="https://covers.openlibrary.org/b/isbn/9780525559474-M.jpg"
               alt="The Midnight Library"
               onerror="this.src='https://placehold.co/120x172/f0ede8/1a3c5e?text=Book'">
        </div>
        <div class="p-3">
          <div class="book-title mb-1">The Midnight Library</div>
          <div class="book-author mb-1">Matt Haig</div>
          <div class="stars mb-2">★★★★☆</div>
          <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
            <span class="price-now">₹315</span>
            <span class="price-old">₹599</span>
            <span class="badge-save">47% off</span>
          </div>
          <form action="addtocart.php" method="POST">
            <input type="hidden" name="book_id" value="8">
            <button type="submit" class="add-cart-btn">
              <i class="bi bi-cart-plus me-1"></i>Add to Cart
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- 1984 (id=9) -->
    <div class="col">
      <div class="book-card position-relative">
        <span class="badge bg-danger position-absolute top-0 end-0 m-2" style="font-size:.68rem;border-radius:4px;">New</span>
        <form id="wishlist-form-9" name="wishlist-form-9" action="addtowishlist.php" method="POST" class="wishlist-form">
          <input id="wishlist-book-id-9" type="hidden" name="book_id" value="9">
          <button id="wishlist-btn-9" type="submit" class="wishlist-btn <?php echo in_array(9,$wishlisted)?'active':''; ?>">
            <i class="bi <?php echo in_array(9,$wishlisted)?'bi-heart-fill':'bi-heart'; ?>"></i>
          </button>
        </form>
        <div class="book-img-wrap">
          <img src="https://covers.openlibrary.org/b/isbn/9780451524935-M.jpg"
               alt="1984"
               onerror="this.src='https://placehold.co/120x172/f0ede8/1a3c5e?text=Book'">
        </div>
        <div class="p-3">
          <div class="book-title mb-1">1984</div>
          <div class="book-author mb-1">George Orwell</div>
          <div class="stars mb-2">★★★★★</div>
          <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
            <span class="price-now">₹150</span>
            <span class="price-old">₹299</span>
            <span class="badge-save">50% off</span>
          </div>
          <form action="addtocart.php" method="POST">
            <input type="hidden" name="book_id" value="9">
            <button type="submit" class="add-cart-btn">
              <i class="bi bi-cart-plus me-1"></i>Add to Cart
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Think and Grow Rich (id=10) -->
    <div class="col">
      <div class="book-card position-relative">
        <span class="badge bg-danger position-absolute top-0 end-0 m-2" style="font-size:.68rem;border-radius:4px;">New</span>
        <form id="wishlist-form-10" name="wishlist-form-10" action="addtowishlist.php" method="POST" class="wishlist-form">
          <input id="wishlist-book-id-10" type="hidden" name="book_id" value="10">
          <button id="wishlist-btn-10" type="submit" class="wishlist-btn <?php echo in_array(10,$wishlisted)?'active':''; ?>">
            <i class="bi <?php echo in_array(10,$wishlisted)?'bi-heart-fill':'bi-heart'; ?>"></i>
          </button>
        </form>
        <div class="book-img-wrap">
          <img src="https://covers.openlibrary.org/b/isbn/9781585424337-M.jpg"
               alt="Think and Grow Rich"
               onerror="this.src='https://placehold.co/120x172/f0ede8/1a3c5e?text=Book'">
        </div>
        <div class="p-3">
          <div class="book-title mb-1">Think and Grow Rich</div>
          <div class="book-author mb-1">Napoleon Hill</div>
          <div class="stars mb-2">★★★★☆</div>
          <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
            <span class="price-now">₹189</span>
            <span class="price-old">₹350</span>
            <span class="badge-save">46% off</span>
          </div>
          <form action="addtocart.php" method="POST">
            <input type="hidden" name="book_id" value="10">
            <button type="submit" class="add-cart-btn">
              <i class="bi bi-cart-plus me-1"></i>Add to Cart
            </button>
          </form>
        </div>
      </div>
    </div>

  </div>

  <!-- Newsletter CTA -->
  <div class="bg-white border rounded-3 p-4 p-md-5 mb-2 text-center" style="border-color: var(--bd-border) !important;">
    <h4 class="section-heading mb-2">Stay in the Loop</h4>
    <p class="text-muted mb-4" style="font-size:.9rem;">Get new arrivals, exclusive deals and reading picks delivered to your inbox.</p>
      <div class="row justify-content-center">
        <div class="col-12 col-md-6">
          <form id="newsletter-form" class="input-group" action="#" method="POST" onsubmit="return false;">
            <input id="newsletter-email" name="newsletter_email" type="email" class="form-control" placeholder="Enter your email address" autocomplete="email" style="border-radius:24px 0 0 24px; border-color:var(--bd-border);">
            <button id="newsletter-btn" type="submit" class="btn text-white px-4" style="background:var(--bd-accent);border-color:var(--bd-accent);border-radius:0 24px 24px 0;">Subscribe</button>
          </form>
        </div>
      </div>
  </div>

</div>

<!-- Footer -->
<footer class="pt-5 pb-3">
  <div class="container-xl">
    <div class="row g-4 mb-4">
      <div class="col-12 col-md-4">
        <div class="d-flex align-items-center gap-2 mb-3" style="font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:700;color:#fff;">
          <img src="bookstoreimages/booklogo.png" alt="Booksdungeon logo" style="width:32px;height:32px;">
          Booksdungeon
        </div>
        <p style="font-size:.83rem;line-height:1.6;color:#cdd6e0;">Your one-stop destination for books across every genre. Delivered fast, at the best prices.</p>
        <div class="footer-social d-flex gap-3 mt-3">
          <i class="bi bi-facebook"></i>
          <i class="bi bi-instagram"></i>
          <i class="bi bi-twitter-x"></i>
          <i class="bi bi-youtube"></i>
        </div>
      </div>
      <div class="col-6 col-md-2">
        <h6 class="mb-3">Shop</h6>
        <a href="#" class="d-block mb-2">Fiction</a>
        <a href="#" class="d-block mb-2">Non-Fiction</a>
        <a href="#" class="d-block mb-2">Children's</a>
        <a href="#" class="d-block mb-2">Bestsellers</a>
        <a href="#" class="d-block mb-2">New Arrivals</a>
      </div>
      <div class="col-6 col-md-2">
        <h6 class="mb-3">Help</h6>
        <a href="#" class="d-block mb-2">My Account</a>
        <a href="#" class="d-block mb-2">Track Order</a>
        <a href="#" class="d-block mb-2">Returns</a>
        <a href="#" class="d-block mb-2">FAQs</a>
        <a href="#" class="d-block mb-2">Contact Us</a>
      </div>
      <div class="col-12 col-md-4">
        <h6 class="mb-3">Download Our App</h6>
        <p style="font-size:.82rem;color:#cdd6e0;">Shop on the go — available on iOS and Android.</p>
        <div class="d-flex gap-2 flex-wrap">
          <a href="#" class="btn btn-outline-light btn-sm d-flex align-items-center gap-2 px-3 py-2" style="border-radius:8px;font-size:.8rem;">
            <i class="bi bi-apple"></i> App Store
          </a>
          <a href="#" class="btn btn-outline-light btn-sm d-flex align-items-center gap-2 px-3 py-2" style="border-radius:8px;font-size:.8rem;">
            <i class="bi bi-google-play"></i> Play Store
          </a>
        </div>
      </div>
    </div>
    <div class="footer-bottom pt-3 text-center">
      © 2026 Booksdungeon. All rights reserved. &nbsp;·&nbsp;
      <a href="#" style="color:#7a90a4;">Privacy Policy</a> &nbsp;·&nbsp;
      <a href="#" style="color:#7a90a4;">Terms of Use</a>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ── WISHLIST – AJAX (no page reload) ──────────────────────
document.querySelectorAll(".wishlist-form").forEach(form => {
  form.addEventListener("submit", function(e) {
    e.preventDefault(); // stop the page from reloading

    const btn      = form.querySelector("button");
    const icon     = btn.querySelector("i");
    const book_id  = form.querySelector('input[name="book_id"]').value;

    // Optimistic UI — flip icon immediately
    const isActive = btn.classList.contains("active");

    fetch("addtowishlist.php", {
      method: "POST",
      credentials: "same-origin",
      headers: {
        "Content-Type":     "application/x-www-form-urlencoded",
        "X-Requested-With": "XMLHttpRequest",
        "Accept":           "application/json"
      },
      body: new URLSearchParams({ book_id })
    })
    .then(res => {
      const contentType = res.headers.get("content-type") || "";
      if (!contentType.includes("application/json")) {
        return res.text().then(text => {
          if (text.includes("Login – Booksdungeon")) {
            window.location.href = "booklogin.php";
            throw new Error("Login required");
          }
          throw new Error("Invalid server response");
        });
      }
      return res.json();
    })
    .then(data => {
      if (data.status === "login_required") {
        // Not logged in — send to login page with message
        window.location.href = "booklogin.php?message=login_first";
        return;
      }

      if (data.status === "error") {
        // Server-side error returned as JSON
        showToast(data.message || "Something went wrong. Try again.");
        return;
      }

      if (data.status === "ok") {
        if (data.action === "added") {
          icon.classList.remove("bi-heart");
          icon.classList.add("bi-heart-fill");
          btn.classList.add("active");
          btn.style.color       = "#e53935";
          btn.style.borderColor = "#e53935";
          showToast("Added to wishlist ❤️");
        } else {
          icon.classList.remove("bi-heart-fill");
          icon.classList.add("bi-heart");
          btn.classList.remove("active");
          btn.style.color       = "";
          btn.style.borderColor = "";
          showToast("Removed from wishlist");
        }

        // Update wishlist badge in navbar
        const badge = document.querySelector(".wishlist-badge");
        if (badge) {
          badge.textContent = data.wishlist_count;
          badge.style.display = data.wishlist_count > 0 ? "inline-flex" : "none";
        }
      }
    })
    .catch(() => {
      showToast("Something went wrong. Try again.");
    });
  });
});

// ── CART – AJAX (no page reload) ───────────────────────────
document.querySelectorAll("form[action='addtocart.php']").forEach(form => {
  form.addEventListener("submit", function(e) {
    e.preventDefault();

    const btn = form.querySelector("button");
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Adding';

    fetch("addtocart.php", {
      method: "POST",
      credentials: "same-origin",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Accept": "application/json"
      },
      body: new URLSearchParams(new FormData(form))
    })
    .then(res => {
      const contentType = res.headers.get("content-type") || "";
      if (!contentType.includes("application/json")) {
        return res.text().then(text => {
          if (text.includes("Login – Booksdungeon") || text.includes("booklogin.php")) {
            window.location.href = "booklogin.php";
            throw new Error("Login required");
          }
          throw new Error("Invalid server response");
        });
      }
      return res.json();
    })
    .then(data => {
      if (data.status === "login_required") {
        window.location.href = "booklogin.php?message=login_first";
        return;
      }

      if (data.status === "error") {
        showToast(data.message || "Something went wrong. Try again.");
        return;
      }

      if (data.status === "ok") {
        const cartBadge = document.querySelector('a[href="bookcart.php"] .cart-badge');
        if (cartBadge) {
          cartBadge.textContent = data.cart_count;
        }

        btn.innerHTML = '<i class="bi bi-cart-check me-1"></i>Added';
        showToast("Added to cart 🛒");
        setTimeout(() => {
          btn.innerHTML = originalHtml;
        }, 1200);
      }
    })
    .catch(() => {
      showToast("Something went wrong. Try again.");
    })
    .finally(() => {
      btn.disabled = false;
    });
  });
});

// ── TOAST NOTIFICATION ────────────────────────────────────
function showToast(message) {
  // Remove any existing toast
  const old = document.getElementById("bd-toast");
  if (old) old.remove();

  const toast = document.createElement("div");
  toast.id = "bd-toast";
  toast.textContent = message;
  toast.style.cssText = `
    position: fixed;
    bottom: 1.5rem;
    left: 50%;
    transform: translateX(-50%);
    background: #1a3c5e;
    color: #fff;
    padding: .65rem 1.5rem;
    border-radius: 30px;
    font-size: .88rem;
    font-weight: 600;
    z-index: 9999;
    box-shadow: 0 4px 16px rgba(0,0,0,.2);
    opacity: 0;
    transition: opacity .3s;
  `;
  document.body.appendChild(toast);

  // Fade in
  requestAnimationFrame(() => { toast.style.opacity = "1"; });

  // Fade out after 2.2s
  setTimeout(() => {
    toast.style.opacity = "0";
    setTimeout(() => toast.remove(), 300);
  }, 2200);
}
// ── NEWSLETTER — simple client-side subscribe feedback ─────────────────
(function(){
  const form = document.getElementById('newsletter-form');
  const input = document.getElementById('newsletter-email');
  const btn = document.getElementById('newsletter-btn');
  if (!form || !input || !btn) return;

  function validEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  form.addEventListener('submit', function(e){
    e.preventDefault();
    const email = (input.value || '').trim();
    if (!email) {
      showToast('Please enter your email address.');
      input.focus();
      return;
    }
    if (!validEmail(email)) {
      showToast('Please enter a valid email address.');
      input.focus();
      return;
    }

    // Optimistic UI: show spinner and disable
    const origHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Subscribing';

    // Simulate a short delay (or replace with real fetch to server)
    setTimeout(() => {
      btn.disabled = false;
      btn.innerHTML = origHtml;
      input.value = '';
      showToast('Thank you for subscribing!');
    }, 800);
  });
})();
</script>
</body>
</html>
