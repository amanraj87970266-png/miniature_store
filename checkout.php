<?php
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

//  Handle Buy Now vs Normal Cart
if (isset($_GET['buy_now']) && isset($_GET['book_id'])) {
    $book_id = intval($_GET['book_id']);
    $stmt = $conn->prepare("
        SELECT c.id AS cart_id, b.id AS book_id, b.title, b.price, b.image, c.quantity
        FROM cart c
        JOIN books b ON c.book_id = b.id
        WHERE c.user_id = ? AND c.book_id = ?
    ");
    $stmt->execute([$user_id, $book_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Normal checkout (all cart items)
    $stmt = $conn->prepare("
        SELECT c.id AS cart_id, b.id AS book_id, b.title, b.price, b.image, c.quantity
        FROM cart c
        JOIN books b ON c.book_id = b.id
        WHERE c.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (!$cart_items) {
    echo "<p style='text-align:center;'>Your cart is empty. <a href='index.php'>Go back to store</a></p>";
    include 'includes/footer.php';
    exit();
}

// Calculate total
$total_amount = 0;
foreach ($cart_items as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

// Razorpay Test Key
$razorpay_key = "rzp_test_RGHRj4ryFAgYcr";
?>

<style>
    .checkout-container {
        max-width: 900px;
        margin: 30px auto;
        padding: 20px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    .checkout-header {
        text-align: center;
        font-size: 26px;
        font-weight: bold;
        color: #4CAF50;
        margin-bottom: 25px;
    }
    .table-wrapper {
        width: 100%;
        overflow-x: auto; /*  allows horizontal scroll if needed */
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    table th, table td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
        text-align: center;
    }
    table th {
        background-color: #4CAF50;
        color: white;
    }
    .book-img {
        height: 80px;
        max-width: 100px;
    }
    .total-amount {
        text-align: right;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 20px;
    }
    .razorpay-payment-button {
        background: linear-gradient(135deg, #6a11cb, #2575fc) !important;
        color: #fff !important;
        font-size: 20px !important;
        font-weight: bold !important;
        padding: 16px 40px !important;
        border-radius: 50px !important;
        border: none !important;
        cursor: pointer !important;
        box-shadow: 0 6px 20px rgba(0,0,0,0.2) !important;
        transition: all 0.3s ease !important;
    }
    .razorpay-payment-button:hover {
        background: linear-gradient(135deg, #2575fc, #6a11cb) !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.3) !important;
    }

    /*  Mobile Fixes */
    @media (max-width: 768px) {
        .checkout-container {
            padding: 15px;
        }
        table, thead, tbody, th, td, tr {
            display: block; /*  makes table responsive */
        }
        table tr {
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
        }
        table td {
            text-align: left;
            padding: 8px 10px;
            position: relative;
        }
        table td:before {
            content: attr(data-label);
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #4CAF50;
        }
        table th {
            display: none; /* hide table headers on mobile */
        }
        .book-img {
            height: 60px;
        }
        .total-amount {
            text-align: center;
            font-size: 18px;
        }
    }
</style>

<div class="checkout-container">
    <div class="checkout-header">Checkout</div>

    <div class="table-wrapper">
        <table>
            <tr>
                <th>Book</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            <?php foreach($cart_items as $item): ?>
                <tr>
                    <td data-label="Book">
                        <?php echo htmlspecialchars($item['title']); ?><br>
                        <img src="assets/images/<?php echo htmlspecialchars($item['image']); ?>" class="book-img" alt="<?php echo htmlspecialchars($item['title']); ?>">
                    </td>
                    <td data-label="Price">₹<?php echo number_format($item['price'],2); ?></td>
                    <td data-label="Quantity"><?php echo $item['quantity']; ?></td>
                    <td data-label="Total">₹<?php echo number_format($item['price'] * $item['quantity'],2); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="total-amount">
        Total Amount: ₹<?php echo number_format($total_amount,2); ?>
    </div>

    <div style="text-align:center;">
        <form action="payment_success.php" method="POST">
            <script
                src="https://checkout.razorpay.com/v1/checkout.js"
                data-key="<?php echo $razorpay_key; ?>"
                data-amount="<?php echo $total_amount * 100; ?>" 
                data-currency="INR"
                data-buttontext="Pay Now"
                data-name="Miniature Store"
                data-description="Book Purchase"
                data-prefill.name="<?php echo htmlspecialchars($_SESSION['user_name']); ?>"
                data-prefill.email="<?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>"
                data-theme.color="#4CAF50"
                class="razorpay-payment-button">
            </script>
            <input type="hidden" value="Hidden Element" name="hidden">
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
