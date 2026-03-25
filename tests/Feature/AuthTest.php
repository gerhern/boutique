<?php

namespace Tests\Feature;

use App\enums\Role;
use App\traits\SetTestingData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, SetTestingData;
    public function test_guest_cannot_enter_to_admin_panel(): void
    {
        $this->actingAsGuest()
            ->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_user_cannot_enter_to_admin_panel(): void
    {
        $user = $this->createUser();
        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertRedirect(route('products.index'));
    }

    public function test_admin_can_enter_to_admin_panel(): void
    {
        $admin = $this->createUser(Role::Admin);
        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertStatus(200);
    }
}
