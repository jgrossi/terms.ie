<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'terms.ie')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-base-200 min-h-screen">

    <nav class="bg-base-100 border-b border-base-200">
        <div class="max-w-3xl mx-auto px-6 h-14 flex items-center">
            <span class="font-bold text-lg tracking-tight text-base-content">
                terms<span class="text-primary">.ie</span>
            </span>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-6 py-10">
        @yield('content')
    </main>

</body>
</html>
