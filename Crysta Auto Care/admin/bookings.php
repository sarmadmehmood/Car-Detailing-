<?php
/**
 * Admin Bookings Management - Crysta Auto Care
 * View, approve, and reject booking requests
 */
require_once 'auth.php';
require_once '../includes/db.php';

$message = '';
$msg_type = '';

// Handle approve/reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['booking_id'])) {
    $booking_id = intval($_POST['booking_id']);
    $action = $_POST['action'];

    if (in_array($action, ['approved', 'rejected'])) {
        $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $action, $booking_id);
        if ($stmt->execute()) {
            $message = "Booking #$booking_id has been $action successfully.";
            $msg_type = $action === 'approved' ? 'green' : 'red';
        } else {
            $message = "Failed to update booking status.";
            $msg_type = 'red';
        }
        $stmt->close();
    }
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    // Delete payment proof file if exists
    $stmt = $conn->prepare("SELECT payment_proof FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    if ($result && $result['payment_proof']) {
        $file_path = '../uploads/payments/' . $result['payment_proof'];
        if (file_exists($file_path)) unlink($file_path);
    }
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "Booking #$delete_id has been deleted.";
        $msg_type = 'red';
    }
    $stmt->close();
}

// Filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$where = '';
if ($filter === 'pending') $where = "WHERE status = 'pending'";
elseif ($filter === 'approved') $where = "WHERE status = 'approved'";
elseif ($filter === 'rejected') $where = "WHERE status = 'rejected'";

$bookings = $conn->query("SELECT * FROM bookings $where ORDER BY created_at DESC");

