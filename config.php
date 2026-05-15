<?php
session_start();

$conn = new mysqli("localhost", "root", "", "bookstore");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>