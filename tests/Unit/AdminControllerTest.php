<?php

namespace Tests\Unit;

use App\Http\Controllers\AdminController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Services\UserService;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    private $userService;
    private $adminController;

    public function setUp(): void
    {
        parent::setUp();

        $this->userService = Mockery::mock(UserService::class);
        $this->adminController = new AdminController($this->userService);
    }

    public function test_register_method(): void
    {
        $userResourceMock = Mockery::mock(UserResource::class);
        $userResourceMock->shouldReceive('jsonSerialize')->andReturn(['some_data']);

        $requestMock = Mockery::mock(RegisterRequest::class);
        $requestMock->shouldReceive('validFields')->andReturn(['field_data']);
        $requestMock->shouldReceive('only')->andReturn(['email' => 'test@example.com', 'password' => 'password']);

        $this->userService->shouldReceive('registerAdmin')->with(['field_data'])->andReturn($userResourceMock);
        $this->userService->shouldReceive('adminLogin')->with(['email' => 'test@example.com', 'password' => 'password'])->andReturn("sample_token");

        $response = $this->adminController->register($requestMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function test_login_method_successful(): void
    {
        $requestMock = Mockery::mock(LoginRequest::class);
        $requestMock->shouldReceive('only')->andReturn(['email' => 'test@example.com', 'password' => 'password']);

        $this->userService->shouldReceive('adminLogin')->with(['email' => 'test@example.com', 'password' => 'password'])->andReturn("sample_token");

        $response = $this->adminController->login($requestMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function test_login_method_failure(): void
    {
        $requestMock = Mockery::mock(LoginRequest::class);
        $requestMock->shouldReceive('only')->andReturn(['email' => 'test@example.com', 'password' => 'password']);

        $this->userService->shouldReceive('adminLogin')->with(['email' => 'test@example.com', 'password' => 'password'])->andReturn(null);

        $response = $this->adminController->login($requestMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function test_logout_method(): void
    {
        $response = $this->adminController->logout();

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
