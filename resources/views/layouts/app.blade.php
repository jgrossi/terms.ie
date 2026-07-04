<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'terms.ie')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-base-200 min-h-screen"
      x-data
      hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'>

    <nav class="bg-base-100 border-b border-base-200">
        <div class="max-w-6xl mx-auto px-6 h-14 flex items-center justify-between">
            <a href="{{ route('app.dashboard') }}" class="font-bold text-lg tracking-tight text-base-content">
                terms<span class="text-primary">.ie</span>
            </a>
            <div class="flex items-center gap-1 flex-1 justify-center">
                <a href="{{ route('app.dashboard') }}"
                   class="btn btn-ghost btn-sm {{ request()->routeIs('app.dashboard') ? 'btn-active' : '' }}">Dashboard</a>
                <a href="{{ route('app.terms.index') }}"
                   class="btn btn-ghost btn-sm {{ request()->routeIs('app.terms.*') ? 'btn-active' : '' }}">Terms</a>
                <a href="{{ route('app.clients.index') }}"
                   class="btn btn-ghost btn-sm {{ request()->routeIs('app.clients.*') ? 'btn-active' : '' }}">Clients</a>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-base-content/60">{{ auth()->user()->email }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-xs">Sign out</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-6 py-8">
        @yield('content')
    </main>

    <div x-data="toastStack(@js(session('toast') ? ['message' => session('toast'), 'type' => 'success'] : null))"
         @toast-push.window="add($event.detail.message, $event.detail.type)"
         class="toast toast-top toast-end z-50">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="true"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 role="alert"
                 class="alert shadow-lg bg-neutral-900 text-white border-0"
                 :class="{
                     'alert-error':   toast.type === 'error',
                     'alert-warning': toast.type === 'warning',
                 }">
                <span x-text="toast.message"></span>
            </div>
        </template>
    </div>


</body>
</html>
