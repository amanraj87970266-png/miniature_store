<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle quantity update
if (isset($_POST['update_qty'])) {
    $cart_id = intval($_POST['cart_id']);
    $quantity = intval($_POST['quantity']);
    if ($quantity > 0) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$quantity, $cart_id, $user_id]);
    }
}

// Handle remove item
if (isset($_GET['remove'])) {
    $cart_id = intval($_GET['remove']);
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$cart_id, $user_id]);
}

// Fetch cart items
$stmt = $conn->prepare("
    SELECT c.id as cart_id, b.id as book_id, b.title, b.price, b.image, c.quantity
    FROM cart c
    JOIN books b ON c.book_id = b.id
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}
?>

<style>
.container {
    max-width: 1000px;
    margin: 30px auto;
    padding: 0 50px;
}

/* Make cart table responsive */
.cart-table-wrapper {
    width: 100%;
    overflow-x: auto;
}
.cart-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 50px;
    min-width: 700px; /* keep desktop look intact */
}
.cart-table th, .cart-table td {
    padding: 20px;
    text-align: center;
    font-weight: bold;
    font-size: 20px;
    border-bottom: 1px solid #ddd;
    white-space: nowrap;
}
.cart-table img {
    width: 100px;
    border-radius: 5px;
}
.update-btn, .remove-btn, .checkout-btn {
    padding: 10px 18px;
    border: none;
    border-radius: 10px;
    font-weight: bold;
    font-size: 18px;
    cursor: pointer;
    transition: background 0.3s ease;
}
.update-btn {
    background-color: #2196F3;
    color: white;
}
.update-btn:hover { background-color: #1976D2; }
.remove-btn {
    background-color: #f44336;
    color: white;
}
.remove-btn:hover { background-color: #d32f2f; }
.checkout-btn {
    background-color: #4CAF50;
    color: white;
    font-size: 25px;
}
.checkout-btn:hover { background-color: #45a049; }
.quantity-input {
    width: 80px;
    padding: 10px;
    font-size: 20px;
    border-radius: 5px;
    border: 1px solid #ccc;
    text-align: center;
}
.total-price {
    text-align: right;
    font-size: 25px;
    font-weight: bold;
    color: #4CAF50;
    margin-bottom: 50px;
}

/*  Mobile tweaks only */
@media (max-width: 768px) {
    .container {
        padding: 0 15px;
    }
    .cart-table th, .cart-table td {
        font-size: 14px;
        padding: 10px;
    }
    .quantity-input {
        width: 60px;
        padding: 6px;
        font-size: 16px;
    }
    .update-btn, .remove-btn {
        padding: 6px 10px;
        font-size: 14px;
        margin-top: 5px;
    }
    .checkout-btn {
        font-size: 18px;
        padding: 12px 20px;
        width: 100%; /* full width button for mobile */
    }
}
</style>

<div class="container">
    <h2 style="text-align:center; margin-bottom:20px;">Your Cart</h2>

    <?php if (empty($cart_items)): ?>
        <p style="text-align:center; margin-bottom:50px; font-weight:bold; font-size:20px;">
            Your cart is empty. 
            <a href="index.php">
                <button style="padding: 20px 30px; background-color: #4513e8ff; color: white; border: white; font-size: 18px; font-weight: italic; border-radius: 10px; cursor: pointer;">
                    Go shopping
                </button>
            </a>.
        </p>
    <?php else: ?>
        <div class="cart-table-wrapper">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($cart_items as $item): ?>
                        <tr>
                            <td><img src="assets/images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>"></td>
                            <td><?php echo htmlspecialchars($item['title']); ?></td>
                            <td>₹<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                    <input type="number" name="quantity" class="quantity-input" value="<?php echo $item['quantity']; ?>" min="1">
                                    <button type="submit" name="update_qty" class="update-btn">Update</button>
                                </form>
                            </td>
                            <td>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <a href="cart.php?remove=<?php echo $item['cart_id']; ?>" class="remove-btn">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="total-price">
            Total: ₹<?php echo number_format($total_price, 2); ?>
        </div>

        <div style="text-align:right;">
            <form method="POST" action="checkout.php">
                <button type="submit" class="checkout-btn">Proceed to Checkout</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
