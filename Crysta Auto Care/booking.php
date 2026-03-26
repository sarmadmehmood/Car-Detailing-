<?php
/**
 * Booking Page - Crysta Auto Care
 * Allows users to submit a booking request
 */
$page_title = 'Book Now';
require_once 'includes/db.php';
require_once 'includes/header.php';

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $name = trim(htmlspecialchars($_POST['name'] ?? ''));
    $phone = trim(htmlspecialchars($_POST['phone'] ?? ''));
    $service = trim(htmlspecialchars($_POST['service'] ?? ''));
    $car_type = trim(htmlspecialchars($_POST['car_type'] ?? ''));
    $car_name = trim(htmlspecialchars($_POST['car_name'] ?? ''));
    $booking_date = trim($_POST['booking_date'] ?? '');
    $booking_time = trim($_POST['booking_time'] ?? '');
    $payment_method = trim(htmlspecialchars($_POST['payment_method'] ?? ''));
    $location = trim(htmlspecialchars($_POST['location'] ?? ''));
    $payment_proof = null;

    // Validation
    if (empty($name) || empty($phone) || empty($service) || empty($car_type) || empty($car_name) || empty($booking_date) || empty($booking_time) || empty($payment_method) || empty($location)) {
        $error = 'Please fill in all required fields.';
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $booking_date)) {
        $error = 'Invalid date format.';
    } elseif (strtotime($booking_date) < strtotime('today')) {
        $error = 'Booking date must be today or in the future.';
    } else {
        // Handle payment proof upload for Easypaisa/JazzCash
        if (in_array($payment_method, ['Easypaisa', 'JazzCash'])) {
            if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                $file_type = $_FILES['payment_proof']['type'];
                $file_size = $_FILES['payment_proof']['size'];

                if (!in_array($file_type, $allowed_types)) {
                    $error = 'Payment proof must be an image (JPG, PNG, or WebP).';
                } elseif ($file_size > 5 * 1024 * 1024) { // 5MB limit
                    $error = 'Payment proof image must be under 5MB.';
                } else {
                    $ext = pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION);
                    $filename = 'payment_' . time() . '_' . uniqid() . '.' . $ext;
                    $upload_path = 'uploads/payments/' . $filename;

                    if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $upload_path)) {
                        $payment_proof = $filename;
                    } else {
                        $error = 'Failed to upload payment proof. Please try again.';
                    }
                }
            } else {
                $error = 'Payment proof is required for ' . $payment_method . ' payments.';
            }
        }

        // Insert booking if no errors
        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO bookings (name, phone, service, car_type, car_name, booking_date, booking_time, payment_method, payment_proof, location, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
            $stmt->bind_param("ssssssssss", $name, $phone, $service, $car_type, $car_name, $booking_date, $booking_time, $payment_method, $payment_proof, $location);

            if ($stmt->execute()) {
                $success = 'Your booking has been submitted successfully! We will contact you shortly to confirm.';
            } else {
                $error = 'Something went wrong. Please try again.';
            }
            $stmt->close();
        }
    }
}

// Pre-select service from URL parameter
$selected_service = isset($_GET['service']) ? htmlspecialchars($_GET['service']) : '';
?>

<!-- Page Header -->
<section class="py-16 sm:py-20 bg-gradient-to-b from-gray-900/50 to-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-xs tracking-[0.3em] sm:tracking-[0.5em] text-amber-400/70 uppercase mb-4 fade-in">Premium Car Detailing Service in Okara</p>
        <h1 class="font-playfair text-4xl sm:text-5xl md:text-6xl font-bold mb-4 fade-in">Book Your Detail</h1>
        <p class="text-gray-400 max-w-xl mx-auto fade-in">Choose your package, pick a time, and we'll handle the rest. Starting from just Rs. 800.</p>
    </div>
</section>

