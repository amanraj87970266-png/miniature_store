<?php   
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch purchased books for the logged-in user
$query = $conn->prepare("SELECT b.*, o.order_date 
                         FROM orders o 
                         JOIN books b ON o.book_id = b.id 
                         WHERE o.user_id = ? 
                         ORDER BY o.order_date DESC");
$query->execute([$user_id]);
$result = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Read your favorite books online at Miniature Store. Explore novels, love stories, and life stories.">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Purchased e-Books - Miniature Store</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f7fa;
      margin: 0;
      padding: 0;
    }
    header {
      background: #4CAF50;
      padding: 30px 45px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    header img {
      height: 80px;
    }
    .btn.home {
      background-color: #FF9800;
      color: white;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s ease;
      padding: 10px 20px;
      font-size: 16px;
    }
    .btn.home:hover {
      background-color: #F57C00;
    }
    .container {
      max-width: 1200px;
      margin: 30px auto;
      padding: 40px;
    }
    .container h1 {
      text-align: center;
      font-size: 32px;
      margin-bottom: 25px;
      color: #333;
    }
    .book-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
    }
    .book-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      overflow: hidden;
      transition: transform 0.2s ease;
      padding: 15px;
      text-align: center;
    }
    .book-card:hover {
      transform: translateY(-5px);
    }
    .book-card img {
      width: 100%;
      height: auto;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 15px;
    }
    .book-card h3 {
      font-size: 20px;
      color: #333;
      margin: 10px 0;
    }
    .book-card p {
      font-size: 14px;
      color: #555;
      margin: 5px 0;
    }
    .manuscript-links {
      margin-top: 12px;
      display: flex;
      justify-content: center;
      gap: 15px;
    }
    /* Enlarged Read and Download buttons */
    .btn.read, .btn.download {
      padding: 12px 25px;      /* Bigger padding */
      font-size: 16px;          /* Bigger font */
      font-weight: bold;
      border-radius: 6px;
      text-decoration: none;
      transition: background 0.3s ease;
    }
    .btn.read {
      background-color: #2196F3;
      color: white;
    }
    .btn.read:hover {
      background-color: #1976D2;
    }
    .btn.download {
      background-color: #4CAF50;
      color: white;
    }
    .btn.download:hover {
      background-color: #388E3C;
    }
    footer {
      margin-top: 40px;
      padding: 20px;
      text-align: center;
      background: #333;
      color: white;
    }
  </style>
</head>
<body>

<header>
  <img src="assets/images/logo.png" alt="Miniature Store Logo">
  <a href="index.php" class="btn home">🏠 Home</a>
</header>

<div class="container">
  <h1>Your Purchased e-Books</h1>
  <?php if ($result): ?>
    <div class="book-grid">
      <?php foreach ($result as $row): ?>
        <div class="book-card">
          <img src="assets/images/<?php echo htmlspecialchars($row['image']); ?>" 
               alt="<?php echo htmlspecialchars($row['title']); ?>">
          <h3><?php echo htmlspecialchars($row['title']); ?></h3>
          <p><strong>Author:</strong> <?php echo htmlspecialchars($row['author']); ?></p>
          <p><strong>Purchased on:</strong> <?php echo date("d M Y", strtotime($row['order_date'])); ?></p>
          <p><?php echo htmlspecialchars($row['description']); ?></p>

          <?php if (!empty($row['manuscript'])): ?>
            <div class="manuscript-links">
              <a href="read.php?id=<?php echo $row['id']; ?>" 
                 target="_blank" class="btn read">📖 Read</a>
              <a href="read.php?id=<?php echo $row['id']; ?>&download=1" 
                 class="btn download">⬇ Download</a>
            </div>
          <?php else: ?>
            <p><em>Manuscript not available</em></p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p style="text-align:center; font-size:20px; font-weight:bold; color:red;">
      You haven’t purchased any books yet.<br>
      <a href="index.php" class="btn download" style="margin-top:15px; display:inline-block;">Browse Books</a>
    </p>
  <?php endif; ?>
</div>
</body>
</html>
<?php include 'includes/footer.php'; ?>
