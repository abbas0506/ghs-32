@extends('layouts.app')

@section('page-content')
    <style>
        .photo-box {
            width: 150px;
            height: 150px;
            border: 2px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #aaa;
            font-size: 18px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }

        .photo-upload-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .custom-file-upload {
            background-color: #007bff;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-align: center;
            transition: background-color 0.3s;
        }

        .custom-file-upload:hover {
            background-color: #0056b3;
        }

        input[type="file"] {
            display: none;
        }
    </style>
    <div class="custom-container">
        <!-- Title     -->
        <h1>Edit Student</h1>
        <div class="flex items-center">
            <div class="flex-1">
                <div class="bread-crumb">
                    <a href="{{ url('/') }}">Dashboard</a>
                    <div>/</div>
                    <a href="{{ route('users.index') }}">users</a>
                    <div>/</div>
                    <div>{{ $user->id }}</div>
                    <div>/</div>
                    <div>Edit</div>
                </div>
            </div>
        </div>
        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <div class="mt-8">
            <img src="{{ asset('storage/' . $user->profile->photo) }}" alt="Student Photo" width="100" height="100"
                class="mx-auto rounded-lg">
            <h2 class="text-center mt-3">{{ $user->profile->name }} </h2>
            <div class="text-center text-slate-400 text-xs">{{ $user->designation }}</div>

        </div>
        {{-- roles --}}
        <div class="md:w-4/5 mx-auto mt-6 bg-white md:p-8 p-4 rounded border relative">

            <form action="{{ route('user.roles.update', [$user->id, 1]) }}" method='post' class="">
                @csrf
                @method('PATCH')
                <h2 class="text-decoration text-green-600">Assigned Roles</h2>
                <div class="grid gap-1 mt-4">
                    @foreach ($roles as $role)
                        <div class="flex item checkable-row text-sm">
                            <label for="role{{ $role->id }}"
                                class="text-sm hover:cursor-pointer text-slate-800 text-left py-1 flex-1">{{ ucfirst($role->name) }}</label>
                            <input type="checkbox" id='role{{ $role->id }}' name='role_names_array[]'
                                class="custom-input w-4 h-4 rounded" value="{{ $role->name }}"
                                @checked($user->hasRole($role->name))>
                            <i class="bx bx-check"></i>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-end space-x-3 text-center mt-8 md:col-span-2">
                    <a href="{{ route('users.index') }}" class="btn-gray rounded py-2 px-3">Cancel </a>
                    <button type="submit" class="btn-green rounded">Update</button>
                </div>
            </form>
        </div>
    @endsection
