<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-900 text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EIS Bridge - Operations Dashboard</title>
    
    <!-- Tailwind v4 & Alpine CSS / JS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Livewire Styles & Scripts -->
    @livewireStyles
    @fluxAppearance
</head>
<body class="h-full font-sans antialiased flex flex-col">
    <!-- Navbar -->
    <header class="bg-slate-800 border-b border-slate-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <span class="text-2xl font-bold bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">
                        EIS Bridge
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                        Active
                    </span>
                </div>
                <nav class="flex items-center gap-6">
                    <span class="text-sm text-slate-400 hidden md:inline">Malawi Revenue Authority (MRA) Integration Portal</span>
                    
                    @auth
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-semibold text-slate-300">{{ auth()->user()->name }}</span>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 text-sm font-bold bg-slate-700 hover:bg-slate-650 text-white rounded-xl shadow transition">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-sm font-bold bg-slate-700 hover:bg-slate-650 text-white rounded-xl shadow transition">
                                Sign Out
                            </button>
                        </form>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 py-8 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Operations View Navigation Links -->
        <div class="flex flex-wrap items-center gap-2 border-b border-slate-700 pb-4">
            <a href="{{ route('tenants') }}" class="px-4 py-2 text-sm font-semibold rounded-lg transition {{ request()->routeIs('tenants') || request()->path() === '/' ? 'bg-cyan-500 text-white shadow' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800' }}">
                Multi-Tenant Configuration
            </a>
            <a href="{{ route('connections') }}" class="px-4 py-2 text-sm font-semibold rounded-lg transition {{ request()->routeIs('connections') ? 'bg-cyan-500 text-white shadow' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800' }}">
                QuickBooks Connections
            </a>
            <a href="{{ route('invoices') }}" class="px-4 py-2 text-sm font-semibold rounded-lg transition {{ request()->routeIs('invoices') ? 'bg-cyan-500 text-white shadow' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800' }}">
                Synchronized Invoices
            </a>
            <a href="{{ route('logs') }}" class="px-4 py-2 text-sm font-semibold rounded-lg transition {{ request()->routeIs('logs') ? 'bg-cyan-500 text-white shadow' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800' }}">
                SOAP & REST Audit Logs
            </a>
        </div>

        @yield('content')
        {{ $slot ?? '' }}
    </main>

    @livewireScripts
    @fluxScripts
</body>
</html>
