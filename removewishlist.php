<?php
include "config.php";
// Detect AJAX
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if (!isset($_SESSION['user_id'])) {
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'login_required']);
        exit();
    }
    header("Location: booklogin.php");
    exit();
}

if (isset($_GET['id'])) {
    $user_id = (int)$_SESSION['user_id'];
    $book_id = (int)$_GET['id'];

    mysqli_query($conn, "DELETE FROM wishlist WHERE user_id='$user_id' AND book_id='$book_id'");

    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'ok', 'removed' => true, 'book_id' => $book_id]);
        exit();
    }
}

header("Location: bookwishlist.php");
exit();
?>
