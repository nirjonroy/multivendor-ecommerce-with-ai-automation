<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<meta name="color-scheme" content="light dark">
<title>{{ $title ?? 'Vendor Dashboard' }}</title>
<link rel="icon" href="{{ $globalSiteInfo?->favicon_path ? asset('storage/' . $globalSiteInfo->favicon_path) : asset('assets/images/favicon/favicon.ico') }}" type="image/x-icon">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous">
<link rel="stylesheet" href="/vendor/css/adminlte.css">
<style>
    .vendor-brand-logo{width:34px;height:34px;object-fit:contain;background:#fff}
    .vendor-logo-full{max-width:150px;max-height:44px;object-fit:contain}
    .app-sidebar .brand-link{text-decoration:none}
</style>
@stack('styles')
