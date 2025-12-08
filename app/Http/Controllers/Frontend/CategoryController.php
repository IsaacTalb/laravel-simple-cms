<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $articles = $category->articles()
            ->published()
            ->latest('published_at')
            ->paginate(12);

        return view('frontend.categories.show', compact('category', 'articles'));
    }
}
