@extends('layouts.basic')

@section('header')
    <x-header></x-header>
@endsection

@section('style')
    <style>
        .form-glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }
    </style>
@endsection

@section('body')
    <div class="px-6 md:px-24 mt-24 min-h-screen pb-20">
        <div class="max-w-2xl mx-auto">
            <div class="mb-10 flex items-center gap-5">
                <a href="{{ route('gallary.index') }}" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-500 hover:text-teal-600 transition-all shadow-sm hover:shadow-md">
                    <i class="bi bi-chevron-left text-lg"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-slate-800 tracking-tight">Edit <span class="text-teal-600">Event Moment</span></h1>
                    <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mt-1">Updating: {{ $event->name }}</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-8 p-5 bg-red-50 border border-red-100 rounded-[1.5rem]">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-[10px] text-red-600 font-bold flex items-center gap-2">
                                <i class="bi bi-exclamation-triangle-fill"></i> {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('gallary.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="form-glass rounded-[2.5rem] p-8 md:p-12 space-y-10">
                @csrf
                @method('PUT')
                
                <div class="space-y-3">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Event Name *</label>
                    <input type="text" name="name" class="w-full px-5 py-4 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none placeholder:text-slate-300" placeholder="e.g. Annual Sports Day 2024" value="{{ old('name', $event->name) }}" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Category *</label>
                        <input type="text" name="category" class="w-full px-5 py-4 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none placeholder:text-slate-300" placeholder="e.g. Sports, Academics" value="{{ old('category', $event->category) }}" required>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Event Date</label>
                        <input type="date" name="event_date" class="w-full px-5 py-4 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none" value="{{ old('event_date', $event->event_date) }}">
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Short Description</label>
                    <textarea name="detail" rows="3" class="w-full px-5 py-4 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none placeholder:text-slate-300 resize-none" placeholder="Briefly describe the moment...">{{ old('detail', $event->detail) }}</textarea>
                </div>

                <div class="flex flex-col items-center gap-8 bg-slate-50/50 p-8 rounded-[2rem] border border-slate-100">
                    <div class="w-full space-y-3">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Update Photo</label>
                        <div class="relative group">
                            <input type="file" name="photo" id="photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                            <label for="photo" class="flex flex-col items-center justify-center gap-4 p-10 bg-white border-2 border-dashed border-slate-200 rounded-[2rem] cursor-pointer hover:border-teal-400 hover:bg-teal-50/50 transition-all group">
                                <div class="w-16 h-16 bg-teal-50 rounded-3xl flex items-center justify-center text-teal-500 group-hover:scale-110 transition-transform shadow-sm">
                                    <i class="bi bi-image-fill text-3xl"></i>
                                </div>
                                <div class="text-center">
                                    <p class="text-[11px] font-bold text-slate-700 uppercase tracking-widest" id="file-name">Click to Change Photo</p>
                                    <p class="text-[9px] text-slate-400 mt-1 font-medium italic">Leave empty to keep current photo</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div id="image-preview-container" class="w-full">
                        <div class="relative max-w-sm mx-auto">
                            <img id="image-preview" src="{{ asset('storage/' . $event->photo) }}" alt="Preview" class="w-full aspect-video rounded-2xl object-cover border-4 border-white shadow-2xl">
                            <div class="absolute -top-3 -right-3 w-8 h-8 bg-teal-600 text-white rounded-full flex items-center justify-center shadow-lg border-4 border-white">
                                <i class="bi bi-check-lg text-sm"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-[1.5rem] text-xs font-black uppercase tracking-[0.3em] shadow-2xl shadow-slate-900/20 hover:bg-teal-600 hover:-translate-y-1 active:translate-y-0 transition-all duration-300">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const fileName = document.getElementById('file-name');
            const preview = document.getElementById('image-preview');

            if (input.files && input.files[0]) {
                fileName.textContent = input.files[0].name;
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
