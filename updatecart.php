<?php
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: booklogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'], $_POST['action'])) {
    $user_id = (int)$_SESSION['user_id'];
    $book_id = (int)$_POST['book_id'];
    $action  = $_POST['action'];

    if ($action === 'increase') {
        mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 WHERE user_id='$user_id' AND book_id='$book_id'");
    } elseif ($action === 'decrease') {
        // Check current quantity first
        $res = mysqli_query($conn, "SELECT quantity FROM cart WHERE user_id='$user_id' AND book_id='$book_id'");
        $row = mysqli_fetch_assoc($res);
        if ($row && $row['quantity'] > 1) {
            mysqli_query($conn, "UPDATE cart SET quantity = quantity - 1 WHERE user_id='$user_id' AND book_id='$book_id'");
        } else {
            // Remove if qty would go to 0
            mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id' AND book_id='$book_id'");
        }
    }
}

header("Location: bookcart.php");
exit();
?>