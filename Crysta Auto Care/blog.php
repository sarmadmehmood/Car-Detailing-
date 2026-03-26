<?php
/**
 * Blog Page - Crysta Auto Care
 * Lists blog posts & shows individual post detail
 */
$page_title = 'Blog';
require_once 'includes/db.php';

// Check if viewing single blog post
$single_post = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM blogs WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $single_post = $result->fetch_assoc();
    $stmt->close();
    
    if ($single_post) {
        $page_title = htmlspecialchars($single_post['title']);
    }
}

require_once 'includes/header.php';
?>

<?php if ($single_post): ?>
<!-- Single Blog Post View -->
<section class="py-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="blog.php" class="inline-flex items-center text-sm text-gray-400 hover:text-white transition-colors mb-8 fade-in">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Blog
        </a>

        <article class="fade-in">
            <p class="text-xs tracking-widest text-gray-500 uppercase mb-4"><?php echo date('F j, Y', strtotime($single_post['created_at'])); ?></p>
            <h1 class="font-playfair text-3xl sm:text-4xl md:text-5xl font-bold mb-8 leading-tight"><?php echo htmlspecialchars($single_post['title']); ?></h1>
            
            <?php if (!empty($single_post['image'])): ?>
            <div class="mb-10 rounded-xl overflow-hidden">
                <img src="uploads/blog/<?php echo htmlspecialchars($single_post['image']); ?>" alt="<?php echo htmlspecialchars($single_post['title']); ?>" class="w-full h-auto">
            </div>
            <?php endif; ?>

            <div class="prose prose-invert max-w-none text-gray-300 leading-relaxed space-y-6">
                <?php echo nl2br(htmlspecialchars($single_post['content'])); ?>
            </div>
        </article>

        <!-- Share & Navigation -->
        <div class="mt-16 pt-8 border-t border-gray-800">
            <div class="flex items-center justify-between">
                <a href="blog.php" class="text-sm text-gray-400 hover:text-white transition-colors">← All Posts</a>
                <a href="booking.php" class="px-6 py-2 bg-amber-400 text-black text-sm font-semibold tracking-wider uppercase hover:bg-amber-300 transition-all duration-300 rounded-sm">Book a Service</a>
            </div>
        </div>
    </div>
</section>

<?php else: ?>
<!-- Blog Listing -->
<section class="py-20 bg-gradient-to-b from-gray-900/50 to-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-xs tracking-[0.5em] text-amber-400/70 uppercase mb-4 fade-in">Insights & Tips</p>
        <h1 class="font-playfair text-4xl sm:text-5xl md:text-6xl font-bold mb-4 fade-in">Our Blog</h1>
        <p class="text-gray-400 max-w-xl mx-auto fade-in">Expert tips, detailing guides, and industry insights from the Crysta team.</p>
    </div>
</section>

<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php
        $blogs = $conn->query("SELECT * FROM blogs ORDER BY created_at DESC");
        ?>

        <?php if ($blogs && $blogs->num_rows > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php while ($blog = $blogs->fetch_assoc()): ?>
            <a href="blog.php?id=<?php echo $blog['id']; ?>" class="card-hover glass rounded-xl overflow-hidden block animate-on-scroll group">
                <?php if (!empty($blog['image'])): ?>
                <div class="aspect-video bg-gray-900 overflow-hidden">
                    <img src="uploads/blog/<?php echo htmlspecialchars($blog['image']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <?php else: ?>
                <div class="aspect-video bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
                <?php endif; ?>
                <div class="p-6">
                    <p class="text-xs tracking-widest text-gray-500 uppercase mb-3"><?php echo date('F j, Y', strtotime($blog['created_at'])); ?></p>
                    <h3 class="text-lg font-semibold mb-3 group-hover:text-gray-300 transition-colors"><?php echo htmlspecialchars($blog['title']); ?></h3>
                    <p class="text-gray-400 text-sm leading-relaxed"><?php echo htmlspecialchars(substr($blog['content'], 0, 150)) . '...'; ?></p>
                    <span class="inline-flex items-center mt-4 text-sm text-gray-400 group-hover:text-white transition-colors">
                        Read More
                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </div>
            </a>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-20 animate-on-scroll">
            <div class="w-24 h-24 mx-auto mb-6 border border-gray-800 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </div>
            <h3 class="text-xl font-semibold mb-2">No Blog Posts Yet</h3>
            <p class="text-gray-500 text-sm">Check back soon for articles and detailing tips from our team.</p>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
