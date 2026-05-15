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
        .gallery-card {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .gallery-card:hover img {
            transform: scale(1.1) rotate(1deg);
        }
        .glass-filter {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        .filter-active {
            background: #0d9488 !important;
            color: white !important;
            box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.3);
        }
        .lightbox-modal {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
@endsection

@section(auth()->check() ? 'page-content' : 'body')
    <div class="{{ auth()->check() ? '' : 'px-6 md:px-24 mt-20' }} min-h-screen pb-20">
        <!-- Hero Section -->
        <div class="flex flex-col md:flex-row justify-between items-center md:items-end mb-12 gap-6 section-reveal">
            <div class="text-center md:text-left max-w-2xl mt-16">
                <h1 class="heading-section">
                    Event <span class="text-teal-600">Gallery</span>
                </h1>
                <p class="heading-label !mb-0 mt-2">Capturing Moments of Excellence</p>
            </div>
            @auth
                <a href="{{ route('gallary.create') }}" class="bg-teal-600 text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-lg shadow-teal-600/20 hover:bg-teal-700 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    <i class="bi bi-cloud-arrow-up text-sm"></i> Upload Photos
                </a>
            @endauth
        </div>

        <!-- Filter Bar -->
        <div class="flex flex-wrap justify-center md:justify-start gap-2 mb-12 glass-filter p-2 rounded-2xl w-fit mx-auto md:mx-0">
            <button class="filter-btn filter-active px-5 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all" data-category="all">All Moments</button>
            @foreach($categories as $category)
                <button class="filter-btn px-5 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest text-slate-500 hover:bg-slate-100 transition-all" data-category="{{ $category }}">
                    {{ $category }}
                </button>
            @endforeach
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 bg-teal-50 border border-teal-100 text-teal-700 text-xs rounded-2xl flex items-center gap-3">
                <i class="bi bi-check-circle-fill"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Gallery Grid -->
        <div id="gallery-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($events as $event)
                <div class="gallery-card group relative rounded-[2rem] overflow-hidden aspect-[4/5] bg-slate-100 cursor-pointer" data-category="{{ $event->category }}" 
                     onclick="openLightbox('{{ asset('storage/' . $event->photo) }}', '{{ $event->name }}', '{{ $event->detail }}')">
                    
                    <img src="{{ asset('storage/' . $event->photo) }}" alt="{{ $event->name }}" class="w-full h-full object-cover transition-all duration-700">
                    
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-6">
                        <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                            <span class="bg-teal-500 text-white text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full mb-2 inline-block">{{ $event->category }}</span>
                            <h3 class="text-white text-sm font-bold uppercase tracking-tight">{{ $event->name }}</h3>
                            <p class="text-white/60 text-[9px] mt-1 line-clamp-2 leading-relaxed">{{ $event->detail }}</p>
                        </div>
                    </div>

                    <!-- Admin Controls -->
                    @auth
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity flex gap-2 z-20">
                            <a href="{{ route('gallary.edit', $event->id) }}" onclick="event.stopPropagation()" class="p-2 bg-white/90 text-blue-600 rounded-xl hover:bg-blue-50 shadow-sm border border-blue-100">
                                <i class="bi bi-pencil-square text-xs"></i>
                            </a>
                            <form action="{{ route('gallary.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Delete this image?')" onclick="event.stopPropagation()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-white/90 text-red-600 rounded-xl hover:bg-red-50 shadow-sm border border-red-100">
                                    <i class="bi bi-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            @empty
                <div class="col-span-full py-32 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-slate-200">
                        <i class="bi bi-images text-4xl"></i>
                    </div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">No photos uploaded yet</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Lightbox Modal -->
    <div id="lightbox" class="lightbox-modal fixed inset-0 z-[100] hidden flex-col items-center justify-center p-4 md:p-10 animate-fade-in" onclick="closeLightbox()">
        <button class="absolute top-6 right-6 text-white/50 hover:text-white transition-colors">
            <i class="bi bi-x-lg text-2xl"></i>
        </button>
        
        <div class="relative max-w-5xl w-full max-h-full flex flex-col items-center gap-6" onclick="event.stopPropagation()">
            <img id="lightbox-img" src="" alt="Full view" class="max-w-full max-h-[70vh] rounded-3xl shadow-2xl border-4 border-white/10 object-contain">
            
            <div class="text-center max-w-2xl px-4">
                <h2 id="lightbox-title" class="text-white text-xl font-black uppercase tracking-tight"></h2>
                <p id="lightbox-desc" class="text-white/60 text-xs mt-2 font-light leading-relaxed"></p>
            </div>
        </div>
    </div>

    <script>
        function openLightbox(src, title, desc) {
            const lightbox = document.getElementById('lightbox');
            document.getElementById('lightbox-img').src = src;
            document.getElementById('lightbox-title').innerText = title;
            document.getElementById('lightbox-desc').innerText = desc;
            lightbox.classList.remove('hidden');
            lightbox.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            const lightbox = document.getElementById('lightbox');
            lightbox.classList.add('hidden');
            lightbox.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        // Filtering
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('filter-active'));
                btn.classList.add('filter-active');

                const category = btn.dataset.category;
                document.querySelectorAll('.gallery-card').forEach(card => {
                    if (category === 'all' || card.dataset.category === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