<!-- Booking Form -->
<section class="py-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if ($success): ?>
        <div class="mb-8 p-6 border border-green-800 bg-green-900/20 rounded-lg text-center fade-in">
            <svg class="w-12 h-12 mx-auto mb-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <p class="text-green-400 font-semibold text-lg"><?php echo $success; ?></p>
            <a href="index.php" class="inline-block mt-4 text-sm text-gray-400 hover:text-white transition-colors">← Back to Home</a>
        </div>
        <?php else: ?>

        <?php if ($error): ?>
        <div class="mb-8 p-4 border border-red-800 bg-red-900/20 rounded-lg fade-in">
            <p class="text-red-400 text-sm"><?php echo $error; ?></p>
        </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="glass rounded-xl p-8 md:p-12 space-y-6 fade-in">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-semibold tracking-wider uppercase mb-2">Full Name <span class="text-red-400">*</span></label>
                <input type="text" id="name" name="name" required value="<?php echo isset($name) ? $name : ''; ?>"
                       class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-white focus:outline-none transition-colors placeholder-gray-600"
                       placeholder="Enter your full name">
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-semibold tracking-wider uppercase mb-2">Phone Number <span class="text-red-400">*</span></label>
                <input type="tel" id="phone" name="phone" required value="<?php echo isset($phone) ? $phone : ''; ?>"
                       class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-white focus:outline-none transition-colors placeholder-gray-600"
                       placeholder="e.g., 0300-1234567">
            </div>

            <!-- Service -->
            <div>
                <label for="service" class="block text-sm font-semibold tracking-wider uppercase mb-2">Service <span class="text-red-400">*</span></label>
                <select id="service" name="service" required
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-white focus:outline-none transition-colors appearance-none cursor-pointer">
                    <option value="">Select a package</option>
                    <option value="Essential Care" <?php echo ($selected_service === 'Essential Care') ? 'selected' : ''; ?>>🥉 Essential Care — from Rs. 800</option>
                    <option value="Premium Care" <?php echo ($selected_service === 'Premium Care') ? 'selected' : ''; ?>>⭐ Premium Care — from Rs. 2,000 (Most Popular)</option>
                    <option value="Ultimate Royal" <?php echo ($selected_service === 'Ultimate Royal') ? 'selected' : ''; ?>>👑 Ultimate Royal — from Rs. 3,000</option>
                    <option value="Ceramic Coating" <?php echo ($selected_service === 'Ceramic Coating') ? 'selected' : ''; ?>>✨ Ceramic Coating — from Rs. 8,000</option>
                </select>
            </div>

            <!-- Car Type -->
            <div>
                <label for="car_type" class="block text-sm font-semibold tracking-wider uppercase mb-2">Car Type <span class="text-red-400">*</span></label>
                <select id="car_type" name="car_type" required
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-white focus:outline-none transition-colors appearance-none cursor-pointer">
                    <option value="">Select car type</option>
                    <option value="Small" <?php echo (isset($car_type) && $car_type === 'Small') ? 'selected' : ''; ?>>Small (Hatchback / Mini)</option>
                    <option value="Sedan" <?php echo (isset($car_type) && $car_type === 'Sedan') ? 'selected' : ''; ?>>Sedan (Sedan / Compact)</option>
                    <option value="SUV" <?php echo (isset($car_type) && $car_type === 'SUV') ? 'selected' : ''; ?>>SUV (SUV / Crossover)</option>
                </select>
            </div>

            <!-- Car Name -->
            <div>
                <label for="car_name" class="block text-sm font-semibold tracking-wider uppercase mb-2">Car Name / Model <span class="text-red-400">*</span></label>
                <input type="text" id="car_name" name="car_name" required value="<?php echo isset($car_name) ? $car_name : ''; ?>"
                       class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-white focus:outline-none transition-colors placeholder-gray-600"
                       placeholder="e.g., Toyota Corolla, Honda Civic, Suzuki Alto">
            </div>

            <!-- Location -->
            <div>
                <label for="location" class="block text-sm font-semibold tracking-wider uppercase mb-2">Your Location <span class="text-red-400">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <input type="text" id="location" name="location" required value="<?php echo isset($location) ? $location : ''; ?>"
                           class="w-full bg-black border border-gray-700 rounded-lg pl-12 pr-4 py-3 text-white focus:border-white focus:outline-none transition-colors placeholder-gray-600"
                           placeholder="e.g., Main Bazar, Okara or House #123, Street 5, Okara">
                </div>
                <p class="text-xs text-gray-600 mt-1">Enter your full address so we can serve you better.</p>
            </div>

            <!-- Booking Date & Time -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="booking_date" class="block text-sm font-semibold tracking-wider uppercase mb-2">Preferred Date <span class="text-red-400">*</span></label>
                    <input type="date" id="booking_date" name="booking_date" required value="<?php echo isset($booking_date) ? $booking_date : ''; ?>"
                           min="<?php echo date('Y-m-d'); ?>"
                           class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-white focus:outline-none transition-colors">
                </div>
                <div>
                    <label for="booking_time" class="block text-sm font-semibold tracking-wider uppercase mb-2">Preferred Time <span class="text-red-400">*</span></label>
                    <select id="booking_time" name="booking_time" required
                            class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-white focus:outline-none transition-colors appearance-none cursor-pointer">
                        <option value="">Select time slot</option>
                        <option value="09:00 AM" <?php echo (isset($booking_time) && $booking_time === '09:00 AM') ? 'selected' : ''; ?>>09:00 AM</option>
                        <option value="10:00 AM" <?php echo (isset($booking_time) && $booking_time === '10:00 AM') ? 'selected' : ''; ?>>10:00 AM</option>
                        <option value="11:00 AM" <?php echo (isset($booking_time) && $booking_time === '11:00 AM') ? 'selected' : ''; ?>>11:00 AM</option>
                        <option value="12:00 PM" <?php echo (isset($booking_time) && $booking_time === '12:00 PM') ? 'selected' : ''; ?>>12:00 PM</option>
                        <option value="01:00 PM" <?php echo (isset($booking_time) && $booking_time === '01:00 PM') ? 'selected' : ''; ?>>01:00 PM</option>
                        <option value="02:00 PM" <?php echo (isset($booking_time) && $booking_time === '02:00 PM') ? 'selected' : ''; ?>>02:00 PM</option>
                        <option value="03:00 PM" <?php echo (isset($booking_time) && $booking_time === '03:00 PM') ? 'selected' : ''; ?>>03:00 PM</option>
                        <option value="04:00 PM" <?php echo (isset($booking_time) && $booking_time === '04:00 PM') ? 'selected' : ''; ?>>04:00 PM</option>
                        <option value="05:00 PM" <?php echo (isset($booking_time) && $booking_time === '05:00 PM') ? 'selected' : ''; ?>>05:00 PM</option>
                        <option value="06:00 PM" <?php echo (isset($booking_time) && $booking_time === '06:00 PM') ? 'selected' : ''; ?>>06:00 PM</option>
                    </select>
                </div>
            </div>

            <!-- Payment Method -->
            <div>
                <label for="payment_method" class="block text-sm font-semibold tracking-wider uppercase mb-2">Payment Method <span class="text-red-400">*</span></label>
                <select id="payment_method" name="payment_method" required onchange="togglePaymentProof()"
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-white focus:outline-none transition-colors appearance-none cursor-pointer">
                    <option value="">Select payment method</option>
                    <option value="Cash on Service" <?php echo (isset($payment_method) && $payment_method === 'Cash on Service') ? 'selected' : ''; ?>>Cash on Service</option>
                    <option value="Easypaisa" <?php echo (isset($payment_method) && $payment_method === 'Easypaisa') ? 'selected' : ''; ?>>Easypaisa</option>
                    <option value="JazzCash" <?php echo (isset($payment_method) && $payment_method === 'JazzCash') ? 'selected' : ''; ?>>JazzCash</option>
                </select>
            </div>

            <!-- Payment Proof (shown for Easypaisa/JazzCash) -->
            <div id="payment_proof_section" class="hidden">
                <label for="payment_proof" class="block text-sm font-semibold tracking-wider uppercase mb-2">Upload Payment Proof <span class="text-red-400">*</span></label>
                <div class="border-2 border-dashed border-gray-700 rounded-lg p-6 text-center hover:border-gray-500 transition-colors">
                    <svg class="w-10 h-10 mx-auto mb-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-sm text-gray-400 mb-2">Upload screenshot of payment</p>
                    <p class="text-xs text-gray-600 mb-3">JPG, PNG, or WebP (Max 5MB)</p>
                    <input type="file" id="payment_proof" name="payment_proof" accept="image/jpeg,image/png,image/webp"
                           class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border file:border-gray-600 file:text-sm file:font-semibold file:bg-black file:text-white hover:file:bg-gray-900 file:cursor-pointer file:transition-colors">
                </div>
                <p id="payment_account_info" class="mt-3 text-sm text-gray-400 p-4 bg-gray-900/50 rounded-lg border border-gray-800"></p>
            </div>

            <!-- Price Display -->
            <div id="price_display" class="hidden p-4 border border-gray-700 rounded-lg bg-gray-900/50">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-400">Estimated Price:</span>
                    <span id="price_amount" class="text-2xl font-bold font-playfair text-amber-400"></span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Starting from — final price may vary based on vehicle condition.</p>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-4 bg-amber-400 text-black font-semibold text-sm tracking-wider uppercase hover:bg-amber-300 transition-all duration-300 rounded-lg">
                Submit Booking Request
            </button>

            <p class="text-center text-xs text-gray-500">By submitting, you agree to our booking terms. We'll confirm your appointment via phone.</p>
        </form>
        <?php endif; ?>
    </div>
