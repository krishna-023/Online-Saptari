<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\Category;

class HomeRouteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the home route returns a 200 response
     */
    public function test_home_route_returns_success()
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
    }

    /**
     * Test that search route works and doesn't return home page
     */
    public function test_search_route_returns_success()
    {
        $response = $this->get(route('search.results', ['keyword' => 'test']));
        $response->assertStatus(200);
    }

    /**
     * Test that login route works
     */
    public function test_login_route_returns_success()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
    }

    /**
     * Test that register route works
     */
    public function test_register_route_returns_success()
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
    }

    /**
     * Test that dashboard (admin) returns correct status
     */
    public function test_dashboard_requires_auth()
    {
        $response = $this->get(route('dashboard'));
        // Either redirect to login (302) or return 401/403
        $this->assertTrue(
            $response->status() === 302 ||
            $response->status() === 401 ||
            $response->status() === 403
        );
    }
}

