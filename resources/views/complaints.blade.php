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
            <a href="@if (auth()->user()->user_type == 'Student'){{ route('dashboard') }}@else {{ route('lec_dashboard') }} @endif" class="text-lg hover:border-b-4 border-emerald-500">Dashboard</a>
            <a href="/complaint/All" class="text-lg hover:border-b-4 border-emerald-500">Complaints</a>
        </div>
        <div class="flex items-center gap-x-3">
            <img src="{{asset('img/user.png')}}" alt="" class="block w-7 h-7">
            <div>
                @php
                    $student = App\Models\Student::where('user_id', auth()->user()->id)->first();
                @endphp
                @if (auth()->user()->user_type == 'Student')
                <h1>{{ $student->matric_number }}</h1>
                @endif
                <h1>{{ auth()->user()->email }}</h1>
            </div>
        </div>
    </nav>
    <main class="px-16 mt-8">
        @if (auth()->user()->user_type == 'Student')
            <div class="flex justify-end mb-5">
                <span onclick="toggleNew()" class="px-8 cursor-pointer py-3 bg-emerald-500 font-bold text-white rounded-md">Add new</span>
            </div>
        @endif
        <div class="flex rounded-md w-full gap-x-8 p-4 bg-white shadow-md">
            <div class="flex gap-x-3 mb-2">
                <div class="w-4 h-4 rounded-full bg-blue-400"></div>
                <p><b>Total complaint sent:</b> {{ $total }}</p>
            </div>
            <div class="flex gap-x-3 mb-2">
                <div class="w-4 h-4 rounded-full bg-green-400"></div>
                <p><b>Total Active complaint:</b> {{ $active }}</p>
            </div>
            <div class="flex gap-x-3 mb-2">
                <div class="w-4 h-4 rounded-full bg-amber-400"></div>
                <p><b>Total Active complaint:</b> {{ $pending }}</p>
            </div>
            <div class="flex gap-x-3 mb-2">
                <div class="w-4 h-4 rounded-full bg-red-400"></div>
                <p><b>Total Closed complaint:</b> {{ $closed }}</p>
            </div>
        </div>
        <div class="mt-6 p-6 bg-white shadow-md">
            <div class="flex items-center mb-4 justify-between">
            <h1 class="text-xl font-medium text-emerald-500 mb-4">Complaint History</h1>
            <form action="" id="status-form" method="get">
                @csrf
                <select name="status-change" onchange="changeStatus()" class="px-5 py-2.5 bg-white shadow-md" id="status-change">
                    <option value="" disabled selected>Status</option>
                    <option value="Active">Active</option>
                    <option value="Closed">Closed</option>
                    <option value="Pending">Pending</option>
                </select>
            </form>
            </div>
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
                                @foreach ($status as $stat)
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
            {!! $complaints->links() !!}

            @else
                <p class="text-center text-gray-300 text-2xl">
                    No complaint has been sent
                </p>
            @endif

        </div>
    </main>
    @if (auth()->user()->user_type == 'Student')
        <div id="new-complain" class="w-screen hidden h-screen top-0 left-0 flex justify-center items-center bg-emerald-950 bg-opacity-70 fixed">

            <div class="mx-auto w-4/12 bg-white p-6">
                <div class="flex mb-3 justify-end">
                    <i onclick="toggleNew()" class="fa cursor-pointer fa-times text-xl"></i>
                </div>
                <h1 class="text-xl font-medium mb-4">Make a new complaint</h1>
                <form action="{{ route('add_complaint') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    <label for="" class="font-bold mb-1.5">Title</label>
                    <input type="text" name="title" class="block p-3 bg-emerald-100 shadow-md w-full rounded-md mb-2" id="">
                    @error('title')
                        <p class="text-red-400 font-bold my-1 text-sm">{{ $message }}</p>
                    @enderror
                    <label for="" class="font-bold mb-1.5">Description</label>
                    <textarea name="description" class="block mb-3 w-full resize-none bg-emerald-100 p-3" id="" cols="30" rows="4"></textarea>
                    @error('description')
                        <p class="text-red-400 font-bold my-1 text-sm">{{ $message }}</p>
                    @enderror
                    <label for="" class="font-bold mb-1.5">Attachments</label>
                    <input type="file" class="block p-3 bg-emerald-100 shadow-md w-full rounded-md mb-2" name="attachments[]" multiple id="">
                    @error('attachments')
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
            document.getElementById('status-form').setAttribute('action',`/complaint/${status}`);
            console.log(document.getElementById('status-change').value);
            document.getElementById('status-form').submit();
        }
    </script>
</body>
</html>
