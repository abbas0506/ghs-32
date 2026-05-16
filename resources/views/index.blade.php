@extends('layouts.basic')

@section('header')
    <x-header></x-header>
@endsection

@section('style')
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }

        .hero-gradient {
            background: radial-gradient(circle at top right, rgba(20, 184, 166, 0.05), transparent 40%),
                        radial-gradient(circle at bottom left, rgba(59, 130, 246, 0.05), transparent 40%);
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .feature-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .btn-premium {
            background: linear-gradient(135deg, #0d9488 0%, #06b6d4 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(13, 148, 136, 0.3);
        }

        .btn-premium:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(13, 148, 136, 0.4);
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

@section('body')
    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center overflow-hidden hero-gradient pt-16">
        <div class="container mx-auto px-6 md:px-24 flex flex-col md:flex-row items-center gap-12">
            <div class="flex-1 space-y-6 md:space-y-8 text-center md:text-left z-10">
                <div class="inline-block px-4 py-1.5 bg-teal-50 text-teal-600 rounded-full text-sm font-semibold tracking-wide uppercase mb-2">
                    Discover Your Potential
                </div>
                <h1 class="heading-section">
                    Nurturing <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-cyan-500">Excellence</span> <br class="hidden md:block"> Through Quality Education
                </h1>
                <p class="text-slate-600 text-sm md:text-base leading-relaxed max-w-2xl mx-auto md:mx-0">
                    We provide a state-of-the-art environment where skilled educators and activity-based learning transform dreams into reality. Join our community of lifelong learners.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start pt-4">
                    <a href="#" class="btn-premium px-6 py-3 rounded-xl text-white font-bold flex items-center justify-center gap-2">
                        Get Started <i class="bi bi-arrow-right text-lg"></i>
                    </a>
                    </div>
            </div>
            <div class="flex-1 relative">
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-teal-100 rounded-full blur-3xl opacity-50"></div>
                <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-blue-100 rounded-full blur-3xl opacity-50"></div>
                <img src="{{ asset('images/small/hero_student.png') }}" alt="Education" class="relative z-10 w-full max-w-md mx-auto md:max-w-xl animate-float">
            </div>
        </div>
    </section>

    <!-- Welcome Section -->
    <section id="features" class="py-16 px-6 md:px-24 bg-white section-reveal">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <h2 class="heading-label">Welcome to our School</h2>
            <h3 class="heading-subtitle">Government High School 32/2L, District Okara</h3>
            <div class="h-1 w-20 bg-teal-600 mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">
            <!-- Feature 1 -->
            <div class="feature-card glass-card p-6 rounded-3xl group">
                <div class="w-14 h-14 bg-pink-50 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <i class="bi bi-journal-text text-2xl text-pink-500"></i>
                </div>
                <h4 class="text-lg font-bold text-slate-900 mb-3">Quality Education</h4>
                <p class="text-slate-600 text-xs leading-relaxed">
                    Comprehensive educational programs from Nursery to 10th class, designed to meet modern academic standards.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="feature-card glass-card p-6 rounded-3xl group">
                <div class="w-14 h-14 bg-teal-50 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <i class="bx bx-run text-2xl text-teal-500"></i>
                </div>
                <h4 class="text-lg font-bold text-slate-900 mb-3">Activity Based</h4>
                <p class="text-slate-600 text-xs leading-relaxed">
                    Engaging interactive teaching methods that encourage students to learn through doing and exploring.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="feature-card glass-card p-6 rounded-3xl group">
                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <i class="bi bi-people text-2xl text-blue-500"></i>
                </div>
                <h4 class="text-lg font-bold text-slate-900 mb-3">Social Development</h4>
                <p class="text-slate-600 text-xs leading-relaxed">
                    Fostering healthy social habits and leadership skills to create responsible and conscious citizens of tomorrow.
                </p>
            </div>
        </div>
    </section>

    <!-- Achievements Section -->
    <section class="py-20 bg-slate-50 overflow-hidden section-reveal">
        <div class="container mx-auto px-6 md:px-24">
            <div class="bg-gradient-to-br from-teal-700 to-teal-900 rounded-[2.5rem] p-6 md:p-12 text-center text-white relative shadow-2xl">
                <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-10 pointer-events-none">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-white rounded-full"></div>
                    <div class="absolute top-1/2 left-1/4 w-20 h-20 bg-white rounded-full"></div>
                    <div class="absolute bottom-1/4 right-1/4 w-32 h-32 bg-white rounded-full"></div>
                </div>
                <h2 class="text-xl md:text-3xl font-bold mb-6">Extra-Curricular Achievements</h2>
                <p class="text-teal-50 text-sm md:text-base max-w-3xl mx-auto leading-relaxed opacity-90">
                    We believe that education extends beyond the classroom. Our students engage in diverse extra-curricular activities that build character, teamwork, and healthy lifestyle habits.
                </p>
            </div>
        </div>
    </section>

    <!-- Message Section -->
    <section class="py-16 px-6 md:px-24 section-reveal">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-10">
                <h2 class="text-xl md:text-3xl font-bold text-slate-900 mb-4">Headmaster's Message</h2>
                <div class="h-1 w-20 bg-teal-600 mx-auto rounded-full"></div>
            </div>
            
            <div class="glass-card p-6 md:p-12 rounded-[2.5rem] relative flex flex-col md:flex-row items-center gap-10">
                <div class="absolute top-10 right-10 opacity-5 hidden md:block">
                    <i class="bx bxs-quote-right text-9xl text-teal-900"></i>
                </div>
                
                <div class="w-full md:w-1/3 flex flex-col items-center">
                    <div class="relative">
                        <div class="absolute inset-0 bg-teal-400 rounded-full blur opacity-20 animate-pulse"></div>
                        <img src="{{ asset('images/default.png') }}" class="relative w-48 h-48 md:w-64 md:h-64 rounded-3xl object-cover shadow-xl rotate-3 hover:rotate-0 transition-transform duration-500" alt="Headmaster">
                    </div>
                    <h3 class="mt-8 text-2xl font-bold text-slate-900">Muhammad Abbas</h3>
                    <p class="text-teal-600 font-semibold tracking-wide uppercase">Senior Headmaster</p>
                </div>
                
                <div class="w-full md:w-2/3">
                    <i class="bx bxs-quote-left text-2xl text-teal-400 mb-3"></i>
                    <p class="text-base md:text-lg text-slate-700 leading-relaxed italic">
                        "We are committed to achieving academic excellence, character education, and inclusive community engagement. We empower our students to become lifelong learners, compassionate leaders, and contributors to a globally connected society."
                    </p>
                    <div class="mt-6 flex items-center gap-4">
                        <div class="w-8 h-px bg-slate-300"></div>
                        <span class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Dedication to Excellence</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-12 section-reveal">
        <div class="container mx-auto px-6 md:px-24">
            <div class="rounded-[2rem] overflow-hidden shadow-2xl border-8 border-white">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3148.1450523610893!2d73.52741423705822!3d30.73508993913517!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39180624c19cb001%3A0x4eb6e3a38a104dbe!2sGovt%20Boys%20High%20School!5e0!3m2!1sen!2s!4v1768930968653!5m2!1sen!2s"
                    class="w-full" height="350" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>

    <!-- footer -->
    <x-footer></x-footer>
@endsection

@section('script')
    <script>
        // Reveal sections on scroll
        const reveals = document.querySelectorAll(".section-reveal");

        function reveal() {
            for (let i = 0; i < reveals.length; i++) {
                let windowHeight = window.innerHeight;
                let elementTop = reveals[i].getBoundingClientRect().top;
                let elementVisible = 150;
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
