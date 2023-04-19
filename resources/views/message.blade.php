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
            <a href="@if (auth()->user()->user_type == 'Student'){{ route('dashboard') }}@else {{ route('lec_dashboard') }}" class="text-lg hover:border-b-4 border-emerald-500">Dashboard</a>
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
        <div class="rounded-md p-7 bg-emerald-100 shadow-md">
            <h1 class="font-bold uppercase text-black mb-3 text-2xl">{{ $complaint->title }}</h1>
            <p class="mb-1"><b>Matric Number:</b> {{ $complaint->matric_number }}</p>
            <p class="mb-1"><b>Date Recieved:</b> {{ $complaint->created_at }}</p>
            <h1 class="font-bold text-lg mb-2">Attachment</h1>
            @foreach ($attachments as $attachment)
                <a _blank="true" href="/storage/complaint_file/{{ $attachment->attachment }}">File</a>
            @endforeach
        </div>
        <div class="h-96 overflow-y-scroll relative bg-white mt-12 p-6 rounded-md shadow-md">
            <h1 class="text-2xl font-bold mb-3">Complaint Trail</h1>
            @if ($messages->count() > 0)
                @foreach ($messages as $message)
                    <div class="flex @if($message->from == auth()->user()->id) justify-end @endif mb-2">
                        <div class="rounded-md shadow md @if($message->from == auth()->user()->id)bg-emerald-100 @endif p-3.5">
                            <p class="mb-1.5">{{ $message->message }}</p>
                            <p class="text-right text-sm text-gray-400">{{ $message->created_at }}</p>
                        </div>
                    </div>
                @endforeach
            @else
                    <p class="text-2xl font-medium mt-8 text-center text-gray-400">No messages were sent</p>
            @endif
            </div>
            <div class="fixed left-0 bottom-0 w-full">
                <form action="/send-message/{{ $complaint->id }}" method="post" class="flex items-center">
                    @csrf
                    <textarea placeholder="Write Message" class="bg-emerald-100 p-3.5 block w-full resize-none" name="message" id="" cols="30" rows="1"></textarea>
                    <button class="px-9 py-2.5 bg-emerald-400" ><i class="fa fa-reply-all text-3xl text-white"></i></button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
