<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_page_loads(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
    }

    public function test_editor_can_access_dashboard(): void
    {
        $editor = User::create([
            'name' => 'Editor',
            'email' => 'editor@test.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Editor,
        ]);

        $response = $this->actingAs($editor)->get('/admin');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_users_resource(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertStatus(200);
    }

    public function test_editor_cannot_access_users_resource(): void
    {
        $editor = User::create([
            'name' => 'Editor',
            'email' => 'editor@test.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Editor,
        ]);

        $response = $this->actingAs($editor)->get('/admin/users');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_articles_resource(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)->get('/admin/articles');

        $response->assertStatus(200);
    }

    public function test_editor_can_access_articles_resource(): void
    {
        $editor = User::create([
            'name' => 'Editor',
            'email' => 'editor@test.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Editor,
        ]);

        $response = $this->actingAs($editor)->get('/admin/articles');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_categories_resource(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)->get('/admin/categories');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_pages_resource(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)->get('/admin/pages');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_media_resource(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)->get('/admin/media');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_article_views_resource(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)->get('/admin/article-views');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_create_article_page(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)->get('/admin/articles/create');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_create_category_page(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)->get('/admin/categories/create');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_create_page_page(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)->get('/admin/pages/create');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_create_user_page(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)->get('/admin/users/create');

        $response->assertStatus(200);
    }

    public function test_editor_can_access_create_article_page(): void
    {
        $editor = User::create([
            'name' => 'Editor',
            'email' => 'editor@test.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Editor,
        ]);

        $response = $this->actingAs($editor)->get('/admin/articles/create');

        $response->assertStatus(200);
    }

    public function test_editor_cannot_access_create_user_page(): void
    {
        $editor = User::create([
            'name' => 'Editor',
            'email' => 'editor@test.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Editor,
        ]);

        $response = $this->actingAs($editor)->get('/admin/users/create');

        $response->assertStatus(403);
    }
}
