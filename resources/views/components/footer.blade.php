<footer class="bg-slate-900 text-slate-300 pt-16 pb-8 px-6 md:px-24 mt-20 relative overflow-hidden">
    <!-- Subtle Background Flare -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-teal-500/5 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-500/5 rounded-full blur-[80px] translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>

    <div class="relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
            <!-- Brand Section -->
            <div class="space-y-6">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo/ghs-32.png') }}" alt="logo" class="w-12 h-12 brightness-110">
                    <div class="flex flex-col">
                        <span class="text-white font-bold text-lg leading-none tracking-tight">GHS 32/2L</span>
                        <span class="text-teal-500 text-[10px] font-black uppercase tracking-[0.2em] mt-1">Believe in Excellence</span>
                    </div>
                </div>
                <p class="text-xs leading-relaxed text-slate-400">
                    Dedicated to providing quality education and fostering character development in a modern learning environment. Nurturing the leaders of tomorrow since our inception.
                </p>
                <div class="flex items-center gap-4 pt-2">
                    <a href="#" class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center hover:bg-teal-600 hover:text-white transition-all duration-300">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center hover:bg-teal-600 hover:text-white transition-all duration-300">
                        <i class="bi bi-twitter"></i>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center hover:bg-teal-600 hover:text-white transition-all duration-300">
                        <i class="bi bi-instagram"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-white font-bold text-sm uppercase tracking-widest mb-8 flex items-center gap-2">
                    <span class="w-2 h-2 bg-teal-500 rounded-full"></span>
                    Quick Links
                </h3>
                <ul class="space-y-4">
                    <li><a href="{{ url('/') }}" class="text-xs hover:text-teal-400 transition-colors flex items-center gap-2 group"><i class="bi bi-chevron-right text-[10px] text-teal-600 group-hover:translate-x-1 transition-transform"></i> Home</a></li>
                    <li><a href="{{ url('about') }}" class="text-xs hover:text-teal-400 transition-colors flex items-center gap-2 group"><i class="bi bi-chevron-right text-[10px] text-teal-600 group-hover:translate-x-1 transition-transform"></i> About Us</a></li>
                    <li><a href="{{ url('faculty') }}" class="text-xs hover:text-teal-400 transition-colors flex items-center gap-2 group"><i class="bi bi-chevron-right text-[10px] text-teal-600 group-hover:translate-x-1 transition-transform"></i> Faculty</a></li>
                    <li><a href="{{ url('alumni') }}" class="text-xs hover:text-teal-400 transition-colors flex items-center gap-2 group"><i class="bi bi-chevron-right text-[10px] text-teal-600 group-hover:translate-x-1 transition-transform"></i> Alumni</a></li>
                    <li><a href="{{ url('gallary') }}" class="text-xs hover:text-teal-400 transition-colors flex items-center gap-2 group"><i class="bi bi-chevron-right text-[10px] text-teal-600 group-hover:translate-x-1 transition-transform"></i> Gallery</a></li>
                </ul>
            </div>

            <!-- Admissions -->
            <div>
                <h3 class="text-white font-bold text-sm uppercase tracking-widest mb-8 flex items-center gap-2">
                    <span class="w-2 h-2 bg-teal-500 rounded-full"></span>
                    Academics
                </h3>
                <ul class="space-y-4">
                    <li><a href="#" class="text-xs hover:text-teal-400 transition-colors flex items-center gap-2 group"><i class="bi bi-chevron-right text-[10px] text-teal-600 group-hover:translate-x-1 transition-transform"></i> Admission Guide</a></li>
                    <li><a href="#" class="text-xs hover:text-teal-400 transition-colors flex items-center gap-2 group"><i class="bi bi-chevron-right text-[10px] text-teal-600 group-hover:translate-x-1 transition-transform"></i> School Rules</a></li>
                    <li><a href="#" class="text-xs hover:text-teal-400 transition-colors flex items-center gap-2 group"><i class="bi bi-chevron-right text-[10px] text-teal-600 group-hover:translate-x-1 transition-transform"></i> Examinations</a></li>
                    <li><a href="#" class="text-xs hover:text-teal-400 transition-colors flex items-center gap-2 group"><i class="bi bi-chevron-right text-[10px] text-teal-600 group-hover:translate-x-1 transition-transform"></i> Curriculum</a></li>
                </ul>
            </div>

            <!-- Contact Details -->
            <div>
                <h3 class="text-white font-bold text-sm uppercase tracking-widest mb-8 flex items-center gap-2">
                    <span class="w-2 h-2 bg-teal-500 rounded-full"></span>
                    Contact Info
                </h3>
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center shrink-0">
                            <i class="bi bi-geo-alt text-teal-500"></i>
                        </div>
                        <p class="text-xs leading-relaxed">
                            Govt High School 32/2L, 4km Dipalpur-Okara Road, Okara, Pakistan
                        </p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center shrink-0">
                            <i class="bi bi-telephone text-teal-500"></i>
                        </div>
                        <p class="text-xs font-bold text-white tracking-wide">
                            +92 300 0373004
                        </p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center shrink-0">
                            <i class="bi bi-envelope text-teal-500"></i>
                        </div>
                        <p class="text-xs font-medium text-slate-400 hover:text-white transition-colors cursor-pointer">
                            abbas.sscs@gmail.com
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="pt-8 border-t border-slate-800/50 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-[10px] font-medium text-slate-500 uppercase tracking-widest">
                &copy; {{ date('Y') }} Government High School 32/2L. All Rights Reserved.
            </p>
            <div class="flex items-center gap-2">
                <span class="text-[10px] text-slate-600 uppercase tracking-tighter">Designed with</span>
                <i class="bi bi-heart-fill text-red-500 text-[10px] animate-pulse"></i>
                <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">by Muhammad Abbas</span>
            </div>
        </div>
    </div>
</footer>
