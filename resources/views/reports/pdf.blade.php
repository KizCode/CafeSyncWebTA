<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>{{ __('ui.revenue_report') }} — CafeSync</title>
    @include('reports.partials.pdf-styles')
</head>

<body>
    @include('reports.partials.document')
</body>

</html>
