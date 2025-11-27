<?php

namespace App\Jobs;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Str;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImportItemRow implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $row;

    /**
     * Create a new job instance.
     */
    public function __construct(array $row)
    {
        $this->row = $row;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $row = $this->row;

        // --- CATEGORY ---
        $category_id = null;
        if (!empty($row['item_categories'])) {
            $categories = explode('|', $row['item_categories']);
            if (!empty($categories)) {
                $firstCategory = explode('>', $categories[0]);
                $category_name = trim(end($firstCategory));
                if ($category_name) {
                    $category = Category::firstOrCreate(
                        ['name' => $category_name],
                        ['slug' => Str::slug($category_name)]
                    );
                    $category_id = $category->id;
                }
            }
        }

        // --- ITEM DATA (serialized) ---
        $itemData = [];
        if (!empty($row['_ait_item_item_data'])) {
            $unserialized = @unserialize($row['_ait_item_item_data']);
            if ($unserialized !== false && is_array($unserialized)) {
                $itemData = $unserialized;
            }
        }

        // --- COLLECTION DATE ---
        $collection_date = null;
        if (!empty($row['date'])) {
            try {
                $collection_date = Carbon::parse($row['date'])->format('Y-m-d');
            } catch (\Exception $e) {
                $collection_date = null; // Invalid date formats fallback
            }
        }

        // --- ITEM ---
        $item = Item::updateOrCreate(
            ['title' => $row['title']],
            [
                'category_id'       => $category_id,
                'reference_id'      => $row['id'] ?? null,
                'title'             => $row['title'],
                'subtitle'          => $row['subtitle'] ?? ($itemData['subtitle'] ?? ''),
                'content'           => $row['content'] ?? '',
                'item_featured'     => $itemData['featuredItem'] ?? 0,
                'collection_date'   => $collection_date,
                'permalink'         => $row['permalink'] ?? '',
                'image'             => $row['image_url'] ?? '',
                'author_username'   => $row['author_username'] ?? '',
                'author_email'      => $row['author_email'] ?? '',
                'author_first_name' => $row['author_first_name'] ?? '',
                'author_last_name'  => $row['author_last_name'] ?? '',
                'slug'              => $row['slug'] ?? Str::slug($row['title']),
            ]
        );

        $item_id = $item->id;

        // --- CONTACTS ---
        $phones = $itemData['telephoneAdditional'] ?? [];
        if (is_array($phones)) {
            foreach ($phones as $phone) {
                DB::table('contacts')->updateOrInsert(
                    ['item_id' => $item_id, 'type' => 'phone', 'value' => $phone['number'] ?? '']
                );
            }
        }

        if (!empty($itemData['email'])) {
            DB::table('contacts')->updateOrInsert(
                ['item_id' => $item_id, 'type' => 'email', 'value' => $itemData['email']]
            );
        }

        if (!empty($itemData['web'])) {
            DB::table('contacts')->updateOrInsert(
                ['item_id' => $item_id, 'type' => 'website', 'value' => $itemData['web']]
            );
        }

        // --- GALLERY ---
        if (!empty($row['image_url'])) {
            $images = explode('|', $row['image_url']);
            foreach ($images as $img) {
                $img = trim($img);
                if ($img) {
                    DB::table('galleries')->updateOrInsert(
                        ['item_id' => $item_id, 'image_url' => $img]
                    );
                }
            }
        }

        // --- OPENING HOURS ---
        $days = [
            'Monday'    => 'openingHoursMonday',
            'Tuesday'   => 'openingHoursTuesday',
            'Wednesday' => 'openingHoursWednesday',
            'Thursday'  => 'openingHoursThursday',
            'Friday'    => 'openingHoursFriday',
            'Saturday'  => 'openingHoursSaturday',
            'Sunday'    => 'openingHoursSunday',
        ];

        foreach ($days as $day => $field) {
            $hours = $itemData[$field] ?? null;
            if ($hours && $hours !== '-') {
                DB::table('item_opening_hours')->updateOrInsert(
                    ['item_id' => $item_id, 'day' => $day],
                    ['hours' => $hours]
                );
            }
        }

        // --- SOCIAL ICONS ---
        if (!empty($itemData['socialIcons'])) {
            $socials = @unserialize($itemData['socialIcons']);
            if (is_array($socials)) {
                foreach ($socials as $icon) {
                    DB::table('item_social_icons')->updateOrInsert(
                        [
                            'item_id' => $item_id,
                            'icon'    => $icon['icon'] ?? '',
                            'url'     => $icon['url'] ?? ''
                        ],
                        [
                            'open_in_new_window' => $itemData['socialIconsOpenInNewWindow'] ?? 1
                        ]
                    );
                }
            }
        }
    }
}
