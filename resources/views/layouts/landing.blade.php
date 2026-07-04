<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'terms.ie — Create, send and sign terms online')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-base-100"
      x-data
      hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'>

    <nav class="sticky top-0 z-40 bg-base-100/95 backdrop-blur border-b border-base-200">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="font-bold text-xl tracking-tight text-base-content">
                terms<span class="text-primary">.ie</span>
            </a>
            <div class="flex items-center gap-2">
                <a href="#pricing" class="btn btn-ghost btn-sm">Pricing</a>
                @auth
                    <a href="{{ route('app.dashboard') }}" class="btn btn-primary btn-sm">Go to app →</a>
                @else
                    <a href="#get-started" class="btn btn-primary btn-sm">Get started</a>
                @endauth
            </div>
        </div>
    </nav>

    @yield('content')

    <div x-data="toastStack()"
         @toast-push.window="add($event.detail.message, $event.detail.type)"
         class="fixed bottom-4 right-4 z-50 flex flex-col gap-2 min-w-64">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="true"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 role="alert"
                 class="alert shadow-lg"
                 :class="{
                     'alert-success': toast.type === 'success',
                     'alert-error':   toast.type === 'error',
                     'alert-warning': toast.type === 'warning',
                 }">
                <span x-text="toast.message"></span>
            </div>
        </template>
    </div>

</body>
</html>
