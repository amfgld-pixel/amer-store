@extends('layouts.storefront')

@section('title', 'AMER — Modern Luxury from Bangladesh')

@section('content')
    @php
        $slideDiskName = config('filesystems.default', 'public');
        $slideDiskName = $slideDiskName === 'local' ? 'public' : $slideDiskName;
        $slideDisk = \Illuminate\Support\Facades\Storage::disk($slideDiskName);
    @endphp
    @if ($promotionBanner)
        <div class="promotion-hero-banner">{{ $promotionBanner }}</div>
    @endif

    @if ($slides->isNotEmpty())
        <section class="cms-slider" data-slider>
            @foreach ($slides as $index => $slide)
                <article class="cms-slide {{ $index === 0 ? 'active' : '' }} slide-align-{{ $slide->text_alignment ?? 'left' }} slide-position-{{ $slide->text_position ?? 'middle' }} slide-shadow-{{ $slide->text_shadow ?? 'none' }}" data-slide style="--slide-title-font:'{{ $slide->title_font ?? 'Manrope' }}';--title-desktop:{{ $slide->title_size_desktop ?? 72 }}px;--title-tablet:{{ $slide->title_size_tablet ?? 52 }}px;--title-mobile:{{ $slide->title_size_mobile ?? 34 }}px;--subtitle-desktop:{{ $slide->subtitle_size_desktop ?? 20 }}px;--subtitle-tablet:{{ $slide->subtitle_size_tablet ?? 18 }}px;--subtitle-mobile:{{ $slide->subtitle_size_mobile ?? 15 }}px;--title-color:{{ $slide->title_color ?? '#161616' }};--subtitle-color:{{ $slide->subtitle_color ?? '#161616' }};--slide-weight:{{ $slide->font_weight ?? 400 }};--slide-line-height:{{ $slide->line_height ?? 1 }};--slide-letter-spacing:{{ $slide->letter_spacing ?? 0 }}px;--overlay-color:{{ $slide->overlay_color ?? '#FFFFFF' }};--overlay-opacity:{{ ($slide->overlay_opacity ?? 25) / 100 }};--button-font:'{{ $slide->button_font ?? 'Inter' }}';--button-size:{{ $slide->button_size ?? 16 }}px;--button-color:{{ $slide->button_text_color ?? '#FFFFFF' }};--button-bg:{{ $slide->button_background_color ?? '#161616' }}">
                    @if ($slide->image_path)
                        <picture class="cms-slide-picture">
                            @if ($slide->image_path_mobile)
                                <source media="(max-width: 620px)" srcset="{{ $slideDisk->url($slide->image_path_mobile) }}">
                            @endif
                            @if ($slide->image_path_tablet)
                                <source media="(max-width: 900px)" srcset="{{ $slideDisk->url($slide->image_path_tablet) }}">
                            @endif
                            <img src="{{ $slideDisk->url($slide->image_path) }}" alt="{{ $slide->title ?: 'Homepage slide' }}" style="object-fit:{{ ($slide->image_fit ?? 'contain') === 'cover' ? 'cover' : 'contain' }};object-position:{{ $slide->image_focal_x ?? 50 }}% {{ $slide->image_focal_y ?? 50 }}%">
                        </picture>
                    @elseif ($slide->video_path || $slide->video_url)
                        <video autoplay muted loop playsinline>
                            <source src="{{ $slide->video_path ? $slideDisk->url($slide->video_path) : $slide->video_url }}">
                        </video>
                    @endif

                    <div class="cms-slide-overlay"></div>
                    <div class="cms-slide-copy">
                        @if ($slide->title)
                            <h1>{!! nl2br(e($slide->title)) !!}</h1>
                        @endif
                        @if ($slide->subtitle)
                            <p class="slide-subtitle">{!! nl2br(e($slide->subtitle)) !!}</p>
                        @endif
                        @if ($slide->button_text && $slide->button_url)
                            <a class="slide-button" href="{{ $slide->button_url }}">{{ $slide->button_text }}</a>
                        @endif
                    </div>
                </article>
            @endforeach

            @if ($slides->count() > 1)
                <div class="slider-dots">
                    @foreach ($slides as $index => $slide)
                        <button type="button" data-slide-to="{{ $index }}" aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            @endif
        </section>
    @else
        <section class="hero">
            <div class="hero-art"><div class="hero-word">AMER</div><div class="hero-orbit one"></div><div class="hero-orbit two"></div></div>
            <div class="hero-copy"><p class="eyebrow">THE NEW SEASON</p><h1>Quiet confidence.<br>Made to be noticed.</h1><p>Contemporary clothing and considered objects for everyday life.</p><div class="hero-actions"><a class="btn dark" href="{{ route('shop') }}">Explore the collection</a></div></div>
        </section>
    @endif

    <section class="section categories">
        <div class="section-title"><div><p class="eyebrow">SHOP BY WORLD</p><h2>Find your AMER</h2></div><a href="{{ route('shop') }}">View all</a></div>
        <div class="category-grid">
            @foreach ($categories->take(4) as $index => $category)
                <a class="category-card" href="{{ route('collection.show', $category) }}">
                    @if ($category->image_path)
                        <img src="{{ asset('storage/'.$category->image_path) }}" alt="{{ $category->name }}" loading="lazy">
                    @else
                        <div class="category-visual"><span>{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span></div>
                    @endif
                    <div><h3>{{ $category->name }}</h3><span>Discover →</span></div>
                </a>
            @endforeach
        </div>
    </section>

    <section class="section products-section">
        <div class="section-title"><div><p class="eyebrow">JUST LANDED</p><h2>New arrivals</h2></div><a href="{{ route('shop') }}">Shop all</a></div>
        <div class="product-grid">
            @forelse ($newArrivals as $product)
                @include('storefront.partials.product-card', ['product' => $product])
            @empty
                <div class="empty">Add products from AMER Admin.</div>
            @endforelse
        </div>
    </section>

    @if ($featured->isNotEmpty())
        <section class="section products-section">
            <div class="section-title"><div><p class="eyebrow">CURATED FOR YOU</p><h2>The AMER edit</h2></div></div>
            <div class="product-grid">
                @foreach ($featured as $product)
                    @include('storefront.partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </section>
    @endif

    <section class="services"><div><span>01</span><h3>Customer account</h3><p>Sign in to see every purchase.</p></div><div><span>02</span><h3>Order tracking</h3><p>Follow order status and updates.</p></div><div><span>03</span><h3>Secure shopping</h3><p>Protected checkout architecture.</p></div><div><span>04</span><h3>Worldwide ready</h3><p>Bangladesh and global selling.</p></div></section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slides = [...document.querySelectorAll('[data-slide]')];
            document.querySelectorAll('[data-slide-to]').forEach((button) => {
                button.addEventListener('click', () => {
                    slides.forEach((slide) => slide.classList.remove('active'));
                    slides[Number(button.dataset.slideTo)]?.classList.add('active');
                });
            });
        });
    </script>
@endpush

@push('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@400;500;700&family=Manrope:wght@400;500;700&family=Montserrat:wght@400;500;700&family=Playfair+Display:wght@400;500;700&family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/amer-slide-typography.css') }}">
    <link rel="stylesheet" href="{{ asset('css/amer-responsive-slides.css') }}">
    <link rel="stylesheet" href="{{ asset('css/amer-promotions.css') }}">
    <link rel="stylesheet" href="{{ asset('css/amer-promotion-banner.css') }}">
@endpush
