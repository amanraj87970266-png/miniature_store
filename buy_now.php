<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_POST['book_id']) || empty($_POST['book_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$book_id = intval($_POST['book_id']);
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

//  Check if book exists
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    echo "<p style='text-align:center; color:red;'>Invalid book selected.</p>";
    exit();
}

//  Check if already in cart
$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND book_id = ?");
$stmt->execute([$user_id, $book_id]);

if ($stmt->rowCount() > 0) {
    // Update existing cart item
    $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND book_id = ?");
    $update->execute([$quantity, $user_id, $book_id]);
} else {
    // Insert new cart item
    $insert = $conn->prepare("INSERT INTO cart (user_id, book_id, quantity) VALUES (?, ?, ?)");
    $insert->execute([$user_id, $book_id, $quantity]);
}

//  Redirect to checkout for this book only
header("Location: checkout.php?buy_now=1&book_id=" . $book_id);
exit();
