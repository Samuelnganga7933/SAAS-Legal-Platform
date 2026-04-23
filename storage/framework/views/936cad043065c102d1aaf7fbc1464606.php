

<?php $__env->startSection('title', 'Le Nium Legal - Remote Guidance & Support Services'); ?>

<?php $__env->startSection('content'); ?>

<!-- Hero Section -->
<section id="hero" style="position: relative; width: 100%; height: 100vh; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #000;">
    
    <!-- Background Video -->
    <video id="hero-video" autoplay muted loop playsinline preload="auto" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; display: block;">
        <source src="/videos/background-vid.mp4" type="video/mp4">
    </video>

    <!-- Dark Overlay -->
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); z-index: 5;"></div>

    <!-- Content -->
    <div style="position: relative; z-index: 10; text-align: center; color: white; max-width: 800px; padding: 20px;">
        <h1 style="font-size: 3.5rem; font-weight: bold; margin-bottom: 1rem;">Le Nium Legal</h1>
        <p style="font-size: 1.5rem; margin-bottom: 1rem;">Global Guidance & Support Services</p>
        <p style="font-size: 1.25rem; margin-bottom: 2rem;">Information, resources, and operational support for your business — globally and remotely.</p>
        <p style="font-size: 1rem; margin-bottom: 2rem;">Offering guidance and informational support in legal operations, compliance, and accounting services.</p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <button onclick="openSignupModal()" style="background: #2563eb; color: white; padding: 0.75rem 2rem; border-radius: 0.5rem; border: none; cursor: pointer; font-weight: bold;">Sign Up</button>
            <a href="#contact" style="border: 2px solid white; color: white; padding: 0.75rem 2rem; border-radius: 0.5rem; text-decoration: none; font-weight: bold;">Book a Call</a>
            <a href="mailto:leniumtradinggroup@outlook.com" style="border: 2px solid white; color: white; padding: 0.75rem 2rem; border-radius: 0.5rem; text-decoration: none; font-weight: bold;">Email Us</a>
        </div>
    </div>
</section>

<!-- Quick Info Section -->
<section class="py-12 px-4 bg-blue-600">
    <div class="container mx-auto">
        <div class="grid md:grid-cols-3 gap-8 max-w-4xl mx-auto text-center">
            <div class="text-white">
                <div class="text-4xl font-bold mb-2">3</div>
                <p class="text-blue-100">Locations<br/>UK • US • Dubai</p>
            </div>
            <div class="text-white">
                <div class="text-4xl font-bold mb-2">100%</div>
                <p class="text-blue-100">Remote<br/>Service Delivery</p>
            </div>
            <div class="text-white">
                <div class="text-4xl font-bold mb-2">B2B+B2C</div>
                <p class="text-blue-100">Services<br/>Businesses & Individuals</p>
            </div>
        </div>
    </div>
</section>

<!-- About Us Section -->
<section class="py-20 px-4 bg-white" data-parallax>
    <div class="container mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4">About Us</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">What we provide and who we serve</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <!-- Who We Are -->
            <div class="bg-blue-50 rounded-xl p-8">
                <h3 class="text-2xl font-bold text-slate-900 mb-4">Who We Are</h3>
                <p class="text-gray-700">
                    We provide informational support, guidance resources, and procedural help across legal operations and accounting domains — tailored for businesses and individuals (B2B + B2C) in the UK, US, Dubai and globally.
                </p>
            </div>

            <!-- Business Support -->
            <div class="bg-blue-50 rounded-xl p-8">
                <h3 class="text-2xl font-bold text-slate-900 mb-4">Business Support (B2B)</h3>
                <p class="text-gray-700">
                    Guidance and resources for companies navigating compliance, setup, and operational needs.
                </p>
            </div>

            <!-- Individual Support -->
            <div class="bg-blue-50 rounded-xl p-8">
                <h3 class="text-2xl font-bold text-slate-900 mb-4">Individual Support (B2C)</h3>
                <p class="text-gray-700">
                    Informational assistance for individuals and startup founders seeking guidance.
                </p>
            </div>

            <!-- Global & Remote -->
            <div class="bg-blue-50 rounded-xl p-8">
                <h3 class="text-2xl font-bold text-slate-900 mb-4">Global & Remote</h3>
                <p class="text-gray-700 mb-3">
                    Operating across UK, US, and Kenya — 100% remote service delivery.
                </p>
                <ul class="space-y-2">
                    <li class="flex gap-3">
                        <span class="text-blue-600 font-bold flex-shrink-0">✓</span>
                        <span class="text-gray-700">24/7 advisory support</span>
                    </li>
                </ul>
            </div>
            <div class="bg-blue-50 rounded-2xl p-8">
                <div class="space-y-6">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-blue-600 mb-2">15+</div>
                        <p class="text-gray-600">Brands Partnered</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-blue-600 mb-2">5,000+</div>
                        <p class="text-gray-600">Individual Clients</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-blue-600 mb-2">10+ Countries</div>
                        <p class="text-gray-600">USA, UK, UAE & More</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Service Overviews Section -->
