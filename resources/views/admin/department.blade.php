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
                <h1 class="text-xl font-medium text-emerald-500">Recently added departments</h1>
                <form action="" id="status-form" method="get">
                    @csrf
                    <select name="status-change" onchange="changeStatus()" class="px-5 py-2.5 bg-white shadow-md" id="status-change">
                        <option value="" disabled selected>Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </form>
            </div>
            <span onclick="toggleNew()" class="cursor-pointer px-8 py-2 bg-emerald-500 text-white rounded-md">Add new</span>
            </div>
            @if ($departments->count() > 0)
            <table class="w-full">
                <thead>
                    <tr>
                        <td class="bg-emerald-700 p-3 text-white font-bold"></td>
                        <td class="bg-emerald-700 p-3 text-white font-bold">Date Added</td>
                        <td class="bg-emerald-700 p-3 text-white font-bold">Department ID</td>
                        <td class="bg-emerald-700 p-3 text-white font-bold">Name</td>
                            <td class="bg-emerald-700 p-3 text-white font-bold">Action</td>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($departments as $department)
                    <tr>
                        <td class="bg-emerald-100 p-3"><div class="w-3.5 h-3.5 rounded-md bg-blue-300"></div></td>
                        <td class="bg-emerald-100 p-3">{{ $department->created_at }}</td>
                        <td class="bg-emerald-100 p-3">{{ $department->department_id }}</td>
                        <td class="bg-emerald-100 p-3">{{ $department->name }}</td>
                        <td class="bg-emerald-100 p-3">
                            <form action="/department/{{ $department->id }}" method="post">
                            @csrf
                            <button class="py-2 px-8 bg-red-400 text-white rounded-md">Change Status</button>
                        </form>
                            </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $departments->links() !!}
            @else
                <p class="text-center text-gray-300 text-2xl">
                    No Department has been added
                </p>
            @endif
        </div>
    </main>
    <div id="new-department" class="hidden fixed w-full h-full flex items-center align-middle bg-emerald-800 bg-opacity-80">
        <div class="mx-auto w-5/12 rounded-md p-6 bg-white">
            <div class="flex mb-3 justify-end">
                <i onclick="toggleNew()" class="fa cursor-pointer fa-times text-xl"></i>
            </div>
            <h1 class="text-xl font-medium mb-4">Add a new Department</h1>
            <form action="{{ route('department') }}" enctype="multipart/form-data" method="post">
                @csrf
                <label for="" class="font-bold mb-1.5">Department ID</label>
                <input type="text" name="department_id" class="block p-3 bg-emerald-100 shadow-md w-full rounded-md mb-2" id="">
                @error('department_id')
                    <p class="text-red-400 font-bold my-1 text-sm">{{ $message }}</p>
                @enderror
                <label for="" class="font-bold mb-1.5">Department name</label>
                <input type="text" name="name" class="block p-3 bg-emerald-100 shadow-md w-full rounded-md mb-2" id="">
                @error('name')
                    <p class="text-red-400 font-bold my-1 text-sm">{{ $message }}</p>
                @enderror
                <div class="mt-3">
                    <button class="bg-emerald-500 px-8 py-3 text-white rounded-md">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleNew(){
            document.getElementById('new-department').classList.toggle('hidden');
        }
        function changeStatus(){
            var status = document.getElementById('status-change').value;
            document.getElementById('status-form').setAttribute('action',`/departments/${status}`);
            console.log(document.getElementById('status-change').value);
            document.getElementById('status-form').submit();
        }
    </script>
</body>

</html>
