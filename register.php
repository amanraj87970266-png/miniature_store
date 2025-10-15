<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'includes/db.php';
session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $address = trim($_POST['address']);

    if (empty($name) || empty($email) || empty($password) || empty($address)) {
        $error = "Please fill all the fields.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $error = "Email already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, address) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$name, $email, $hashed_password, $address])) {
                header('Location: login.php');
                exit();
            } else {
                $error = "Something went wrong. Try again.";
            }
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
.register-container {
    max-width: 600px;
    margin: 50px auto;
    background: #fff;
    padding: 40px 50px;
    border-radius: 25px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
.register-container h2 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 28px;
    color: #333;
}
.register-container label {
    font-size: 20px;
    color: #555;
}
.register-container input, 
.register-container textarea {
    width: 100%;
    padding: 12px 15px;
    margin: 8px 0 20px 0;
    border: 1px solid #533535ff;
    border-radius: 5px;
    font-size: 16px;
    box-sizing: border-box;
}
.register-container button {
    width: 100%;
    padding: 12px;
    background-color: #6126addf;
    color: white;
    font-size: 18px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s ease;
}
.register-container button:hover {
    background-color: #521ea6ff;
}
.message {
    text-align: center;
    margin-bottom: 20px;
    font-size: 16px;
}
.message.error {
    color: red;
}
.message.success {
    color: green;
}

/* Show/hide password toggle */
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
    font-size: 16px;
    color: #333;
}

/*  Mobile responsive */
@media (max-width: 768px) {
    .register-container {
        padding: 30px 20px;
        margin: 20px 10px;
    }
    .register-container h2 {
        font-size: 24px;
    }
    .register-container label {
        font-size: 16px;
    }
    .register-container input, 
    .register-container textarea {
        font-size: 14px;
        padding: 10px;
    }
    .register-container button {
        font-size: 16px;
        padding: 10px;
    }
}
</style>

<div class="register-container">
    <h2>User Registration</h2>

    <?php if($error): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if($success): ?>
        <div class="message success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" action="register.php">
        <label>Name:</label>
        <input type="text" name="name" placeholder="Enter your name" required>

        <label>Email:</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <label>Password:</label>
        <div class="password-wrapper">
            <input type="password" name="password" id="password" placeholder="Enter password" required>
            <span class="toggle-password" onclick="togglePassword()">Show</span>
        </div>

        <label>Address:</label>
        <textarea name="address" rows="4" placeholder="Enter your address" required></textarea>

        <button type="submit">Register</button>
    </form>
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
