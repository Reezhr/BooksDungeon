<?php
include "config.php";

$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// Not logged in
if (!isset($_SESSION['user_id'])) {
    if ($is_ajax) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'login_required']);
        exit();
    }
    header("Location: booklogin.php?message=login_first");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $user_id = (int)$_SESSION['user_id'];
    $book_id = (int)$_POST['book_id'];

    // Check if already wishlisted — toggle behavior
    $check = mysqli_query($conn, "SELECT wishlist_id FROM wishlist WHERE user_id='$user_id' AND book_id='$book_id'");
    if ($check === false) {
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
            exit();
        }
        die('Database error: ' . mysqli_error($conn));
    }

    if (mysqli_num_rows($check) > 0) {
        // Already in wishlist — remove it
        $del = mysqli_query($conn, "DELETE FROM wishlist WHERE user_id='$user_id' AND book_id='$book_id'");
        if ($del === false) {
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
                exit();
            }
            die('Delete failed: ' . mysqli_error($conn));
        }
        $action = 'removed';
    } else {
        // Add to wishlist
        $ins = mysqli_query($conn, "INSERT INTO wishlist (user_id, book_id) VALUES ('$user_id', '$book_id')");
        if ($ins === false) {
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
                exit();
            }
            die('Insert failed: ' . mysqli_error($conn));
        }
        $action = 'added';
    }

    // Updated count for badge
    $count_res = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM wishlist WHERE user_id='$user_id'");
    if ($count_res === false) {
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
            exit();
        }
        die('Count query failed: ' . mysqli_error($conn));
    }
    $count_row = mysqli_fetch_assoc($count_res);
    $wishlist_count = $count_row['cnt'];

    if ($is_ajax) {
        header('Content-Type: application/json');
        echo json_encode([
            'status'         => 'ok',
            'action'         => $action,
            'wishlist_count' => $wishlist_count
        ]);
        exit();
    }
}

header("Location: index.php");
exit();
?>
