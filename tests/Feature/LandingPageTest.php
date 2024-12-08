<?php

namespace Tests\Feature;

use Tests\TestCase;

class LandingPageTest extends TestCase
{
    /** @test */
    public function it_loads_the_landing_page()
    {
        // Act: Make a GET request to the landing page
        $response = $this->get('public/'); // Adjust this route based on your actual landing page route

        // Assert: Check that the response is successful
        $response->assertStatus(200); // Check for a successful response (HTTP 200)
    }
}