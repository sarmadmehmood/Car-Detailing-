<?php
/**
 * Admin Login - Crysta Auto Care
 * Simple login with hardcoded credentials
 */
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

// Hardcoded credentials
$admin_username = 'admin';
$admin_password = 'crysta2024';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Crysta Auto Care</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; } .font-playfair { font-family: 'Playfair Display', serif; }</style>
</head>
<body class="bg-black text-white min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md px-4">
        <!-- Logo -->
        <div class="text-center mb-10">
            <img src="../includes/logo.png" alt="Crysta Auto Care" class="h-16 sm:h-20 w-auto mx-auto mb-4 object-contain drop-shadow-[0_0_10px_rgba(251,191,36,0.3)]">
            <p class="text-xs tracking-[0.3em] text-gray-500 uppercase mt-1">Admin Panel</p>
        </div>

        <?php if ($error): ?>
        <div class="mb-6 p-4 border border-red-800 bg-red-900/20 rounded-lg">
            <p class="text-red-400 text-sm text-center"><?php echo $error; ?></p>
        </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" class="space-y-6 border border-gray-800 rounded-xl p-8" style="background: rgba(255,255,255,0.03);">
            <div>
                <label for="username" class="block text-sm font-semibold tracking-wider uppercase mb-2">Username</label>
                <input type="text" id="username" name="username" required
                       class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-white focus:outline-none transition-colors placeholder-gray-600"
                       placeholder="Enter username" autocomplete="username">
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold tracking-wider uppercase mb-2">Password</label>
                <input type="password" id="password" name="password" required
                       class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-white focus:outline-none transition-colors placeholder-gray-600"
                       placeholder="Enter password" autocomplete="current-password">
            </div>

            <button type="submit" class="w-full py-4 bg-amber-400 text-black font-semibold text-sm tracking-wider uppercase hover:bg-amber-300 transition-all duration-300 rounded-lg">
                Sign In
            </button>
        </form>

        <p class="text-center mt-8">
            <a href="../index.php" class="text-xs text-gray-500 hover:text-white transition-colors tracking-wider uppercase">← Back to Website</a>
        </p>
    </div>
</body>
</html>
