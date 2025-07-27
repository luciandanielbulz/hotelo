<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Logging für Debug-Zwecke
        $email = $this->input('email');
        \Log::info('Login attempt', ['email' => $email, 'ip' => $this->ip()]);

        // Prüfe, ob Benutzer existiert
        $user = \App\Models\User::where('email', $email)->first();
        if (!$user) {
            \Log::warning('Login failed: User not found', ['email' => $email]);
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Prüfe, ob Benutzer aktiv ist (vor Passwort-Prüfung)
        if (!$user->isactive) {
            \Log::warning('Login failed: User inactive', [
                'email' => $email,
                'user_id' => $user->id,
                'isactive' => $user->isactive
            ]);
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Ihr Konto ist deaktiviert. Bitte wenden Sie sich an den Administrator.',
            ]);
        }

        // Erst normale Authentifizierung mit E-Mail und Passwort
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            \Log::warning('Login failed: Invalid credentials', [
                'email' => $email,
                'user_id' => $user->id,
                'password_provided' => !empty($this->input('password'))
            ]);
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Erfolgreiches Login
        \Log::info('Login successful', [
            'email' => $email,
            'user_id' => Auth::id(),
            'role' => Auth::user()->role->name ?? 'Unknown'
        ]);

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
