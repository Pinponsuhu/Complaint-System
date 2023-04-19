<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Complaint System</title>
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <script src="{{ asset('js/all.js') }}"></script>
    <style>
        body{
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body class="bg-emerald-100 w-screen h-screen flex justify-center items-center content-center">
    <div class="md:w-6/12 rounded-md lg:w-4/12 w-11/12 mx-auto bg-white px-4 md:px-14 content-center py-6 md:py-10">
        <img src="{{ asset('img/lasu.png') }}" class="h-20 w-auto" alt="">
        <h1 class="font-bold text-3xl text-emerald-900 mt-6">Welcome Back</h1>
        <p class="text-md font-medium text-gray-400">Don't have an account? <a href="{{ route('register') }}" class="border-dotted border-b-2 border-gray-400">Click here</a></p>
        <form action="{{ route('login') }}" method="post" class="mt-4">
            @csrf
            @if (session('message'))
                <p class="text-red-400 text-md text-center font-bold text-sm my-2">{{ session('message') }}</p>
            @endif
            <input type="text" name="email" class="block w-full border-b-2 border-emerald-900 mb-3 py-3.5 outline-none bg-emerald-100 px-1.5" placeholder="Email Address">
            @error('email')
                <p class="text-red-400 font-bold my-1 text-sm">{{ $message }}</p>
            @enderror
            <input type="password" name="password" class="block w-full border-b-2 border-emerald-900 mb-3 py-3.5 outline-none bg-emerald-100 px-1.5" placeholder="Password">
            @error('password')
                <p class="text-red-400 font-bold my-1 text-sm">{{ $message }}</p>
            @enderror
            <div class="flex justify-between items-center mt-2">
                <div class="flex gap-x-2 items-center">
                    <input type="checkbox" name="remember_me" id="">
                    <p>Remember me</p>
                </div>
                <p>
            </div>
            <button class="font-bold mt-3 bg-emerald-500 text-white w-36 mx-auto py-2.5">Sign In</button>
        </form>
    </div>
</body>
</html>
