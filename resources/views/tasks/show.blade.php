@extends('layouts.app')
@section('page-content')
    <h2>
        Task # {{ $task->id }}</h2>
    <div class="bread-crumb">
        <a href="/">Home</a>
        <div>/</div>
        <a href="{{ route('tasks.index') }}">Tasks</a>
        <div>/</div>
        <div>View</div>
    </div>

    <div class="grid md:grid-cols-3 md:w-4/5 mx-auto mt-6 bg-white md:p-8 p-4 rounded border">
        <div class="md:col-span-2 text-slate-400 text-sm">
            Removing task is a destructive activity.
            It will destory all data associated with this task.
            Remove only if you are sure!
        </div>
        <div class="flex items-center justify-end space-x-2 mt-4">
            <div class="flex w-8 h-8 rounded-full border justify-center items-center">
                <form action="{{ route('tasks.destroy', $task) }}" method="post" onsubmit="return confirmDel(event)">
                    @csrf
                    @method('DELETE')
                    <button><i class="bx bx-trash text-red-600"></i></button>
                </form>
            </div>
            <div class="flex w-8 h-8 rounded-full border justify-center items-center">
                <a href="{{ route('tasks.edit', $task) }}"><i class="bx bx-pencil text-green-600"></i></a>
            </div>

        </div>
    </div>

    <!-- message -->
    <div class="md:w-4/5 mx-auto">
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif
    </div>

    <div class="md:w-4/5 overflow-x-auto mx-auto bg-white md:p-8 p-4 rounded border mt-3">
        <div class="flex justify-between items-center flex-wrap">
            <h2 class=""><i class="bi-calendar-event text-slate-500 mr-2"></i> {{ $task->description }} </h2>
            <a href="{{ route('task.task-lines.create', $task) }}"><i class="bi-folder-plus"></i></a>
        </div>
        <table class="table-auto borderless w-full mt-5">
            <thead>
                <tr>
                    <th class="w-2/3 text-left">user</th>
                    <th class=""></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($task->taskLines as $taskLine)
                    <tr class="tr">
                        <td class="text-left text-sm">{{ $taskLine->user->profile?->name }}</td>
                        <td class="text-sm text-right">

                            <form action="{{ route('task.task-lines.update', [$task, $taskLine]) }}" method='post'>
                                @csrf
                                @method('patch')
                                <button type="submit">
                                    @if ($taskLine->status)
                                        <span class="pill green py-1 px-3"><i class="ri-checkbox-circle-line"></i></span>
                                    @else
                                        <span class="pill red"><i class="ri-question-line"></i></span>
                                    @endif
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
@section('script')
    <script type="module">
        $('.checkable-row input').change(function() {
            if ($(this).prop('checked'))
                $(this).parents('.checkable-row').addClass('active')
            else
                $(this).parents('.checkable-row').removeClass('active')
        })

        $('#check_all').change(function() {
            if ($(this).prop('checked')) {
                $('.checkable-row input').each(function() {
                    $(this).prop('checked', true)
                    $(this).parents('.checkable-row').addClass('active')
                })
            } else {
                $('.checkable-row input').each(function() {
                    $(this).prop('checked', false)
                    $(this).parents('.checkable-row').removeClass('active')
                })
            }
        })
    </script>

    <script>
        function confirmDel(event) {
            event.preventDefault(); // prevent form submit
            var form = event.target; // storing the form

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    //submit corresponding form
                    form.submit();
                }
            });
        }
    </script>
@endsection
