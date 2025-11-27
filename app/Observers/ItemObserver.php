<?php

namespace App\Observers;

use App\Models\Item;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ItemObserver
{
    /**
     * Clear home cache whenever an item is created, updated, or deleted
     */
    public function created(Item $item): void
    {
        $this->clearHomeCache();
    }

    public function updated(Item $item): void
    {
        $this->clearHomeCache();
    }

    public function deleted(Item $item): void
    {
        $this->clearHomeCache();
    }

    public function restored(Item $item): void
    {
        $this->clearHomeCache();
    }

    public function forceDeleted(Item $item): void
    {
        $this->clearHomeCache();
    }

    /**
     * Clear cached home view for all users
     */
    private function clearHomeCache(): void
    {
        try {
            // Clear cache for guest users
            Cache::forget('home_view_cache_guest');

            // Clear cache for all logged-in users (if tracking per-user cache)
            // In a more complex system, you might iterate over active users:
            // User::pluck('id')->each(fn($id) => Cache::forget("home_view_cache_$id"));
        } catch (\Throwable $e) {
            // Silently fail cache invalidation to avoid disrupting the app
            Log::warning('Failed to clear home cache on item change: ' . $e->getMessage());
        }
    }
}
