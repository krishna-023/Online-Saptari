<?php

namespace App\Observers;

use App\Models\Banner;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BannerObserver
{
    /**
     * Clear home cache whenever a banner is created, updated, or deleted
     */
    public function created(Banner $banner): void
    {
        $this->clearHomeCache();
    }

    public function updated(Banner $banner): void
    {
        $this->clearHomeCache();
    }

    public function deleted(Banner $banner): void
    {
        $this->clearHomeCache();
    }

    public function restored(Banner $banner): void
    {
        $this->clearHomeCache();
    }

    public function forceDeleted(Banner $banner): void
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
            Log::warning('Failed to clear home cache on banner change: ' . $e->getMessage());
        }
    }
}
