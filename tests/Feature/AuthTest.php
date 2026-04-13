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

    public function test_if_user_is_admin(): void {
        $user = $this->createUser();
        $admin = $this->createUser(Role::Admin);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }
    // public function test_enum_retrieves_roles(): void {
    //     $roles = Role::getRoles();
    //     dd($roles);
    //     $this->assertArrayHasKey(Role::Admin, $roles);
    //     $this->assertArrayHasKey(Role::User, $roles);
    // }

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
        $this->withExceptionHandling();
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
                ->assertSessionHasErrors([
                    'error' => 'This section is reserved for the administrative team of ' . config('app.name')
                ]);
    }

    public static function adminRoutesProvider(): array
    {
        return [
            'dashboard' => ['dashboard', 'get'],
            'products_index' => ['admin.products.index', 'get'],
            'products_create' => ['admin.products.create', 'get'],
            'raffles_index'   => ['admin.raffles.index', 'get'],
            'products_create' => ['admin.products.store', 'post']
        ];
    }
}
