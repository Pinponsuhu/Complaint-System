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
            <h1 class="text-2xl font-bold text-emerald-900">user System</h1>
        </div>
        <div class="flex items-center gap-x-5">
            <a href="@if (auth()->user()->user_type == 'Student'){{ route('dashboard') }}@else {{ route('lec_dashboard') }} @endif" class="text-lg hover:border-b-4 border-emerald-500">Dashboard</a>
            <a href="/user/All" class="text-lg hover:border-b-4 border-emerald-500">Lecturers</a>
        </div>
        <div class="flex items-center gap-x-3">
            <img src="{{asset('img/user.png')}}" alt="" class="block w-7 h-7">
            <div>

                <h1>{{ auth()->user()->email }}</h1>
            </div>
        </div>
    </nav>
    <main class="px-16 mt-8">
        @if (auth()->user()->is_head == true)
            <div class="flex justify-end mb-5">
                <span onclick="toggleNew()" class="px-8 cursor-pointer py-3 bg-emerald-500 font-bold text-white rounded-md">Add new</span>
            </div>
        @endif
        <div class="mt-6 p-6 bg-white shadow-md">
            <div class="flex items-center mb-4 justify-between">
            <h1 class="text-xl font-medium text-emerald-500 mb-4">Department Lecturers</h1>
            </div>
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
            {!! $users->links() !!}
            @else
                <p class="text-center text-gray-300 text-2xl">
                    No lecturer has been registers
                </p>
            @endif

        </div>
    </main>
    @if (auth()->user()->is_head == true)
        <div id="new-complain" class="w-screen hidden h-screen top-0 left-0 flex justify-center items-center bg-emerald-950 bg-opacity-70 fixed">

            <div class="mx-auto w-4/12 bg-white p-6">
                <div class="flex mb-3 justify-end">
                    <i onclick="toggleNew()" class="fa cursor-pointer fa-times text-xl"></i>
                </div>
                <h1 class="text-xl font-medium mb-4">New Lecturer</h1>
                <form action="/lecturers" enctype="multipart/form-data" method="post">
                    @csrf
                    <label for="" class="font-bold mb-1.5">Staff ID</label>
                    <input type="text" name="staff_id" class="block p-3 bg-emerald-100 shadow-md w-full rounded-md mb-2" id="">
                    @error('staff_id')
                        <p class="text-red-400 font-bold my-1 text-sm">{{ $message }}</p>
                    @enderror
                    <label for="" class="font-bold mb-1.5">Level Assigned</label>
                    <select name="level_assigned" class="block p-3 bg-emerald-100 shadow-md w-full rounded-md mb-2" id="">
                        <option value="" selected disabled>None</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                        <option value="300">300</option>
                        <option value="400">400</option>
                        <option value="Final">Final</option>
                    </select>
                    @error('level_assigned')
                        <p class="text-red-400 font-bold my-1 text-sm">{{ $message }}</p>
                    @enderror
                    <label for="" class="font-bold mb-1.5">Email Address</label>
                    <input type="email" name="email" class="block p-3 bg-emerald-100 shadow-md w-full rounded-md mb-2" id="">
                    @error('email')
                        <p class="text-red-400 font-bold my-1 text-sm">{{ $message }}</p>
                    @enderror
                    <div class="mt-3">
                        <button class="bg-emerald-500 px-8 py-3 text-white rounded-md">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
    <script>
        function toggleNew(){
            document.getElementById('new-complain').classList.toggle('hidden');
        }

        function changeStatus(){
            var status = document.getElementById('status-change').value;
            document.getElementById('status-form').setAttribute('action',`/user/${status}`);
            console.log(document.getElementById('status-change').value);
            document.getElementById('status-form').submit();
        }
    </script>
</body>
</html>
