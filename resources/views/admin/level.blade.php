<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Complaint System</title>
    @vite('resources/css/app.css')
    <script src="{{ asset('js/all.js') }}"></script>
</head>
<body class="w-screen h-screen flex">
    <nav class="w-56 h-screen bg-emerald-600 p-6">
        <img src="{{ asset('img/lasu.png') }}" class="h-20 w-auto" alt="">
        <div class="mt-6 text-white font-medium">
            <a href="{{ route('admin_dashboard') }}" class="p-3 mb-2 flex gap-x-2">
                Dashboard
            </a>
            <a href="{{ route('students') }}" class="p-3 mb-2 flex gap-x-2">
                Students
            </a>
            <a href="{{ route('lecturers') }}" class="p-3 mb-2 flex gap-x-2">
                Lecturers
            </a>
            <a href="/levels" class="p-3 mb-2 flex gap-x-2">
                levels
            </a>
        </div>
    </nav>
    <main class="w-full">
        <div class="p-4 mb-8 flex justify-between items-center shadow-md">
            <h1 class="text-2xl font-bold text-emerald-900">Complaint System Administrator</h1>
            <div class="flex items-center gap-x-3">
                <img src="{{asset('img/user.png')}}" alt="" class="block w-7 h-7">
                <div>
                    <h1>{{ auth()->user()->email }}</h1>
                </div>
            </div>
        </div>

        <div class="w-full p-6 rounded-md shadow-md">
            <div class="flex justify-between items-center mb-5">
            <div class="flex justify-between mb-4 items-center">
                <h1 class="text-xl font-medium text-emerald-500">All Levels</h1>

            </div>
            <span onclick="toggleNew()" class="cursor-pointer px-8 py-2 bg-emerald-500 text-white rounded-md">Add new</span>
            </div>
            @if ($levels->count() > 0)
            <table class="w-full">
                <thead>
                    <tr>
                        <td class="bg-emerald-700 p-3 text-white font-bold"></td>
                        <td class="bg-emerald-700 p-3 text-white font-bold">Date Added</td>
                        <td class="bg-emerald-700 p-3 text-white font-bold">level ID</td>
                        <td class="bg-emerald-700 p-3 text-white font-bold">level</td>
                            <td class="bg-emerald-700 p-3 text-white font-bold">Action</td>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($levels as $level)
                    <tr>
                        <td class="bg-emerald-100 p-3"><div class="w-3.5 h-3.5 rounded-md bg-blue-300"></div></td>
                        <td class="bg-emerald-100 p-3">{{ $level->created_at }}</td>
                        <td class="bg-emerald-100 p-3">{{ $level->prefix }}</td>
                        <td class="bg-emerald-100 p-3">{{ $level->level }}</td>
                        <td class="bg-emerald-100 p-3">
                            <form action="/level/{{ $level->id }}" method="post">
                            @csrf
                            <select name="level" id="" class="px-8 py-2 bg-blue-500 rounded-md">
                                <option value="" selected disabled>Update Status</option>
                                @foreach ($status as $stat)
                                    @if ($stat != $level->level)
                                    <option value="{{ $stat }}">{{ $stat }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </form>
                            </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $levels->links() !!}
            @else
                <p class="text-center text-gray-300 text-2xl">
                    No level has been added
                </p>
            @endif
        </div>
    </main>
    <div id="new-level" class="hidden fixed w-full h-full flex items-center align-middle bg-emerald-800 bg-opacity-80">
        <div class="mx-auto w-5/12 rounded-md p-6 bg-white">
            <div class="flex mb-3 justify-end">
                <i onclick="toggleNew()" class="fa cursor-pointer fa-times text-xl"></i>
            </div>
            <h1 class="text-xl font-medium mb-4">Add a new level</h1>
            <form action="{{ route('level') }}" enctype="multipart/form-data" method="post">
                @csrf
                <label for="" class="font-bold mb-1.5">level prefix</label>
                <input type="text" name="prefix" class="block p-3 bg-emerald-100 shadow-md w-full rounded-md mb-2" id="">
                @error('prefix')
                    <p class="text-red-400 font-bold my-1 text-sm">{{ $message }}</p>
                @enderror
                <label for="" class="font-bold mb-1.5">level</label>
                <select name="level" id="" class="px-8 py-2 bg-blue-500 rounded-md">
                    <option value="" selected disabled>Update Status</option>
                    @foreach ($status as $stat)
                        <option value="{{ $stat }}">{{ $stat }}</option>
                    @endforeach
                </select>
                <div class="mt-3">
                    <button class="bg-emerald-500 px-8 py-3 text-white rounded-md">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleNew(){
            document.getElementById('new-level').classList.toggle('hidden');
        }
        function changeStatus(){
            var status = document.getElementById('status-change').value;
            document.getElementById('status-form').setAttribute('action',`/levels/${status}`);
            console.log(document.getElementById('status-change').value);
            document.getElementById('status-form').submit();
        }
    </script>
</body>

</html>
