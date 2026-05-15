<?php
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: booklogin.php");
    exit();
}

if (isset($_GET['id'])) {
    $user_id = (int)$_SESSION['user_id'];
    $book_id = (int)$_GET['id'];

    mysqli_query($conn, "DELETE FROM wishlist WHERE user_id='$user_id' AND book_id='$book_id'");
}

header("Location: bookwishlist.php");
exit();
?>