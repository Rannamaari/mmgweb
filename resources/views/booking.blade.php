<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Service - Micro Moto Garage</title>
    <meta name="description"
        content="Book your motorcycle service with Micro Moto Garage. Fast, reliable service in Malé.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-E7NSJ7WHVP"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-E7NSJ7WHVP');
    </script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'red-primary': '#DC2626',
                        'red-secondary': '#EF4444',
                        'black-primary': '#0F0F0F',
                        'black-secondary': '#1F1F1F',
                        'gray-dark': '#2D2D2D',
                    }
                }
            }
        }
    </script>
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #0F0F0F 0%, #1F1F1F 100%);
        }

        .form-input {
            transition: all 0.3s ease;
        }

        .form-input:focus {
            transform: translateY(-2px);
        }
    </style>
</head>

<body class="bg-black-primary text-white">
    <!-- Navigation -->
    <nav class="bg-black-secondary border-b border-red-primary/20 sticky top-0 z-40">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <div class="text-red-primary text-3xl">🏍️</div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">Micro Moto Garage</h1>
                        <p class="text-gray-400 text-sm">Professional Motorcycle Service</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-6">
                    <a href="/" class="text-white hover:text-red-primary transition duration-200">Home</a>
                    <a href="/#services" class="text-white hover:text-red-primary transition duration-200">Services</a>
                    <a href="/#about" class="text-white hover:text-red-primary transition duration-200">About</a>
                    <a href="/#contact" class="text-white hover:text-red-primary transition duration-200">Contact</a>
                    <a href="tel:+9609996210"
                        class="bg-red-primary hover:bg-red-secondary text-white px-4 py-2 rounded-lg transition duration-200">Call
                        9996210</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Book Your Service
            </h1>
            <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
                Schedule your motorcycle service with our expert team.
                We'll contact you to confirm your appointment.
            </p>
        </div>
    </section>

    <!-- Booking Form -->
    <section class="py-16 bg-black-secondary">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="bg-black-primary rounded-lg p-8">
                    <form id="bookingForm" class="space-y-6">
                        @csrf
                        <!-- Customer Information -->
                        <div class="border-b border-gray-600 pb-6">
                            <h3 class="text-xl font-semibold mb-4 text-red-primary">👤 Customer Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Name *</label>
                                    <input type="text" name="name" required
                                        class="w-full px-4 py-3 bg-gray-dark border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-red-primary form-input"
                                        placeholder="Your full name">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Phone/WhatsApp *</label>
                                    <input type="tel" name="phone" required
                                        class="w-full px-4 py-3 bg-gray-dark border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-red-primary form-input"
                                        placeholder="9996210">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">Email (Optional)</label>
                                <input type="email" name="email"
                                    class="w-full px-4 py-3 bg-gray-dark border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-red-primary form-input"
                                    placeholder="your.email@example.com">
                            </div>
                        </div>

                        <!-- Service Information -->
                        <div class="border-b border-gray-600 pb-6">
                            <h3 class="text-xl font-semibold mb-4 text-red-primary">🔧 Service Information</h3>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Service Type *</label>
                                <select name="service_type" required
                                    class="w-full px-4 py-3 bg-gray-dark border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-primary form-input">
                                    <option value="">Select service</option>
                                    <option value="Full Service">Full Service</option>
                                    <option value="Oil Change">Oil Change</option>
                                    <option value="Tyre Change">Tyre Change</option>
                                    <option value="Brake Service">Brake Service</option>
                                    <option value="Electrical Repair">Electrical Repair</option>
                                    <option value="Engine Overhaul">Engine Overhaul</option>
                                    <option value="Wash/Detail">Wash/Detail</option>
                                    <option value="Body Wrap">Body Wrap</option>
                                    <option value="Road-Worthiness">Road-Worthiness</option>
                                    <option value="Custom Work">Custom Work</option>
                                </select>
                            </div>
                        </div>

                        <!-- Bike Information -->
                        <div class="border-b border-gray-600 pb-6">
                            <h3 class="text-xl font-semibold mb-4 text-red-primary">🏍️ Bike Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Bike Make *</label>
                                    <input type="text" name="bike_make" required
                                        class="w-full px-4 py-3 bg-gray-dark border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-red-primary form-input"
                                        placeholder="e.g., Honda, Yamaha">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Bike Model *</label>
                                    <input type="text" name="bike_model" required
                                        class="w-full px-4 py-3 bg-gray-dark border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-red-primary form-input"
                                        placeholder="e.g., CBR150R, R15">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Year (Optional)</label>
                                    <input type="text" name="bike_year"
                                        class="w-full px-4 py-3 bg-gray-dark border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-red-primary form-input"
                                        placeholder="e.g., 2020">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Plate Number
                                        (Optional)</label>
                                    <input type="text" name="plate_number"
                                        class="w-full px-4 py-3 bg-gray-dark border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-red-primary form-input"
                                        placeholder="e.g., A-12345">
                                </div>
                            </div>
                        </div>

                        <!-- Appointment Details -->
                        <div class="border-b border-gray-600 pb-6">
                            <h3 class="text-xl font-semibold mb-4 text-red-primary">📅 Appointment Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Preferred Date *</label>
                                    <input type="date" name="preferred_date" required
                                        class="w-full px-4 py-3 bg-gray-dark border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-primary form-input">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Preferred Time *</label>
                                    <select name="preferred_time" required
                                        class="w-full px-4 py-3 bg-gray-dark border border-gray-600 rounded-lg text-white focus:outline-none focus:border-red-primary form-input">
                                        <option value="">Select time</option>
                                        <option value="08:00">8:00 AM</option>
                                        <option value="10:00">10:00 AM</option>
                                        <option value="12:00">12:00 PM</option>
                                        <option value="14:00">2:00 PM</option>
                                        <option value="16:00">4:00 PM</option>
                                        <option value="18:00">6:00 PM</option>
                                        <option value="20:00">8:00 PM</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Pickup & Delivery -->
                        <div class="border-b border-gray-600 pb-6">
                            <h3 class="text-xl font-semibold mb-4 text-red-primary">🚚 Pickup & Delivery</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="pickup_needed" value="1"
                                            class="mr-3 w-4 h-4 text-red-primary bg-gray-dark border-gray-600 rounded focus:ring-red-primary">
                                        <span class="text-gray-300">I need pickup and delivery service</span>
                                    </label>
                                </div>
                                <div id="pickupAddressField" class="hidden">
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Pickup Address</label>
                                    <textarea name="pickup_address" rows="3"
                                        class="w-full px-4 py-3 bg-gray-dark border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-red-primary form-input resize-none"
                                        placeholder="Enter your address for pickup"></textarea>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Pickup fees: Malé MVR 50, Hulhumalé Phase 1 MVR 100, Hulhumalé Phase 2 MVR 150
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Issue Description -->
                        <div>
                            <h3 class="text-xl font-semibold mb-4 text-red-primary">📝 Issue Description</h3>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Describe the Issue
                                    (Optional)</label>
                                <textarea name="issue_description" rows="4"
                                    class="w-full px-4 py-3 bg-gray-dark border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-red-primary form-input resize-none"
                                    placeholder="Tell us about your motorcycle problem or what service you need"></textarea>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-6">
                            <button type="submit" id="submitBtn"
                                class="w-full bg-red-primary hover:bg-red-secondary text-white py-4 rounded-lg font-semibold text-lg transition duration-200 flex items-center justify-center space-x-2">
                                <span id="submitText">📋 Submit Booking</span>
                                <span id="loadingText" class="hidden">⏳ Processing...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Info -->
    <section class="py-16 bg-black-primary">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-8">Need Help?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                <div class="bg-black-secondary rounded-lg p-6">
                    <div class="text-3xl mb-4">📞</div>
                    <h3 class="text-xl font-semibold mb-2">Call Us</h3>
                    <p class="text-gray-400 mb-4">Speak directly with our team</p>
                    <a href="tel:+9609996210"
                        class="text-red-primary hover:text-red-secondary font-semibold">9996210</a>
                </div>
                <div class="bg-black-secondary rounded-lg p-6">
                    <div class="text-3xl mb-4">💬</div>
                    <h3 class="text-xl font-semibold mb-2">WhatsApp</h3>
                    <p class="text-gray-400 mb-4">Send us a message</p>
                    <a href="https://wa.me/9609996210"
                        class="text-red-primary hover:text-red-secondary font-semibold">Send Message</a>
                </div>
                <div class="bg-black-secondary rounded-lg p-6">
                    <div class="text-3xl mb-4">📍</div>
                    <h3 class="text-xl font-semibold mb-2">Visit Us</h3>
                    <p class="text-gray-400 mb-4">Janavaree Hingun, Malé</p>
                    <a href="https://maps.google.com/?q=Micro+Moto+Garage+Male+City" target="_blank"
                        class="text-red-primary hover:text-red-secondary font-semibold">Open in Maps</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black-secondary border-t border-gray-600 py-8">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-400">
                © 2024 Micro Moto Garage. All rights reserved.
            </p>
        </div>
    </footer>

    <script>
        // Handle pickup address field visibility
        document.querySelector('input[name="pickup_needed"]').addEventListener('change', function () {
            const pickupField = document.getElementById('pickupAddressField');
            if (this.checked) {
                pickupField.classList.remove('hidden');
            } else {
                pickupField.classList.add('hidden');
            }
        });

        // Handle form submission
        document.getElementById('bookingForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const loadingText = document.getElementById('loadingText');

            // Show loading state
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            loadingText.classList.remove('hidden');

            // Collect form data
            const formData = new FormData(this);

            // Add CSRF token to form data
            const csrfToken = document.querySelector('input[name="_token"]').value;
            formData.append('_token', csrfToken);

            try {
                const response = await fetch('/booking', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    alert(result.message);
                    this.reset();
                    document.getElementById('pickupAddressField').classList.add('hidden');
                } else {
                    let errorMessage = result.message || 'There was an error submitting your booking. Please try again.';

                    // Add validation errors if available
                    if (result.errors) {
                        const errorDetails = Object.values(result.errors).flat().join('\n');
                        errorMessage += '\n\nDetails:\n' + errorDetails;
                    }

                    alert(errorMessage);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('There was an error submitting your booking. Please try again or call us at 9996210.');
            } finally {
                // Reset button state
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                loadingText.classList.add('hidden');
            }
        });

        // Set minimum date to today
        const dateInput = document.querySelector('input[name="preferred_date"]');
        const today = new Date().toISOString().split('T')[0];
        dateInput.min = today;
    </script>
</body>

</html>