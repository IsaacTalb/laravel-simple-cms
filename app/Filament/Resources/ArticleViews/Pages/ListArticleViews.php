<?php

namespace App\Filament\Resources\ArticleViews\Pages;

use App\Filament\Resources\ArticleViews\ArticleViewResource;
use Filament\Resources\Pages\ListRecords;

class ListArticleViews extends ListRecords
{
    protected static string $resource = ArticleViewResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
