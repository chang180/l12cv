<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        if (! $this->isGoogleAuthEnabled()) {
            return Redirect::back()->with('google_oauth_error', $this->disabledMessage());
        }

        session(['google_oauth_intent' => Auth::check() ? 'link' : 'login']);

        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        if (! $this->isGoogleAuthEnabled()) {
            return redirect()->route('login')->with('google_oauth_error', $this->disabledMessage());
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable) {
            return redirect()->route('login')->with('google_oauth_error', 'Google 授權未完成，請重新嘗試。');
        }

        $googleId = (string) $googleUser->getId();
        $email = Str::lower((string) $googleUser->getEmail());
        $name = trim((string) $googleUser->getName()) ?: Str::before($email, '@');
        $avatar = $googleUser->getAvatar();
        $raw = $googleUser->user ?? [];
        $emailVerified = (bool) ($raw['email_verified'] ?? true);

        if ($email === '') {
            return redirect()->route('login')->with('google_oauth_error', 'Google 帳號沒有提供電子郵件，無法登入。');
        }

        if (! $emailVerified) {
            return redirect()->route('login')->with('google_oauth_error', 'Google 帳號電子郵件尚未驗證，無法登入。');
        }

        if (Auth::check() || session('google_oauth_intent') === 'link') {
            return $this->linkGoogleAccount($googleId, $email, $avatar);
        }

        if ($user = User::where('google_id', $googleId)->first()) {
            Auth::login($user, remember: true);
            request()->session()->regenerate();

            return redirect()->intended(route('resume.dashboard'))->with('status', '已使用 Google 登入。');
        }

        if (User::where('email', $email)->exists()) {
            return redirect()->route('login')->with(
                'google_oauth_error',
                '這個 Google 電子郵件已存在於原有帳號。請先用電子郵件與密碼登入，再到設定頁綁定 Google 帳號。'
            );
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make(Str::password(48)),
            'password_set_at' => null,
            'google_id' => $googleId,
            'google_avatar' => $avatar,
            'google_linked_at' => now(),
        ]);

        event(new Registered($user));

        Auth::login($user, remember: true);
        request()->session()->regenerate();

        return redirect()->route('resume.dashboard')->with('status', '已使用 Google 建立帳號並登入。');
    }

    public function destroy(): RedirectResponse
    {
        $user = Auth::user();

        if (! $user->google_id) {
            return Redirect::back()->with('google_oauth_status', '目前沒有綁定 Google 帳號。');
        }

        if (! $user->password_set_at) {
            return Redirect::back()->with(
                'google_oauth_error',
                '解除 Google 綁定前，請先設定可登入的密碼，避免無法回到帳號。'
            );
        }

        $user->forceFill([
            'google_id' => null,
            'google_avatar' => null,
            'google_linked_at' => null,
        ])->save();

        return Redirect::back()->with('google_oauth_status', '已解除 Google 帳號綁定。');
    }

    private function linkGoogleAccount(string $googleId, string $email, ?string $avatar): RedirectResponse
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->with('google_oauth_error', '請先登入原有帳號，再綁定 Google 帳號。');
        }

        $existing = User::where('google_id', $googleId)
            ->whereKeyNot($user->id)
            ->first();

        if ($existing) {
            return redirect()->route('settings.profile')->with('google_oauth_error', '這個 Google 帳號已綁定到其他使用者。');
        }

        if (Str::lower($user->email) !== $email) {
            return redirect()->route('settings.profile')->with(
                'google_oauth_error',
                'Google 電子郵件與目前帳號不同，請改用相同電子郵件的 Google 帳號。'
            );
        }

        $user->forceFill([
            'google_id' => $googleId,
            'google_avatar' => $avatar,
            'google_linked_at' => now(),
            'email_verified_at' => $user->email_verified_at ?? now(),
        ])->save();

        return redirect()->route('settings.profile')->with('google_oauth_status', '已綁定 Google 帳號。');
    }

    private function isGoogleAuthEnabled(): bool
    {
        return (bool) config('services.google.enabled')
            && filled(config('services.google.client_id'))
            && filled(config('services.google.client_secret'))
            && filled(config('services.google.redirect'));
    }

    private function disabledMessage(): string
    {
        return '本機開發預設未啟用 Google 登入。請使用電子郵件登入，或設定 GOOGLE_AUTH_ENABLED=true 與 Google OAuth 憑證後再測試。';
    }
}
