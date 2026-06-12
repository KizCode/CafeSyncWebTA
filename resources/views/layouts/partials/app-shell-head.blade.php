<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'CafeSync')</title>

<style>
    html { background: #f5f1e8; }
    body { background: #f5f1e8; color: #111827; }
    html.dark { background: #0f172a; }
    html.dark body { background: #0f172a; color: #e2e8f0; }
</style>
<script>
    (function() {
        try {
            if (localStorage.getItem('appTheme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        } catch (e) {}
    })();
</script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@include('layouts.partials.fonts')
@include('layouts.partials.cashier-core-styles')
@stack('styles')
