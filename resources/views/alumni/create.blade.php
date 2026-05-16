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
            <div class="mb-8 flex items-center gap-4">
                <a href="{{ route('alumni.index') }}" class="w-8 h-8 flex items-center justify-center rounded-full bg-white border border-slate-100 text-slate-500 hover:text-teal-600 transition-colors shadow-sm">
                    <i class="bi bi-chevron-left text-sm"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-slate-800 tracking-tight">Add New <span class="text-teal-600">Alumni</span></h1>
                    <p class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold mt-0.5">Register yourself as an Alumni</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl">
                    <ul class="list-disc list-inside text-[10px] text-red-600 font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('alumni.store') }}" method="POST" enctype="multipart/form-data" class="form-glass rounded-[2rem] p-6 md:p-12 space-y-8">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Name Prefix</label>
                        <input type="text" name="prefix" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none placeholder:text-slate-300" placeholder="e.g. Mr., Dr., Capt." value="{{ old('prefix') }}">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Full Name *</label>
                        <input type="text" name="name" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none placeholder:text-slate-300" placeholder="Enter full name" value="{{ old('name') }}" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Phone Number</label>
                        <input type="text" name="phone" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none placeholder:text-slate-300" placeholder="e.g. 0300-1234567" value="{{ old('phone') }}">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Pass out Session (Year)</label>
                        <input type="number" name="session" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none placeholder:text-slate-300" placeholder="e.g. 2015" value="{{ old('session', date('Y')) }}">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Current Address</label>
                    <input type="text" name="address" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none placeholder:text-slate-300" placeholder="Current location or organization" value="{{ old('address') }}">
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Brief Introduction (Workplace & Designation) </label>
                    <textarea name="introduction" rows="3" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all outline-none placeholder:text-slate-300 resize-none" placeholder="Mention your work place like Manager, HBL Okara, Principal DPS Tandlianwala, Inspector Police Lahore, etc." >{{ old('introduction') }}</textarea>
                </div>

                <div class="flex flex-col md:flex-row items-center gap-8 bg-slate-50/50 p-6 rounded-[1.5rem] border border-slate-100">
                    <div class="flex-1 w-full space-y-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Display Photo</label>
                        <div class="relative">
                            <input type="file" name="photo" id="photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                            <label for="photo" class="flex items-center gap-4 px-4 py-3 bg-white border-2 border-dashed border-slate-200 rounded-2xl cursor-pointer hover:border-teal-400 hover:bg-teal-50/50 transition-all group">
                                <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center text-teal-500 group-hover:scale-110 transition-transform">
                                    <i class="bi bi-camera text-lg"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[11px] font-bold text-slate-600 truncate" id="file-name">Select Image</p>
                                    <p class="text-[9px] text-slate-400 italic">JPG, PNG up to 2MB</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div id="image-preview-container" class="hidden">
                        <div class="relative">
                            <img id="image-preview" src="#" alt="Preview" class="w-20 h-20 rounded-2xl object-cover border-4 border-white shadow-xl">
                            <div class="absolute -top-2 -right-2 w-6 h-6 bg-teal-500 text-white rounded-full flex items-center justify-center shadow-lg border-2 border-white">
                                <i class="bi bi-check-lg text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <!-- add go back chevron button -->
                    <div>
                        
                    </div>
                    <div class="pt-4 md:pt-6">
                        <button type="submit" class="w-full bg-teal-600 text-white py-4 rounded-2xl text-xs font-bold uppercase tracking-[0.2em] shadow-xl shadow-teal-600/20 hover:bg-teal-700 hover:-translate-y-1 active:translate-y-0 transition-all duration-300">
                            Register Profile
                        </button>
                    </div>
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
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
