<?php

use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

function enableGoogleAuth(): void
{
    Config::set('services.google.enabled', true);
    Config::set('services.google.client_id', 'client-id');
    Config::set('services.google.client_secret', 'client-secret');
    Config::set('services.google.redirect', 'http://localhost/auth/google/callback');
}

function mockGoogleUser(array $overrides = []): void
{
    $user = new class($overrides) {
        public array $user;

        public function __construct(private array $overrides)
        {
            $this->user = ['email_verified' => $overrides['email_verified'] ?? true];
        }

        public function getId(): string
        {
            return $this->overrides['id'] ?? 'google-123';
        }

        public function getEmail(): ?string
        {
            return $this->overrides['email'] ?? 'google@example.com';
        }

        public function getName(): ?string
        {
            return $this->overrides['name'] ?? 'Google User';
        }

        public function getAvatar(): ?string
        {
            return $this->overrides['avatar'] ?? 'https://example.com/avatar.jpg';
        }
    };

    $provider = Mockery::mock();
    $provider->shouldReceive('user')->andReturn($user);
    Socialite::shouldReceive('driver')->with('google')->andReturn($provider);
}

test('google redirect is disabled by default for local development', function () {
    $response = $this->from('/login')->get(route('auth.google.redirect'));

    $response->assertRedirect('/login');
    $response->assertSessionHas('google_oauth_error');
});

test('google callback creates a new user when enabled', function () {
    enableGoogleAuth();
    mockGoogleUser();

    $response = $this->get(route('auth.google.callback'));

    $user = User::where('email', 'google@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->google_id)->toBe('google-123')
        ->and($user->password_set_at)->toBeNull()
        ->and(Hash::check('password', $user->password))->toBeFalse();

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('resume.dashboard'));
});

test('google callback does not auto link an existing email while logged out', function () {
    enableGoogleAuth();
    User::factory()->create(['email' => 'google@example.com']);
    mockGoogleUser();

    $response = $this->get(route('auth.google.callback'));

    expect(User::where('email', 'google@example.com')->first()->google_id)->toBeNull();
    $this->assertGuest();
    $response->assertRedirect(route('login'));
    $response->assertSessionHas('google_oauth_error');
});

test('authenticated users can link matching google account', function () {
    enableGoogleAuth();
    $user = User::factory()->create(['email' => 'google@example.com']);
    mockGoogleUser();

    $response = $this->actingAs($user)->get(route('auth.google.callback'));

    expect($user->fresh()->google_id)->toBe('google-123');
    $response->assertRedirect(route('settings.profile'));
    $response->assertSessionHas('google_oauth_status');
});

test('users without a set password cannot unlink google account', function () {
    $user = User::factory()->create([
        'google_id' => 'google-123',
        'google_linked_at' => now(),
        'password_set_at' => null,
    ]);

    $response = $this->actingAs($user)->delete(route('settings.google.destroy'));

    expect($user->fresh()->google_id)->toBe('google-123');
    $response->assertSessionHas('google_oauth_error');
});

test('users with a set password can unlink google account', function () {
    $user = User::factory()->create([
        'google_id' => 'google-123',
        'google_avatar' => 'https://example.com/avatar.jpg',
        'google_linked_at' => now(),
        'password_set_at' => now(),
    ]);

    $response = $this->actingAs($user)->delete(route('settings.google.destroy'));

    expect($user->fresh()->google_id)->toBeNull()
        ->and($user->fresh()->google_avatar)->toBeNull()
        ->and($user->fresh()->google_linked_at)->toBeNull();
    $response->assertSessionHas('google_oauth_status');
});
