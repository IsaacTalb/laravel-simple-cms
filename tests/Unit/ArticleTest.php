<?php

namespace Tests\Unit;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_auto_generates_slug_from_title(): void
    {
        $article = Article::create([
            'title' => 'My First Blog Post',
            'content' => 'Content here',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->assertEquals('my-first-blog-post', $article->slug);
    }

    public function test_article_belongs_to_category(): void
    {
        $category = Category::create([
            'title' => 'Technology',
            'slug' => 'technology',
            'is_active' => true,
        ]);

        $article = Article::create([
            'title' => 'Tech Article',
            'slug' => 'tech-article',
            'content' => 'Content',
            'category_id' => $category->id,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->assertInstanceOf(Category::class, $article->category);
        $this->assertEquals('Technology', $article->category->title);
    }

    public function test_published_scope_filters_correctly(): void
    {
        // Create published article
        Article::create([
            'title' => 'Published Article',
            'slug' => 'published-article',
            'content' => 'Content',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        // Create unpublished article
        Article::create([
            'title' => 'Draft Article',
            'slug' => 'draft-article',
            'content' => 'Content',
            'is_published' => false,
        ]);

        // Create future scheduled article
        Article::create([
            'title' => 'Future Article',
            'slug' => 'future-article',
            'content' => 'Content',
            'is_published' => true,
            'published_at' => now()->addDay(),
        ]);

        $publishedArticles = Article::published()->get();

        $this->assertCount(1, $publishedArticles);
        $this->assertEquals('Published Article', $publishedArticles->first()->title);
    }

    public function test_article_can_have_null_category(): void
    {
        $article = Article::create([
            'title' => 'Uncategorized Article',
            'slug' => 'uncategorized-article',
            'content' => 'Content',
            'category_id' => null,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->assertNull($article->category);
    }

    public function test_read_count_defaults_to_zero(): void
    {
        $article = Article::create([
            'title' => 'New Article',
            'slug' => 'new-article',
            'content' => 'Content',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->assertEquals(0, $article->read_count);
    }
}
