<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\MagicLoginLink;

class MagicLinkController extends Controller
{
    /**
     * Show the magic login form (email only).
     */
    public function showLoginForm(Request $request)
    {
        return redirect('/?login=true' . ($request->email ? '&email=' . urlencode($request->email) : ''));
    }

    /**
     * Send a magic login link to the user's email.
     */
    public function sendLoginLink(Request $request)
    {
        // Verify reCAPTCHA v2
        $recaptchaToken = $request->input('recaptcha_token');
        if (empty($recaptchaToken)) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => ['email' => ['Пожалуйста, подтвердите, что вы не робот.']]], 422);
            }
            return back()->withErrors(['email' => 'Пожалуйста, подтвердите, что вы не робот.']);
        }

        $secretKey = config('services.recaptcha.secret_key');
        $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';

        $response = \Http::withoutVerifying()->post($verifyUrl, [
            'secret' => $secretKey,
            'response' => $recaptchaToken,
        ]);

        $responseData = $response->json();

        if (!$responseData['success']) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => ['email' => ['Ошибка проверки reCAPTCHA. Попробуйте еще раз.']]], 422);
            }
            return back()->withErrors(['email' => 'Ошибка проверки reCAPTCHA. Попробуйте еще раз.']);
        }

        $validator = \Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator->errors());
        }

        // Rate limiting: max 3 requests per minute per email
        $key = 'magic-login:' . $request->email;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            if ($request->expectsJson()) {
                return response()->json(['errors' => ['email' => ['Слишком много попыток. Попробуйте через ' . $seconds . ' секунд.']]], 429);
            }
            return back()->withErrors(['email' => 'Слишком много попыток. Попробуйте через ' . $seconds . ' секунд.']);
        }

        $user = User::where('email', $request->email)->first();

        // Generate a unique token
        $token = Str::random(64);
        $user->magic_login_token = hash('sha256', $token);
        $user->magic_login_expires_at = now()->addMinutes(15);
        $user->save();

        // Send the magic link email
        Mail::to($user->email)->send(new MagicLoginLink($user, $token));

        RateLimiter::hit($key, 60);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('status', 'Ссылка для входа отправлена на вашу почту!');
    }

    /**
     * Handle the magic login link click.
     */
    public function loginViaToken(Request $request, string $token)
    {
        $hashedToken = hash('sha256', $token);

        $user = User::where('magic_login_token', $hashedToken)
            ->where('magic_login_expires_at', '>', now())
            ->first();

        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'Ссылка для входа истекла или недействительна.',
            ]);
        }

        // Clear the magic login token
        $user->magic_login_token = null;
        $user->magic_login_expires_at = null;
        $user->email_verified_at = now();
        $user->save();

        // Log the user in
        Auth::login($user);

        // Redirect to their chart (first one, or create new)
        $chart = $user->natalCharts()->first();

        if ($chart) {
            return redirect()->route('charts.show', $chart);
        }

        return redirect()->route('calculate');
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