<section class="py-20 px-4 bg-gray-50" data-parallax>
    <div class="container mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4">Service Overviews</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Guidance, support, and resources tailored to your needs</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            <div class="bg-white rounded-xl p-8 shadow-md">
                <h3 class="text-xl font-bold text-slate-900 mb-3">Business Setup Guidance</h3>
                <p class="text-gray-600 mb-4">Informational support for business registration and setup procedures in the UK, US, and Kenya.</p>
                <p class="text-sm font-semibold text-blue-600">UK • US • Kenya</p>
            </div>

            <div class="bg-white rounded-xl p-8 shadow-md">
                <h3 class="text-xl font-bold text-slate-900 mb-3">Compliance & Regulatory Support Information</h3>
                <p class="text-gray-600 mb-4">General guidance on compliance requirements and regulatory frameworks — not legal advice.</p>
                <p class="text-sm font-semibold text-blue-600">All Regions</p>
            </div>

            <div class="bg-white rounded-xl p-8 shadow-md">
                <h3 class="text-xl font-bold text-slate-900 mb-3">Document Preparation & Templates</h3>
                <p class="text-gray-600 mb-4">Access to document templates and preparation resources for common business needs.</p>
                <p class="text-sm font-semibold text-blue-600">Global</p>
            </div>

            <div class="bg-white rounded-xl p-8 shadow-md">
                <h3 class="text-xl font-bold text-slate-900 mb-3">Accounting & Reporting Guidance</h3>
                <p class="text-gray-600 mb-4">Informational support for accounting processes, bookkeeping, and financial reporting.</p>
                <p class="text-sm font-semibold text-blue-600">UK • US • Kenya</p>
            </div>

            <div class="bg-white rounded-xl p-8 shadow-md">
                <h3 class="text-xl font-bold text-slate-900 mb-3">Remote Assistance & Workflow Support</h3>
                <p class="text-gray-600 mb-4">Ongoing operational support and guidance delivered 100% remotely.</p>
                <p class="text-sm font-semibold text-blue-600">Global</p>
            </div>

            <div class="bg-white rounded-xl p-8 shadow-md">
                <h3 class="text-xl font-bold text-slate-900 mb-3">Resources & Information Hub</h3>
                <p class="text-gray-600 mb-4">Access to curated resources, guides, and informational materials for your business journey.</p>
                <p class="text-sm font-semibold text-blue-600">Global</p>
            </div>

            <div class="bg-white rounded-xl p-8 shadow-md hover:shadow-xl transition-shadow">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Financial Structuring</h3>
                <p class="text-gray-600">Optimize your trading structure for legal and tax efficiency</p>
            </div>

            <div class="bg-white rounded-xl p-8 shadow-md hover:shadow-xl transition-shadow">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Risk Management</h3>
                <p class="text-gray-600">Mitigate legal and operational risks in your trading business</p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="py-20 px-4 bg-white" data-parallax>
    <div class="container mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4">How It Works</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Simple steps to get the support you need</p>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="space-y-8">
                <div class="flex gap-6">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-600 text-white font-bold text-lg">1</div>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Connect / Submit Inquiry</h3>
                        <p class="text-gray-600">Reach out via email or WhatsApp to tell us what you need</p>
                    </div>
                </div>

                <div class="flex gap-6">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-600 text-white font-bold text-lg">2</div>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Discuss Needs</h3>
                        <p class="text-gray-600">We will discuss your requirements through mail or WhatsApp</p>
                    </div>
                </div>

                <div class="flex gap-6">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-600 text-white font-bold text-lg">3</div>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Receive Support Packets</h3>
                        <p class="text-gray-600">Get customized support packets, templates, and resources</p>
                    </div>
                </div>

                <div class="flex gap-6">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-600 text-white font-bold text-lg">4</div>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Sign Up for Membership</h3>
                        <p class="text-gray-600">Create an account for ongoing access and support</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Reviews Section -->
