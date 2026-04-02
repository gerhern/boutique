<?php

namespace Tests\Feature;

use App\enums\Role;
use App\traits\SetTestingData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, SetTestingData;

    // public function test_login_screen_can_be_rendered(): void
    // {
    //     $response = $this->get('/login');

    //     $response->assertStatus(200);
    //     $response->assertSee('bg-bg-base');
    // }

    // public function test_guest_cannot_enter_to_admin_panel(): void
    // {
    //     $this->actingAsGuest()
    //         ->get(route('dashboard'))
    //         ->assertRedirect(route('login'));
    // }

    // public function test_user_cannot_enter_to_admin_panel(): void
    // {
    //     $user = $this->createUser();
    //     $this->actingAs($user)
    //         ->get(route('dashboard'))
    //         ->assertRedirect(route('products.index'));
    // }

    // public function test_admin_can_enter_to_admin_panel(): void
    // {
    //     $admin = $this->createUser(Role::Admin);
    //     $this->actingAs($admin)
    //         ->get(route('dashboard'))
    //         ->assertStatus(200);
    // }



    #[DataProvider('adminRoutesProvider')]
    public function test_guest_cannot_enter_admin_routes($routeName, $verb): void
    {
        $this->actingAsGuest();

        if($verb === 'get'){
            $response = $this->get(route($routeName));
        }else if($verb === 'post'){
            $response = $this->post(route($routeName));
        }

        $response->assertRedirect(route('login'));
    }

    #[DataProvider('adminRoutesProvider')]
    public function test_user_cannot_enter_admin_routes($routeName, $verb):void {
        $user = $this->createUser();
        $this->actingAs($user);

        if($verb === 'get'){
            $response = $this->get(route($routeName));
        }else if($verb === 'post'){
            $response = $this->post(route($routeName));
        }
            $response->assertRedirect(route('products.index'))
            ->assertSessionHas(['error' => '403, Esta sección está reservada para el equipo administrativo de ' . config('app.name')]);
    }

    public static function adminRoutesProvider(): array
    {
        return [
            'dashboard' => ['dashboard', 'get'], // Asegúrate que este nombre de ruta exista
            'products_index' => ['admin.products.index', 'get'],
            'products_create' => ['admin.products.create', 'get'],
            'raffles_index'   => ['admin.raffles.index', 'get'],
            'products_create' => ['admin.products.store', 'post']
        ];
    }
}
