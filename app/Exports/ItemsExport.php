<?php

namespace App\Exports;

use App\Models\Item;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ItemsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $selectedIds;

    // Accept selected IDs
    public function __construct($selectedIds)
    {
        $this->selectedIds = $selectedIds;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Item::with(['category', 'contacts', 'opening_Time', 'socialIcons', 'galleries'])
                   ->whereIn('id', $this->selectedIds)
                   ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Item ID',
            'Category',
            'reference_id',
            'Title',
            'Subtitle',
            'Item Featured',
            'Collection Date',
            'Permalink',
            'Image',
            'Author Username',
            'Author Email',
            'Author First Name',
            'Author Last Name',
            'Slug',
            'Parent',
            'Parent Slug',
            'Contact Telephone',
            'Phone1',
            'Phone2',
            'Contact Email',
            'Display Opening Hours',
            'Opening Hours Monday',
            'Opening Hours Tuesday',
            'Opening Hours Wednesday',
            'Opening Hours Thursday',
            'Opening Hours Friday',
            'Opening Hours Saturday',
            'Opening Hours Sunday',
            'Social Icons',
            'Social Icons Open In New Window',
            'Display Social Icons',
            'Gallery URLs',
            'Display Gallery',
            'Created At',
            'Updated At'
        ];
    }

    /**
     * @param mixed $item
     * @return array
     */
    public function map($item): array
    {
        $contact = $item->contacts->first() ?? (object) [];
        $openingTime = $item->opening_Time ?? (object) [];
        $socialIcons = $item->socialIcons ?? collect([]);
        $galleries = $item->galleries ?? collect([]);

        return [
            $item->id,
            optional($item->category)->Category_Name ?? 'N/A',
            $item->reference_id ?? 'N/A',
            $item->title ?? 'N/A',
            $item->subtitle ?? 'N/A',
            $item->item_featured ?? 'N/A',
            $item->collection_date ? Carbon::parse($item->collection_date)->format('Y-m-d') : 'N/A',
            $item->permalink ?? 'N/A',
            $item->image ?? 'N/A',
            $item->author_username ?? 'N/A',
            $item->author_email ?? 'N/A',
            $item->author_first_name ?? 'N/A',
            $item->author_last_name ?? 'N/A',
            $item->slug ?? 'N/A',
            $item->parent ?? 'N/A',
            $item->parent_slug ?? 'N/A',
            $contact->telephone ?? 'N/A',
            $contact->phone1 ?? 'N/A',
            $contact->phone2 ?? 'N/A',
            $contact->email ?? 'N/A',
            $openingTime->displayOpeningHours ?? 'N/A',
            $openingTime->openingHoursMonday ?? 'N/A',
            $openingTime->openingHoursTuesday ?? 'N/A',
            $openingTime->openingHoursWednesday ?? 'N/A',
            $openingTime->openingHoursThursday ?? 'N/A',
            $openingTime->openingHoursFriday ?? 'N/A',
            $openingTime->openingHoursSaturday ?? 'N/A',
            $openingTime->openingHoursSunday ?? 'N/A',
            $socialIcons->pluck('socialIcons')->implode('; ') ?: 'N/A',
            $socialIcons->pluck('socialIconsOpenInNewWindow')->implode('; ') ?: 'N/A',
            $socialIcons->pluck('displaySocialIcons')->implode('; ') ?: 'N/A',
            $galleries->pluck('gallery')->implode('; ') ?: 'N/A',
            $galleries->isNotEmpty() ? 'Yes' : 'No',
            optional($item->created_at)->format('Y-m-d H:i:s') ?? 'N/A',
            optional($item->updated_at)->format('Y-m-d H:i:s') ?? 'N/A',
        ];
    }
}
