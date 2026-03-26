<?php
/**
 * Admin Add Blog Post - Crysta Auto Care
 * Create and manage blog posts
 */
require_once 'auth.php';
require_once '../includes/db.php';

$message = '';
$msg_type = '';

// Create blog uploads directory if it doesn't exist
if (!is_dir('../uploads/blog')) {
    mkdir('../uploads/blog', 0755, true);
}

// Handle blog post creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_blog'])) {
    $title = trim(htmlspecialchars($_POST['title'] ?? ''));
    $content = trim($_POST['content'] ?? '');
    $image = null;

    if (empty($title) || empty($content)) {
        $message = 'Title and content are required.';
        $msg_type = 'red';
    } else {
        // Handle optional image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            $file_type = $_FILES['image']['type'];

            if (in_array($file_type, $allowed_types)) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = 'blog_' . time() . '_' . uniqid() . '.' . $ext;
                $upload_path = '../uploads/blog/' . $filename;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $image = $filename;
                }
            }
        }

        $stmt = $conn->prepare("INSERT INTO blogs (title, content, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $content, $image);
        if ($stmt->execute()) {
            $message = 'Blog post published successfully!';
            $msg_type = 'green';
        } else {
            $message = 'Failed to publish blog post.';
            $msg_type = 'red';
        }
        $stmt->close();
    }
}

// Handle blog delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_blog_id'])) {
    $delete_id = intval($_POST['delete_blog_id']);

    // Delete image if exists
    $stmt = $conn->prepare("SELECT image FROM blogs WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    if ($result && $result['image']) {
        $file_path = '../uploads/blog/' . $result['image'];
        if (file_exists($file_path)) unlink($file_path);
    }
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM blogs WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = 'Blog post deleted successfully.';
        $msg_type = 'green';
    }
    $stmt->close();
}

// Fetch existing blogs
$blogs = $conn->query("SELECT * FROM blogs ORDER BY created_at DESC");

$current_admin_page = 'blog';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog | Crysta Admin</title>
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
                        <a href="bookings.php" class="text-xs tracking-wider uppercase text-gray-400 hover:text-amber-400 transition-colors">Bookings</a>
                        <a href="upload-video.php" class="text-xs tracking-wider uppercase text-gray-400 hover:text-amber-400 transition-colors">Gallery</a>
                        <a href="add-blog.php" class="text-xs tracking-wider uppercase text-amber-400 border-b border-amber-400 pb-0.5">Blog</a>
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
                <a href="bookings.php" class="block text-sm py-2 px-3 rounded text-gray-400 hover:bg-gray-900">Bookings</a>
                <a href="upload-video.php" class="block text-sm py-2 px-3 rounded text-gray-400 hover:bg-gray-900">Gallery</a>
                <a href="add-blog.php" class="block text-sm py-2 px-3 rounded text-amber-400 bg-amber-400/10">Blog</a>
                <a href="../index.php" target="_blank" class="block text-sm py-2 px-3 rounded text-gray-400 hover:bg-gray-900">View Site →</a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-2">Blog Management</h1>
            <p class="text-gray-400 text-sm">Create and manage blog posts.</p>
        </div>

        <?php if ($message): ?>
        <div class="mb-6 p-4 border border-<?php echo $msg_type; ?>-800 bg-<?php echo $msg_type; ?>-900/20 rounded-lg">
            <p class="text-<?php echo $msg_type; ?>-400 text-sm"><?php echo $message; ?></p>
        </div>
        <?php endif; ?>

        <!-- Create Blog Form -->
        <div class="border border-gray-800 rounded-xl p-8 mb-12">
            <h2 class="text-xl font-bold mb-6">Create New Post</h2>
            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-semibold tracking-wider uppercase mb-2">Post Title <span class="text-red-400">*</span></label>
                    <input type="text" id="title" name="title" required
                           class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-white focus:outline-none transition-colors placeholder-gray-600"
                           placeholder="Enter post title">
                </div>

                <div>
                    <label for="content" class="block text-sm font-semibold tracking-wider uppercase mb-2">Content <span class="text-red-400">*</span></label>
                    <textarea id="content" name="content" required rows="12"
                              class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-white focus:outline-none transition-colors placeholder-gray-600 resize-vertical"
                              placeholder="Write your blog post content here..."></textarea>
                </div>

                <div>
                    <label for="image" class="block text-sm font-semibold tracking-wider uppercase mb-2">Featured Image (Optional)</label>
                    <div class="border-2 border-dashed border-gray-700 rounded-lg p-6 text-center hover:border-gray-500 transition-colors">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-xs text-gray-600 mb-3">JPG, PNG, or WebP</p>
                        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp"
                               class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border file:border-gray-600 file:text-sm file:font-semibold file:bg-black file:text-white hover:file:bg-gray-900 file:cursor-pointer file:transition-colors">
                    </div>
                </div>

                <button type="submit" name="add_blog" class="px-8 py-3 bg-amber-400 text-black font-semibold text-sm tracking-wider uppercase hover:bg-amber-300 transition-all duration-300 rounded-lg">
                    Publish Post
                </button>
            </form>
        </div>

        <!-- Existing Blog Posts -->
        <div>
            <h2 class="text-xl font-bold mb-6">Published Posts</h2>
            <?php if ($blogs && $blogs->num_rows > 0): ?>
            <div class="space-y-4">
                <?php while ($blog = $blogs->fetch_assoc()): ?>
                <div class="border border-gray-800 rounded-xl p-6 hover:border-gray-600 transition-colors">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-start space-x-4">
                                <?php if ($blog['image']): ?>
                                <img src="../uploads/blog/<?php echo htmlspecialchars($blog['image']); ?>" alt="" class="w-20 h-16 object-cover rounded-lg flex-shrink-0">
                                <?php endif; ?>
                                <div>
                                    <h3 class="text-lg font-semibold mb-1"><?php echo htmlspecialchars($blog['title']); ?></h3>
                                    <p class="text-gray-400 text-sm mb-2"><?php echo htmlspecialchars(substr($blog['content'], 0, 200)); ?>...</p>
                                    <p class="text-xs text-gray-600"><?php echo date('F j, Y \a\t g:i A', strtotime($blog['created_at'])); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 flex-shrink-0">
                            <a href="../blog.php?id=<?php echo $blog['id']; ?>" target="_blank" class="text-xs px-3 py-1.5 text-gray-400 border border-gray-700 rounded-md hover:bg-gray-900 transition-colors">View</a>
                            <form method="POST" class="inline">
                                <input type="hidden" name="delete_blog_id" value="<?php echo $blog['id']; ?>">
                                <button type="submit" class="text-xs px-3 py-1.5 text-red-400 border border-red-800/50 rounded-md hover:bg-red-900/20 transition-colors" onclick="return confirm('Delete this blog post?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-16 border border-gray-800 rounded-xl">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <h3 class="text-lg font-semibold mb-2">No Blog Posts Yet</h3>
                <p class="text-gray-500 text-sm">Create your first blog post using the form above.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
