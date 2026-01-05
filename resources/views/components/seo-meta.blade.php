@php
    $seo = \App\Helpers\SeoHelper::metaTags($seoData ?? []);
    $canonicalUrl = $canonicalUrl ?? url()->current();
@endphp

{{-- Basis Meta-Tags --}}
<meta name="description" content="{{ $seo['description'] }}">
@if(!empty($seo['keywords']))
<meta name="keywords" content="{{ $seo['keywords'] }}">
@endif
<meta name="author" content="{{ config('seo.default_author', config('app.name')) }}">
<meta name="robots" content="{{ config('seo.robots_allow', true) ? 'index, follow' : 'noindex, nofollow' }}">

{{-- Canonical URL --}}
@if(config('seo.canonical_enabled', true))
<link rel="canonical" href="{{ $canonicalUrl }}">
@endif

{{-- Open Graph Tags --}}
@if(config('seo.og_enabled', true))
<meta property="og:type" content="{{ $seo['type'] }}">
<meta property="og:title" content="{{ $seo['title'] }}">
<meta property="og:description" content="{{ $seo['description'] }}">
<meta property="og:image" content="{{ $seo['image'] }}">
<meta property="og:url" content="{{ $seo['url'] }}">
<meta property="og:site_name" content="{{ $seo['site_name'] }}">
<meta property="og:locale" content="{{ str_replace('_', '-', $seo['locale']) }}">
@if(config('seo.facebook_app_id'))
<meta property="fb:app_id" content="{{ config('seo.facebook_app_id') }}">
@endif
@endif

{{-- Twitter Card Tags --}}
@if(config('seo.twitter_card_enabled', true))
<meta name="twitter:card" content="{{ config('seo.twitter_card_type', 'summary_large_image') }}">
<meta name="twitter:title" content="{{ $seo['title'] }}">
<meta name="twitter:description" content="{{ $seo['description'] }}">
<meta name="twitter:image" content="{{ $seo['image'] }}">
@if(config('seo.twitter_handle'))
<meta name="twitter:site" content="{{ config('seo.twitter_handle') }}">
<meta name="twitter:creator" content="{{ config('seo.twitter_handle') }}">
@endif
@endif

{{-- Strukturierte Daten (JSON-LD) --}}
@if(isset($structuredData) && is_array($structuredData))
@foreach($structuredData as $data)
<script type="application/ld+json">
{!! json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endforeach
@endif



