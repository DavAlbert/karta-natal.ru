<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidHCaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('services.hcaptcha.secret_key');

        if (empty($secret)) {
            \Log::error('hCaptcha secret key not configured');
            $fail('hCaptcha ist nicht konfiguriert.');
        }

        $response = \Http::asForm()->post('https://hcaptcha.com/siteverify', [
            'secret' => $secret,
            'response' => $value,
        ]);

        $data = $response->json();

        if (!$data['success']) {
            $errorCodes = $data['error-codes'] ?? [];
            \Log::error('hCaptcha validation failed', ['error-codes' => $errorCodes]);
            $fail('Bitte bestätigen Sie, dass Sie kein Roboter sind.');
        }
    }
}
