<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidYandexCaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Skip captcha validation in non-production environments
        if (!app()->environment('production')) {
            return;
        }

        $secret = config('services.yandex_captcha.secret_key');

        if (empty($secret)) {
            \Log::error('Yandex SmartCaptcha secret key not configured');
            $fail('Ошибка конфигурации капчи.');
            return;
        }

        $response = \Http::asForm()->post('https://smartcaptcha.yandexcloud.net/validate', [
            'secret' => $secret,
            'token' => $value,
        ]);

        $data = $response->json();

        if (($data['status'] ?? '') !== 'ok') {
            $message = $data['message'] ?? 'unknown error';
            \Log::error('Yandex SmartCaptcha validation failed', ['message' => $message]);
            $fail('Пожалуйста, подтвердите, что вы не робот.');
        }
    }
}
