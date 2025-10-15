<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'includes/db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please fill all the fields.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header('Location: admin/index.php');
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
}

include 'includes/header.php';
?>

<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
}
.login-container {
    max-width: 600px;
    margin: 80px auto;
    background: #fff;
    padding: 40px 50px;
    border-radius: 20px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
.login-container h2 {
    text-align: center;
    margin-bottom: 25px;
    font-size: 28px;
    color: #333;
}
.login-container label {
    font-size: 18px;
    color: #555;
}
.login-container input {
    width: 100%;
    padding: 12px 15px;
    margin: 8px 0 20px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    box-sizing: border-box;
}
.login-container button {
    width: 100%;
    padding: 15px;
    background-color: #560d51ff;
    color: white;
    font-size: 18px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s ease;
}
.login-container button:hover {
    background-color: #3a0d55ff;
}
.message {
    text-align: center;
    margin-bottom: 20px;
    font-size: 16px;
}
.message.error {
    color: red;
}
.register-link {
    text-align: center;
    margin-top: 20px;
}
.register-link a {
    color: #2b115bff;
    text-decoration: none;
}

/* Show/hide password */
.password-wrapper {
    position: relative;
}
.password-wrapper input[type="password"],
.password-wrapper input[type="text"] {
    padding-right: 40px;
}
.toggle-password {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 14px;
    color: #333;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .login-container {
        padding: 30px 20px;
        margin: 40px 10px;
    }
    .login-container h2 {
        font-size: 24px;
    }
    .login-container label {
        font-size: 16px;
    }
    .login-container input {
        font-size: 14px;
        padding: 10px;
    }
    .login-container button {
        font-size: 16px;
        padding: 12px;
    }
}
</style>

<div class="login-container">
    <h2>User Login</h2>

    <?php if($error): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label>Email:</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <label>Password:</label>
        <div class="password-wrapper">
            <input type="password" name="password" id="password" placeholder="Enter password" required>
            <span class="toggle-password" onclick="togglePassword()">Show</span>
        </div>

        <button type="submit">Login</button>
    </form>

    <div class="register-link">
        Don't have an account? <a href="register.php">Register here</a>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleBtn = document.querySelector('.toggle-password');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleBtn.textContent = 'Hide';
    } else {
        passwordInput.type = 'password';
        toggleBtn.textContent = 'Show';
    }
}
</script>

<?php include 'includes/footer.php'; ?>
