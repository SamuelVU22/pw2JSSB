<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\News;

class NewsControllerTest extends TestCase
{
    use RefreshDatabase; // Use this trait to reset the database after each test

    /** @test */
    public function it_displays_news_articles()
    {
        // Arrange: Create some news articles in the database
        $newsArticle = News::create([
            'title' => 'Test News Title',
            'description' => 'This is a description for the test news article.',
            'date' => now()->toDateString(),
            'numLikes' => 0,
            'isLike' => false,
            'urlPhoto' => 'http://example.com/image.jpg',
            'urlNews' => 'Hello world',
        ]);

        // Act: Make a GET request to the show method of NewsController
        $response = $this->get(route('news.show')); // Adjust this route based on your actual route name

        // Assert: Check that the response is successful and contains the news article
        $response->assertStatus(200); // Check for a successful response
        $response->assertSee($newsArticle->title); // Check if the title is visible on the page
        $response->assertSee($newsArticle->description); // Check if the description is visible on the page
    }
}
