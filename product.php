<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';

// Validate product ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p style='text-align:center; color:red;'>Invalid product ID.</p>";
    include 'includes/footer.php';
    exit();
}

$id = intval($_GET['id']);

// Fetch book from database
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    echo "<p style='text-align:center; color:red;'>Book not found.</p>";
    include 'includes/footer.php';
    exit();
}

// Check if user has purchased this book
$hasPurchased = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $check = $conn->prepare("SELECT * FROM orders WHERE user_id = ? AND book_id = ? AND status = 'Paid'");
    $check->execute([$user_id, $id]);
    if ($check->rowCount() > 0) {
        $hasPurchased = true;
    }
}
?>

<style>
.product-container {
    max-width: 1200px;
    margin: 30px auto;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}
.product-image {
    flex: 1;
    min-width: 250px;
}
.product-image img {
    width: 100%;
    border-radius: 10px;
}
.product-details {
    flex: 2;
    min-width: 400px;
}
.product-details h2 {
    font-size: 40px;
    color: #4CAF50;
    margin-bottom: 10px;
}
.product-details p {
    font-size: 25px;
    margin: 20px 0;
    color: #555;
}
.product-details .price {
    font-size: 22px;
    font-weight: bold;
    color: #4CAF50;
    margin-top: 10px;
}
.product-details .buttons {
    margin-top: 30px;
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}
.product-details .buttons button,
.product-details .buttons a {
    background-color: #4CAF50;
    border: none;
    padding: 12px 25px;
    color: white;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s ease;
    font-size: 20px;
    text-decoration: none;
    display: inline-block;
}
.product-details .buttons button:hover,
.product-details .buttons a:hover {
    background-color: #45a049;
}
.quantity-input {
    width: 60px;
    padding: 10px;
    font-size: 20px;
    margin-right: 10px;
    border-radius: 10px;
    border: 1px solid #ccc;
}

/* Mobile fixes */
@media (max-width: 768px) {
    .product-container {
        flex-direction: column;
        padding: 20px;
    }
    .product-details {
        min-width: 100%; /*  prevent overflow */
    }
    .product-details h2 {
        font-size: 28px; /* slightly smaller for mobile */
        word-wrap: break-word; /*  break long titles */
    }
    .product-details p {
        font-size: 18px;
        word-wrap: break-word;
    }
    .product-details .buttons {
        flex-direction: column;
        align-items: stretch;
    }
    .product-details .buttons button,
    .product-details .buttons a {
        width: 100%; /* full width buttons on mobile */
        text-align: center;
    }
}
</style>

<div class="product-container">
    <div class="product-image">
        <img src="assets/images/<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
    </div>
    <div class="product-details">
        <h2><?php echo htmlspecialchars($book['title']); ?></h2>
        <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
        <p><?php echo htmlspecialchars($book['description']); ?></p>
        <div class="price">₹<?php echo number_format($book['price'], 2); ?></div>
        
        <div class="buttons">
            <?php if ($hasPurchased): ?>
                <!-- If purchased → show manuscript access -->
                <a href="purchased.php?id=<?php echo $book['id']; ?>">📖 Read Manuscript</a>
            <?php else: ?>
                <!-- Add to Cart Form -->
                <form method="POST" action="add_to_cart.php" style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                    <input type="number" name="quantity" class="quantity-input" value="1" min="1">
                    <button type="submit">Add to Cart</button>
                </form>
                
                <!-- Buy Now Form (updated) -->
                <form method="POST" action="buy_now.php" style="display:flex; align-items:center; gap:10px;">
                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit">Buy Now</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
