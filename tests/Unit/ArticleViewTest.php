<?php

namespace Tests\Unit;

use App\Models\Article;
use App\Models\ArticleView;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_view_belongs_to_article(): void
    {
        $article = Article::create([
            'title' => 'Test Article',
            'slug' => 'test-article',
            'content' => 'Content',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $view = ArticleView::create([
            'article_id' => $article->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Test Agent',
            'viewed_at' => now(),
        ]);

        $this->assertInstanceOf(Article::class, $view->article);
        $this->assertEquals($article->id, $view->article->id);
    }

    public function test_article_has_many_views(): void
    {
        $article = Article::create([
            'title' => 'Test Article',
            'slug' => 'test-article',
            'content' => 'Content',
            'is_published' => true,
            'published_at' => now(),
        ]);

        ArticleView::create([
            'article_id' => $article->id,
            'ip_address' => '127.0.0.1',
            'viewed_at' => now(),
        ]);

        ArticleView::create([
            'article_id' => $article->id,
            'ip_address' => '192.168.1.1',
            'viewed_at' => now(),
        ]);

        $this->assertCount(2, $article->views);
    }

    public function test_article_record_view_creates_view(): void
    {
        $article = Article::create([
            'title' => 'Test Article',
            'slug' => 'test-article',
            'content' => 'Content',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $view = $article->recordView('127.0.0.1', 'Test Agent', 'https://google.com');

        $this->assertInstanceOf(ArticleView::class, $view);
        $this->assertEquals($article->id, $view->article_id);
        $this->assertEquals('127.0.0.1', $view->ip_address);
        $this->assertEquals('Test Agent', $view->user_agent);
        $this->assertEquals('https://google.com', $view->referer);
        $this->assertNotNull($view->viewed_at);
    }

    public function test_article_view_can_have_null_optional_fields(): void
    {
        $article = Article::create([
            'title' => 'Test Article',
            'slug' => 'test-article',
            'content' => 'Content',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $view = $article->recordView(null, null, null);

        $this->assertInstanceOf(ArticleView::class, $view);
        $this->assertNull($view->ip_address);
        $this->assertNull($view->user_agent);
        $this->assertNull($view->referer);
    }

    public function test_viewed_at_is_cast_to_datetime(): void
    {
        $article = Article::create([
            'title' => 'Test Article',
            'slug' => 'test-article',
            'content' => 'Content',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $view = ArticleView::create([
            'article_id' => $article->id,
            'viewed_at' => '2025-01-15 10:30:00',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $view->viewed_at);
    }

    public function test_views_are_deleted_when_article_is_deleted(): void
    {
        $article = Article::create([
            'title' => 'Test Article',
            'slug' => 'test-article',
            'content' => 'Content',
            'is_published' => true,
            'published_at' => now(),
        ]);

        ArticleView::create([
            'article_id' => $article->id,
            'viewed_at' => now(),
        ]);

        ArticleView::create([
            'article_id' => $article->id,
            'viewed_at' => now(),
        ]);

        $this->assertEquals(2, ArticleView::count());

        $article->delete();

        $this->assertEquals(0, ArticleView::count());
    }
}
