<?php
/**
 * Homepage - Crysta Auto Care
 * Premium car detailing landing page
 */
$page_title = 'Home';
require_once 'includes/db.php';
require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden -mt-20">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-gradient-to-br from-black via-gray-900 to-black"></div>
    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.03) 0%, transparent 50%), radial-gradient(circle at 80% 50%, rgba(255,255,255,0.03) 0%, transparent 50%);"></div>
    
    <!-- Animated Lines -->
    <div class="absolute top-0 left-1/4 w-px h-full bg-gradient-to-b from-transparent via-gray-700 to-transparent opacity-20"></div>
    <div class="absolute top-0 right-1/4 w-px h-full bg-gradient-to-b from-transparent via-gray-700 to-transparent opacity-20"></div>

    <div class="relative z-10 text-center px-4 sm:px-6 max-w-5xl mx-auto">
        <p class="text-xs tracking-[0.3em] sm:tracking-[0.5em] text-amber-400/60 uppercase mb-6 sm:mb-8 fade-in">Premium Auto Detailing</p>
        <h1 class="font-playfair text-4xl sm:text-5xl md:text-7xl lg:text-8xl font-bold mb-4 sm:mb-6 leading-tight fade-in">
            Precision Care.<br>
            <span class="text-amber-400/80">Flawless Finish.</span>
        </h1>
        <p class="text-gray-400 text-base sm:text-lg md:text-xl max-w-2xl mx-auto mb-3 sm:mb-4 font-light fade-in">
            We transform your vehicle with meticulous attention to detail. 
            Every surface perfected, every inch restored to showroom glory.
        </p>
        <p class="text-amber-400/50 text-xs sm:text-sm tracking-wider mb-8 sm:mb-12 fade-in">Premium car detailing service in Okara — Starting from Rs. 800</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 fade-in">
            <a href="booking.php" class="w-full sm:w-auto px-10 py-4 bg-amber-400 text-black font-semibold text-sm tracking-wider uppercase hover:bg-amber-300 transition-all duration-300 rounded-sm text-center">
                Book Your Detail
            </a>
            <a href="services.php" class="w-full sm:w-auto px-10 py-4 border border-amber-400/40 text-amber-400 font-semibold text-sm tracking-wider uppercase hover:bg-amber-400 hover:text-black transition-all duration-300 rounded-sm text-center">
                Our Services
            </a>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 flex flex-col items-center">
        <span class="text-xs tracking-widest text-gray-500 uppercase mb-3">Scroll</span>
        <div class="w-px h-12 bg-gradient-to-b from-gray-500 to-transparent animate-pulse"></div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-12 sm:py-20 border-y border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8">
            <div class="text-center animate-on-scroll">
                <p class="text-3xl sm:text-4xl md:text-5xl font-bold font-playfair mb-2">500+</p>
                <p class="text-[10px] sm:text-xs tracking-widest text-gray-500 uppercase">Cars Detailed</p>
            </div>
            <div class="text-center animate-on-scroll">
                <p class="text-3xl sm:text-4xl md:text-5xl font-bold font-playfair mb-2">4.9</p>
                <p class="text-[10px] sm:text-xs tracking-widest text-gray-500 uppercase">Customer Rating</p>
            </div>
            <div class="text-center animate-on-scroll">
                <p class="text-3xl sm:text-4xl md:text-5xl font-bold font-playfair mb-2">3+</p>
                <p class="text-[10px] sm:text-xs tracking-widest text-gray-500 uppercase">Years Experience</p>
            </div>
            <div class="text-center animate-on-scroll">
                <p class="text-3xl sm:text-4xl md:text-5xl font-bold font-playfair mb-2">100%</p>
                <p class="text-[10px] sm:text-xs tracking-widest text-gray-500 uppercase">Satisfaction</p>
            </div>
        </div>
    </div>
</section>

