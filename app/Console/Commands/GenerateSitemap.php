<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Article;
use App\Models\Category;
use App\Models\Page;
use Carbon\Carbon;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml for QuickTech.info';

    public function handle()
    {
        $sitemap = Sitemap::create();

        /*
         |--------------------------------------------------
         | Homepage
         |--------------------------------------------------
         */
        $sitemap->add(
            Url::create('/')
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );

        /*
         |--------------------------------------------------
         | Categories
         |--------------------------------------------------
         */
        Category::where('is_active', true)->get()->each(function (Category $category) use ($sitemap) {
            $sitemap->add(
                Url::create("/category/{$category->slug}")
                    ->setLastModificationDate($category->updated_at ?? Carbon::now())
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        });

        /*
         |--------------------------------------------------
         | Articles (Published Only)
         |--------------------------------------------------
         */
        Article::published()->get()->each(function (Article $article) use ($sitemap) {
            $sitemap->add(
                Url::create("/{$article->slug}")
                    ->setLastModificationDate(
                        $article->updated_at ?? $article->published_at
                    )
                    ->setPriority(0.7)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        });

        /*
         |--------------------------------------------------
         | Pages (Published)
         |--------------------------------------------------
         */
        Page::published()->get()->each(function (Page $page) use ($sitemap) {

            $url = $page->parent
                ? "/{$page->parent->slug}/{$page->slug}"
                : "/{$page->slug}";

            $sitemap->add(
                Url::create($url)
                    ->setLastModificationDate($page->updated_at ?? Carbon::now())
                    ->setPriority(0.6)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        });

        /*
         |--------------------------------------------------
         | Write File
         |--------------------------------------------------
         */
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('✅ sitemap.xml generated successfully for QuickTech.info');
    }
}
