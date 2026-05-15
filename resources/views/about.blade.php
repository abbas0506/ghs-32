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
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
        }

        .about-gradient {
            background: radial-gradient(circle at 10% 20%, rgba(20, 184, 166, 0.03) 0%, transparent 40%),
                        radial-gradient(circle at 90% 80%, rgba(59, 130, 246, 0.03) 0%, transparent 40%);
        }

        .hero-pattern {
            background-image: radial-gradient(#0d9488 0.5px, transparent 0.5px);
            background-size: 24px 24px;
            opacity: 0.1;
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

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
        }
    </style>
@endsection

@section(auth()->check() ? 'page-content' : 'body')
    <div class="about-gradient min-h-screen">
        <!-- Hero Section -->
        <section class="relative pt-24 pb-16 px-6 md:px-24 overflow-hidden">
            <div class="hero-pattern absolute inset-0 z-0"></div>
            
            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="section-reveal active mt-16">
                    <h2 class="heading-label">Established Excellence</h2>
                    <h1 class="heading-section">
                        Nurturing Minds, <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-cyan-500">Building Futures</span>
                    </h1>
                    <p class="text-slate-600 text-base md:text-lg leading-relaxed font-light mb-8">
                        At Government High School 32/2L, we believe education is the most powerful weapon which you can use to change the world. Our commitment to academic rigor and character building prepares students for the challenges of tomorrow.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center gap-2 px-4 py-2 bg-teal-50 rounded-full text-teal-700 text-xs font-bold border border-teal-100">
                            <i class="bi bi-patch-check"></i> Quality Education
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 rounded-full text-blue-700 text-xs font-bold border border-blue-100">
                            <i class="bi bi-shield-check"></i> Character Building
                        </div>
                    </div>
                </div>
                
                <div class="relative section-reveal active" style="transition-delay: 200ms;">
                    <div class="absolute -inset-4 bg-teal-500/10 rounded-[3rem] blur-2xl -z-10"></div>
                    <img src="{{ url('images/events/event_1.png') }}" alt="Campus" class="rounded-[2.5rem] shadow-2xl border-8 border-white transform rotate-2 hover:rotate-0 transition-all duration-700">
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-16 px-6 md:px-24">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @php
                    $stats = [
                        ['count' => '500+', 'label' => 'Active Students', 'icon' => 'bi-people', 'color' => 'teal'],
                        ['count' => '20+', 'label' => 'Expert Teachers', 'icon' => 'bi-mortarboard', 'color' => 'blue'],
                        ['count' => '1:30', 'label' => 'Teacher Ratio', 'icon' => 'bi-graph-up', 'color' => 'indigo'],
                        ['count' => '100%', 'label' => 'Dedication', 'icon' => 'bi-heart', 'color' => 'pink'],
                    ];
                @endphp

                @foreach($stats as $index => $stat)
                    <div class="stat-card glass-card p-6 md:p-8 rounded-[2rem] text-center section-reveal group" style="transition-delay: {{ $index * 100 }}ms;">
                        <div class="stat-icon w-12 h-12 bg-{{ $stat['color'] }}-50 rounded-xl flex items-center justify-center mx-auto mb-4 transition-transform duration-500">
                            <i class="bi {{ $stat['icon'] }} text-{{ $stat['color'] }}-600 text-xl"></i>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">{{ $stat['count'] }}</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Mission & Vision -->
        <section class="py-20 px-6 md:px-24 bg-white/40">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="glass-card p-10 rounded-[3rem] section-reveal relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-teal-500/5 rounded-full blur-3xl group-hover:bg-teal-500/10 transition-all"></div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-3">
                        <span class="w-10 h-1 bg-teal-500 rounded-full"></span> Our Mission
                    </h3>
                    <p class="text-slate-600 leading-relaxed font-light italic">
                        "To provide a holistic education that empowers students to reach their full potential, fostering a community of lifelong learners and responsible global citizens."
                    </p>
                </div>

                <div class="glass-card p-10 rounded-[3rem] section-reveal relative overflow-hidden group" style="transition-delay: 150ms;">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-500/5 rounded-full blur-3xl group-hover:bg-blue-500/10 transition-all"></div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-3">
                        <span class="w-10 h-1 bg-blue-500 rounded-full"></span> Our Vision
                    </h3>
                    <p class="text-slate-600 leading-relaxed font-light italic">
                        "To be a beacon of academic excellence and character development, recognized for producing compassionate leaders who drive positive change in society."
                    </p>
                </div>
            </div>
        </section>

        <!-- Core Values -->
        <section class="py-24 px-6 md:px-24">
            <div class="text-center max-w-2xl mx-auto mb-16 section-reveal">
                <h2 class="heading-section mb-4">Our Core Values</h2>
                <p class="text-slate-500 text-sm font-light">The principles that guide our everyday actions and shape our school culture.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $values = [
                        ['title' => 'Integrity', 'desc' => 'Upholding honesty and strong moral principles in all we do.', 'icon' => 'bi-shield-check'],
                        ['title' => 'Excellence', 'desc' => 'Striving for the highest standards in academics and conduct.', 'icon' => 'bi-star'],
                        ['title' => 'Respect', 'desc' => 'Valuing every individual and fostering a diverse, inclusive community.', 'icon' => 'bi-people'],
                        ['title' => 'Innovation', 'desc' => 'Embracing new ideas and modern teaching methodologies.', 'icon' => 'bi-lightbulb'],
                    ];
                @endphp

                @foreach($values as $index => $value)
                    <div class="stat-card glass-card p-8 rounded-[2.5rem] section-reveal text-center group" style="transition-delay: {{ $index * 100 }}ms;">
                        <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-teal-600 group-hover:text-white transition-all duration-500">
                            <i class="bi {{ $value['icon'] }} text-2xl text-teal-600 group-hover:text-white transition-colors"></i>
                        </div>
                        <h4 class="text-lg font-bold text-slate-800 mb-3">{{ $value['title'] }}</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">{{ $value['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Message from Head -->
        <section class="py-20 px-6 md:px-24 mb-16 section-reveal">
            <div class="glass-card p-8 md:p-16 rounded-[4rem] bg-gradient-to-br from-slate-900 to-slate-800 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-96 h-96 bg-teal-500/10 rounded-full blur-[100px]"></div>
                
                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-5 gap-12 items-center">
                    <div class="lg:col-span-2 text-center lg:text-left">
                        <div class="relative inline-block">
                            <div class="absolute -inset-2 bg-teal-500/20 rounded-full blur-xl"></div>
                            <i class="bi bi-quote text-6xl text-teal-500 opacity-50"></i>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-bold text-white mt-4 mb-2">Message from the <span class="text-teal-400">Head</span></h3>
                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.3em]">Education First Policy</p>
                    </div>
                    
                    <div class="lg:col-span-3">
                        <p class="text-slate-300 text-base md:text-lg leading-relaxed font-light italic">
                            "Education is not just about books and exams; it's about discovering one's potential and learning how to think. We are committed to providing an environment where every student feels safe, valued, and inspired to reach for the stars. Join us as we build a brighter future together."
                        </p>
                        <div class="mt-8 flex items-center justify-center lg:justify-start gap-4">
                            <div class="w-12 h-1 bg-teal-500 rounded-full"></div>
                            <p class="text-white font-bold tracking-tight uppercase text-xs">Muhammad Abbas <span class="text-slate-500 ml-2 font-normal">| Headmaster</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

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
