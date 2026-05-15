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
        <div class="max-w-3xl mx-auto">
            <div class="mb-10 flex items-center gap-5">
                <a href="{{ route('faculty.index') }}" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-500 hover:text-teal-600 transition-all shadow-sm hover:shadow-md">
                    <i class="bi bi-chevron-left text-lg"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-slate-800 tracking-tight">Add New <span class="text-teal-600">Faculty Member</span></h1>
                    <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mt-1">Register a new educator to the system</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-8 p-5 bg-red-50 border border-red-100 rounded-[1.5rem] animate-shake">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-[10px] text-red-600 font-bold flex items-center gap-2">
                                <i class="bi bi-exclamation-triangle-fill"></i> {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('faculty.store') }}" method="POST" enctype="multipart/form-data" class="form-glass rounded-[2.5rem] p-8 md:p-12 space-y-10">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Prefix & Full Name *</label>
                        <div class="flex gap-2 flex-wrap">
                            <input type="text" name="prefix" class="w-24 px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none placeholder:text-slate-300" placeholder="Mr." value="{{ old('prefix') }}">
                            <input type="text" name="name" class="flex-1 px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none placeholder:text-slate-300" placeholder="Enter full name" value="{{ old('name') }}" required>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Login Email *</label>
                        <input type="email" name="email" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none placeholder:text-slate-300" placeholder="email@school.com" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Official Designation</label>
                        <input type="text" name="designation" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none placeholder:text-slate-300" placeholder="e.g. Senior Teacher, SST" value="{{ old('designation') }}">
                    </div>
                    <div class="space-y-3">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Basic Pay Scale (BPS)</label>
                        <input type="number" name="bps" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none" value="{{ old('bps', 14) }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Academic Qualification</label>
                        <input type="text" name="qualification" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none placeholder:text-slate-300" placeholder="e.g. M.Phil Physics" value="{{ old('qualification') }}">
                    </div>
                    <div class="space-y-3">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Date of Joining</label>
                        <input type="date" name="joined_at" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none" value="{{ old('joined_at') }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Phone Number</label>
                        <input type="text" name="phone" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none placeholder:text-slate-300" placeholder="03xx-xxxxxxx" value="{{ old('phone') }}">
                    </div>
                    <div class="space-y-3">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Residential Address</label>
                        <input type="text" name="address" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none placeholder:text-slate-300" placeholder="Current address" value="{{ old('address') }}">
                    </div>
                </div>

                <div class="flex flex-col md:flex-row items-center gap-10 bg-teal-50/30 p-8 rounded-[2rem] border border-teal-100/50">
                    <div class="flex-1 w-full space-y-3">
                        <label class="block text-[10px] font-bold text-teal-600 uppercase tracking-[0.2em] ml-1">Profile Photograph</label>
                        <div class="relative group">
                            <input type="file" name="photo" id="photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                            <label for="photo" class="flex items-center gap-5 px-6 py-4 bg-white border-2 border-dashed border-teal-200 rounded-[1.5rem] cursor-pointer hover:border-teal-500 hover:bg-teal-50/50 transition-all">
                                <div class="w-12 h-12 bg-teal-50 rounded-2xl flex items-center justify-center text-teal-500 group-hover:scale-110 transition-transform shadow-sm">
                                    <i class="bi bi-camera-fill text-xl"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[11px] font-bold text-slate-700 truncate" id="file-name">Upload Image</p>
                                    <p class="text-[9px] text-slate-400 font-medium italic">High resolution JPG/PNG</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div id="image-preview-container" class="hidden">
                        <div class="relative">
                            <img id="image-preview" src="#" alt="Preview" class="w-24 h-24 rounded-[1.5rem] object-cover border-4 border-white shadow-2xl scale-110">
                            <div class="absolute -top-3 -right-3 w-8 h-8 bg-teal-600 text-white rounded-full flex items-center justify-center shadow-lg border-4 border-white">
                                <i class="bi bi-check-lg text-sm"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-[1.5rem] text-xs font-black uppercase tracking-[0.3em] shadow-2xl shadow-slate-900/20 hover:bg-teal-600 hover:-translate-y-1 active:translate-y-0 transition-all duration-300">
                        Finalize & Create Profile
                    </button>
                    <p class="text-center text-[9px] text-slate-400 mt-5 uppercase tracking-widest font-bold">Default password for new users is: <span class="text-teal-600">password</span></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const fileName = document.getElementById('file-name');
            const preview = document.getElementById('image-preview');
            const container = document.getElementById('image-preview-container');

            if (input.files && input.files[0]) {
                fileName.textContent = input.files[0].name;
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                    container.classList.add('animate-fade-in');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