<!-- Services Preview — 3 Package Cards -->
<section class="py-16 sm:py-24">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14 animate-on-scroll">
            <p class="text-xs tracking-[0.3em] sm:tracking-[0.5em] text-amber-400/60 uppercase mb-4">Premium Car Detailing Service in Okara</p>
            <h2 class="font-playfair text-3xl sm:text-4xl md:text-5xl font-bold mb-4">Choose Your Package</h2>
            <p class="text-gray-400 max-w-xl mx-auto text-sm sm:text-base">Affordable packages crafted for every car. Starting prices that fit your budget.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 items-stretch">
            <!-- Essential Care -->
            <div class="group glass rounded-2xl p-6 sm:p-8 border border-gray-800 hover:border-amber-400/30 transition-all duration-500 hover:scale-[1.03] hover:shadow-[0_0_40px_rgba(251,191,36,0.08)] flex flex-col text-center animate-on-scroll">
                <div class="text-3xl mb-4">🥉</div>
                <h3 class="text-xl font-bold font-playfair mb-2">Essential Care</h3>
                <p class="text-gray-400 text-sm mb-5 leading-relaxed">Signature exterior wash + light interior touch-up. Quick & affordable.</p>
                <div class="mt-auto pt-4 border-t border-gray-800">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Starting from</p>
                    <p class="text-3xl font-bold font-playfair text-white mt-1">Rs. 800</p>
                    <a href="booking.php?service=Essential+Care" class="block w-full mt-4 py-3 text-sm font-semibold tracking-wider uppercase border border-amber-400/40 text-amber-400 rounded-lg hover:bg-amber-400 hover:text-black transition-all duration-300">Book Now</a>
                </div>
            </div>

            <!-- Premium Care — MOST POPULAR -->
            <div class="group relative rounded-2xl p-6 sm:p-8 border-2 border-amber-400/60 bg-gradient-to-b from-amber-400/5 via-black to-black transition-all duration-500 hover:scale-[1.05] hover:shadow-[0_0_60px_rgba(251,191,36,0.15)] flex flex-col text-center animate-on-scroll md:-mt-3 md:mb-[-0.75rem]" style="backdrop-filter:blur(10px);">
                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2">
                    <span class="inline-flex items-center space-x-1 bg-amber-400 text-black text-[10px] font-bold tracking-wider uppercase px-4 py-1 rounded-full shadow-lg shadow-amber-400/20">
                        <span>⭐</span><span>Most Popular</span>
                    </span>
                </div>
                <div class="text-3xl mb-4 mt-2">🥈</div>
                <h3 class="text-xl font-bold font-playfair mb-2">Premium Care</h3>
                <p class="text-gray-400 text-sm mb-5 leading-relaxed">Interior refresh + exterior wash & wax. Deep cleaning for a better finish.</p>
                <div class="mt-auto pt-4 border-t border-amber-400/20">
                    <p class="text-xs text-amber-400/70 uppercase tracking-wider">Starting from</p>
                    <p class="text-3xl font-bold font-playfair text-amber-400 mt-1">Rs. 2,000</p>
                    <a href="booking.php?service=Premium+Care" class="block w-full mt-4 py-3 text-sm font-bold tracking-wider uppercase bg-amber-400 text-black rounded-lg hover:bg-amber-300 transition-all duration-300 shadow-lg shadow-amber-400/10">Book Now</a>
                </div>
            </div>

            <!-- Ultimate Royal -->
            <div class="group glass rounded-2xl p-6 sm:p-8 border border-gray-800 hover:border-amber-400/30 transition-all duration-500 hover:scale-[1.03] hover:shadow-[0_0_40px_rgba(251,191,36,0.08)] flex flex-col text-center animate-on-scroll">
                <div class="text-3xl mb-4">👑</div>
                <h3 class="text-xl font-bold font-playfair mb-2">Ultimate Royal</h3>
                <p class="text-gray-400 text-sm mb-5 leading-relaxed">Full interior + exterior, polish, wax — showroom finish & complete detailing.</p>
                <div class="mt-auto pt-4 border-t border-gray-800">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Starting from</p>
                    <p class="text-3xl font-bold font-playfair text-white mt-1">Rs. 3,000</p>
                    <a href="booking.php?service=Ultimate+Royal" class="block w-full mt-4 py-3 text-sm font-semibold tracking-wider uppercase border border-amber-400/40 text-amber-400 rounded-lg hover:bg-amber-400 hover:text-black transition-all duration-300">Book Now</a>
                </div>
            </div>
        </div>

        <!-- Ceramic Coating Teaser + Combo Deal -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-10">
            <!-- Ceramic Teaser -->
            <div class="glass rounded-2xl p-5 sm:p-8 border border-gray-800 hover:border-amber-400/20 transition-all duration-300 animate-on-scroll">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="flex items-center gap-4 sm:gap-5 flex-grow min-w-0">
                        <div class="flex-shrink-0 w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-amber-400/20 to-amber-400/5 rounded-full flex items-center justify-center border border-amber-400/20">
                            <span class="text-xl sm:text-2xl">✨</span>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold mb-0.5 text-sm sm:text-base">Ceramic Coating</h4>
                            <p class="text-gray-400 text-xs sm:text-sm">Premium 9H protection — from <span class="text-amber-400 font-semibold">Rs. 8,000</span></p>
                        </div>
                    </div>
                    <a href="services.php#ceramic" class="flex-shrink-0 block text-center sm:inline-block bg-amber-400/10 sm:bg-transparent text-amber-400 hover:bg-amber-400 hover:text-black text-sm font-semibold py-2.5 sm:py-0 rounded-lg sm:rounded-none transition-all">View Details →</a>
                </div>
            </div>

            <!-- Combo Deal -->
            <div class="relative overflow-hidden rounded-2xl p-5 sm:p-8 border border-amber-400/30 bg-gradient-to-r from-amber-400/5 via-amber-400/10 to-amber-400/5 animate-on-scroll" style="backdrop-filter:blur(10px);">
                <div class="absolute top-0 left-0 w-full h-0.5 bg-gradient-to-r from-transparent via-amber-400 to-transparent"></div>
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="flex items-center gap-4 sm:gap-5 flex-grow min-w-0">
                        <div class="flex-shrink-0 text-xl sm:text-2xl">🔥</div>
                        <div class="min-w-0">
                            <h4 class="font-bold mb-0.5 text-sm sm:text-base">Interior + Exterior = Save Rs. 500</h4>
                            <p class="text-gray-400 text-xs sm:text-sm">Book both together for the best value.</p>
                        </div>
                    </div>
                    <a href="booking.php?service=Premium+Care" class="flex-shrink-0 block text-center bg-amber-400 text-black text-xs font-bold px-4 py-2.5 rounded-lg hover:bg-amber-300 transition-all uppercase tracking-wider">Claim Deal</a>
                </div>
            </div>
        </div>

        <div class="text-center mt-10 animate-on-scroll">
            <a href="services.php" class="inline-block px-10 py-4 border border-amber-400/40 text-amber-400 text-sm tracking-wider uppercase hover:bg-amber-400 hover:text-black transition-all duration-300 rounded-lg">
                View All Packages & Pricing
            </a>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-24 bg-gradient-to-b from-gray-900/50 to-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="animate-on-scroll">
                <p class="text-xs tracking-[0.5em] text-amber-400/60 uppercase mb-4">Why Crysta</p>
                <h2 class="font-playfair text-4xl md:text-5xl font-bold mb-8">Craftsmanship<br>Meets Technology</h2>
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center border border-gray-700 rounded-full mt-1">
                            <span class="text-xs font-bold">01</span>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1">Premium Products</h4>
                            <p class="text-gray-400 text-sm">We use only the finest imported detailing products and ceramic coatings.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center border border-gray-700 rounded-full mt-1">
                            <span class="text-xs font-bold">02</span>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1">Expert Technicians</h4>
                            <p class="text-gray-400 text-sm">Our trained professionals deliver consistent, flawless results every time.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center border border-gray-700 rounded-full mt-1">
                            <span class="text-xs font-bold">03</span>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1">Satisfaction Guaranteed</h4>
                            <p class="text-gray-400 text-sm">We're not done until you're completely satisfied with the results.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="animate-on-scroll">
                <div class="relative">
                    <div class="glass rounded-2xl p-12 text-center">
                        <img src="includes/logo.png" alt="Crysta Auto Care" class="h-24 sm:h-28 w-auto mx-auto mb-6 object-contain drop-shadow-[0_0_12px_rgba(251,191,36,0.3)]">
                        <p class="text-gray-400 italic">Where every detail matters</p>
                        <div class="mt-8 grid grid-cols-2 gap-4 text-center">
                            <div class="p-4 border border-gray-800 rounded-lg">
                                <p class="text-2xl font-bold">24/7</p>
                                <p class="text-xs text-gray-500 uppercase">Booking</p>
                            </div>
                            <div class="p-4 border border-gray-800 rounded-lg">
                                <p class="text-2xl font-bold">100%</p>
                                <p class="text-xs text-gray-500 uppercase">Quality</p>
                            </div>
                        </div>
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute -top-4 -right-4 w-24 h-24 border border-gray-800 rounded-lg"></div>
                    <div class="absolute -bottom-4 -left-4 w-24 h-24 border border-gray-800 rounded-lg"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 sm:py-24">
    <div class="max-w-4xl mx-auto px-4 text-center animate-on-scroll">
        <h2 class="font-playfair text-3xl sm:text-4xl md:text-6xl font-bold mb-4 sm:mb-6">Ready to Transform<br>Your Vehicle?</h2>
        <p class="text-gray-400 text-base sm:text-lg mb-8 sm:mb-10 max-w-2xl mx-auto">Book your appointment today and experience the Crysta difference. Professional detailing that speaks for itself.</p>
        <a href="booking.php" class="inline-block px-10 sm:px-12 py-4 sm:py-5 bg-amber-400 text-black font-semibold text-sm tracking-wider uppercase hover:bg-amber-300 transition-all duration-300 rounded-sm">
            Schedule Your Appointment
        </a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
