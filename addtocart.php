<?php
include "config.php";

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if (!isset($_SESSION['user_id'])) {
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'login_required', 'message' => 'Please log in to add items to your cart.']);
        exit();
    }
    header("Location: booklogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $user_id = (int)$_SESSION['user_id'];
    $book_id = (int)$_POST['book_id'];

    $sql = "INSERT INTO cart (user_id, book_id, quantity)
            VALUES ('$user_id', '$book_id', 1)
            ON DUPLICATE KEY UPDATE quantity = quantity + 1";

    if (!mysqli_query($conn, $sql)) {
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Unable to add item to cart.']);
            exit();
        }

        $_SESSION['flash'] = "Book could not be added to your cart.";
        header("Location: index.php");
        exit();
    }

    $result = mysqli_query($conn, "SELECT SUM(quantity) AS total FROM cart WHERE user_id='$user_id'");
    $row = mysqli_fetch_assoc($result);
    $cart_count = (int)($row['total'] ?? 0);

    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'ok',
            'action' => 'added',
            'cart_count' => $cart_count,
        ]);
        exit();
    }

    $_SESSION['flash'] = "Book added to your cart!";
}

header("Location: index.php");
exit();
?>