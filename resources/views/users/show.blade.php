@extends('layouts.app')
@section('page-content')
    <h1>View user</h1>
    <div class="flex items-center">
        <div class="flex-1">
            <div class="bread-crumb">
                <a href="{{ url('/') }}">Home</a>
                <div>/</div>
                <a href="{{ route('users.index') }}">users</a>
                <div>/</div>
                <div>{{ $user->id }}</div>
            </div>
        </div>
    </div>
    <div class="md:w-4/5 mx-auto mt-6 bg-white md:p-8 p-4 gap-3 rounded border relative">
        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif


        <div class="flex items-center gap-4 mb-6">
            @php
                $initials = strtoupper(
                    implode('', array_map(fn($w) => $w[0] ?? '', explode(' ', $user->profile->name))),
                );
                $colors = [
                    'bg-indigo-500',
                    'bg-blue-500',
                    'bg-green-500',
                    'bg-purple-500',
                    'bg-pink-500',
                    'bg-red-500',
                    'bg-yellow-500',
                    'bg-teal-500',
                ];
                $colorIndex = (strlen($user->profile->name) + $user->id) % count($colors);
                $bgColor = $colors[$colorIndex];
            @endphp
            <div
                class="flex w-8 h-8 p-1 rounded-full {{ $bgColor }} flex items-center justify-center text-white font-bold text-sm shadow-md">
                {{ $initials }}
            </div>
            <div>
                <h2 class="font-semibold text-slate-800">{{ $user->profile->name }}</h2>
                <p class="text-slate-600 text-sm">{{ $user->profile->father_name }}</p>
            </div>
        </div>




        {{-- action buttons --}}
        <div class="flex items-center justify-center space-x-2 absolute top-2 right-2">
            <div class="">
                <form action="{{ route('users.destroy', $user) }}" method="post" onsubmit="return confirmDel(event)">
                    @csrf
                    @method('DELETE')
                    <button><i class="bx bx-trash text-red-600"></i></button>
                </form>
            </div>
            <div class="">
                <a href="{{ route('users.edit', $user) }}"><i class="bx bx-pencil text-green-600"></i></a>
            </div>

        </div>
        <div class="grid md:grid-cols-2 gap-3 mt-8">
            <!-- display info -->
            <div>
                <label for="">CNIC</label>
                <h3>{{ $user->profile->cnic }}</h3>
            </div>
            <div>
                <label for="">Phone</label>
                <h3>{{ $user->profile->phone }}</h3>
            </div>
            <div>
                <label for="">Email</label>
                <h3>{{ $user->email }}</h3>
            </div>
            <div>
                <label for="">Qualification</label>
                <h3>{{ $user->profile->qualification }}</h3>
            </div>
            <div>
                <label for="">Address</label>
                <h3>{{ $user->profile->address }}</h3>
            </div>
        </div>

    </div>
    <div class="md:w-4/5 mx-auto mt-6 bg-white md:p-8 p-4 gap-3 rounded border relative">
        <div class="flex items-center">
            <label>Roles</label>
            <a href="{{ route('user.roles.edit', [$user, 1]) }}"><i
                    class="bx bx-pencil text-green-600 ml-2 pt-2 absolute top-2 right-2"></i></a>
        </div>

        @foreach ($user->roles as $role)
            <h3 class="">{{ ucfirst($role->name) }}</h3>
        @endforeach

    </div>
    {{-- close button --}}
    <div class="btn btn-blue rounded mt-5 mx-auto text-center w-24">
        <a href="{{ route('users.index') }}">Close</a>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        function confirmDel(event) {
            event.preventDefault(); // prevent form submit
            var form = event.target; // storing the form

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    form.submit();
                }
            })
        }
    </script>
@endsection
