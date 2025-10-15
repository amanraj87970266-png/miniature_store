<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid book ID.");
}

$book_id = (int)$_GET['id'];
$download = isset($_GET['download']) ? true : false;

// Check if user purchased this book
$stmt = $conn->prepare("SELECT b.title, b.manuscript, b.image 
                        FROM orders o 
                        JOIN books b ON o.book_id = b.id 
                        WHERE o.user_id = ? AND o.book_id = ?");
$stmt->execute([$user_id, $book_id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    die("You do not have access to this book.");
}

$manuscript_path = 'assets/manuscripts/' . $book['manuscript'];

if (!file_exists($manuscript_path)) {
    die("Manuscript file not found.");
}

// Handle download request
if ($download) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($manuscript_path) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($manuscript_path));
    readfile($manuscript_path);
    exit();
}

// Determine file type
$file_ext = strtolower(pathinfo($manuscript_path, PATHINFO_EXTENSION));
$can_view_inline = $file_ext === 'pdf';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Read your favorite books online at Miniature Store. Explore novels, love stories, and life stories.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['title']); ?> - Read</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f7fa;
        }
        header {
            background: #4CAF50;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        header img {
            height: 80px;
        }
        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        iframe {
            width: 100%;
            height: 80vh;
            border: none;
        }
        .btn-back, .btn-download {
            display: inline-block;
            margin-bottom: 15px;
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }
        .btn-back {
            background: #2196F3;
            color: #fff;
        }
        .btn-back:hover { background: #1976D2; }
        .btn-download {
            background: #4CAF50;
            color: #fff;
            margin-left: 10px;
        }
        .btn-download:hover { background: #388E3C; }
    </style>
</head>
<body>
<header>
    <img src="assets/images/logo.png" alt="Miniature Store Logo">
</header>

<div class="container">
    <a href="purchased.php" class="btn-back">⬅ Back to Purchased Books</a>
    <a href="read.php?id=<?php echo $book_id; ?>&download=1" class="btn-download">⬇ Download</a>
    <h2><?php echo htmlspecialchars($book['title']); ?></h2>

    <?php if ($can_view_inline): ?>
        <!-- Display PDF inline -->
        <iframe src="<?php echo $manuscript_path; ?>"></iframe>
    <?php else: ?>
        <!-- Word documents cannot be displayed inline -->
        <p style="font-size:16px; color:#555;">
            This manuscript is a Word document. Click the download button to open it in Word or Google Docs.
        </p>
    <?php endif; ?>
</div>
</body>
</html>
