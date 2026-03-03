<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

use App\Models\User;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Autenticación:
     * 1) Busca en BD central (li_users). Si existe y password match -> login.
     * 2) Si no existe/ no match -> intenta login local normal (Auth::attempt).
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $email = Str::lower(trim((string) $this->input('email')));
        $pass  = (string) $this->input('password');
        $remember = $this->boolean('remember');

        // ===========================
        // 1) INTENTO: BD CENTRAL
        // ===========================
        try {
            // OJO: aquí debe coincidir con tu database.php: connections['li_users']
            $central = DB::connection('li_users')
                ->table('users')
                ->where('email', $email)
                ->first();
        } catch (\Throwable $e) {
            // si la conexión falla, no tumbes el login; solo cae a local
            $central = null;
        }

        if ($central) {
            // Ajusta si en la tabla central el campo de password se llama distinto
            $centralHash = $central->password ?? null;

            if (is_string($centralHash) && $centralHash !== '' && Hash::check($pass, $centralHash)) {

                // Sincroniza/crea usuario local para sesión y para spatie roles locales
                $local = User::where('email', $email)->first();

                if (!$local) {
                    $local = new User();
                    $local->email = $email;
                }

                // campos típicos (ajusta si tu central usa otros nombres)
                $local->name = $central->name ?? ($central->nombre ?? $local->name ?? $email);
                $local->password = $centralHash; // guardamos hash tal cual
                $local->save();

                Auth::login($local, $remember);

                // 👇 Forzar admin para auxsoporte
                if ($local->email === 'auxsoporte@lineaitalia.com.mx') {
                    if (! $local->hasRole('admin')) {
                        $local->assignRole('admin');
                    }
                }

                RateLimiter::clear($this->throttleKey());
                return;
            }
        }

        // ===========================
        // 2) FALLBACK: LOGIN LOCAL
        // ===========================
        if (! Auth::attempt(['email' => $email, 'password' => $pass], $remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

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

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}