</section>

<script>
// Price matrix
const prices = {
    'Essential Care': { 'Small': 800, 'Sedan': 1200, 'SUV': 1500 },
    'Premium Care': { 'Small': 2000, 'Sedan': 2500, 'SUV': 3000 },
    'Ultimate Royal': { 'Small': 3000, 'Sedan': 4000, 'SUV': 5500 },
    'Ceramic Coating': { 'Small': 8000, 'Sedan': 12000, 'SUV': 18000 }
};

// Toggle payment proof section
function togglePaymentProof() {
    const method = document.getElementById('payment_method').value;
    const section = document.getElementById('payment_proof_section');
    const info = document.getElementById('payment_account_info');

    if (method === 'Easypaisa' || method === 'JazzCash') {
        section.classList.remove('hidden');
        if (method === 'Easypaisa') {
            info.innerHTML = '📱 <strong>Easypaisa Account:</strong> 0300-1234567 (Crysta Auto Care)';
        } else {
            info.innerHTML = '📱 <strong>JazzCash Account:</strong> 0300-1234567 (Crysta Auto Care)';
        }
    } else {
        section.classList.add('hidden');
    }
}

// Update price display
function updatePrice() {
    const service = document.getElementById('service').value;
    const carType = document.getElementById('car_type').value;
    const display = document.getElementById('price_display');
    const amount = document.getElementById('price_amount');

    if (service && carType && prices[service] && prices[service][carType]) {
        display.classList.remove('hidden');
        amount.textContent = 'Rs. ' + prices[service][carType].toLocaleString();
    } else {
        display.classList.add('hidden');
    }
}

document.getElementById('service').addEventListener('change', updatePrice);
document.getElementById('car_type').addEventListener('change', updatePrice);

// Initialize on page load
togglePaymentProof();
updatePrice();
</script>

<?php require_once 'includes/footer.php'; ?>
