@extends('layouts.admin')

@section('title', 'Settings — AMER Admin')
@section('heading', 'Settings & Navigation')

@section('content')
    <div class="settings-intro">
        <div>
            <p class="settings-kicker">AMER CONTROL CENTRE</p>
            <h2>Manage the entire store from one place.</h2>
            <p>Choose a section below. You no longer need to type an admin URL manually.</p>
        </div>
        <a class="settings-store-button" href="{{ route('home') }}" target="_blank" rel="noopener">
            View live storefront ↗
        </a>
    </div>

    <section class="settings-section">
        <div class="settings-section-head">
            <span>01</span>
            <div><h3>Storefront & Branding</h3><p>Control how customers see AMER.</p></div>
        </div>
        <div class="settings-card-grid">
            <a class="settings-card featured" href="{{ route('admin.settings.store-design.edit') }}">
                <span class="settings-icon">◇</span><div><h4>Store Design</h4><p>Logo, favicon, colours, announcement, homepage slides and videos.</p></div><strong>Open →</strong>
            </a>
            <a class="settings-card" href="{{ route('admin.pages.index') }}">
                <span class="settings-icon">▤</span><div><h4>Custom Pages</h4><p>About, contact, privacy, shipping, refund and other information pages.</p></div><strong>Open →</strong>
            </a>
            <a class="settings-card" href="{{ route('orders.track.form') }}" target="_blank" rel="noopener">
                <span class="settings-icon">◎</span><div><h4>Order Tracking Page</h4><p>Preview the public customer order-tracking experience.</p></div><strong>Preview →</strong>
            </a>
        </div>
    </section>

    <section class="settings-section">
        <div class="settings-section-head">
            <span>02</span>
            <div><h3>Products & Catalog</h3><p>Create brands, categories and products.</p></div>
        </div>
        <div class="settings-card-grid">
            <a class="settings-card" href="{{ route('admin.products.index') }}"><span class="settings-icon">□</span><div><h4>Products</h4><p>Create, edit, price and publish products and variants.</p></div><strong>Open →</strong></a>
            <a class="settings-card" href="{{ route('admin.brands.index') }}"><span class="settings-icon">A</span><div><h4>Brands</h4><p>Create AMER and other brands with individual logos.</p></div><strong>Open →</strong></a>
            <a class="settings-card" href="{{ route('admin.categories.index') }}"><span class="settings-icon">⌘</span><div><h4>Categories</h4><p>Arrange Clothing, Men, Women, Accessories, Toys and collections.</p></div><strong>Open →</strong></a>
        </div>
    </section>

    <section class="settings-section">
        <div class="settings-section-head">
            <span>03</span>
            <div><h3>Stock & Sales Operations</h3><p>Manage stock, purchasing and counter sales.</p></div>
        </div>
        <div class="settings-card-grid">
            <a class="settings-card" href="{{ route('admin.inventory.index') }}"><span class="settings-icon">▦</span><div><h4>Inventory</h4><p>View current stock and make authorized adjustments.</p></div><strong>Open →</strong></a>
            <a class="settings-card" href="{{ route('admin.inventory.movements') }}"><span class="settings-icon">↕</span><div><h4>Stock Ledger</h4><p>Review every stock movement and transaction.</p></div><strong>Open →</strong></a>
            <a class="settings-card" href="{{ route('admin.purchases.index') }}"><span class="settings-icon">+</span><div><h4>Purchases</h4><p>Create purchase orders and receive stock.</p></div><strong>Open →</strong></a>
            <a class="settings-card" href="{{ route('admin.suppliers.index') }}"><span class="settings-icon">◯</span><div><h4>Suppliers</h4><p>Manage product and stock suppliers.</p></div><strong>Open →</strong></a>
            <a class="settings-card" href="{{ route('admin.pos.index') }}"><span class="settings-icon">▣</span><div><h4>Point of Sale</h4><p>Open the online POS sales interface.</p></div><strong>Open →</strong></a>
        </div>
    </section>

    <section class="settings-section">
        <div class="settings-section-head">
            <span>04</span>
            <div><h3>Company & System</h3><p>Business identity and system access.</p></div>
        </div>
        <div class="settings-card-grid">
            @if ($company)
                <a class="settings-card" href="{{ route('admin.settings.company.edit', $company) }}"><span class="settings-icon">AM</span><div><h4>Company Settings</h4><p>Name, contact, address and official business information.</p></div><strong>Open →</strong></a>
            @endif
            <a class="settings-card" href="{{ route('admin.dashboard') }}"><span class="settings-icon">⌂</span><div><h4>Admin Dashboard</h4><p>Return to the main AMER administration overview.</p></div><strong>Open →</strong></a>
            <a class="settings-card" href="{{ route('health.ready') }}" target="_blank" rel="noopener"><span class="settings-icon">✓</span><div><h4>System Health</h4><p>Check application, database and cache readiness.</p></div><strong>Check →</strong></a>
        </div>
    </section>
@endsection

