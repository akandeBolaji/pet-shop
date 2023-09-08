<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can register.
     *
     * @return void
     */
    public function test_user_registration(): void
    {
        $user = User::factory()->make()->toArray();
        $user['password'] = 'password1';
        $user['password_confirmation'] = 'password1';
        $user['is_marketing'] = 'is_marketing';

        $response = $this->post(route('user.create'), $user);

        $response->assertStatus(Response::HTTP_OK);

        //check if access token was created
        $content = json_decode($response->content(), true);
        $data = $content['data'];
        $this->assertArrayHasKey('token', $data);
    }

    public function test_user_can_login()
    {
        //create admin user with default password - password
        $user = User::factory()->create();

        $response = $this->post(route('user.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_OK);

        //check if access token was created
        $content = json_decode($response->content(), true);
        $data = $content['data'];
        $this->assertArrayHasKey('token', $data);
    }

    public function test_admin_cannot_login_on_user_route()
    {
        //create user with default password - password
        $user = User::factory()->admin()->create();

        $response = $this->post(route('user.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        //create user with default password - password
        $user = User::factory()->admin()->create();

        $response = $this->post(route('user.login'), [
            'email' => $user->email,
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_user_can_logout()
    {
        $response = $this->get(route('user.logout'), $this->getUserAuthHeaders());
        $response->assertStatus(200);
    }
}
