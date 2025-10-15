<?php
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT name, email, password FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$success_msg = '';
$error_msg = '';

// Handle Edit Account submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['edit_account'])) {
        $new_name = trim($_POST['name']);
        $new_email = trim($_POST['email']);

        if ($new_name && $new_email) {
            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $stmt->execute([$new_name, $new_email, $user_id]);
            $_SESSION['user_name'] = $new_name; // Update session
            $success_msg = "Account updated successfully!";
            $user['name'] = $new_name;
            $user['email'] = $new_email;
        }
    }

    // Handle Change Password submission
    if (isset($_POST['change_password'])) {
        $current_pass = $_POST['current_password'] ?? '';
        $new_pass = $_POST['new_password'] ?? '';
        $confirm_pass = $_POST['confirm_password'] ?? '';

        if (!$current_pass || !$new_pass || !$confirm_pass) {
            $error_msg = "All password fields are required!";
        } elseif (!password_verify($current_pass, $user['password'])) {
            $error_msg = "Current password is incorrect!";
        } elseif ($new_pass !== $confirm_pass) {
            $error_msg = "New password and confirm password do not match!";
        } else {
            $new_hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$new_hashed, $user_id]);
            $success_msg = "Password changed successfully!";
        }
    }

    // Handle Delete Account
    if (isset($_POST['delete_account'])) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        session_destroy();
        header('Location: index.php');
        exit();
    }
}
?>

<style>
.account-container {
    max-width: 1000px;
    margin: 30px auto;
    padding: 50px;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
}
.account-header {
    text-align: center;
    font-size: 26px;
    font-weight: bold;
    color: #4CAF50;
    margin-bottom: 25px;
}
.user-info {
    margin-bottom: 20px;
    font-size: 25px;
    font-weight: bold;
}
.user-info p {
    margin: 8px 0;
    font-weight: bold;
}
.links, .account-actions, .password-section {
    margin-bottom: 25px;
    text-align: center;
}
.links a, .account-actions button {
    display: inline-block;
    padding: 10px 20px;
    margin: 8px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    border: none;
    border-radius: 10px;
    font-weight: bold;
    font-size: 18px;
    cursor: pointer;
    transition: background 0.3s ease;
}
.links a:hover, .account-actions button:hover {
    background-color: #45a049;
}
.edit-form input, .password-form input {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 20px;
    font-weight: bold;
}
.success-msg {
    color: green;
    font-weight: bold;
    margin-bottom: 15px;
    text-align: center;
}
.error-msg {
    color: red;
    font-weight: bold;
    margin-bottom: 15px;
    text-align: center;
}

/*  Mobile Responsive Fixes */
@media (max-width: 768px) {
    .account-container {
        padding: 30px 20px;
    }
    .account-header {
        font-size: 22px;
    }
    .user-info {
        font-size: 20px;
    }
    .edit-form input, .password-form input {
        font-size: 18px;
        padding: 8px;
    }
    .links a, .account-actions button {
        font-size: 16px;
        padding: 10px 15px;
        margin: 5px 0;
        width: 100%;
        box-sizing: border-box;
    }
}
</style>

<div class="account-container">
    <div class="account-header">My Account</div>

    <?php if($success_msg): ?>
        <div class="success-msg"><?php echo $success_msg; ?></div>
    <?php endif; ?>
    <?php if($error_msg): ?>
        <div class="error-msg"><?php echo $error_msg; ?></div>
    <?php endif; ?>

    <div class="user-info">
        <p>Name: <?php echo htmlspecialchars($user['name']); ?></p>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
    </div>

    <div class="account-actions">
        <!-- Edit Account Form -->
        <form class="edit-form" method="POST">
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required placeholder="Your Name">
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required placeholder="Your Email">
            <button type="submit" name="edit_account">Update Account</button>
        </form>

        <!-- Change Password Form -->
        <form class="password-form" method="POST">
            <input type="password" name="current_password" placeholder="Current Password" required>
            <input type="password" name="new_password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
            <button type="submit" name="change_password">Change Password</button>
        </form>

        <!-- Delete Account -->
        <form method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
            <button type="submit" name="delete_account" style="background-color: red; font-weight: bold; font-size: 18px;">Delete Account</button>
        </form>
    </div>

    <div class="links">
        <a href="cart.php">View Cart</a>
        <a href="purchased.php">Purchased Books</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
