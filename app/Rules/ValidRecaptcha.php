<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidRecaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!app()->environment('production')) {
            return;
        }

        $secret = config('services.recaptcha.secret_key');

        if (empty($secret)) {
            \Log::error('reCAPTCHA secret key not configured');
            $fail('Captcha configuration error.');
            return;
        }

        $response = \Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secret,
            'response' => $value,
            'remoteip' => request()->ip(),
        ]);

        $data = $response->json();

        if (!($data['success'] ?? false) || ($data['score'] ?? 0) < 0.3) {
            \Log::warning('reCAPTCHA validation failed', ['data' => $data]);
            $fail('Captcha verification failed. Please try again.');
        }
    }
}
