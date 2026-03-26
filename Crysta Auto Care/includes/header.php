<?php
/**
 * Header Component
 * Crysta Auto Care - Reusable Navigation Header
 */

// Determine current page for active nav highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title><?php echo isset($page_title) ? $page_title . ' | ' : ''; ?>Crysta Auto Care</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-playfair { font-family: 'Playfair Display', serif; }
        /* Smooth transitions */
        .nav-link { position: relative; }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #fbbf24;
            transition: width 0.3s ease;
        }
        .nav-link:hover::after,
        .nav-link.active::after { width: 100%; }
        /* Fade in animation */
        .fade-in { animation: fadeIn 0.8s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .slide-up { animation: slideUp 0.6s ease-out; }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        /* Card hover effect */
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(251,191,36,0.1);
        }
        /* Glass effect */
        .glass {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
        }
        /* Mobile improvements */
        @media (max-width: 640px) {
            .font-playfair { letter-spacing: -0.01em; }
            section { overflow-x: hidden; }
            .mobile-px { padding-left: 1rem; padding-right: 1rem; }
            nav img { height: 2.75rem; }
        }
        @media (min-width: 641px) and (max-width: 768px) {
            nav img { height: 3rem; }
        }
        /* Smooth select styling */
        select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1.25rem; }
        /* WhatsApp button */
        .whatsapp-btn { position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 999; width: 56px; height: 56px; border-radius: 50%; background: #25D366; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 20px rgba(37,211,102,0.4); transition: all 0.3s ease; animation: whatsappPulse 2s infinite; }
        .whatsapp-btn:hover { transform: scale(1.1); box-shadow: 0 6px 30px rgba(37,211,102,0.6); }
        @keyframes whatsappPulse { 0%, 100% { box-shadow: 0 4px 20px rgba(37,211,102,0.4); } 50% { box-shadow: 0 4px 30px rgba(37,211,102,0.6); } }
        @media (max-width: 640px) { .whatsapp-btn { bottom: 1rem; right: 1rem; width: 50px; height: 50px; } }
    </style>
</head>
<body class="bg-black text-white min-h-screen">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 glass transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Logo -->
                <a href="index.php" class="flex items-center space-x-2 sm:space-x-3 group">
                    <img src="includes/logo.png" alt="Crysta Auto Care" class="h-12 sm:h-14 w-auto object-contain group-hover:scale-105 transition-transform duration-300 drop-shadow-[0_0_8px_rgba(251,191,36,0.3)]">
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="index.php" class="nav-link text-sm tracking-wider uppercase <?php echo $current_page == 'index.php' ? 'active' : ''; ?> hover:text-gray-300 transition-colors">Home</a>
                    <a href="services.php" class="nav-link text-sm tracking-wider uppercase <?php echo $current_page == 'services.php' ? 'active' : ''; ?> hover:text-gray-300 transition-colors">Services</a>
                    <a href="booking.php" class="nav-link text-sm tracking-wider uppercase <?php echo $current_page == 'booking.php' ? 'active' : ''; ?> hover:text-gray-300 transition-colors">Booking</a>
                    <a href="gallery.php" class="nav-link text-sm tracking-wider uppercase <?php echo $current_page == 'gallery.php' ? 'active' : ''; ?> hover:text-gray-300 transition-colors">Gallery</a>
                    <a href="blog.php" class="nav-link text-sm tracking-wider uppercase <?php echo $current_page == 'blog.php' ? 'active' : ''; ?> hover:text-gray-300 transition-colors">Blog</a>
                    <a href="contact.php" class="nav-link text-sm tracking-wider uppercase <?php echo $current_page == 'contact.php' ? 'active' : ''; ?> hover:text-gray-300 transition-colors">Contact</a>
                    <a href="booking.php" class="ml-4 px-6 py-2 bg-amber-400 text-black text-sm font-semibold tracking-wider uppercase hover:bg-amber-300 transition-all duration-300 rounded-sm">Book Now</a>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden text-white focus:outline-none p-2 -mr-2" aria-label="Toggle menu">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-black/95 border-t border-gray-800" style="backdrop-filter:blur(20px);">
            <div class="px-6 py-6 space-y-1">
                <a href="index.php" class="block text-base tracking-wider uppercase py-3 px-4 rounded-lg <?php echo $current_page == 'index.php' ? 'text-amber-400 bg-amber-400/10' : 'text-white hover:bg-gray-900'; ?> transition-colors">Home</a>
                <a href="services.php" class="block text-base tracking-wider uppercase py-3 px-4 rounded-lg <?php echo $current_page == 'services.php' ? 'text-amber-400 bg-amber-400/10' : 'text-white hover:bg-gray-900'; ?> transition-colors">Services</a>
                <a href="booking.php" class="block text-base tracking-wider uppercase py-3 px-4 rounded-lg <?php echo $current_page == 'booking.php' ? 'text-amber-400 bg-amber-400/10' : 'text-white hover:bg-gray-900'; ?> transition-colors">Booking</a>
                <a href="gallery.php" class="block text-base tracking-wider uppercase py-3 px-4 rounded-lg <?php echo $current_page == 'gallery.php' ? 'text-amber-400 bg-amber-400/10' : 'text-white hover:bg-gray-900'; ?> transition-colors">Gallery</a>
                <a href="blog.php" class="block text-base tracking-wider uppercase py-3 px-4 rounded-lg <?php echo $current_page == 'blog.php' ? 'text-amber-400 bg-amber-400/10' : 'text-white hover:bg-gray-900'; ?> transition-colors">Blog</a>
                <a href="contact.php" class="block text-base tracking-wider uppercase py-3 px-4 rounded-lg <?php echo $current_page == 'contact.php' ? 'text-amber-400 bg-amber-400/10' : 'text-white hover:bg-gray-900'; ?> transition-colors">Contact</a>
                <div class="pt-4">
                    <a href="booking.php" class="block text-center py-3 px-6 bg-amber-400 text-black text-sm font-semibold tracking-wider uppercase hover:bg-amber-300 transition-colors rounded-lg">Book Now</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Spacer for fixed nav -->
    <div class="h-16 sm:h-20"></div>

    <!-- Mobile Menu Toggle Script -->
    <script>
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            // Toggle hamburger / X icon
            if (mobileMenu.classList.contains('hidden')) {
                menuIcon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
            } else {
                menuIcon.setAttribute('d', 'M6 18L18 6M6 6l12 12');
            }
        });
        // Close mobile menu on link click
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                menuIcon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
            });
        });

        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(0,0,0,0.95)';
            } else {
                navbar.style.background = 'rgba(255,255,255,0.05)';
            }
        });
    </script>
