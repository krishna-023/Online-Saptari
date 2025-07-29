<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Contact;
use App\Models\OpeningTime;
use App\Models\SocialIcon;
use App\Models\Gallery;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemsImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
     * Map the row data to the Item model.
     *
     * @param array $row
     * @return \App\Models\Item|null
     */
    public function model(array $row)
    {
        // Ensure that required fields are present
        if (empty($row['Title']) || empty($row['Category_Name'])) {
            \Log::warning('Skipping row due to missing required data:', ['row_data' => $row]);
            return null;
        }

        // Create or update the main item
        $item = Item::updateOrCreate(
            ['id' => $row['Item ID'] ?? null], // Ensure this matches your database schema
            [
                'Category' => $row['Category_Name'] ?? null,
                'reference_id' => $row['reference_id'] ?? null,
                'title' => $row['Title'] ?? null,
                'subtitle' => $row['Subtitle'] ?? null,
                'item_featured' => $row['Item Featured'] ?? null,
                'collection_date' => $row['Collection Date'] ?? null,
                'permalink' => $row['Permalink'] ?? null,
                'image' => $row['Image'] ?? null,
                'author_username' => $row['Author Username'] ?? null,
                'author_email' => $row['Author Email'] ?? null,
                'author_first_name' => $row['Author First Name'] ?? null,
                'author_last_name' => $row['Author Last Name'] ?? null,
                'slug' => $row['Slug'] ?? null,
                'parent' => $row['Parent'] ?? null,
                'parent_slug' => $row['Parent Slug'] ?? null,
            ]
        );

        \Log::info('Item created/updated:', ['item_data' => $item->toArray()]);

        // Create or update related data
        $this->createOrUpdateContact($item, $row);
        $this->createOrUpdateOpeningTime($item, $row);
        $this->createOrUpdateSocialIcon($item, $row);
        $this->createOrUpdateGallery($item, $row);

        return $item;
    }

    protected function createOrUpdateContact(Item $item, array $row)
    {
        if (!empty($row['Contact Telephone']) || !empty($row['Contact Email'])) {
            Contact::updateOrCreate(
                ['item_id' => $item->id], // Ensure this matches your foreign key in the contacts table
                [
                    'telephone' => $row['Contact Telephone'] ?? null,
                    'phone1' => $row['Phone1'] ?? null,
                    'phone2' => $row['Phone2'] ?? null,
                    'email' => $row['Contact Email'] ?? null,
                ]
            );
            \Log::info('Contact created/updated for item:', ['item_id' => $item->id]);
        }
    }

    protected function createOrUpdateOpeningTime(Item $item, array $row)
    {
        if (!empty($row['Display Opening Hours'])) {
            OpeningTime::updateOrCreate(
                ['item_id' => $item->id], // Ensure this matches your foreign key in the opening_times table
                [
                    'display_opening_hours' => $row['Display Opening Hours'] ?? null,
                    'opening_hours_monday' => $row['Opening Hours Monday'] ?? null,
                    'opening_hours_tuesday' => $row['Opening Hours Tuesday'] ?? null,
                    'opening_hours_wednesday' => $row['Opening Hours Wednesday'] ?? null,
                    'opening_hours_thursday' => $row['Opening Hours Thursday'] ?? null,
                    'opening_hours_friday' => $row['Opening Hours Friday'] ?? null,
                    'opening_hours_saturday' => $row['Opening Hours Saturday'] ?? null,
                    'opening_hours_sunday' => $row['Opening Hours Sunday'] ?? null,
                ]
            );
            \Log::info('OpeningTime created/updated for item:', ['item_id' => $item ->id]);
        }
    }

    protected function createOrUpdateSocialIcon(Item $item, array $row)
    {
        if (!empty($row['Display Social Icons'])) {
            SocialIcon::updateOrCreate(
                ['item_id' => $item->id], // Ensure this matches your foreign key in the social_icons table
                [
                    'display_social_icons' => $row['Display Social Icons'] ?? null,
                    'social_icons_open_in_new_window' => $row['Social Icons Open In New Window'] ?? null,
                    'social_icons' => $row['Social Icons'] ?? null,
                    'social_icons_url' => $row['Social Icons URL'] ?? null,
                ]
            );
            \Log::info('SocialIcon created/updated for item:', ['item_id' => $item->id]);
        }
    }

    protected function createOrUpdateGallery(Item $item, array $row)
    {
        if (!empty($row['Display Gallery'])) {
            Gallery::updateOrCreate(
                ['item_id' => $item->id], // Ensure this matches your foreign key in the galleries table
                [
                    'display_gallery' => $row['Display Gallery'] ?? null,
                    'gallery_urls' => $row['Gallery URLs'] ?? null,
                ]
            );
            \Log::info('Gallery created/updated for item:', ['item_id' => $item->id]);
        }
    }
}
