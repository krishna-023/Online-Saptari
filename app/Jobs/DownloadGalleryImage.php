<?php

namespace App\Jobs;

use App\Models\Gallery;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DownloadGalleryImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $galleryId;
    public $imageUrl;
    public $itemSlug;
    public $imageIndex;
    public $tries = 3;
    public $timeout = 60;

    public function __construct($galleryId, $imageUrl, $itemSlug, $imageIndex = 0)
    {
        $this->galleryId = $galleryId;
        $this->imageUrl = $imageUrl;
        $this->itemSlug = $itemSlug;
        $this->imageIndex = $imageIndex;
    }

    public function handle(): void
    {
        try {
            Log::info('Starting gallery image download', [
                'gallery_id' => $this->galleryId,
                'url' => $this->imageUrl,
                'slug' => $this->itemSlug
            ]);

            if (empty($this->imageUrl)) {
                Log::warning('Empty image URL provided for gallery download');
                return;
            }

            // Download image
            $response = Http::timeout(30)
                ->retry(2, 1000)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ])
                ->get($this->imageUrl);

            if (!$response->successful()) {
                Log::warning('Gallery image download failed: HTTP ' . $response->status());
                return;
            }

            $content = $response->body();
            if (empty($content)) {
                Log::warning('Empty image content for gallery download');
                return;
            }

            // Determine file extension
            $extension = $this->getImageExtension($this->imageUrl, $content);

            // Generate filename
            $filename = $this->generateFilename($this->itemSlug, $this->imageIndex, $extension);
            $path = "gallery/{$filename}";

            // Store file
            $stored = Storage::disk('public')->put($path, $content);

            if ($stored) {
                // Update gallery record
                $gallery = Gallery::find($this->galleryId);
                if ($gallery) {
                    $gallery->update([
                        'image_path' => $path,
                        'file_size' => strlen($content),
                        'file_extension' => $extension,
                        'download_status' => 'completed',
                        'downloaded_at' => now(),
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::error('Gallery image download failed: ' . $e->getMessage());

            // Update gallery record with failure
            $gallery = Gallery::find($this->galleryId);
            if ($gallery) {
                $gallery->update([
                    'download_status' => 'failed',
                    'download_error' => $e->getMessage(),
                ]);
            }
        }
    }

    private function getImageExtension(string $url, string $content): string
    {
        $path = parse_url($url, PHP_URL_PATH);
        if ($path) {
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            if ($ext && in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                return $ext;
            }
        }

        return 'jpg';
    }

    private function generateFilename(string $slug, int $index, string $extension): string
    {
        $cleanSlug = Str::slug($slug);
        $timestamp = now()->format('Ymd_His');
        return "{$cleanSlug}_{$timestamp}_{$index}.{$extension}";
    }
}
