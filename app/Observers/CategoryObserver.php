<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CategoryObserver
{
    /**
     * Clear home cache whenever a category is created, updated, or deleted
     */
    public function created(Category $category): void
    {
        $this->clearHomeCache();
    }

    public function updated(Category $category): void
    {
        $this->clearHomeCache();
    }

    public function deleted(Category $category): void
    {
        $this->clearHomeCache();
    }

    public function restored(Category $category): void
    {
        $this->clearHomeCache();
    }

    public function forceDeleted(Category $category): void
    {
        $this->clearHomeCache();
    }

    /**
     * Clear cached home view for all users
     */
    private function clearHomeCache(): void
    {
        try {
            Cache::forget('home_view_cache_guest');
        } catch (\Throwable $e) {
            Log::warning('Failed to clear home cache on category change: ' . $e->getMessage());
        }
    }
}
