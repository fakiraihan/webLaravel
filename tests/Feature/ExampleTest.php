<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // Test the login page instead of protected route
        $response = $this->get('/login');

        $response->assertStatus(200);
    }
    
    /**
     * Test that the home page redirects unauthenticated users.
     */
    public function test_home_redirects_unauthenticated_users(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}
