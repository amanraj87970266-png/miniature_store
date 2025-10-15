<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];


// Validate POST data
if (!isset($_POST['book_id']) || empty($_POST['book_id']) || !isset($_POST['quantity'])) {
    header('Location: index.php');
    exit();
}

$book_id = intval($_POST['book_id']);
$quantity = intval($_POST['quantity']);
if ($quantity < 1) $quantity = 1;

// Check if book already in cart
$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND book_id = ?");
$stmt->execute([$user_id, $book_id]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing) {
    // Update quantity
    $new_qty = $existing['quantity'] + $quantity;
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $stmt->execute([$new_qty, $existing['id']]);
} else {
    // Insert new item
    $stmt = $conn->prepare("INSERT INTO cart (user_id, book_id, quantity) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $book_id, $quantity]);
}

// Redirect back to cart page
header('Location: cart.php');
exit();
?>
