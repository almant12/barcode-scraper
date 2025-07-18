<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'App')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

    <header class="bg-blue-600 text-white p-4">
        <div class="container mx-auto">
            <h1 class="text-xl font-bold">📦 Barcode App</h1>
        </div>
    </header>

    <main class="flex-grow container mx-auto py-6">
        @yield('content')
    </main>

    <footer class="bg-gray-200 text-center text-sm py-4">
        &copy; {{ date('Y') }} Barcode App
    </footer>

</body>

</html>
