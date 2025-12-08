<?php

namespace Tests\Unit;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_auto_generates_slug_from_title(): void
    {
        $category = Category::create([
            'title' => 'Web Development',
            'is_active' => true,
        ]);

        $this->assertEquals('web-development', $category->slug);
    }

    public function test_category_has_many_articles(): void
    {
        $category = Category::create([
            'title' => 'Technology',
            'slug' => 'technology',
            'is_active' => true,
        ]);

        Article::create([
            'title' => 'Article 1',
            'slug' => 'article-1',
            'content' => 'Content',
            'category_id' => $category->id,
            'is_published' => true,
            'published_at' => now(),
        ]);

        Article::create([
            'title' => 'Article 2',
            'slug' => 'article-2',
            'content' => 'Content',
            'category_id' => $category->id,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->assertCount(2, $category->articles);
    }

    public function test_active_scope_filters_correctly(): void
    {
        Category::create([
            'title' => 'Active Category',
            'slug' => 'active-category',
            'is_active' => true,
        ]);

        Category::create([
            'title' => 'Inactive Category',
            'slug' => 'inactive-category',
            'is_active' => false,
        ]);

        $activeCategories = Category::where('is_active', true)->get();

        $this->assertCount(1, $activeCategories);
        $this->assertEquals('Active Category', $activeCategories->first()->title);
    }
}