<section id="reviews" class="py-20 px-4 bg-gradient-to-br from-blue-50 via-slate-50 to-white" data-parallax>
    <div class="container mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4">What Our Clients Say</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Real experiences from businesses and individuals we've helped</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto mb-12">
            <!-- Review Card 1 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 hover:-translate-y-2 border-2 border-transparent hover:border-blue-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex gap-1">
                        <span class="text-yellow-400 text-xl">★★★★★</span>
                    </div>
                </div>
                <p class="text-gray-700 mb-6 italic leading-relaxed text-lg">"Le Nium Legal provided excellent guidance for setting up our UK business. The templates and support materials were incredibly helpful."</p>
                <div class="border-t-2 border-gray-100 pt-4">
                    <p class="font-bold text-slate-900 text-lg">James M.</p>
                    <p class="text-sm text-blue-600 font-semibold mb-2">Small Business Owner</p>
                    <p class="text-sm text-gray-600 flex items-center gap-1">
                        <span class="text-lg">🇬🇧</span> United Kingdom
                    </p>
                </div>
            </div>

            <!-- Review Card 2 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 hover:-translate-y-2 border-2 border-transparent hover:border-green-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex gap-1">
                        <span class="text-yellow-400 text-xl">★★★★★</span>
                    </div>
                </div>
                <p class="text-gray-700 mb-6 italic leading-relaxed text-lg">"As a first-time founder, I needed clear information about compliance requirements. The team was responsive and provided valuable resources through WhatsApp."</p>
                <div class="border-t-2 border-gray-100 pt-4">
                    <p class="font-bold text-slate-900 text-lg">Amara K.</p>
                    <p class="text-sm text-blue-600 font-semibold mb-2">Individual Startup Founder</p>
                    <p class="text-sm text-gray-600 flex items-center gap-1">
                        <span class="text-lg">🇰🇪</span> Kenya
                    </p>
                </div>
            </div>

            <!-- Review Card 3 -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 hover:-translate-y-2 border-2 border-transparent hover:border-purple-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex gap-1">
                        <span class="text-yellow-400 text-xl">★★★★★</span>
                    </div>
                </div>
                <p class="text-gray-700 mb-6 italic leading-relaxed text-lg">"The remote support model works perfectly for our distributed team. Quick responses and practical guidance that helped streamline our accounting processes."</p>
                <div class="border-t-2 border-gray-100 pt-4">
                    <p class="font-bold text-slate-900 text-lg">David R.</p>
                    <p class="text-sm text-blue-600 font-semibold mb-2">Remote Enterprise Client</p>
                    <p class="text-sm text-gray-600 flex items-center gap-1">
                        <span class="text-lg">🇺🇸</span> United States
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center bg-white rounded-xl p-6 shadow-md max-w-2xl mx-auto mb-8">
            <p class="text-gray-600 mb-4">✓ Verified Client Reviews • Reviews reflect individual experiences; results may vary. <a href="/terms" class="text-blue-600 hover:text-blue-700 font-semibold">See our Terms of Service</a>.</p>
        </div>
    </div>
</section>

