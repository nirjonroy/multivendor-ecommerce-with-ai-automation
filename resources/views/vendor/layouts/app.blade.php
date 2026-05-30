<!doctype html>
<html lang="en">
<head>
    @include('vendor.partials.head', ['title' => $title ?? null])
</head>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <div class="app-wrapper">
        @include('vendor.partials.header')
        @include('vendor.partials.sidebar')
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6"><h3 class="mb-0">@yield('page_title', 'Dashboard')</h3></div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">@yield('page_title', 'Dashboard')</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </main>
        @include('vendor.partials.footer')
    </div>
    @include('vendor.partials.message-notification-modal')
    @include('vendor.partials.scripts')
    @if(($globalVendorUnreadMessages ?? collect())->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalElement = document.getElementById('vendorMessageNotificationModal');
                if (modalElement && window.bootstrap) {
                    new bootstrap.Modal(modalElement).show();
                }
            });
        </script>
    @endif
</body>
</html>
