<?php
/**
 * Admin Dashboard - Crysta Auto Care
 * Overview of bookings, gallery, and blog stats
 */
require_once 'auth.php';
require_once '../includes/db.php';

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Get stats
$total_bookings = $conn->query("SELECT COUNT(*) as c FROM bookings")->fetch_assoc()['c'];
$pending_bookings = $conn->query("SELECT COUNT(*) as c FROM bookings WHERE status = 'pending'")->fetch_assoc()['c'];
$approved_bookings = $conn->query("SELECT COUNT(*) as c FROM bookings WHERE status = 'approved'")->fetch_assoc()['c'];
$total_videos = $conn->query("SELECT COUNT(*) as c FROM gallery")->fetch_assoc()['c'];
$total_blogs = $conn->query("SELECT COUNT(*) as c FROM blogs")->fetch_assoc()['c'];

// Recent bookings
$recent_bookings = $conn->query("SELECT * FROM bookings ORDER BY created_at DESC LIMIT 5");

$current_admin_page = 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Crysta Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-black text-white min-h-screen">
    <!-- Admin Navigation -->
    <nav class="border-b border-gray-800 bg-black/95 sticky top-0 z-50" style="backdrop-filter: blur(10px);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="dashboard.php" class="flex items-center space-x-2">
                        <img src="../includes/logo.png" alt="Crysta" class="h-8 w-auto object-contain">
                        <span class="text-sm font-bold tracking-wider">CRYSTA ADMIN</span>
                    </a>
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="dashboard.php" class="text-xs tracking-wider uppercase <?php echo $current_admin_page === 'dashboard' ? 'text-amber-400 border-b border-amber-400 pb-0.5' : 'text-gray-400 hover:text-amber-400'; ?> transition-colors">Dashboard</a>
                        <a href="bookings.php" class="text-xs tracking-wider uppercase <?php echo $current_admin_page === 'bookings' ? 'text-amber-400 border-b border-amber-400 pb-0.5' : 'text-gray-400 hover:text-amber-400'; ?> transition-colors">Bookings</a>
                        <a href="upload-video.php" class="text-xs tracking-wider uppercase <?php echo $current_admin_page === 'gallery' ? 'text-amber-400 border-b border-amber-400 pb-0.5' : 'text-gray-400 hover:text-amber-400'; ?> transition-colors">Gallery</a>
                        <a href="add-blog.php" class="text-xs tracking-wider uppercase <?php echo $current_admin_page === 'blog' ? 'text-amber-400 border-b border-amber-400 pb-0.5' : 'text-gray-400 hover:text-amber-400'; ?> transition-colors">Blog</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="../index.php" target="_blank" class="hidden sm:inline text-xs text-gray-400 hover:text-white transition-colors">View Site →</a>
                    <a href="dashboard.php?logout=1" class="text-xs text-red-400 hover:text-red-300 transition-colors">Logout</a>
                    <button onclick="document.getElementById('admin-mobile-menu').classList.toggle('hidden')" class="md:hidden p-2 text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
        </div>
        <div id="admin-mobile-menu" class="hidden md:hidden border-t border-gray-800 bg-black">
            <div class="px-4 py-3 space-y-1">
                <a href="dashboard.php" class="block text-sm py-2 px-3 rounded <?php echo $current_admin_page === 'dashboard' ? 'text-amber-400 bg-amber-400/10' : 'text-gray-400 hover:bg-gray-900'; ?>">Dashboard</a>
                <a href="bookings.php" class="block text-sm py-2 px-3 rounded <?php echo $current_admin_page === 'bookings' ? 'text-amber-400 bg-amber-400/10' : 'text-gray-400 hover:bg-gray-900'; ?>">Bookings</a>
                <a href="upload-video.php" class="block text-sm py-2 px-3 rounded <?php echo $current_admin_page === 'gallery' ? 'text-amber-400 bg-amber-400/10' : 'text-gray-400 hover:bg-gray-900'; ?>">Gallery</a>
                <a href="add-blog.php" class="block text-sm py-2 px-3 rounded <?php echo $current_admin_page === 'blog' ? 'text-amber-400 bg-amber-400/10' : 'text-gray-400 hover:bg-gray-900'; ?>">Blog</a>
                <a href="../index.php" target="_blank" class="block text-sm py-2 px-3 rounded text-gray-400 hover:bg-gray-900">View Site →</a>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="mb-10">
            <h1 class="text-3xl font-bold mb-2">Dashboard</h1>
            <p class="text-gray-400 text-sm">Welcome back. Here's an overview of your business.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-12">
            <div class="border border-gray-800 rounded-xl p-6 hover:border-gray-600 transition-colors">
                <p class="text-3xl font-bold mb-1"><?php echo $total_bookings; ?></p>
                <p class="text-xs text-gray-500 tracking-wider uppercase">Total Bookings</p>
            </div>
            <div class="border border-yellow-800/50 rounded-xl p-6 bg-yellow-900/10">
                <p class="text-3xl font-bold text-yellow-400 mb-1"><?php echo $pending_bookings; ?></p>
                <p class="text-xs text-gray-500 tracking-wider uppercase">Pending</p>
            </div>
            <div class="border border-green-800/50 rounded-xl p-6 bg-green-900/10">
                <p class="text-3xl font-bold text-green-400 mb-1"><?php echo $approved_bookings; ?></p>
                <p class="text-xs text-gray-500 tracking-wider uppercase">Approved</p>
            </div>
            <div class="border border-gray-800 rounded-xl p-6 hover:border-gray-600 transition-colors">
                <p class="text-3xl font-bold mb-1"><?php echo $total_videos; ?></p>
                <p class="text-xs text-gray-500 tracking-wider uppercase">Videos</p>
            </div>
            <div class="border border-gray-800 rounded-xl p-6 hover:border-gray-600 transition-colors">
                <p class="text-3xl font-bold mb-1"><?php echo $total_blogs; ?></p>
                <p class="text-xs text-gray-500 tracking-wider uppercase">Blog Posts</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-12">
            <a href="bookings.php" class="flex items-center space-x-4 p-5 border border-gray-800 rounded-xl hover:border-amber-400/50 transition-all duration-300 group">
                <div class="w-12 h-12 border border-gray-700 rounded-full flex items-center justify-center group-hover:bg-amber-400 group-hover:text-black transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-sm">Manage Bookings</p>
                    <p class="text-xs text-gray-500">Approve or reject requests</p>
                </div>
            </a>
            <a href="upload-video.php" class="flex items-center space-x-4 p-5 border border-gray-800 rounded-xl hover:border-amber-400/50 transition-all duration-300 group">
                <div class="w-12 h-12 border border-gray-700 rounded-full flex items-center justify-center group-hover:bg-amber-400 group-hover:text-black transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-sm">Upload Video</p>
                    <p class="text-xs text-gray-500">Add to gallery</p>
                </div>
            </a>
            <a href="add-blog.php" class="flex items-center space-x-4 p-5 border border-gray-800 rounded-xl hover:border-amber-400/50 transition-all duration-300 group">
                <div class="w-12 h-12 border border-gray-700 rounded-full flex items-center justify-center group-hover:bg-amber-400 group-hover:text-black transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-sm">Write Blog Post</p>
                    <p class="text-xs text-gray-500">Create new article</p>
                </div>
            </a>
        </div>

        <!-- Recent Bookings -->
        <div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold">Recent Bookings</h2>
                <a href="bookings.php" class="text-xs text-gray-400 hover:text-white transition-colors tracking-wider uppercase">View All →</a>
            </div>

            <?php if ($recent_bookings && $recent_bookings->num_rows > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-800">
                            <th class="text-left text-xs tracking-wider uppercase text-gray-500 py-3 px-4">Customer</th>
                            <th class="text-left text-xs tracking-wider uppercase text-gray-500 py-3 px-4">Service</th>
                            <th class="text-left text-xs tracking-wider uppercase text-gray-500 py-3 px-4">Date</th>
                            <th class="text-left text-xs tracking-wider uppercase text-gray-500 py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($booking = $recent_bookings->fetch_assoc()): ?>
                        <tr class="border-b border-gray-800/50 hover:bg-gray-900/50 transition-colors">
                            <td class="py-4 px-4">
                                <p class="text-sm font-semibold"><?php echo htmlspecialchars($booking['name']); ?></p>
                                <p class="text-xs text-gray-500"><?php echo htmlspecialchars($booking['phone']); ?></p>
                            </td>
                            <td class="py-4 px-4 text-sm text-gray-300"><?php echo htmlspecialchars($booking['service']); ?></td>
                            <td class="py-4 px-4 text-sm text-gray-300"><?php echo date('M j, Y', strtotime($booking['booking_date'])); ?></td>
                            <td class="py-4 px-4">
                                <?php
                                $status_colors = [
                                    'pending' => 'text-yellow-400 bg-yellow-900/20 border-yellow-800/50',
                                    'approved' => 'text-green-400 bg-green-900/20 border-green-800/50',
                                    'rejected' => 'text-red-400 bg-red-900/20 border-red-800/50',
                                ];
                                $color = $status_colors[$booking['status']] ?? 'text-gray-400 bg-gray-900/20 border-gray-800';
                                ?>
                                <span class="text-xs px-3 py-1 rounded-full border <?php echo $color; ?> capitalize"><?php echo $booking['status']; ?></span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-12 border border-gray-800 rounded-xl">
                <p class="text-gray-500 text-sm">No bookings yet.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