<!-- Testimonial Submission Section -->
<section id="submit-testimonial" class="py-20 px-4 bg-white" data-parallax>
    <div class="container mx-auto">
        <?php echo $__env->make('partials.testimonial-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-20 px-4 bg-slate-50" data-parallax>
    <div class="container mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4">Contact & Connect</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Ready to get started? Reach out to us today</p>
        </div>

        <div class="grid lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
            <!-- Contact Info -->
            <div>
                <h3 class="text-2xl font-bold text-slate-900 mb-8">Get in Touch</h3>

                <div class="space-y-6 mb-12">
                    <a href="mailto:leniumtradinggroup@outlook.com" class="flex items-start gap-4 p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600 mb-1">Email</div>
                            <div class="text-lg font-semibold text-slate-900">leniumtradinggroup@outlook.com</div>
                        </div>
                    </a>

                    <a href="https://wa.me/254104921009" target="_blank" rel="noopener noreferrer" class="flex items-start gap-4 p-4 bg-green-50 rounded-xl hover:bg-green-100 transition-colors">
                        <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h3.5a1 1 0 011 .82l.553 2.763a1 1 0 01-.832 1.167l-1.933-.484a11.001 11.001 0 008.441 8.441l.484-1.933a1 1 0 011.167-.832l2.763.553A1 1 0 0117 13.5V17a1 1 0 01-1 1A16 16 0 013 2a1 1 0 01-1-1z"/></svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600 mb-1">WhatsApp Business</div>
                            <div class="text-lg font-semibold text-slate-900">+254 104 921 009</div>
                        </div>
                    </a>

                    <a href="https://www.instagram.com/leniumtradinggroup?igsh=NXdybnVwOW9waWV3" target="_blank" rel="noopener noreferrer" class="flex items-start gap-4 p-4 bg-pink-50 rounded-xl hover:bg-pink-100 transition-colors">
                        <div class="w-12 h-12 bg-pink-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm3.6 12c0 1.99-1.61 3.6-3.6 3.6s-3.6-1.61-3.6-3.6 1.61-3.6 3.6-3.6 3.6 1.61 3.6 3.6z"/></svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600 mb-1">Instagram</div>
                            <div class="text-lg font-semibold text-slate-900">@leniumtradinggroup</div>
                        </div>
                    </a>
                </div>

                <h3 class="text-xl font-bold text-slate-900 mb-4">Available Globally</h3>
                <p class="text-gray-600 mb-4">100% remote support across:</p>
                <div class="space-y-2 text-gray-700">
                    <p>🇬🇧 United Kingdom</p>
                    <p>🇺🇸 United States</p>
                    <p>🇰🇪 Kenya</p>
                </div>
            </div>

            <!-- Contact Form -->
            <div>
                <h3 class="text-2xl font-bold text-slate-900 mb-8">Send us a Message</h3>

                <form action="<?php echo e(route('contact.store')); ?>" method="POST" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">I am contacting as: *</label>
                        <div class="flex gap-4">
                            <label class="flex items-center">
                                <input type="radio" name="contact_type" value="business" required class="mr-2">
                                <span class="text-gray-700">Business (B2B)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="contact_type" value="individual" required class="mr-2">
                                <span class="text-gray-700">Individual (B2C)</span>
                            </label>
                        </div>
                    </div>

                    <input type="text" name="company_name" placeholder="Your Company Ltd" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    
                    <input type="email" name="email" required placeholder="info@company.com" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    
                    <select name="country" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="">Select a country</option>
                        <option value="uk">United Kingdom</option>
                        <option value="us">United States</option>
                        <option value="kenya">Kenya</option>
                        <option value="dubai">Dubai</option>
                        <option value="other">Other</option>
                    </select>
                    
                    <textarea name="message" required placeholder="Tell us how we can help you..." rows="5" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600"></textarea>
                    
                    <div class="flex items-start gap-2">
                        <input type="checkbox" id="contact-terms" name="contact_terms" required class="mt-1">
                        <label for="contact-terms" class="text-sm text-gray-600">I have read and agree to the <a href="/terms" class="text-blue-600 hover:text-blue-700 font-semibold">Terms of Service</a>. *</label>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Sign Up Modal -->
<div id="signupModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-slate-900">Create Account</h2>
                <button onclick="closeSignupModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>

            <!-- Step 1: Initial Signup Form -->
            <div id="signupStep1" class="space-y-4">
                <form id="signupForm" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Full Name *</label>
                        <input type="text" name="name" required placeholder="Your full name" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Email *</label>
                        <input type="email" name="email" required placeholder="your@email.com" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Password *</label>
                        <input type="password" name="password" required placeholder="At least 8 characters" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Confirm Password *</label>
                        <input type="password" name="password_confirmation" required placeholder="Confirm your password" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    </div>

                    <!-- reCAPTCHA -->
                    <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>

                    <div class="flex items-start gap-2 mt-4">
                        <input type="checkbox" id="terms-checkbox" name="terms" required class="mt-1">
                        <label for="terms-checkbox" class="text-sm text-gray-600">I have read, understood and do agree to the <a href="/terms" target="_blank" class="text-blue-600 hover:text-blue-700 font-semibold">Terms of Service</a> *</label>
                    </div>

                    <button type="submit" id="signupSubmitBtn" disabled class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                        Continue
                    </button>
                </form>
            </div>

            <!-- Step 2: Email Verification Code -->
            <div id="signupStep2" class="hidden space-y-4">
                <div class="bg-blue-50 p-4 rounded-lg mb-4">
                    <p class="text-sm text-gray-700"><strong>Verification code sent to your email.</strong></p>
                    <p class="text-sm text-gray-600">Enter the 6-digit code below. Valid for <span id="countdown">10:00</span></p>
                </div>

                <div class="flex justify-between gap-2 mb-4">
                    <input type="text" maxlength="1" class="verification-code w-full h-12 text-center text-2xl font-bold rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600" inputmode="numeric">
                    <input type="text" maxlength="1" class="verification-code w-full h-12 text-center text-2xl font-bold rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600" inputmode="numeric">
                    <input type="text" maxlength="1" class="verification-code w-full h-12 text-center text-2xl font-bold rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600" inputmode="numeric">
                    <input type="text" maxlength="1" class="verification-code w-full h-12 text-center text-2xl font-bold rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600" inputmode="numeric">
                    <input type="text" maxlength="1" class="verification-code w-full h-12 text-center text-2xl font-bold rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600" inputmode="numeric">
                    <input type="text" maxlength="1" class="verification-code w-full h-12 text-center text-2xl font-bold rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600" inputmode="numeric">
                </div>

                <button id="verifyCodeBtn" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    Verify & Complete Signup
                </button>

                <p class="text-sm text-gray-600 text-center">
                    <span class="text-gray-500">Can't find the code?</span><br>
                    <span class="text-xs text-gray-500">Check your spam folder or wait for the countdown timer</span>
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script>
    // Modal Functions
    function openSignupModal() {
        document.getElementById('signupModal').classList.remove('hidden');
        resetSignupModal();
    }

    function closeSignupModal() {
        document.getElementById('signupModal').classList.add('hidden');
        resetSignupModal();
    }

    function resetSignupModal() {
        document.getElementById('signupStep1').classList.remove('hidden');
        document.getElementById('signupStep2').classList.add('hidden');
        document.getElementById('signupForm').reset();
        document.getElementById('terms-checkbox').checked = false;
        updateSignupButtonState();
    }

    // Close modal when clicking outside
    document.getElementById('signupModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeSignupModal();
        }
    });

    // Terms checkbox validation
    document.getElementById('terms-checkbox')?.addEventListener('change', updateSignupButtonState);

    function updateSignupButtonState() {
        const isChecked = document.getElementById('terms-checkbox')?.checked || false;
        document.getElementById('signupSubmitBtn').disabled = !isChecked;
    }

    // Signup form submission
    document.getElementById('signupForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = {
            name: formData.get('name'),
            email: formData.get('email'),
            password: formData.get('password'),
            password_confirmation: formData.get('password_confirmation'),
            captcha_token: grecaptcha.getResponse(),
            terms: formData.get('terms')
        };

        try {
            const response = await fetch('<?php echo e(route('auth.register.initial')); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || formData.get('_token')
                },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                // Move to verification step
                document.getElementById('signupStep1').classList.add('hidden');
                document.getElementById('signupStep2').classList.remove('hidden');
                startVerificationCountdown();
            } else {
                const error = await response.json();
                alert('Error: ' + (error.message || 'Failed to create account'));
                grecaptcha.reset();
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            grecaptcha.reset();
        }
    });

    // Verification code input handling
    const codeInputs = document.querySelectorAll('.verification-code');
    codeInputs.forEach((input, index) => {
        input.addEventListener('input', function() {
            if (this.value && index < codeInputs.length - 1) {
                codeInputs[index + 1].focus();
            }
            updateVerifyButtonState();
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && index > 0) {
                codeInputs[index - 1].focus();
            }
        });
    });

    function updateVerifyButtonState() {
        const allFilled = Array.from(codeInputs).every(input => input.value.length === 1);
        document.getElementById('verifyCodeBtn').disabled = !allFilled;
    }

    // Verification countdown timer
    let countdownInterval;

    function startVerificationCountdown() {
        let timeLeft = 600; // 10 minutes in seconds

        function updateCountdown() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            document.getElementById('countdown').textContent = 
                `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                document.getElementById('verifyCodeBtn').disabled = true;
                document.getElementById('verifyCodeBtn').textContent = 'Code Expired';
                codeInputs.forEach(input => input.disabled = true);
            } else {
                timeLeft--;
            }
        }

        clearInterval(countdownInterval);
        updateCountdown();
        countdownInterval = setInterval(updateCountdown, 1000);
    }

    // Verify code submission
    document.getElementById('verifyCodeBtn')?.addEventListener('click', async function() {
        const code = Array.from(codeInputs).map(input => input.value).join('');

        try {
            const response = await fetch('<?php echo e(route('auth.register.verify')); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: JSON.stringify({ code })
            });

            if (response.ok) {
                closeSignupModal();
                // Redirect to account created page
                window.location.href = '<?php echo e(route('account.created')); ?>';
            } else {
                const error = await response.json();
                alert('Invalid code. Please try again.');
                codeInputs.forEach(input => input.value = '');
                codeInputs[0].focus();
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        }
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Le Nium Advisors website\nium-advisors-laravel\resources\views/home.blade.php ENDPATH**/ ?>