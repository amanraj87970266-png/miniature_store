<?php
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$category_id = intval($_GET['id']);

// Fetch category name
$cat_stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
$cat_stmt->execute([$category_id]);
$category = $cat_stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    echo "<div style='text-align:center; padding:20px;'>Category not found.</div>";
    include 'includes/footer.php';
    exit();
}

// Fetch books in this category using category_id
$book_stmt = $conn->prepare("SELECT * FROM books WHERE category_id = ?");
$book_stmt->execute([$category_id]);
$books = $book_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: url('assets/images/background.png') no-repeat center center fixed;
        background-size: cover;
        margin: 0;
        padding: 0;
        color: #333;
    }
    .container {
        max-width: 1200px;
        margin: 40px auto;
        background: rgba(255,255,255,0.95);
        padding: 40px;
        border-radius: 20px;
    }
    h2 {
        color: #4CAF50;
        text-align: center;
        margin-bottom: 30px;
    }
    .books-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }
    .book-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 0 8px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    .book-card:hover {
        transform: translateY(5px);
    }
    .book-card img {
        width: 100%;
        border-radius: 5px;
        height: 200px;
        object-fit: cover;
    }
    .book-card h3 {
        margin: 10px 0 5px 0;
    }
    .book-card p {
        font-size: 18px;
        font-weight: 300;
        color: #666;
        min-height: 50px;
    }
    .book-card .price {
        color: #4CAF50;
        font-size: 20px;
        font-weight: bold;
        margin-top: 10px;
    }
    .book-card a {
        text-decoration: none;
        display: inline-block;
        margin-top: 10px;
        padding: 8px 12px;
        background-color: #4CAF50;
        color: white;
        border-radius: 5px;
        font-size: 20px;
    }
    .book-card a:hover {
        background-color: #45a049;
    }
</style>

<div class="container">
    <h2><?php echo htmlspecialchars($category['name']); ?> Books</h2>

    <?php if ($books): ?>
        <div class="books-grid">
            <?php foreach($books as $book): ?>
                <div class="book-card">
                    <img src="assets/images/<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                    <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                    <p><?php echo htmlspecialchars($book['description']); ?></p>
                    <div class="price">₹<?php echo number_format($book['price'], 2); ?></div>
                    <a href="product.php?id=<?php echo $book['id']; ?>">View Details</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p style="text-align:center; font-weight:bold; font-size:25px; color:red;">No books found in this category.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
