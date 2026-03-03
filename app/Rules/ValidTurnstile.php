<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ValidTurnstile implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!app()->environment('production')) {
            return;
        }

        $secret = config('services.turnstile.secret_key');

        if (empty($secret)) {
            Log::error('Turnstile secret key not configured');
            $fail(__('common.captcha_error'));
            return;
        }

        $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => $secret,
            'response' => $value,
            'remoteip' => request()->ip(),
        ]);

        $data = $response->json();

        if (!($data['success'] ?? false)) {
            Log::warning('Turnstile validation failed', ['data' => $data]);
            $fail(__('common.captcha_failed'));
        }
    }
}
