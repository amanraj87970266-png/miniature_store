<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';

// Fetch featured books
$stmt = $conn->prepare("SELECT * FROM books WHERE is_featured = 1 LIMIT 6");
$stmt->execute();
$featured_books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all categories with count of books using category_id
$stmt = $conn->prepare("
    SELECT c.id, c.name, COUNT(b.id) as book_count 
    FROM categories c 
    LEFT JOIN books b ON b.category_id = c.id
    GROUP BY c.id, c.name
");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch settings for contact info
$stmt = $conn->prepare("SELECT * FROM tbl_settings WHERE id = 1");
$stmt->execute();
$settings = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Miniature Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="keywords" content="online bookstore, buy books, novels, love stories, life stories, miniature store">
    <meta name="author" content="Aman Raj">
    <meta name="description" content="Miniature Store - Your one-stop online bookstore for a wide range of e-books including novels, love stories, and life stories. Discover your next favorite read today!" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="shortcut icon" href="assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-cover bg-center min-h-screen" style="background-image: url('assets/images/background.png');">

<!-- Navbar: only for Featured Books -->
<div class="bg-white shadow-lg p-6 flex justify-between items-center">
    <div class="flex flex-wrap gap-4 justify-end">
        <img src="assets/images/logo.png" alt="Logo" class="h-16 w-16">
        <span class="text-3xl font-bold text-green-600">Miniature Store</span>
    </div>
    <div class="flex flex-wrap gap-4 justify-end">
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="cart.php" class="px-5 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition font-semibold text-lg">Cart</a>
            <a href="purchased.php" class="px-5 py-2 bg-purple-600 text-white rounded-lg shadow hover:bg-purple-700 transition font-semibold text-lg">My e-Books</a> 
            <a href="account.php" class="px-5 py-2 bg-gray-100 text-green-600 rounded-lg shadow hover:bg-gray-200 transition font-semibold text-lg">Account</a>
            <a href="logout.php" class="px-5 py-2 bg-red-600 text-white rounded-lg shadow hover:bg-red-700 transition font-semibold text-lg">Logout</a>
        <?php else: ?>
            <a href="login.php" class="px-5 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition font-semibold text-lg">Login</a>
            <a href="register.php" class="px-5 py-2 bg-gray-100 text-green-600 rounded-lg shadow hover:bg-gray-200 transition font-semibold text-lg">Register</a>
        <?php endif; ?>
    </div>
</div>

<!-- Featured Books Section -->
<section class="max-w-7xl mx-auto py-12">
    <h2 class="text-4xl font-bold text-center text-green-600 mb-8">Featured Books</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 px-6">
        <?php if($featured_books): ?>
            <?php foreach($featured_books as $book): ?>
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition p-6">
                    <img src="assets/images/<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" class="w-full h-64 object-cover rounded-lg">
                    <h3 class="mt-5 font-semibold text-2xl"><?php echo htmlspecialchars($book['title']); ?></h3>
                    <p class="text-gray-600 text-base mt-2"><?php echo htmlspecialchars($book['description']); ?></p>
                    <div class="text-green-600 font-bold text-xl mt-4">₹<?php echo number_format($book['price'], 2); ?></div>
                    <a href="product.php?id=<?php echo $book['id']; ?>" class="block mt-5 bg-green-600 text-white text-center py-3 rounded-lg text-lg hover:bg-green-700 transition">View Details</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-gray-500 text-xl">No featured books available right now.</p>
        <?php endif; ?>
    </div>
</section>

<?php if(isset($_SESSION['user_id'])): ?>
<!-- Our Books Section -->
<section class="max-w-7xl mx-auto py-12">
    <h2 class="text-4xl font-bold text-center text-green-600 mb-8">Our Books</h2>

    <!-- Search Bar -->
    <div class="flex justify-center mb-8">
        <form method="get" action="search.php" class="flex border-2 border-green-600 rounded-lg overflow-hidden w-full max-w-2xl shadow-md">
            <input type="text" name="query" placeholder="Search books..." class="flex-grow px-6 py-4 text-lg outline-none">
            <button type="submit" class="bg-green-600 text-white px-6 py-4 text-lg hover:bg-green-700 transition">Search</button>
        </form>
    </div>

    <!-- Categories -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6 px-6">
        <?php foreach($categories as $category): ?>
            <a href="category.php?id=<?php echo $category['id']; ?>" class="bg-white text-center p-6 rounded-xl shadow hover:shadow-md transition transform hover:-translate-y-1">
                <h3 class="font-semibold text-xl"><?php echo htmlspecialchars($category['name']); ?></h3>
                <p class="text-gray-500 text-base mt-2"><?php echo $category['book_count']; ?> books</p>
            </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Footer -->
<footer class="bg-black text-white py-8 mt-12">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <img src="assets/images/logo.png" alt="Logo" class="mx-auto h-24 w-auto mb-4">
        <p class="text-xl font-semibold mb-2">Miniature Store</p>
        <p class="text-gray-400 text-sm">© <?php echo date('Y'); ?> Miniature Store. All rights reserved.</p>
        <?php if(!empty($settings['contact_info'])): ?>
            <p class="text-gray-400 text-sm mt-1"><?php echo nl2br(htmlspecialchars($settings['contact_info'])); ?></p>
        <?php endif; ?>
    </div>
</footer>

<script>
    feather.replace();
</script>
</body>
</html>
