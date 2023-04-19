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
        <div class=" rounded-md w-full p-4 bg-white shadow-md">
            <h1 class="text-xl font-medium text-emerald-500 mb-4">All Lecturers</h1>
            <div class="flex rounded-md w-full gap-x-8 p-4 bg-white shadow-md">
                <div class="p-6 w-72 justify-between flex items-center gap-x-5 rounded-md bg-gradient-to-br from-emerald-600 to-emerald-300">
                    <div>
                        <h1 class="text-white font-bold text-xl">Active<br>Lecturers</h1>
                        <h1 class="font-extrabold text-5xl mt-3 text-white">{{ $active }}</h1>
                    </div>
                    <div class="w-12 h-12 rounded-full flex items-center justify-center align-middle bg-opacity-90 bg-white">
                        <i class="fas text-emerald-400 fa-comment text-3xl"></i>
                    </div>
                </div>
                <div class="p-6 w-72 justify-between flex items-center gap-x-5 rounded-md bg-gradient-to-br from-amber-500 to-amber-300">
                    <div>
                        <h1 class="text-white font-bold text-xl">Banned<br>Lecturers</h1>
                        <h1 class="font-extrabold text-5xl mt-3 text-white">{{ $banned }}</h1>
                    </div>
                    <div class="w-12 h-12 rounded-full flex items-center justify-center align-middle bg-opacity-90 bg-white">
                        <i class="fas text-amber-400 fa-comment-dots text-3xl"></i>
                    </div>
                </div>

            </div>
        </div>
        <div class="w-full p-6 rounded-md shadow-md">
            <h1 class="text-xl font-medium text-emerald-500 mb-4">Recently added users</h1>
            @if ($users->count() > 0)
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
                    @foreach ($users as $user)
                    <tr>
                        <td class="bg-emerald-100 p-3"><div class="w-3.5 h-3.5 rounded-md bg-blue-300"></div></td>
                        <td class="bg-emerald-100 p-3">{{ $user->created_at }}</td>
                        <td class="bg-emerald-100 p-3">Lecturers</td>
                        @php
                            if($user->user_type == 'Student'){
                                $student = App\Models\Student::where('user_id', $user->id)->first();
                            }else{
                                $student = App\Models\Lecturer::where('user_id', $user->id)->first();
                            }
                        @endphp
                        @if ($user->user_type == 'Student')
                        <td class="bg-emerald-100 p-3">{{ $student->matric_number }}</td>
                        @else
                        <td class="bg-emerald-100 p-3">{{ $student->staff_id }}</td>

                        @endif
                        <td class="bg-emerald-100 p-3">{{ $user->email }}</td>
                        <td class="bg-emerald-100 p-3"><p class="text-white py-1 px-4 rounded-md bg-emerald-500 text-md">@if($user->is_banned == true) Banned @else Active @endif</p></td>
                        @if(auth()->user()->user_type == 'Admin')
                        <td class="bg-emerald-100 p-3"><form action="/toggle/{{ $user->id }}" method="post">
                            @csrf
                            <button class="py-2 px-8 bg-red-400 text-white rounded-md font-medium">Toggle</button>
                        </form></td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $users->links() !!}
            @else
                <p class="text-center text-gray-300 text-2xl">
                    No Lecturers has been added
                </p>
            @endif
        </div>
    </main>
</body>
</html>
