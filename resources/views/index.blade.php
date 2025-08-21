<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Micro Moto Garage - Professional Motorcycle Service in Malé | MMG</title>
    <meta name="description"
        content="Expert motorcycle service in Malé. Full service, repairs, roadside assistance. Call 9996210 for quick, reliable service.">
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><circle cx='50' cy='50' r='40' fill='%23DC2626'/></svg>">
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

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -10px rgba(220, 38, 38, 0.3);
        }
    </style>
</head>

<body class="bg-black-primary text-white">
    <!-- Sticky Urgent Call Bar -->
    <div
        class="fixed bottom-0 left-0 right-0 bg-red-primary text-white py-2 px-4 text-center text-sm font-semibold z-50">
        🚨 Urgent? Call <a href="tel:+9609996210" class="underline">9996210</a> for immediate assistance
    </div>

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
                    <a href="#home" class="text-white hover:text-red-primary transition duration-200">Home</a>
                    <a href="#services" class="text-white hover:text-red-primary transition duration-200">Services</a>
                    <a href="#about" class="text-white hover:text-red-primary transition duration-200">About</a>
                    <a href="#contact" class="text-white hover:text-red-primary transition duration-200">Contact</a>
                    <a href="/booking"
                        class="bg-red-primary hover:bg-red-secondary text-white px-4 py-2 rounded-lg transition duration-200">Book
                        Service</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-gradient py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
                Expert Motorcycle Service<br>
                <span class="text-red-primary">in Malé</span>
            </h1>
            <p class="text-xl text-gray-300 mb-8 max-w-3xl mx-auto">
                Fast, reliable motorcycle service for office-goers and commuters.
                Same-day service available. Professional mechanics, transparent pricing.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                <a href="/booking"
                    class="bg-red-primary hover:bg-red-secondary text-white px-8 py-4 rounded-lg text-lg font-semibold transition duration-200">
                    🛠️ Book Service
                </a>
                <a href="tel:+9609996210"
                    class="bg-gray-dark hover:bg-gray-600 text-white px-8 py-4 rounded-lg text-lg font-semibold transition duration-200">
                    📞 Call 9996210
                </a>
            </div>

            <!-- Trust Badges -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <div class="bg-black-secondary rounded-lg p-6 card-hover">
                    <div class="text-3xl mb-3">🚚</div>
                    <h3 class="text-lg font-semibold mb-2">Roadside Pickup</h3>
                    <p class="text-gray-400">We come to you. Free pickup in Malé.</p>
                </div>
                <div class="bg-black-secondary rounded-lg p-6 card-hover">
                    <div class="text-3xl mb-3">🔧</div>
                    <h3 class="text-lg font-semibold mb-2">Genuine Parts</h3>
                    <p class="text-gray-400">Quality parts with warranty.</p>
                </div>
                <div class="bg-black-secondary rounded-lg p-6 card-hover">
                    <div class="text-3xl mb-3">✅</div>
                    <h3 class="text-lg font-semibold mb-2">Warranty on Work</h3>
                    <p class="text-gray-400">30-day warranty on all services.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Services -->
    <section id="services" class="py-16 bg-black-secondary">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Our Services</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-black-primary rounded-lg p-6 text-center">
                    <div class="text-2xl mb-3">🔧</div>
                    <h3 class="font-semibold mb-2">Full Service</h3>
                    <p class="text-sm text-gray-400">Complete maintenance</p>
                </div>
                <div class="bg-black-primary rounded-lg p-6 text-center">
                    <div class="text-2xl mb-3">🛢️</div>
                    <h3 class="font-semibold mb-2">Oil Change</h3>
                    <p class="text-sm text-gray-400">Quick oil service</p>
                </div>
                <div class="bg-black-primary rounded-lg p-6 text-center">
                    <div class="text-2xl mb-3">🛞</div>
                    <h3 class="font-semibold mb-2">Tyres</h3>
                    <p class="text-sm text-gray-400">Tyre change & balance</p>
                </div>
                <div class="bg-black-primary rounded-lg p-6 text-center">
                    <div class="text-2xl mb-3">🛑</div>
                    <h3 class="font-semibold mb-2">Brakes</h3>
                    <p class="text-sm text-gray-400">Brake service</p>
                </div>
                <div class="bg-black-primary rounded-lg p-6 text-center">
                    <div class="text-2xl mb-3">⚡</div>
                    <h3 class="font-semibold mb-2">Electrical</h3>
                    <p class="text-sm text-gray-400">Electrical repairs</p>
                </div>
                <div class="bg-black-primary rounded-lg p-6 text-center">
                    <div class="text-2xl mb-3">🔨</div>
                    <h3 class="font-semibold mb-2">Engine Overhaul</h3>
                    <p class="text-sm text-gray-400">Major engine work</p>
                </div>
                <div class="bg-black-primary rounded-lg p-6 text-center">
                    <div class="text-2xl mb-3">🚚</div>
                    <h3 class="font-semibold mb-2">Roadside Help</h3>
                    <p class="text-sm text-gray-400">Emergency assistance</p>
                </div>
                <div class="bg-black-primary rounded-lg p-6 text-center">
                    <div class="text-2xl mb-3">📋</div>
                    <h3 class="font-semibold mb-2">Road-Worthiness</h3>
                    <p class="text-sm text-gray-400">Inspection service</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose MMG -->
    <section class="py-16 bg-black-primary">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Why Choose MMG?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-4xl mb-4">⚡</div>
                    <h3 class="text-xl font-semibold mb-3">Fast Turnaround</h3>
                    <p class="text-gray-400">Same-day service for most repairs. Quick oil changes in 30 minutes.</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl mb-4">👨‍🔧</div>
                    <h3 class="text-xl font-semibold mb-3">Expert Mechanics</h3>
                    <p class="text-gray-400">Experienced team with 10+ years in motorcycle service.</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl mb-4">💰</div>
                    <h3 class="text-xl font-semibold mb-3">Transparent Pricing</h3>
                    <p class="text-gray-400">No hidden costs. Get a quote before we start work.</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl mb-4">🚚</div>
                    <h3 class="text-xl font-semibold mb-3">Pickup & Delivery</h3>
                    <p class="text-gray-400">Free pickup in Malé. Delivery back to your location.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Promo Strip -->
    <section class="bg-red-primary py-8">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl font-bold mb-2">Special Offer</h2>
            <p class="text-lg">Full Service from MVR 500</p>
            <p class="text-sm mt-2">Book now and save! Limited time offer.</p>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-16 bg-black-secondary">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">What Our Customers Say</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-black-primary rounded-lg p-6">
                    <p class="text-gray-300 mb-4">"Fast service, fair price. My bike was ready in 2 hours. Highly
                        recommend!"</p>
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-red-primary rounded-full flex items-center justify-center text-white font-bold">
                            A</div>
                        <div class="ml-3">
                            <p class="font-semibold">Ahmed</p>
                            <p class="text-sm text-gray-400">Hulhumalé</p>
                        </div>
                    </div>
                </div>
                <div class="bg-black-primary rounded-lg p-6">
                    <p class="text-gray-300 mb-4">"They picked up my bike from work and delivered it back. Very
                        convenient!"</p>
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-red-primary rounded-full flex items-center justify-center text-white font-bold">
                            S</div>
                        <div class="ml-3">
                            <p class="font-semibold">Sarah</p>
                            <p class="text-sm text-gray-400">Malé City</p>
                        </div>
                    </div>
                </div>
                <div class="bg-black-primary rounded-lg p-6">
                    <p class="text-gray-300 mb-4">"Honest mechanics. They found an issue I didn't know about and fixed
                        it properly."</p>
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-red-primary rounded-full flex items-center justify-center text-white font-bold">
                            M</div>
                        <div class="ml-3">
                            <p class="font-semibold">Mohamed</p>
                            <p class="text-sm text-gray-400">Villingili</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Us -->
    <section id="about" class="py-16 bg-black-primary">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold mb-6">About Micro Moto Garage</h2>
                    <p class="text-gray-300 mb-6">
                        We're a local team of motorcycle enthusiasts serving Malé since 2018.
                        We focus on office-goers and commuters who need reliable, fast service.
                    </p>
                    <p class="text-gray-300 mb-6">
                        Our mission is simple: keep your motorcycle running safely and efficiently.
                        We believe in fair pricing, honest work, and customer satisfaction.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-primary">5+</div>
                            <div class="text-sm text-gray-400">Years Experience</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-primary">1000+</div>
                            <div class="text-sm text-gray-400">Happy Customers</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-primary">10AM to 6PM</div>
                            <div class="text-sm text-gray-400">Emergency Support</div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-dark rounded-lg p-8">
                    <h3 class="text-xl font-semibold mb-4">What We Stand For</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="text-red-primary mr-3">🛡️</div>
                            <div>
                                <h4 class="font-semibold">Safety First</h4>
                                <p class="text-sm text-gray-400">We never compromise on safety standards</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="text-red-primary mr-3">⚙️</div>
                            <div>
                                <h4 class="font-semibold">Reliability</h4>
                                <p class="text-sm text-gray-400">Your bike works when you need it</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="text-red-primary mr-3">💰</div>
                            <div>
                                <h4 class="font-semibold">Fair Pricing</h4>
                                <p class="text-sm text-gray-400">No hidden costs, transparent quotes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact & Location -->
    <section id="contact" class="py-16 bg-black-secondary">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Contact & Location</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div>
                    <div class="bg-black-primary rounded-lg p-8">
                        <h3 class="text-xl font-semibold mb-6">Get in Touch</h3>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="text-red-primary mr-4 text-2xl">📞</div>
                                <div>
                                    <p class="font-semibold">Hotline</p>
                                    <a href="tel:+9609996210"
                                        class="text-red-primary hover:text-red-secondary">9996210</a>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="text-red-primary mr-4 text-2xl">💬</div>
                                <div>
                                    <p class="font-semibold">WhatsApp</p>
                                    <a href="https://wa.me/9609996210"
                                        class="text-red-primary hover:text-red-secondary">Send Message</a>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="text-red-primary mr-4 text-2xl">📍</div>
                                <div>
                                    <p class="font-semibold">Location</p>
                                    <p class="text-gray-400">Janavaree Hingun, near Dharubaarige, Malé City</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="text-red-primary mr-4 text-2xl">🕒</div>
                                <div>
                                    <p class="font-semibold">Operating Hours</p>
                                    <p class="text-gray-400">8:00 AM - 10:00 PM</p>
                                    <p class="text-gray-400 text-sm">Friday: Closed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="bg-gray-dark rounded-lg p-8 h-96 flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-4xl mb-4">🗺️</div>
                            <p class="text-lg font-semibold mb-4">Google Maps</p>
                            <p class="text-gray-400 mb-4">Micro Moto Garage, Malé City</p>
                            <a href="https://maps.google.com/?q=Micro+Moto+Garage+Male+City" target="_blank"
                                class="bg-red-primary hover:bg-red-secondary text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                                Open in Maps
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black-primary border-t border-gray-600 py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="text-red-primary text-2xl">🏍️</div>
                        <div>
                            <h3 class="text-xl font-bold">Micro Moto Garage</h3>
                            <p class="text-sm text-gray-400">Professional Motorcycle Service</p>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm">
                        Expert motorcycle service in Malé. Fast, reliable, and honest work.
                    </p>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#home" class="text-gray-400 hover:text-white transition duration-200">Home</a></li>
                        <li><a href="#services"
                                class="text-gray-400 hover:text-white transition duration-200">Services</a></li>
                        <li><a href="#about" class="text-gray-400 hover:text-white transition duration-200">About</a>
                        </li>
                        <li><a href="#contact"
                                class="text-gray-400 hover:text-white transition duration-200">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Contact Info</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>📍 Janavaree Hingun, Malé</li>
                        <li>📞 <a href="tel:+9609996210" class="hover:text-white">9996210</a></li>
                        <li>💬 <a href="https://wa.me/9609996210" class="hover:text-white">WhatsApp</a></li>
                        <li>📧 info@mmg.mv</li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Follow Us</h4>
                    <div class="flex space-x-4">
                        <a href="https://facebook.com/micromotogarage" target="_blank"
                            class="text-gray-400 hover:text-red-primary transition duration-200 text-2xl">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="https://instagram.com/micromotomv" target="_blank"
                            class="text-gray-400 hover:text-red-primary transition duration-200 text-2xl">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.323-1.297C4.198 14.895 3.708 13.744 3.708 12.447s.49-2.448 1.418-3.323c.875-.807 2.026-1.297 3.323-1.297s2.448.49 3.323 1.297c.928.875 1.418 2.026 1.418 3.323s-.49 2.448-1.418 3.244c-.875.807-2.026 1.297-3.323 1.297zm7.83-9.781c-.49 0-.928-.175-1.297-.49-.368-.315-.49-.753-.49-1.243 0-.49.122-.928.49-1.243.369-.315.807-.49 1.297-.49s.928.175 1.297.49c.368.315.49.753.49 1.243 0 .49-.122.928-.49 1.243-.369.315-.807.49-1.297.49z" />
                            </svg>
                        </a>
                        <a href="https://x.com/micromotomv" target="_blank"
                            class="text-gray-400 hover:text-red-primary transition duration-200 text-2xl">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                        <a href="https://tiktok.com/micromotomv" target="_blank"
                            class="text-gray-400 hover:text-red-primary transition duration-200 text-2xl">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-600 mt-8 pt-8 text-center">
                <p class="text-sm text-gray-400">
                    Road-worthiness checks are performed per local guidelines; final approval rests with authorities.
                </p>
                <p class="text-sm text-gray-400 mt-2">
                    © 2024 Micro Moto Garage. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>

</html>