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
<body class>
    <nav class="bg-white flex justify-between items-center py-3 px-6 shadow-md sticky">
        <div class="flex gap-x-4 items-center">
            <img src="{{asset('img/lasu.png')}}" class="block h-14 w-auto" alt="" srcset="">
            <h1 class="text-2xl font-bold text-emerald-900">Complaint System</h1>
        </div>
        <div class="flex items-center gap-x-5">
            <a href="{{ route('lec_dashboard') }}" class="text-lg hover:border-b-4 border-emerald-500">Dashboard</a>
            @if (auth()->user()->is_head == true)
            <a href="/lecturers" class="text-lg hover:border-b-4 border-emerald-500">Lecturers</a>
            @endif
            @if (auth()->user()->is_head == false)
            <a href="/complaint/All" class="text-lg hover:border-b-4 border-emerald-500">Complaints</a>
            @endif
        </div>
        <div class="flex items-center gap-x-3">
            <img src="{{asset('img/user.png')}}" alt="" class="block w-7 h-7">
            <div>
                @php
                    $student = App\Models\Lecturer::where('user_id', auth()->user()->id)->first();
                @endphp
                {{-- <h1>{{ $student->staff_id }}</h1> --}}
                <h1>{{ auth()->user()->email }}</h1>
            </div>
        </div>
    </nav>
    <main class="px-16 mt-8">
        <div class="flex rounded-md w-full gap-x-8 p-4 bg-white shadow-md">
            <div class="p-6 w-72 justify-between flex items-center gap-x-5 rounded-md bg-gradient-to-br from-emerald-600 to-emerald-300">
                <div>
                    <h1 class="text-white font-bold text-xl">Assigned<br>Complaint</h1>
                    <h1 class="font-extrabold text-5xl mt-3 text-white">{{ $total }}</h1>
                </div>
                <div class="w-12 h-12 rounded-full flex items-center justify-center align-middle bg-opacity-90 bg-white">
                    <i class="fas text-emerald-400 fa-comment text-3xl"></i>
                </div>
            </div>
            <div class="p-6 w-72 justify-between flex items-center gap-x-5 rounded-md bg-gradient-to-br from-amber-500 to-amber-300">
                <div>
                    <h1 class="text-white font-bold text-xl">Pending<br>Complaint</h1>
                    <h1 class="font-extrabold text-5xl mt-3 text-white">{{ $pending }}</h1>
                </div>
                <div class="w-12 h-12 rounded-full flex items-center justify-center align-middle bg-opacity-90 bg-white">
                    <i class="fas text-amber-400 fa-comment-dots text-3xl"></i>
                </div>
            </div>
            <div class="p-6 w-72 justify-between flex items-center gap-x-5 rounded-md bg-gradient-to-br from-rose-500 to-rose-300">
                <div>
                    <h1 class="text-white font-bold text-xl">Closed<br>Complaint</h1>
                    <h1 class="font-extrabold text-5xl mt-3 text-white">{{ $closed }}</h1>
                </div>
                <div class="w-12 h-12 rounded-full flex items-center justify-center align-middle bg-opacity-90 bg-white">
                    <i class="fas text-rose-500 fa-comment-slash text-3xl"></i>
                </div>
            </div>
        </div>
        <div class="mt-6 p-6 bg-white shadow-md">
        @if (auth()->user()->is_head == true)
        <div class="mt-6 p-6 bg-white shadow-md">
            <h1 class="text-xl font-bold text-emerald-500 mb-4">Lecturers</h1>
            @if ($users->count() > 0)
            <table class="w-full">
                <thead>
                    <tr>
                        <td class="bg-emerald-700 p-3 text-white font-bold"></td>
                        <td class="bg-emerald-700 p-3 text-white font-bold">Created date</td>
                        <td class="bg-emerald-700 p-3 text-white font-bold">Staff ID</td>
                        <td class="bg-emerald-700 p-3 text-white font-bold">Email</td>
                        <td class="bg-emerald-700 p-3 text-white font-bold">Level Assigned</td>
                        <td class="bg-emerald-700 p-3 text-white font-bold">Status</td>
                            <td class="bg-emerald-700 p-3 text-white font-bold">Action</td>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    @php
                        $check = App\Models\Lecturer::where('user_id',$user->id)->first();
                        $me = App\Models\Lecturer::where('user_id',auth()->user()->id)->first();
                    @endphp
                    @if ($check->department_id == $me->department_id)
                    <tr>
                        <td class="bg-emerald-100 p-3"><div class="w-3.5 h-3.5 rounded-md bg-blue-300"></div></td>
                        <td class="bg-emerald-100 p-3">{{ $user->created_at }}</td>
                        @php
                            $staff =  App\Models\Lecturer::where('user_id',$user->id)->first();
                        @endphp
                        <td class="bg-emerald-100 p-3">{{ $staff->staff_id }}</td>
                        <td class="bg-emerald-100 p-3">{{ $user->email }}</td>
                        <td class="bg-emerald-100 p-3">{{ $staff->level_assigned }}</td>
                        <td class="bg-emerald-100 p-3"><p class="text-white py-1 px-4 rounded-md bg-emerald-500 text-md">{{ $user->is_banned }}</p></td>
                        <td class="bg-emerald-100 p-3"><form action="/toggle/{{ $user->id }}" method="post">
                            @csrf
                            <button class="py-2 px-8 bg-red-500 text-white">Toggle</button>
                        </form></td>
                    </tr>

                    @endif
                    @endforeach
                </tbody>
            </table>
            @else
                <p class="text-center text-gray-300 text-2xl">
                    No lecturer has been registers
                </p>
            @endif
            @if ($users->count() > 0)
                <div class="flex justify-center mt-6 font-medium text-emerald-500"><a href="">See All</a></div>
            @endif
        </div>
        @else
        @if ($complaints->count() > 0)
        <table class="w-full">
            <thead>
                <tr>
                    <td class="bg-emerald-700 p-3 text-white font-bold"></td>
                    <td class="bg-emerald-700 p-3 text-white font-bold">Date Sent</td>
                    <td class="bg-emerald-700 p-3 text-white font-bold">Complaint ID</td>
                    <td class="bg-emerald-700 p-3 text-white font-bold">Topic</td>
                    <td class="bg-emerald-700 p-3 text-white font-bold">Narrative</td>
                    <td class="bg-emerald-700 p-3 text-white font-bold">Status</td>
                    @if(auth()->user()->user_type == 'Lecturer' || auth()->user()->user_type == 'Admin')
                        <td class="bg-emerald-700 p-3 text-white font-bold">Action</td>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($complaints as $complaint)
                <tr>
                    <td class="bg-emerald-100 p-3"><div class="w-3.5 h-3.5 rounded-md bg-blue-300"></div></td>
                    <td class="bg-emerald-100 p-3">{{ $complaint->created_at }}</td>
                    <td class="bg-emerald-100 p-3"><a href="/complaint-trail/{{ $complaint->id }}" class="text-blue-400 hover:underline">#{{ $complaint->complaint_id }}</a></td>
                    <td class="bg-emerald-100 p-3">{{ Str::limit($complaint->title, 30) }}</td>
                    <td class="bg-emerald-100 p-3">{{ Str::limit($complaint->description, 80) }}</td>
                    <td class="bg-emerald-100 p-3"><p class="text-white py-1 px-4 rounded-md bg-emerald-500 text-md">{{ $complaint->status }}</p></td>
                    @if(auth()->user()->user_type == 'Lecturer' || auth()->user()->user_type == 'Admin')
                    <td class="bg-emerald-100 p-3"><form action="/status/{{ $complaint->id }}" method="post">
                        @csrf
                        <select name="status" onchange="this.form.submit()" class="bg-white p-3" id="">
                            <option value="" selected disabled>Change status</option>
                            @foreach ($status2 as $stat)
                                @if ($stat != $complaint->status)
                                <option value="{{ $stat }}">{{ $stat }}</option>
                                @endif
                            @endforeach
                        </select>
                    </form></td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>

        @else
            <p class="text-center text-gray-300 text-2xl">
                No complaint has been sent
            </p>
        @endif
        @endif
        </div>
    </main>
    <script>
        function changeStatus(){
            document.getElementById('change').submit();
        }
    </script>
</body>
</html>
