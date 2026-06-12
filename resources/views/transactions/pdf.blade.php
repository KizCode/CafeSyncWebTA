<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('ui.print_receipt_title') }} — {{ $transaction->invoice_number }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('layouts.partials.fonts')
    <link rel="stylesheet" href="{{ asset('css/transaction-receipt.css') }}">
    <style>
        body {
            margin: 0;
            padding: 1rem;
            background: #fff;
            display: flex;
            justify-content: center;
        }

        @media print {
            body {
                padding: 0;
                display: block;
            }
        }
    </style>
</head>

<body>
    <x-transaction-receipt :transaction="$transaction" />

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 350);
        });
    </script>
</body>

</html>
