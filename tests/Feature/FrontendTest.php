<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Laravel Simple CMS');
    }

    public function test_homepage_displays_published_articles(): void
    {
        $category = Category::create([
            'title' => 'Technology',
            'slug' => 'technology',
            'is_active' => true,
        ]);

        // Create 3 articles to trigger hero section display
        foreach (range(1, 3) as $i) {
            Article::create([
                'title' => "Test Article $i",
                'slug' => "test-article-$i",
                'content' => 'Test content',
                'category_id' => $category->id,
                'is_published' => true,
                'published_at' => now(),
            ]);
        }

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Test Article 1');
    }

    public function test_articles_index_page_loads(): void
    {
        $response = $this->get('/articles');

        $response->assertStatus(200);
        $response->assertSee('All Articles');
    }

    public function test_article_show_page_loads(): void
    {
        $category = Category::create([
            'title' => 'Technology',
            'slug' => 'technology',
            'is_active' => true,
        ]);

        $article = Article::create([
            'title' => 'My Test Article',
            'slug' => 'my-test-article',
            'content' => '<p>Article content here</p>',
            'category_id' => $category->id,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/article/my-test-article');

        $response->assertStatus(200);
        $response->assertSee('My Test Article');
        $response->assertSee('Article content here');
    }

    public function test_article_view_records_view(): void
    {
        $article = Article::create([
            'title' => 'View Test Article',
            'slug' => 'view-test-article',
            'content' => 'Content',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->assertEquals(0, $article->views()->count());

        $this->get('/article/view-test-article');

        $this->assertEquals(1, $article->views()->count());

        $this->get('/article/view-test-article');

        $this->assertEquals(2, $article->views()->count());
    }

    public function test_unpublished_article_returns_404(): void
    {
        $article = Article::create([
            'title' => 'Draft Article',
            'slug' => 'draft-article',
            'content' => 'Content',
            'is_published' => false,
        ]);

        $response = $this->get('/article/draft-article');

        $response->assertStatus(404);
    }

    public function test_category_page_loads(): void
    {
        $category = Category::create([
            'title' => 'Design',
            'slug' => 'design',
            'is_active' => true,
        ]);

        $article = Article::create([
            'title' => 'Design Article',
            'slug' => 'design-article',
            'content' => 'Content',
            'category_id' => $category->id,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/category/design');

        $response->assertStatus(200);
        $response->assertSee('Design');
        $response->assertSee('Design Article');
    }

    public function test_page_show_loads(): void
    {
        $page = Page::create([
            'title' => 'About Us',
            'slug' => 'about-us',
            'content' => '<p>About us content</p>',
            'is_published' => true,
        ]);

        $response = $this->get('/page/about-us');

        $response->assertStatus(200);
        $response->assertSee('About Us');
        $response->assertSee('About us content');
    }

    public function test_unpublished_page_returns_404(): void
    {
        $page = Page::create([
            'title' => 'Draft Page',
            'slug' => 'draft-page',
            'content' => 'Content',
            'is_published' => false,
        ]);

        $response = $this->get('/page/draft-page');

        $response->assertStatus(404);
    }

    public function test_nonexistent_article_returns_404(): void
    {
        $response = $this->get('/article/does-not-exist');

        $response->assertStatus(404);
    }

    public function test_navigation_shows_published_pages(): void
    {
        $contactPage = Page::create([
            'title' => 'Contact',
            'slug' => 'contact',
            'content' => 'Contact us',
            'is_published' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Contact');
    }
}
