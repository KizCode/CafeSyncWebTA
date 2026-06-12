<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocaleController extends Controller
{
    private const SUPPORTED = ['en', 'id'];

    public function __invoke(Request $request, string $locale): RedirectResponse
    {
        if (! in_array($locale, self::SUPPORTED, true)) {
            abort(404);
        }

        session(['locale' => $locale]);

        $previous = url()->previous();
        $current = $request->url();

        if ($this->isSafeRedirectTarget($previous, $current)) {
            return redirect()->to($previous);
        }

        if (Auth::check()) {
            return redirect()->route(Auth::user()->homeRoute());
        }

        return redirect()->route('login');
    }

    private function isSafeRedirectTarget(?string $url, string $current): bool
    {
        if (! $url || $url === $current) {
            return false;
        }

        $path = parse_url($url, PHP_URL_PATH) ?? '';

        return ! str_starts_with($path, '/locale/');
    }
}
