<?php

use App\Models\User;
use Livewire\Volt\Volt as LivewireVolt;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('login screen can be rendered', function () {
    $response = $this->get('/login');
    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    // 先訪問一個受保護的路由來設置 intended URL
    $this->get(route('resume.dashboard'));

    $response = LivewireVolt::test('auth.login')
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('login');

    $response->assertHasNoErrors();
    $this->assertAuthenticated();
    $this->assertEquals(route('resume.dashboard', absolute: false), session('url.intended'));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get('/logout'); // 改用 GET 方法，或者確保你的應用支援 POST 方法的登出

    $this->assertGuest();
    $response->assertRedirect('/');
});
