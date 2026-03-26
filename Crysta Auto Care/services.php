<?php
/**
 * Services Page - Crysta Auto Care
 * Premium pricing with psychological strategy
 * 3 main packages + Ceramic Coating as separate premium section
 */
$page_title = 'Services';
require_once 'includes/db.php';
require_once 'includes/header.php';
?>

<!-- Page Header -->
<section class="py-16 sm:py-20 bg-gradient-to-b from-gray-900/50 to-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-xs tracking-[0.3em] sm:tracking-[0.5em] text-amber-400/70 uppercase mb-4 fade-in">Premium Car Detailing Service in Okara</p>
        <h1 class="font-playfair text-4xl sm:text-5xl md:text-6xl font-bold mb-4 fade-in">Choose Your Care</h1>
        <p class="text-gray-400 max-w-xl mx-auto fade-in">Every package is crafted to deliver a flawless finish. Pick the one that suits your ride.</p>
    </div>
</section>

<!-- 3 Main Pricing Cards -->
<section class="py-12 sm:py-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 items-stretch">

            <!-- 🥉 BASIC CARE -->
            <div class="group relative glass rounded-2xl p-6 sm:p-8 border border-gray-800 hover:border-amber-400/30 transition-all duration-500 hover:scale-[1.03] hover:shadow-[0_0_40px_rgba(251,191,36,0.08)] flex flex-col animate-on-scroll">
                <div class="mb-6">
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="text-2xl">🥉</span>
                        <span class="text-[10px] tracking-[0.3em] uppercase text-gray-500">Package 01</span>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-bold font-playfair mb-2">Essential Care</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Signature exterior wash with a light interior touch-up. Perfect for regular maintenance.</p>
                </div>

                <div class="space-y-3 mb-8 flex-grow">
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">Signature Exterior Wash</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">Light Interior Wipe</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">Tire & Rim Cleaning</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">Glass Cleaning</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">Affordable & Quick</span>
                    </div>
                </div>

                <div class="border-t border-gray-800 pt-6">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Starting from</p>
                    <p class="text-3xl sm:text-4xl font-bold font-playfair text-white">Rs. 800</p>
                    <a href="booking.php?service=Essential+Care" class="block w-full mt-5 py-3.5 text-center text-sm font-semibold tracking-wider uppercase border border-amber-400/40 text-amber-400 rounded-lg hover:bg-amber-400 hover:text-black transition-all duration-300">
                        Book Now
                    </a>
                </div>
            </div>

            <!-- 🥈 PREMIUM CARE — MOST POPULAR -->
            <div class="group relative rounded-2xl p-6 sm:p-8 border-2 border-amber-400/60 bg-gradient-to-b from-amber-400/5 via-black to-black transition-all duration-500 hover:scale-[1.05] hover:shadow-[0_0_60px_rgba(251,191,36,0.15)] flex flex-col animate-on-scroll md:-mt-4 md:mb-[-1rem]" style="backdrop-filter: blur(10px);">
                <!-- Badge -->
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 z-10">
                    <span class="inline-flex items-center space-x-1.5 bg-amber-400 text-black text-xs font-bold tracking-wider uppercase px-5 py-1.5 rounded-full shadow-lg shadow-amber-400/20">
                        <span>⭐</span>
                        <span>Most Popular</span>
                    </span>
                </div>

                <div class="mb-6 mt-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="text-2xl">🥈</span>
                        <span class="text-[10px] tracking-[0.3em] uppercase text-amber-400/70">Package 02</span>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-bold font-playfair mb-2">Premium Care</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Complete interior refresh with exterior wash & wax. Deep cleaning for a showroom feel.</p>
                </div>

                <div class="space-y-3 mb-8 flex-grow">
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">Full Interior Refresh</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">Exterior Wash + Wax</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">Deep Steam Cleaning</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">Leather Conditioning</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">Odor Elimination</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">Better Finish Guaranteed</span>
                    </div>
                </div>

                <div class="border-t border-amber-400/20 pt-6">
                    <p class="text-xs text-amber-400/70 uppercase tracking-wider mb-1">Starting from</p>
                    <p class="text-3xl sm:text-4xl font-bold font-playfair text-amber-400">Rs. 2,000</p>
                    <a href="booking.php?service=Premium+Care" class="block w-full mt-5 py-3.5 text-center text-sm font-bold tracking-wider uppercase bg-amber-400 text-black rounded-lg hover:bg-amber-300 transition-all duration-300 shadow-lg shadow-amber-400/10">
                        Book Now
                    </a>
                </div>
            </div>

            <!-- 🥇 ULTIMATE ROYAL -->
            <div class="group relative glass rounded-2xl p-6 sm:p-8 border border-gray-800 hover:border-amber-400/30 transition-all duration-500 hover:scale-[1.03] hover:shadow-[0_0_40px_rgba(251,191,36,0.08)] flex flex-col animate-on-scroll">
                <div class="mb-6">
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="text-2xl">👑</span>
                        <span class="text-[10px] tracking-[0.3em] uppercase text-gray-500">Package 03</span>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-bold font-playfair mb-2">Ultimate Royal</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">The complete transformation — full interior & exterior with polish, wax, and showroom finish.</p>
                </div>

                <div class="space-y-3 mb-8 flex-grow">
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">Full Interior + Exterior Detail</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">Paint Polish + Wax</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">Showroom Finish</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">Engine Bay Cleaning</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">Headlight Restoration</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">Complete Detailing</span>
                    </div>
                </div>

                <div class="border-t border-gray-800 pt-6">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Starting from</p>
                    <p class="text-3xl sm:text-4xl font-bold font-playfair text-white">Rs. 3,000</p>
                    <a href="booking.php?service=Ultimate+Royal" class="block w-full mt-5 py-3.5 text-center text-sm font-semibold tracking-wider uppercase border border-amber-400/40 text-amber-400 rounded-lg hover:bg-amber-400 hover:text-black transition-all duration-300">
                        Book Now
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Combo Deal Banner -->
<section class="py-10 sm:py-14">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-2xl border border-amber-400/30 bg-gradient-to-r from-amber-400/5 via-amber-400/10 to-amber-400/5 p-6 sm:p-10 text-center animate-on-scroll" style="backdrop-filter:blur(10px);">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-amber-400 to-transparent"></div>
            <p class="text-xs tracking-[0.3em] text-amber-400 uppercase mb-3">🔥 Limited Combo Deal</p>
            <h3 class="font-playfair text-2xl sm:text-3xl font-bold mb-2">Interior + Exterior = Save Rs. 500</h3>
            <p class="text-gray-400 text-sm sm:text-base mb-6">Book both interior & exterior together and save. Premium finish at unbeatable value.</p>
            <a href="booking.php?service=Premium+Care" class="inline-block px-8 py-3 bg-amber-400 text-black text-sm font-bold tracking-wider uppercase rounded-lg hover:bg-amber-300 transition-all duration-300">
                Claim This Deal
            </a>
        </div>
    </div>
