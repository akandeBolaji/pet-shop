<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin can register.
     *
     * @return void
     */
    public function test_admin_registration(): void
    {
        $user = User::factory()->make()->toArray();
        $user['password'] = 'password1';
        $user['password_confirmation'] = 'password1';
        $user['is_marketing'] = 'is_marketing';

        $response = $this->post(route('admin.create'), $user);

        $response->assertStatus(Response::HTTP_OK)
                ->assertJson( fn (AssertableJson $json) =>
                    $json->where('success', 1)
                        ->has('data', fn ($json) =>
                            $json->where('first_name', $user['first_name'])
                                ->where('last_name', $user['last_name'])
                                ->where('email', $user['email'])
                                ->where('phone_number', $user['phone_number'])
                                ->has('token')
                                ->etc()
                        )
                        ->etc()
                );
    }

    public function test_admin_can_login()
    {
        //create admin user with default password - password
        $user = User::factory()->admin()->create();

        $response = $this->post(route('admin.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson( fn (AssertableJson $json) =>
            $json->where('success', 1)
                ->has('data.token')
                ->etc()
            );
    }

    public function test_user_cannot_login_on_admin_route()
    {
        //create user with default password - password
        $user = User::factory()->create();

        $response = $this->post(route('admin.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson( fn (AssertableJson $json) =>
                $json->where('success', 0)
                    ->etc()
            );
    }

    public function test_admin_cannot_login_with_invalid_credentials()
    {
        //create user with default password - password
        $user = User::factory()->admin()->create();

        $response = $this->post(route('admin.login'), [
            'email' => $user->email,
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson( fn (AssertableJson $json) =>
                $json->where('success', 0)
                    ->etc()
            );
    }

    public function test_admin_can_logout()
    {
        $response = $this->get(route('admin.logout'), $this->getAdminAuthHeaders());
        $response->assertStatus(Response::HTTP_OK);
    }
}
