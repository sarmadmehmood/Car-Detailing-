<?php
/**
 * Gallery Page - Crysta Auto Care
 * Displays uploaded videos in a responsive grid
 */
$page_title = 'Gallery';
require_once 'includes/db.php';
require_once 'includes/header.php';

// Fetch gallery videos
$videos = $conn->query("SELECT * FROM gallery ORDER BY created_at DESC");
?>

<!-- Page Header -->
<section class="py-20 bg-gradient-to-b from-gray-900/50 to-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-xs tracking-[0.5em] text-amber-400/70 uppercase mb-4 fade-in">Our Work</p>
        <h1 class="font-playfair text-4xl sm:text-5xl md:text-6xl font-bold mb-4 fade-in">Gallery</h1>
        <p class="text-gray-400 max-w-xl mx-auto fade-in">See the transformation. Watch our detailing process and results in action.</p>
    </div>
</section>

<!-- Video Gallery -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if ($videos && $videos->num_rows > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-8">
            <?php while ($video = $videos->fetch_assoc()): ?>
            <div class="card-hover animate-on-scroll group">
                <div class="glass rounded-xl overflow-hidden">
                    <div class="relative aspect-video bg-gray-900">
                        <video class="w-full h-full object-cover" controls preload="metadata" playsinline>
                            <source src="uploads/videos/<?php echo htmlspecialchars($video['video']); ?>">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    <?php if (!empty($video['title'])): ?>
                    <div class="p-4">
                        <h3 class="text-sm font-semibold tracking-wider"><?php echo htmlspecialchars($video['title']); ?></h3>
                        <p class="text-xs text-gray-500 mt-1"><?php echo date('F j, Y', strtotime($video['created_at'])); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-20 animate-on-scroll">
            <div class="w-24 h-24 mx-auto mb-6 border border-gray-800 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-xl font-semibold mb-2">No Videos Yet</h3>
            <p class="text-gray-500 text-sm">Check back soon for our latest detailing videos.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA -->
<section class="py-20 border-t border-gray-800">
    <div class="max-w-4xl mx-auto px-4 text-center animate-on-scroll">
        <h2 class="font-playfair text-4xl font-bold mb-6">Want Your Car to Look This Good?</h2>
        <p class="text-gray-400 mb-8">Book your appointment today and let us work our magic.</p>
        <a href="booking.php" class="inline-block px-10 py-4 bg-amber-400 text-black text-sm font-semibold tracking-wider uppercase hover:bg-amber-300 transition-all duration-300 rounded-sm">Book Now</a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