</section>

<!-- ✨ CERAMIC COATING — Separate Premium Section (Anchoring: show high price first) -->
<section class="py-16 sm:py-24 border-t border-gray-800">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <!-- Info -->
            <div class="animate-on-scroll">
                <div class="flex items-center space-x-3 mb-4">
                    <span class="text-3xl">✨</span>
                    <p class="text-xs tracking-[0.3em] text-amber-400/70 uppercase">Premium Protection</p>
                </div>
                <h2 class="font-playfair text-3xl sm:text-4xl md:text-5xl font-bold mb-6">Ceramic Coating</h2>
                <p class="text-gray-400 leading-relaxed mb-8">Professional-grade 9H ceramic coating that provides years of protection. Achieve a mirror-like finish with hydrophobic properties that repel water, dirt, and UV damage.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">Paint Decontamination</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">Multi-Stage Polish</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">9H Ceramic Coating</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">UV Protection</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">Hydrophobic Finish</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-300">2-Year Warranty</span>
                    </div>
                </div>

                <a href="booking.php?service=Ceramic+Coating" class="inline-block px-8 py-3.5 bg-amber-400 text-black text-sm font-bold tracking-wider uppercase rounded-lg hover:bg-amber-300 transition-all duration-300">
                    Get Ceramic Coating
                </a>
            </div>

            <!-- Ceramic Price Card -->
            <div class="animate-on-scroll">
                <div class="glass rounded-2xl p-8 sm:p-10 border border-gray-800 text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-amber-400 to-transparent"></div>
                    <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-amber-400/20 to-amber-400/5 rounded-full flex items-center justify-center border border-amber-400/30">
                        <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 tracking-wider uppercase">Ceramic Protection</h3>
                    <p class="text-gray-500 text-sm mb-6">Long-lasting shine. Ultimate shield.</p>

                    <div class="space-y-3 mb-8">
                        <div class="flex items-center justify-between p-3 border border-gray-800 rounded-lg">
                            <span class="text-sm text-gray-400">Small / Hatchback</span>
                            <span class="text-lg font-bold text-white">Rs. 8,000</span>
                        </div>
                        <div class="flex items-center justify-between p-3 border border-gray-800 rounded-lg">
                            <span class="text-sm text-gray-400">Sedan / Compact</span>
                            <span class="text-lg font-bold text-white">Rs. 12,000</span>
                        </div>
                        <div class="flex items-center justify-between p-3 border border-gray-800 rounded-lg">
                            <span class="text-sm text-gray-400">SUV / Crossover</span>
                            <span class="text-lg font-bold text-white">Rs. 18,000</span>
                        </div>
                    </div>

                    <p class="text-xs text-gray-500">Prices vary based on vehicle condition</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-16 sm:py-20 border-t border-gray-800">
    <div class="max-w-4xl mx-auto px-4 text-center animate-on-scroll">
        <h2 class="font-playfair text-3xl sm:text-4xl font-bold mb-4">Not Sure Which Package?</h2>
        <p class="text-gray-400 mb-6 text-sm sm:text-base">Contact us and we'll recommend the perfect treatment for your vehicle.</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4">
            <a href="contact.php" class="w-full sm:w-auto px-10 py-4 border border-amber-400/40 text-amber-400 text-sm tracking-wider uppercase hover:bg-amber-400 hover:text-black transition-all duration-300 rounded-lg text-center">Contact Us</a>
            <a href="booking.php" class="w-full sm:w-auto px-10 py-4 bg-amber-400 text-black text-sm font-bold tracking-wider uppercase hover:bg-amber-300 transition-all duration-300 rounded-lg text-center">Book Now</a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
