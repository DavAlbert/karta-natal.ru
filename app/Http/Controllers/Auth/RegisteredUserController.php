<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\NatalChart;
use App\Services\AstrologyCalculationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Check if there is chart data in the session
        if (session()->has('natal_chart_data')) {
            $data = session('natal_chart_data');

            // Basic stub for calculation - in real app use the Service
            $service = new AstrologyCalculationService();
            $calculatedData = $service->calculate(
                $data['birth_date'],
                $data['birth_time'] ?? '00:00', // Default time if missing
                55.75, // TODO: Geocoding from birth_place string
                37.61
            );

            $chart = NatalChart::create([
                'user_id' => $user->id,
                'name' => $data['name'] . "'s Chart",
                'birth_date' => $data['birth_date'],
                'birth_time' => $data['birth_time'],
                'birth_place' => $data['birth_place'],
                'latitude' => 55.75, // Placeholder
                'longitude' => 37.61, // Placeholder
                'timezone' => 'UTC',
                'chart_data' => $calculatedData,
                'type' => 'natal',
            ]);

            session()->forget('natal_chart_data');

            return redirect(route('charts.show', $chart));
        }

        return redirect(route('dashboard', absolute: false));
    }
}