$current_admin_page = 'bookings';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings | Crysta Admin</title>
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
                        <a href="dashboard.php" class="text-xs tracking-wider uppercase text-gray-400 hover:text-amber-400 transition-colors">Dashboard</a>
                        <a href="bookings.php" class="text-xs tracking-wider uppercase text-amber-400 border-b border-amber-400 pb-0.5">Bookings</a>
                        <a href="upload-video.php" class="text-xs tracking-wider uppercase text-gray-400 hover:text-amber-400 transition-colors">Gallery</a>
                        <a href="add-blog.php" class="text-xs tracking-wider uppercase text-gray-400 hover:text-amber-400 transition-colors">Blog</a>
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
                <a href="dashboard.php" class="block text-sm py-2 px-3 rounded text-gray-400 hover:bg-gray-900">Dashboard</a>
                <a href="bookings.php" class="block text-sm py-2 px-3 rounded text-amber-400 bg-amber-400/10">Bookings</a>
                <a href="upload-video.php" class="block text-sm py-2 px-3 rounded text-gray-400 hover:bg-gray-900">Gallery</a>
                <a href="add-blog.php" class="block text-sm py-2 px-3 rounded text-gray-400 hover:bg-gray-900">Blog</a>
                <a href="../index.php" target="_blank" class="block text-sm py-2 px-3 rounded text-gray-400 hover:bg-gray-900">View Site →</a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold mb-2">Bookings</h1>
                <p class="text-gray-400 text-sm">Manage customer booking requests.</p>
            </div>
        </div>

        <?php if ($message): ?>
        <div class="mb-6 p-4 border border-<?php echo $msg_type; ?>-800 bg-<?php echo $msg_type; ?>-900/20 rounded-lg">
            <p class="text-<?php echo $msg_type; ?>-400 text-sm"><?php echo $message; ?></p>
        </div>
        <?php endif; ?>

        <!-- Filters -->
        <div class="flex items-center space-x-3 mb-8 overflow-x-auto pb-2">
            <a href="bookings.php" class="text-xs tracking-wider uppercase px-4 py-2 rounded-full border <?php echo $filter === 'all' ? 'border-white text-white' : 'border-gray-700 text-gray-400 hover:border-gray-500'; ?> transition-colors whitespace-nowrap">All</a>
            <a href="bookings.php?filter=pending" class="text-xs tracking-wider uppercase px-4 py-2 rounded-full border <?php echo $filter === 'pending' ? 'border-yellow-500 text-yellow-400' : 'border-gray-700 text-gray-400 hover:border-gray-500'; ?> transition-colors whitespace-nowrap">Pending</a>
            <a href="bookings.php?filter=approved" class="text-xs tracking-wider uppercase px-4 py-2 rounded-full border <?php echo $filter === 'approved' ? 'border-green-500 text-green-400' : 'border-gray-700 text-gray-400 hover:border-gray-500'; ?> transition-colors whitespace-nowrap">Approved</a>
            <a href="bookings.php?filter=rejected" class="text-xs tracking-wider uppercase px-4 py-2 rounded-full border <?php echo $filter === 'rejected' ? 'border-red-500 text-red-400' : 'border-gray-700 text-gray-400 hover:border-gray-500'; ?> transition-colors whitespace-nowrap">Rejected</a>
        </div>

        <!-- Bookings Table -->
        <?php if ($bookings && $bookings->num_rows > 0): ?>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto border border-gray-800 rounded-xl">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-800 bg-gray-900/30">
                        <th class="text-left text-xs tracking-wider uppercase text-gray-500 py-4 px-4">#</th>
                        <th class="text-left text-xs tracking-wider uppercase text-gray-500 py-4 px-4">Customer</th>
                        <th class="text-left text-xs tracking-wider uppercase text-gray-500 py-4 px-4">Service</th>
                        <th class="text-left text-xs tracking-wider uppercase text-gray-500 py-4 px-4">Car</th>
                        <th class="text-left text-xs tracking-wider uppercase text-gray-500 py-4 px-4">Location</th>
                        <th class="text-left text-xs tracking-wider uppercase text-gray-500 py-4 px-4">Schedule</th>
                        <th class="text-left text-xs tracking-wider uppercase text-gray-500 py-4 px-4">Payment</th>
                        <th class="text-left text-xs tracking-wider uppercase text-gray-500 py-4 px-4">Status</th>
                        <th class="text-left text-xs tracking-wider uppercase text-gray-500 py-4 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($booking = $bookings->fetch_assoc()): ?>
                    <tr class="border-b border-gray-800/50 hover:bg-gray-900/50 transition-colors">
                        <td class="py-4 px-4 text-sm text-gray-500"><?php echo $booking['id']; ?></td>
                        <td class="py-4 px-4">
                            <p class="text-sm font-semibold"><?php echo htmlspecialchars($booking['name']); ?></p>
                            <p class="text-xs text-gray-500"><?php echo htmlspecialchars($booking['phone']); ?></p>
                        </td>
                        <td class="py-4 px-4 text-sm"><?php echo htmlspecialchars($booking['service']); ?></td>
                        <td class="py-4 px-4">
                            <p class="text-sm text-gray-300"><?php echo htmlspecialchars($booking['car_type']); ?></p>
                            <?php if (!empty($booking['car_name'])): ?>
                            <p class="text-xs text-amber-400/70"><?php echo htmlspecialchars($booking['car_name']); ?></p>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-4">
                            <?php if (!empty($booking['location'])): ?>
                            <p class="text-sm text-gray-300 flex items-start gap-1"><svg class="w-3.5 h-3.5 text-amber-400/60 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg><?php echo htmlspecialchars($booking['location']); ?></p>
                            <?php else: ?>
                            <span class="text-xs text-gray-600">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-4">
                            <p class="text-sm text-gray-300"><?php echo date('M j, Y', strtotime($booking['booking_date'])); ?></p>
                            <?php if (!empty($booking['booking_time'])): ?>
                            <p class="text-xs text-gray-500"><?php echo htmlspecialchars($booking['booking_time']); ?></p>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-4">
                            <p class="text-sm"><?php echo htmlspecialchars($booking['payment_method']); ?></p>
                            <?php if ($booking['payment_proof']): ?>
                            <a href="../uploads/payments/<?php echo htmlspecialchars($booking['payment_proof']); ?>" target="_blank" class="text-xs text-blue-400 hover:text-blue-300 transition-colors">View Proof</a>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-4">
                            <?php
                            $status_colors = [
                                'pending' => 'text-yellow-400 bg-yellow-900/20 border-yellow-800/50',
                                'approved' => 'text-green-400 bg-green-900/20 border-green-800/50',
                                'rejected' => 'text-red-400 bg-red-900/20 border-red-800/50',
                            ];
                            $color = $status_colors[$booking['status']] ?? 'text-gray-400';
                            ?>
                            <span class="text-xs px-3 py-1 rounded-full border <?php echo $color; ?> capitalize"><?php echo $booking['status']; ?></span>
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex items-center space-x-2">
                                <?php if ($booking['status'] === 'pending'): ?>
                                <form method="POST" class="inline">
                                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                    <input type="hidden" name="action" value="approved">
                                    <button type="submit" class="text-xs px-3 py-1.5 bg-green-900/30 text-green-400 border border-green-800/50 rounded-md hover:bg-green-900/50 transition-colors" onclick="return confirm('Approve this booking?')">Approve</button>
                                </form>
                                <form method="POST" class="inline">
                                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                    <input type="hidden" name="action" value="rejected">
                                    <button type="submit" class="text-xs px-3 py-1.5 bg-red-900/30 text-red-400 border border-red-800/50 rounded-md hover:bg-red-900/50 transition-colors" onclick="return confirm('Reject this booking?')">Reject</button>
                                </form>
                                <?php endif; ?>
                                <form method="POST" class="inline">
                                    <input type="hidden" name="delete_id" value="<?php echo $booking['id']; ?>">
                                    <button type="submit" class="text-xs px-3 py-1.5 text-gray-400 border border-gray-700 rounded-md hover:bg-gray-900 transition-colors" onclick="return confirm('Delete this booking permanently?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4">
            <?php 
            $bookings->data_seek(0);
            while ($booking = $bookings->fetch_assoc()): 
            ?>
            <div class="border border-gray-800 rounded-xl p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold"><?php echo htmlspecialchars($booking['name']); ?></p>
                        <p class="text-xs text-gray-500"><?php echo htmlspecialchars($booking['phone']); ?></p>
                    </div>
                    <?php
                    $status_colors_m = [
                        'pending' => 'text-yellow-400 bg-yellow-900/20 border-yellow-800/50',
                        'approved' => 'text-green-400 bg-green-900/20 border-green-800/50',
                        'rejected' => 'text-red-400 bg-red-900/20 border-red-800/50',
                    ];
                    $color_m = $status_colors_m[$booking['status']] ?? 'text-gray-400';
                    ?>
                    <span class="text-xs px-3 py-1 rounded-full border <?php echo $color_m; ?> capitalize"><?php echo $booking['status']; ?></span>
                </div>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Service</p>
                        <p class="text-gray-300"><?php echo htmlspecialchars($booking['service']); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Car</p>
                        <p class="text-gray-300"><?php echo htmlspecialchars($booking['car_type']); ?><?php if (!empty($booking['car_name'])): ?> <span class="text-amber-400/70">(<?php echo htmlspecialchars($booking['car_name']); ?>)</span><?php endif; ?></p>
                    </div>                    <div class="col-span-2">
                        <p class="text-xs text-gray-500 uppercase">Location</p>
                        <p class="text-gray-300 flex items-start gap-1"><?php if (!empty($booking['location'])): ?><svg class="w-3.5 h-3.5 text-amber-400/60 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg><?php echo htmlspecialchars($booking['location']); ?><?php else: ?>—<?php endif; ?></p>
                    </div>                    <div>
                        <p class="text-xs text-gray-500 uppercase">Date</p>
                        <p class="text-gray-300"><?php echo date('M j, Y', strtotime($booking['booking_date'])); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Time</p>
                        <p class="text-gray-300"><?php echo !empty($booking['booking_time']) ? htmlspecialchars($booking['booking_time']) : '—'; ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Payment</p>
                        <p class="text-gray-300"><?php echo htmlspecialchars($booking['payment_method']); ?></p>
                        <?php if ($booking['payment_proof']): ?>
                        <a href="../uploads/payments/<?php echo htmlspecialchars($booking['payment_proof']); ?>" target="_blank" class="text-xs text-blue-400">View Proof</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex items-center gap-2 pt-2 border-t border-gray-800">
                    <?php if ($booking['status'] === 'pending'): ?>
                    <form method="POST" class="inline">
                        <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                        <input type="hidden" name="action" value="approved">
                        <button type="submit" class="text-xs px-3 py-1.5 bg-green-900/30 text-green-400 border border-green-800/50 rounded-md" onclick="return confirm('Approve?')">Approve</button>
                    </form>
                    <form method="POST" class="inline">
                        <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                        <input type="hidden" name="action" value="rejected">
                        <button type="submit" class="text-xs px-3 py-1.5 bg-red-900/30 text-red-400 border border-red-800/50 rounded-md" onclick="return confirm('Reject?')">Reject</button>
                    </form>
                    <?php endif; ?>
                    <form method="POST" class="inline ml-auto">
                        <input type="hidden" name="delete_id" value="<?php echo $booking['id']; ?>">
                        <button type="submit" class="text-xs px-3 py-1.5 text-gray-400 border border-gray-700 rounded-md" onclick="return confirm('Delete permanently?')">Delete</button>
                    </form>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-16 border border-gray-800 rounded-xl">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <h3 class="text-lg font-semibold mb-2">No Bookings Found</h3>
            <p class="text-gray-500 text-sm">
                <?php echo $filter !== 'all' ? "No $filter bookings. " : ''; ?>
                Bookings will appear here when customers submit requests.
            </p>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
