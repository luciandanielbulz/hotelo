@if($allow)
User-agent: *
@foreach($disallowPaths as $path)
Disallow: {{ $path }}
@endforeach
@else
User-agent: *
Disallow: /
@endif

@if($sitemapUrl)
Sitemap: {{ $sitemapUrl }}
@endif

