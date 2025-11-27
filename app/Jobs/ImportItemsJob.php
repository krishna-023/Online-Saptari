<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Category;
use App\Models\Gallery;

class ImportItemsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $dryRun;

    public function __construct(string $filePath, bool $dryRun = false)
    {
        $this->filePath = $filePath;
        $this->dryRun = $dryRun;
    }

    public function handle()
    {
        $csvData = Storage::get($this->filePath);
        $rows = str_getcsv($csvData, "\n");
        $header = str_getcsv(array_shift($rows));

        $successCount = 0;
        $errorCount = 0;

        foreach ($rows as $row) {
            $data = str_getcsv($row);
            if (count($data) !== count($header)) {
                Log::warning("Skipping row due to column mismatch: " . implode(',', $data));
                $errorCount++;
                continue;
            }

            $rowData = array_combine($header, $data);
            try {
                if ($this->dryRun) {
                    Log::info("Dry run for row ID {$rowData['id']}: " . json_encode($this->prepareRowData($rowData)));
                    $successCount++;
                } else {
                    $this->importRow($rowData);
                    $successCount++;
                }
            } catch (\Exception $e) {
                $errorCount++;
                Log::error("Import error for row ID {$rowData['id']}: " . $e->getMessage(), ['row' => $rowData]);
            }
        }

        // Cleanup
        Storage::delete($this->filePath);

        Log::info("Import job completed. Success: $successCount, Errors: $errorCount");
        // Optional: Send notification here, e.g., Mail::to('admin@example.com')->send(new ImportCompleted($successCount, $errorCount));
    }

    private function importRow(array $row)
    {
        DB::beginTransaction();

        try {
            $data = $this->prepareRowData($row);

            if (empty($data['title'])) {
                throw new \Exception("Title is required but missing");
            }

            $slug = Str::slug($data['title']);
            $baseSlug = $slug;
            $count = 1;
            while (Item::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }
            $permalink = url('/items/' . $slug);

            $imagePath = null;
            if (!empty($data['galleryUrls'])) {
                $firstUrl = trim($data['galleryUrls'][0]);
                $imagePath = $this->downloadAndStoreImage($firstUrl, 'items/images');
            }

            $item = Item::create([
                'title' => $data['title'],
                'subtitle' => $data['subtitle'],
                'content' => $data['content'],
                'item_featured' => $data['item_featured'],
                'collection_date' => $data['collection_date'],
                'slug' => $slug,
                'permalink' => $permalink,
                'image' => $imagePath,
                'category_id' => $data['categoryId'],
                'author_username' => $data['author_username'],
                'author_email' => $data['author_email'],
                'author_first_name' => $data['author_first_name'],
                'author_last_name' => $data['author_last_name'],
            ]);

            $item->contacts()->create([
                'telephone' => $data['telephone'],
                'phone1' => $data['phone1'],
                'phone2' => $data['phone2'],
                'email' => $data['email'],
                'web' => $data['web'],
                'webLinkLabel' => $data['webLinkLabel'],
                'address' => $data['address'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'streetview' => $data['streetview'],
            ]);

            $item->opening_Time()->create([
                'display_opening_hours' => $data['displayOpeningHours'],
                'openingHoursMonday' => $data['openingHoursMonday'],
                'openingHoursTuesday' => $data['openingHoursTuesday'],
                'openingHoursWednesday' => $data['openingHoursWednesday'],
                'openingHoursThursday' => $data['openingHoursThursday'],
                'openingHoursFriday' => $data['openingHoursFriday'],
                'openingHoursSaturday' => $data['openingHoursSaturday'],
                'openingHoursSunday' => $data['openingHoursSunday'],
                'openingHoursNote' => $data['openingHoursNote'],
            ]);

            if (!empty($data['socialIcons']) && !empty($data['socialIcons_url'])) {
                $item->socialIcons()->create([
                    'displaySocialIcons' => $data['displaySocialIcons'],
                    'openInNewWindow' => $data['socialIconsOpenInNewWindow'],
                    'socialIcons' => $data['socialIcons'],
                    'socialIcons_url' => $data['socialIcons_url'],
                ]);
            }

            if (!empty($data['galleryUrls'])) {
                foreach ($data['galleryUrls'] as $url) {
                    $url = trim($url);
                    if (!empty($url)) {
                        $fileName = $this->downloadAndStoreImage($url, 'gallery', $slug);
                        if ($fileName) {
                            Gallery::create([
                                'item_id' => $item->id,
                                'gallery' => $fileName,
                                'display_gallery' => $data['displayGallery'],
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            Log::info("Successfully imported item: {$item->id}");
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function prepareRowData(array $row): array
    {
        $itemData = [];
        if (!empty($row['_ait_item_item_data'])) {
            $itemData = @unserialize($row['_ait_item_item_data']);
            if ($itemData === false) {
                Log::warning("Failed to unserialize for row ID {$row['id']}");
                $itemData = [];
            }
        }

        $data = [
            'title' => $row['Title'] ?? null,
            'subtitle' => $row['subtitle'] ?? ($itemData['subtitle'] ?? null),
            'content' => $row['Content'] ?? null,
            'item_featured' => $row['_ait_item_item-featured'] ?? null,
            'collection_date' => null,
            'author_username' => $row['Author Username'] ?? null,
            'author_email' => $row['Author Email'] ?? null,
            'author_first_name' => $row['Author First Name'] ?? null,
            'author_last_name' => $row['Author Last Name'] ?? null,
            'telephone' => $itemData['telephone'] ?? null,
            'phone1' => $itemData['telephoneAdditional'][0]['number'] ?? null,
            'phone2' => $itemData['telephoneAdditional'][1]['number'] ?? null,
            'email' => $itemData['email'] ?? null,
            'web' => $itemData['web'] ?? null,
            'webLinkLabel' => $itemData['webLinkLabel'] ?? null,
            'address' => $row['Item Locations'] ?? ($itemData['map']['address'] ?? null),
            'latitude' => $row['ait-latitude'] ?? ($itemData['map']['latitude'] ?? null),
            'longitude' => $row['ait-longitude'] ?? ($itemData['map']['longitude'] ?? null),
            'streetview' => $itemData['map']['streetview'] ?? null,
            'displayOpeningHours' => !empty($itemData['displayOpeningHours']) && $itemData['displayOpeningHours'] == '1',
            'openingHoursMonday' => $itemData['openingHoursMonday'] ?? null,
            'openingHoursTuesday' => $itemData['openingHoursTuesday'] ?? null,
            'openingHoursWednesday' => $itemData['openingHoursWednesday'] ?? null,
            'openingHoursThursday' => $itemData['openingHoursThursday'] ?? null,
            'openingHoursFriday' => $itemData['openingHoursFriday'] ?? null,
            'openingHoursSaturday' => $itemData['openingHoursSaturday'] ?? null,
            'openingHoursSunday' => $itemData['openingHoursSunday'] ?? null,
            'openingHoursNote' => $itemData['openingHoursNote'] ?? null,
            'displaySocialIcons' => !empty($itemData['displaySocialIcons']) && $itemData['displaySocialIcons'] == '1',
            'socialIconsOpenInNewWindow' => !empty($itemData['socialIconsOpenInNewWindow']) && $itemData['socialIconsOpenInNewWindow'] == '1',
            'socialIcons' => $itemData['socialIcons'] ?? null,
            'socialIcons_url' => $itemData['socialIcons_url'] ?? null,
            'displayGallery' => !empty($itemData['displayGallery']) && $itemData['displayGallery'] == '1',
            'galleryUrls' => !empty($row['Image URL']) ? explode('|', $row['Image URL']) : [],
            'categoryId' => null,
        ];

        if (!empty($row['Date'])) {
            try {
                $data['collection_date'] = Carbon::createFromFormat('d-m-Y', $row['Date'])->format('Y-m-d');
            } catch (\Exception $e) {
                Log::warning("Invalid date for row ID {$row['id']}: {$row['Date']}");
            }
        }

        if (!empty($row['Item Categories'])) {
            $categories = explode('|', $row['Item Categories']);
            foreach ($categories as $catString) {
                $parts = explode('>', $catString);
                $parentId = null;
                foreach ($parts as $part) {
                    $part = trim($part);
                    if (!empty($part)) {
                        $category = Category::firstOrCreate(['Category_Name' => $part, 'parent_id' => $parentId]);
                        $parentId = $category->id;
                    }
                }
                $data['categoryId'] = $parentId;
            }
        }

        return $data;
    }

    private function downloadAndStoreImage(string $url, string $directory, string $prefix = ''): ?string
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            Log::warning("Invalid URL: $url");
            return null;
        }

        try {
            $contents = file_get_contents($url);
            if ($contents === false) {
                Log::warning("Failed to download: $url");
                return null;
            }
            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $fileName = ($prefix ? $prefix . '_' : '') . uniqid() . '.' . $extension;
            Storage::disk('public')->put($directory . '/' . $fileName, $contents);
            return $fileName;
        } catch (\Exception $e) {
            Log::warning("Exception downloading $url: " . $e->getMessage());
            return null;
        }
    }
}
