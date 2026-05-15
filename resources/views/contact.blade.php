@extends(auth()->check() ? 'layouts.app' : 'layouts.basic')

@section('header')
    @if(auth()->check())
        <x-headers.user></x-headers.user>
    @else
        <x-header></x-header>
    @endif
@endsection

@section('style')
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }

        .contact-gradient {
            background: radial-gradient(circle at top right, rgba(20, 184, 166, 0.05), transparent 40%),
                        radial-gradient(circle at bottom left, rgba(59, 130, 246, 0.05), transparent 40%);
        }

        .hero-shape {
            position: absolute;
            filter: blur(80px);
            opacity: 0.4;
            z-index: 0;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .section-reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }

        .section-reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
@endsection

@section(auth()->check() ? 'page-content' : 'body')
    <div class="{{ auth()->check() ? '' : 'pt-16' }} min-h-screen contact-gradient overflow-hidden">
        <!-- Hero Section -->
        <div class="relative py-16 px-6 md:px-24">
            <div class="hero-shape top-0 right-0 w-96 h-96 bg-teal-100 rounded-full -translate-y-1/2 translate-x-1/3"></div>
            <div class="hero-shape bottom-0 left-0 w-80 h-80 bg-blue-100 rounded-full translate-y-1/3 -translate-x-1/4"></div>
            
            <div class="relative z-10 text-center max-w-3xl mx-auto">
                <h2 class="heading-label">Get in Touch</h2>
                <h1 class="heading-hero mb-6">
                    We're Here to <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-cyan-500">Support</span> Your Journey
                </h1>
                <p class="text-slate-600 text-sm md:text-base leading-relaxed font-light">
                    Have questions about admissions, academic programs, or campus life? Our dedicated team is ready to assist you. Reach out through any of the channels below.
                </p>
            </div>
        </div>

        <div class="px-6 md:px-24 pb-20">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Contact Info Cards -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Address -->
                    <div class="glass-card p-8 rounded-[2.5rem] section-reveal group hover:bg-white transition-all duration-500">
                        <div class="w-14 h-14 bg-teal-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <i class="bi bi-geo-alt-fill text-2xl text-teal-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Our Campus</h3>
                        <p class="text-slate-500 text-xs leading-relaxed">
                            Govt High School 32/2L,<br>
                            4km Dipalpur-Okara Road,<br>
                            Okara, Pakistan
                        </p>
                    </div>

                    <!-- Email -->
                    <div class="glass-card p-8 rounded-[2.5rem] section-reveal group hover:bg-white transition-all duration-500" style="transition-delay: 100ms;">
                        <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <i class="bi bi-envelope-at-fill text-2xl text-blue-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Email Us</h3>
                        <p class="text-slate-500 text-xs font-medium">abbas.sscs@gmail.com</p>
                        <p class="text-slate-400 text-[10px] mt-2">We respond within 24 hours</p>
                    </div>

                    <!-- Phone -->
                    <div class="glass-card p-8 rounded-[2.5rem] section-reveal group hover:bg-white transition-all duration-500" style="transition-delay: 200ms;">
                        <div class="w-14 h-14 bg-pink-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <i class="bi bi-telephone-fill text-2xl text-pink-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Call Us</h3>
                        <p class="text-slate-500 text-xs font-medium">+92 300 0373004</p>
                        <p class="text-slate-400 text-[10px] mt-2">Mon - Fri, 8:00 AM - 2:00 PM</p>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="lg:col-span-2">
                    <div class="glass-card p-8 md:p-12 rounded-[3rem] section-reveal h-full bg-white/40">
                        <h3 class="heading-subtitle mb-8">Send us a Message</h3>
                        
                        <form class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Full Name</label>
                                    <input type="text" placeholder="John Doe" class="w-full px-5 py-3.5 bg-white/50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Email Address</label>
                                    <input type="email" placeholder="john@example.com" class="w-full px-5 py-3.5 bg-white/50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all">
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Subject</label>
                                <input type="text" placeholder="How can we help?" class="w-full px-5 py-3.5 bg-white/50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Message</label>
                                <textarea rows="5" placeholder="Your message here..." class="w-full px-5 py-3.5 bg-white/50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all resize-none"></textarea>
                            </div>

                            <button type="submit" class="w-full md:w-auto px-10 py-4 bg-teal-600 text-white rounded-2xl text-xs font-bold uppercase tracking-widest hover:bg-teal-700 hover:shadow-xl hover:shadow-teal-600/20 hover:-translate-y-0.5 transition-all">
                                Send Message <i class="bi bi-send ml-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Map Section -->
            <div class="mt-16 section-reveal">
                <div class="rounded-[3rem] overflow-hidden shadow-2xl border-8 border-white group">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3148.1450523610893!2d73.52741423705822!3d30.73508993913517!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39180624c19cb001%3A0x4eb6e3a38a104dbe!2sGovt%20Boys%20High%20School!5e0!3m2!1sen!2s!4v1768930968653!5m2!1sen!2s"
                        class="w-full grayscale-[0.2] group-hover:grayscale-0 transition-all duration-700" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>

        <x-footer></x-footer>
    </div>
@endsection

@section('script')
    <script>
        // Reveal sections on scroll
        const reveals = document.querySelectorAll(".section-reveal");

        function reveal() {
            for (let i = 0; i < reveals.length; i++) {
                let windowHeight = window.innerHeight;
                let elementTop = reveals[i].getBoundingClientRect().top;
                let elementVisible = 100;
                if (elementTop < windowHeight - elementVisible) {
                    reveals[i].classList.add("active");
                }
            }
        }

        window.addEventListener("scroll", reveal);
        // Trigger once on load
        reveal();
    </script>
@endsection
