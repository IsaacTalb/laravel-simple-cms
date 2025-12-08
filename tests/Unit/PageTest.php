<?php

namespace Tests\Unit;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_auto_generates_slug_from_title(): void
    {
        $page = Page::create([
            'title' => 'About Our Company',
            'content' => 'Content here',
            'is_published' => true,
        ]);

        $this->assertEquals('about-our-company', $page->slug);
    }

    public function test_published_scope_filters_correctly(): void
    {
        Page::create([
            'title' => 'Published Page',
            'slug' => 'published-page',
            'content' => 'Content',
            'is_published' => true,
        ]);

        Page::create([
            'title' => 'Draft Page',
            'slug' => 'draft-page',
            'content' => 'Content',
            'is_published' => false,
        ]);

        $publishedPages = Page::published()->get();

        $this->assertCount(1, $publishedPages);
        $this->assertEquals('Published Page', $publishedPages->first()->title);
    }

    public function test_page_can_have_parent(): void
    {
        $parent = Page::create([
            'title' => 'Parent Page',
            'slug' => 'parent-page',
            'content' => 'Parent content',
            'is_published' => true,
        ]);

        $child = Page::create([
            'title' => 'Child Page',
            'slug' => 'child-page',
            'content' => 'Child content',
            'parent_id' => $parent->id,
            'is_published' => true,
        ]);

        $this->assertEquals($parent->id, $child->parent_id);
        $this->assertInstanceOf(Page::class, $child->parent);
        $this->assertEquals('Parent Page', $child->parent->title);
    }

    public function test_page_can_have_children(): void
    {
        $parent = Page::create([
            'title' => 'Parent Page',
            'slug' => 'parent-page',
            'content' => 'Parent content',
            'is_published' => true,
        ]);

        Page::create([
            'title' => 'Child 1',
            'slug' => 'child-1',
            'content' => 'Content',
            'parent_id' => $parent->id,
            'is_published' => true,
        ]);

        Page::create([
            'title' => 'Child 2',
            'slug' => 'child-2',
            'content' => 'Content',
            'parent_id' => $parent->id,
            'is_published' => true,
        ]);

        $this->assertCount(2, $parent->children);
    }

    public function test_roots_scope_returns_only_top_level_pages(): void
    {
        $root1 = Page::create([
            'title' => 'Root 1',
            'slug' => 'root-1',
            'content' => 'Content',
            'is_published' => true,
        ]);

        $root2 = Page::create([
            'title' => 'Root 2',
            'slug' => 'root-2',
            'content' => 'Content',
            'is_published' => true,
        ]);

        Page::create([
            'title' => 'Child',
            'slug' => 'child',
            'content' => 'Content',
            'parent_id' => $root1->id,
            'is_published' => true,
        ]);

        $roots = Page::roots()->get();

        $this->assertCount(2, $roots);
    }
}
