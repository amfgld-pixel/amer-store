<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AMER Admin')</title>
    <link rel="stylesheet" href="{{ asset('css/amer-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/amer-catalog.css') }}">
    <link rel="stylesheet" href="{{ asset('css/amer-inventory.css') }}">
    <link rel="stylesheet" href="{{ asset('css/amer-pack-013.css') }}">
    <link rel="stylesheet" href="{{ asset('css/amer-admin-navigation.css') }}">
</head>
<body>
    <button class="admin-menu-button" type="button" data-admin-menu aria-label="Open admin menu">☰</button>

    <aside class="sidebar admin-sidebar" data-admin-sidebar>
        <div class="sidebar-brand-row">
            <a class="brand" href="{{ route('admin.dashboard') }}">AMER</a>
            <button type="button" data-admin-close aria-label="Close admin menu">×</button>
        </div>
        <span class="edition">COMMERCE OS</span>

        <nav class="admin-navigation">
            <span class="nav-heading">Overview</span>
            <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><span>⌂</span> Dashboard</a>
            @if (auth()->user()?->hasRole('super-admin'))
                <a class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}"><span>⚙</span> All Settings</a>
            @endif

            <span class="nav-heading">Catalog</span>
            <a class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}"><span>□</span> Products</a>
            <a class="{{ request()->routeIs('admin.brands.*') ? 'active' : '' }}" href="{{ route('admin.brands.index') }}"><span>A</span> Brands</a>
            <a class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}"><span>⌘</span> Categories</a>

            <span class="nav-heading">Operations</span>
            <a class="{{ request()->routeIs('admin.inventory.index') ? 'active' : '' }}" href="{{ route('admin.inventory.index') }}"><span>▦</span> Inventory</a>
            <a class="{{ request()->routeIs('admin.inventory.movements') ? 'active' : '' }}" href="{{ route('admin.inventory.movements') }}"><span>↕</span> Stock Ledger</a>
            <a class="{{ request()->routeIs('admin.purchases.*') ? 'active' : '' }}" href="{{ route('admin.purchases.index') }}"><span>+</span> Purchases</a>
            <a class="{{ request()->routeIs('admin.work-orders.*') ? 'active' : '' }}" href="{{ route('admin.work-orders.index') }}"><span>W</span> Work Orders</a>
            <a class="{{ request()->routeIs('admin.proforma-invoices.*') ? 'active' : '' }}" href="{{ route('admin.proforma-invoices.index') }}"><span>PI</span> Proforma Invoices</a>
            <a class="{{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}" href="{{ route('admin.suppliers.index') }}"><span>◯</span> Suppliers</a>
            <a class="{{ request()->routeIs('admin.pos.*') ? 'active' : '' }}" href="{{ route('admin.pos.index') }}"><span>▣</span> Point of Sale</a>

            @if (auth()->user()?->hasRole('super-admin'))
                <span class="nav-heading">Website</span>
                <a class="{{ request()->routeIs('admin.settings.store-design.*') ? 'active' : '' }}" href="{{ route('admin.settings.store-design.edit') }}"><span>◇</span> Store Design</a>
                <a class="{{ request()->routeIs('admin.pages.*') ? 'active' : '' }}" href="{{ route('admin.pages.index') }}"><span>▤</span> Custom Pages</a>
            @endif

            <span class="nav-heading">Storefront</span>
            <a href="{{ route('home') }}" target="_blank" rel="noopener"><span>↗</span> View Website</a>
            <a href="{{ route('orders.track.form') }}" target="_blank" rel="noopener"><span>◎</span> Track Order Page</a>
        </nav>
    </aside>

    <div class="admin-overlay" data-admin-overlay></div>

    <div class="workspace">
        <header>
            <div><small>AMER / ADMINISTRATION</small><h1>@yield('heading', 'Dashboard')</h1></div>
            <div class="admin-header-actions">
                @if (auth()->user()?->hasRole('super-admin'))
                    <a href="{{ route('admin.settings.index') }}">Settings</a>
                @endif
                <a href="{{ route('home') }}" target="_blank" rel="noopener">View Store ↗</a>
                <div class="profile">{{ auth()->user()->name ?? 'Admin' }}</div>
            </div>
        </header>
        <main>
            @if (session('status'))<div class="notice">{{ session('status') }}</div>@endif
            @if ($errors->any())<div class="notice error">{{ $errors->first() }}</div>@endif
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.querySelector('[data-admin-sidebar]');
            const overlay = document.querySelector('[data-admin-overlay]');
            const open = () => { sidebar?.classList.add('open'); overlay?.classList.add('open'); };
            const close = () => { sidebar?.classList.remove('open'); overlay?.classList.remove('open'); };
            document.querySelector('[data-admin-menu]')?.addEventListener('click', open);
            document.querySelector('[data-admin-close]')?.addEventListener('click', close);
            overlay?.addEventListener('click', close);
        });
    </script>
</body>
</html>

