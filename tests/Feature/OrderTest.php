<?php

namespace Tests\Feature;

use App\Models\OrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_view_order_listing(): void
    {
        $response = $this->get(route('orders'), $this->getUserAuthHeaders());
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_admin_can_view_order_listing(): void
    {
        $response = $this->get(route('orders'), $this->getAdminAuthHeaders());
        $response->assertStatus(Response::HTTP_OK)
            //confirm if record is paginated
            ->assertJsonPath('data.current_page', 1);
    }

    public function test_user_cannot_view_orders_dashboard(): void
    {
        $response = $this->get(route('orders.dashboard'), $this->getUserAuthHeaders());
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_admin_can_view_orders_dashboard(): void
    {
        $response = $this->get(route('orders.dashboard'), $this->getAdminAuthHeaders());
        $response->assertStatus(Response::HTTP_OK);
    }
}
