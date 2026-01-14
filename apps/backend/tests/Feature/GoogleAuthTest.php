<?php

use App\Models\User;
use Illuminate\Support\Facades\Config;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;

test('non-whitelist user is rejected with 403', function () {
    Config::set('auth.allowed_emails', 'allowed@example.com');

    $socialiteUser = Mockery::mock(SocialiteUser::class);
    $socialiteUser->shouldReceive('getEmail')
        ->andReturn('notallowed@example.com');
    $socialiteUser->shouldReceive('getName')
        ->andReturn('Not Allowed');
    $socialiteUser->shouldReceive('getId')
        ->andReturn('12345');

    Socialite::shouldReceive('driver')
        ->with('google')
        ->andReturnSelf();
    Socialite::shouldReceive('user')
        ->andReturn($socialiteUser);

    $response = $this->get('/auth/google/callback');

    $response->assertStatus(403);
    $response->assertSeeText('Email not authorized');
});

test('whitelisted user can login successfully', function () {
    Config::set('auth.allowed_emails', 'allowed@example.com,test@example.com');

    $socialiteUser = Mockery::mock(SocialiteUser::class);
    $socialiteUser->shouldReceive('getEmail')
        ->andReturn('allowed@example.com');
    $socialiteUser->shouldReceive('getName')
        ->andReturn('Allowed User');
    $socialiteUser->shouldReceive('getId')
        ->andReturn('67890');

    Socialite::shouldReceive('driver')
        ->with('google')
        ->andReturnSelf();
    Socialite::shouldReceive('user')
        ->andReturn($socialiteUser);

    $response = $this->get('/auth/google/callback');

    $response->assertRedirect('/admin');
    $this->assertAuthenticated();

    expect(User::where('email', 'allowed@example.com')->exists())->toBeTrue();
});
