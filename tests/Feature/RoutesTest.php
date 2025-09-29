<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_sees_welcome_on_home(): void
    {
        $this->get('/')->assertOk()->assertSee('Laravel');
    }

    public function test_authenticated_user_is_redirected_to_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/')
            ->assertRedirect(route('dashboard'));
    }

    public function test_dashboard_requires_authentication(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_movimenti_routes_are_protected(): void
    {
        $this->get('/movimenti/trasferimento')->assertRedirect(route('login'));
    }

    public function test_movimentazioni_redirects_to_transfer(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/movimentazioni')
            ->assertRedirect(route('movimenti.transfer'));
    }
}
