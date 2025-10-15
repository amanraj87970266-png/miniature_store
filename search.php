<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';

// Get the search query from the URL
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

// Initialize results array
$search_results = [];

// If query is not empty, search in books table
if (!empty($query)) {
    $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE :query OR description LIKE :query LIMIT 20");
    $stmt->execute(['query' => "%$query%"]);
    $search_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Search Results - Miniature Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="shortcut icon" href="assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-cover bg-center min-h-screen" style="background-image: url('assets/images/background.png');">

<!-- Page Heading -->
<section class="max-w-7xl mx-auto py-16 text-center">
    <h1 class="text-5xl font-extrabold text-green-600 mb-6">Search Results</h1>
    <p class="text-2xl font-semibold text-white bg-green-600 inline-block px-6 py-3 rounded-lg shadow-lg">
        You searched for: 
        <span class="italic text-yellow-300"><?php echo htmlspecialchars($query); ?></span>
    </p>
</section>

<!-- Results -->
<section class="max-w-7xl mx-auto py-8 px-6 flex flex-wrap justify-center gap-12">
    <?php if (!empty($query) && $search_results): ?>
        <?php foreach($search_results as $book): ?>
            <div class="bg-white rounded-2xl shadow-2xl hover:shadow-3xl transition transform hover:-translate-y-2 p-8 text-center w-full sm:w-96">
                <img src="assets/images/<?php echo htmlspecialchars($book['image']); ?>" 
                     alt="<?php echo htmlspecialchars($book['title']); ?>" 
                     class="w-full h-80 object-cover rounded-xl mx-auto">
                <h3 class="mt-6 font-bold text-3xl text-gray-800"><?php echo htmlspecialchars($book['title']); ?></h3>
                <p class="text-gray-600 text-lg mt-3"><?php echo htmlspecialchars($book['description']); ?></p>
                <div class="text-green-600 font-extrabold text-2xl mt-5">₹<?php echo number_format($book['price'], 2); ?></div>
                <a href="product.php?id=<?php echo $book['id']; ?>" 
                   class="block mt-6 bg-green-600 text-white text-center py-4 rounded-xl text-xl font-semibold hover:bg-green-700 transition">
                   View Details
                </a>
            </div>
        <?php endforeach; ?>
    <?php elseif (!empty($query)): ?>
        <p class="text-center text-red-500 font-bold text-2xl w-full">No books found matching your search.</p>
    <?php else: ?>
        <p class="text-center text-gray-500 text-2xl w-full">Please enter a search query.</p>
    <?php endif; ?>
</section>


<script>
    feather.replace();
</script>
</body>
</html>
<!-- Footer -->
<?php include 'includes/footer.php'; ?>
