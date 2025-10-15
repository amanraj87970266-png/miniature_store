<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Razorpay POST data
$razorpay_payment_id = $_POST['razorpay_payment_id'] ?? '';

if (!$razorpay_payment_id) {
    echo "<p style='text-align:center; color:red;'>Payment not completed properly.</p>";
    exit();
}

// Fetch cart items
$stmt = $conn->prepare("SELECT c.book_id, b.price, c.quantity FROM cart c JOIN books b ON c.book_id = b.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$cart_items) {
    echo "<p style='text-align:center; color:red;'>Your cart is empty.</p>";
    exit();
}

// Insert each item into orders/purchased table
foreach($cart_items as $item) {
    $stmt = $conn->prepare("INSERT INTO orders (user_id, book_id, quantity, price, purchase_date, payment_id) VALUES (?, ?, ?, ?, NOW(), ?)");
    $stmt->execute([$user_id, $item['book_id'], $item['quantity'], $item['price'], $razorpay_payment_id]);
}

// Clear user's cart
$stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
$stmt->execute([$user_id]);

echo "<p style='text-align:center; color:green; font-size:18px;'>Payment successful! Your purchased books are now available in your account.</p>";
echo "<p style='text-align:center;'><a href='purchased.php'>View Purchased Books</a></p>";
