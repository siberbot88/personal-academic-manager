<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_whitelist_user_is_rejected_with_403(): void
    {
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
        $response->assertSee('Email not authorized');
    }

    public function test_whitelisted_user_can_login_successfully(): void
    {
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

        $this->assertDatabaseHas('users', [
            'email' => 'allowed@example.com',
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
