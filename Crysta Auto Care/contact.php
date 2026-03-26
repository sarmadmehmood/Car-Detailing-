<?php
/**
 * Contact Page - Crysta Auto Care
 * Contact information and contact form
 */
$page_title = 'Contact';
require_once 'includes/db.php';
require_once 'includes/header.php';

$contact_success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    // In a real app, you'd send an email or save to DB
    $contact_success = true;
}
?>

<!-- Page Header -->
<section class="py-20 bg-gradient-to-b from-gray-900/50 to-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-xs tracking-[0.5em] text-amber-400/70 uppercase mb-4 fade-in">Get In Touch</p>
        <h1 class="font-playfair text-4xl sm:text-5xl md:text-6xl font-bold mb-4 fade-in">Contact Us</h1>
        <p class="text-gray-400 max-w-xl mx-auto fade-in">Have questions or need a custom quote? We'd love to hear from you.</p>
    </div>
</section>

<!-- Contact Content -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            <!-- Contact Info -->
            <div class="animate-on-scroll">
                <h2 class="font-playfair text-3xl font-bold mb-8">Let's Talk</h2>
                <p class="text-gray-400 leading-relaxed mb-10">
                    Whether you need a quick wash or a full ceramic coating, we're here to help. 
                    Reach out to us through any of the channels below or fill out the contact form.
                </p>

                <div class="space-y-6">
                    <!-- Location -->
                    <div class="flex items-start space-x-4 p-4 glass rounded-lg">
                        <div class="w-12 h-12 flex-shrink-0 border border-gray-700 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1">Location</h4>
                            <p class="text-gray-400 text-sm">Okara, Pakistan</p>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="flex items-start space-x-4 p-4 glass rounded-lg">
                        <div class="w-12 h-12 flex-shrink-0 border border-gray-700 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1">Phone</h4>
                            <p class="text-gray-400 text-sm">0300-1234567</p>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start space-x-4 p-4 glass rounded-lg">
                        <div class="w-12 h-12 flex-shrink-0 border border-gray-700 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1">Email</h4>
                            <p class="text-gray-400 text-sm">info@crystaautocare.com</p>
                        </div>
                    </div>

                    <!-- Hours -->
                    <div class="flex items-start space-x-4 p-4 glass rounded-lg">
                        <div class="w-12 h-12 flex-shrink-0 border border-gray-700 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1">Working Hours</h4>
                            <p class="text-gray-400 text-sm">Mon - Sat: 9:00 AM - 7:00 PM</p>
                            <p class="text-gray-400 text-sm">Sunday: By Appointment</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="animate-on-scroll">
                <?php if ($contact_success): ?>
                <div class="glass rounded-xl p-12 text-center">
                    <svg class="w-16 h-16 mx-auto mb-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <h3 class="text-2xl font-bold mb-3">Message Sent!</h3>
                    <p class="text-gray-400 mb-6">Thank you for reaching out. We'll get back to you within 24 hours.</p>
                    <a href="contact.php" class="text-sm text-gray-400 hover:text-white transition-colors">Send Another Message</a>
                </div>
                <?php else: ?>
                <form method="POST" class="glass rounded-xl p-8 md:p-10 space-y-6">
                    <h3 class="text-2xl font-bold mb-2">Send a Message</h3>
                    <p class="text-gray-400 text-sm mb-6">Fill out the form and we'll respond as soon as possible.</p>

                    <div>
                        <label for="contact_name" class="block text-sm font-semibold tracking-wider uppercase mb-2">Name</label>
                        <input type="text" id="contact_name" name="contact_name" required
                               class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-white focus:outline-none transition-colors placeholder-gray-600"
                               placeholder="Your full name">
                    </div>

                    <div>
                        <label for="contact_phone" class="block text-sm font-semibold tracking-wider uppercase mb-2">Phone</label>
                        <input type="tel" id="contact_phone" name="contact_phone" required
                               class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-white focus:outline-none transition-colors placeholder-gray-600"
                               placeholder="Your phone number">
                    </div>

                    <div>
                        <label for="contact_message" class="block text-sm font-semibold tracking-wider uppercase mb-2">Message</label>
                        <textarea id="contact_message" name="contact_message" required rows="5"
                                  class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-white focus:outline-none transition-colors placeholder-gray-600 resize-none"
                                  placeholder="How can we help you?"></textarea>
                    </div>

                    <button type="submit" name="contact_submit" class="w-full py-4 bg-amber-400 text-black font-semibold text-sm tracking-wider uppercase hover:bg-amber-300 transition-all duration-300 rounded-lg">
                        Send Message
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Google Map -->
<section class="py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 animate-on-scroll">
            <p class="text-xs tracking-[0.5em] text-amber-400/70 uppercase mb-3">Find Us</p>
            <h2 class="font-playfair text-3xl md:text-4xl font-bold">Our Location</h2>
        </div>
        <div class="glass rounded-xl overflow-hidden animate-on-scroll" style="height: 400px;">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d54868.25977646147!2d73.4095!3d30.8138!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3922b62cd06a595b%3A0x1234567890abcdef!2sOkara%2C%20Punjab%2C%20Pakistan!5e0!3m2!1sen!2s!4v1710000000000!5m2!1sen!2s"
                width="100%" 
                height="100%" 
                style="border:0; filter: grayscale(60%) contrast(1.1);" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
