<?php

namespace App\Imports;

use App\Jobs\DownloadGalleryImage;
use App\Models\{Item, Category, Contact, OpeningTime, SocialIcon, Gallery};
use Illuminate\Support\Facades\{Log, Storage, Http, DB};
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\{ToCollection, WithHeadingRow, WithChunkReading, WithEvents};
use Maatwebsite\Excel\Events\{AfterImport, ImportFailed};
use App\Notifications\ImportCompletedNotification;
use App\Notifications\ImportFailedNotification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Throwable;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ItemImport implements ToCollection, WithHeadingRow, WithChunkReading, WithEvents, ShouldQueue
{
    protected array $categoryMap = [];
    protected array $processedSlugs = [];
    protected array $nepaliDetectionLog = [];
    public int $timeout = 300;
    public ?int $initiatedBy = null;
    protected int $processedCount = 0;
    protected int $errorCount = 0;

    public function __construct(?int $initiatedBy = null)
    {
        $this->initiatedBy = $initiatedBy;
    }

    public function collection(Collection $rows): void
    {
        DB::beginTransaction();

        try {
            foreach ($rows as $index => $row) {
                $rowArray = [];
                try {
                    $rowArray = is_array($row) ? $row : $row->toArray();
                    $this->processRow($rowArray, (int)$index);
                    $this->processedCount++;
                } catch (Throwable $e) {
                    $this->errorCount++;
                    Log::error("Error processing row {$index}", [
                        'row_data' => $rowArray,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    continue;
                }
            }

            DB::commit();

            Log::info('CSV import completed', [
                'processed' => $this->processedCount,
                'errors' => $this->errorCount,
                'total_rows' => $rows->count()
            ]);

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('CSV import failed', [
                'error' => $e->getMessage(),
                'processed' => $this->processedCount,
                'errors' => $this->errorCount
            ]);
            throw $e;
        }
    }

    private function processRow(array $row, int $index): void
{
    // Skip empty rows
    if (empty($row['title']) && empty($row['Title']) && empty($row['id'])) {
        return;
    }

    // 🔍 DEBUG: Check available columns
    Log::info("=== ROW {$index} COLUMNS ===", array_keys($row));

    // 🔍 Auto-detect Nepali text in every field of the row
    $nepaliFields = $this->detectNepaliTextInRow($row);

    if (!empty($nepaliFields)) {
        $this->logNepaliDetection($row, $nepaliFields);
    }

    /** STEP 1: Process and validate row data with UTF-8 handling */
    $processedRow = $this->processRowData($row);

        if (!$this->isValidRow($processedRow)) {
            Log::warning('Skipping invalid row', ['title' => $processedRow['title'] ?? 'Unknown']);
            return;
        }

        /** STEP 2: Create or update item */
        $item = $this->createOrUpdateItem($processedRow);

        if (!$item instanceof Item) {
            Log::error('Failed to create/update item', ['title' => $processedRow['title']]);
            return;
        }

        /** STEP 3: Create related records */
        $this->createOrUpdateRelatedRecords($item, $processedRow);

        Log::info("✅ Imported item: {$item->title} (ID: {$item->id})");
    }
private function debugColumnNames(array $row): void
{
    Log::info('🔍 DEBUG COLUMN NAMES');
    foreach ($row as $key => $value) {
        if (stripos($key, 'image') !== false || stripos($key, 'url') !== false) {
            Log::info("📋 Column match: '{$key}' => '" . substr($value, 0, 100) . "'");
        }
    }

    // Also log first 10 columns to see the structure
    $count = 0;
    foreach ($row as $key => $value) {
        if ($count++ < 10) {
            Log::info("📋 Column [{$key}]: " . (is_string($value) ? substr($value, 0, 50) : gettype($value)));
        }
    }
}
    /* ---------------------- NEPALI TEXT DETECTION ---------------------- */

    private function detectNepaliTextInRow(array $row): array
    {
        $nepaliFields = [];

        foreach ($row as $key => $value) {
            if ($this->isNepaliText($value)) {
                $nepaliFields[$key] = $value;
            }
        }

        return $nepaliFields;
    }

    private function isNepaliText(mixed $text): bool
    {
        if (!is_string($text) || trim($text) === '') {
            return false;
        }

        // Matches any character in Devanagari Unicode block (U+0900–U+097F)
        return (bool) preg_match('/[\x{0900}-\x{097F}]/u', $text);
    }

    private function logNepaliDetection(array $row, array $nepaliFields): void
    {
        $title = $row['Title'] ?? $row['title'] ?? 'Unknown';

        Log::info('Nepali text detected in import row', [
            'row_title' => $title,
            'nepali_field_count' => count($nepaliFields),
            'nepali_fields' => array_keys($nepaliFields),
            'sample_text' => $this->getSampleNepaliText($nepaliFields)
        ]);

        // Store for summary reporting
        $this->nepaliDetectionLog[] = [
            'title' => $title,
            'fields' => array_keys($nepaliFields)
        ];
    }

    private function getSampleNepaliText(array $nepaliFields): string
    {
        foreach ($nepaliFields as $text) {
            $cleanText = $this->forceUtf8($text);
            return mb_substr($cleanText, 0, 50) . (mb_strlen($cleanText) > 50 ? '...' : '');
        }
        return '';
    }

    /* ---------------------- MAIN PROCESSING METHODS ---------------------- */

   private function processRowData(array $row): array
{
    Log::info('=== PROCESS ROW DATA DEBUG ===');
    Log::info('All row keys:', array_keys($row));

    // Fix corrupted Nepali text BEFORE any processing
    $fixedRow = $this->fixCorruptedNepaliText($row);

    // Decode and flatten meta data
    $meta = $this->decodeMeta($fixedRow['ait_item_item_data'] ?? $fixedRow['_ait_item_item_data'] ?? null);
    $flattened = array_merge($this->flattenArray($meta), $fixedRow);

    // Process core fields with the FIXED data
    $title = $this->processTitle($flattened);
    $slug = $this->generateUniqueSlug($title);

    // Extract gallery images from multiple possible sources
    $galleryImages = $this->extractGalleryImages($flattened, $fixedRow);

    return [
        'title' => $title,
        'slug' => $slug,
        'subtitle' => $this->processContent($flattened['subtitle'] ?? $flattened['Subtitle'] ?? null),
        'content' => $this->processContent($flattened['content'] ?? $flattened['Content'] ?? null),
        'item_featured' => $this->processFeatured($flattened),
        'collection_date' => $this->parseDate($flattened['date'] ?? $flattened['Date'] ?? null),
        'permalink' => $flattened['permalink'] ?? url('/items/' . $slug),
        'image_url' => $flattened['image_url'] ?? $flattened['image'] ?? null,
        'category_string' => $flattened['item_categories'] ?? $flattened['Item Categories'] ?? $flattened['category_ref'] ?? '',
        'author_username' => $flattened['author_username'] ?? $flattened['Author Username'] ?? null,
        'author_email' => $flattened['author_email'] ?? $flattened['Author Email'] ?? null,
        'author_first_name' => $flattened['author_first_name'] ?? $flattened['Author First Name'] ?? null,
        'author_last_name' => $flattened['author_last_name'] ?? $flattened['Author Last Name'] ?? null,
        'item_locations' => $flattened['item_locations'] ?? $flattened['Item Locations'] ?? null,
        'ait_latitude' => $flattened['ait_latitude'] ?? $flattened['latitude'] ?? null,
        'ait_longitude' => $flattened['ait_longitude'] ?? $flattened['longitude'] ?? null,
        'gallery_images' => $galleryImages,
        'contact_data' => $flattened,
        'opening_hours_data' => $flattened,
        'social_data' => $flattened,
        'telephone_additional' => $flattened['telephoneAdditional'] ?? $flattened['telephone_additional'] ?? null,
        'raw_data' => $flattened,
    ];
}

private function fixCorruptedNepaliText(array $row): array
{
    $fixedRow = $row;

    $nepaliFixes = [
        // Common Nepali text corruptions
        '??.??. ??????' => 'एम.के. टेलर्स',
        '???? ???? ?????? ??? ????, ??????, ????, ????????' => 'सबै प्रकारका टेलरिङ्ग सेवा उपलब्ध, युनिफर्म, कोट, सुट, सलवार',

        // Individual word fixes
        '????' => 'सबै',
        '????' => 'प्रकारका',
        '??????' => 'टेलरिङ्ग',
        '???' => 'सेवा',
        '??????' => 'उपलब्ध',
        '??????' => 'युनिफर्म',
        '???' => 'कोट',
        '???' => 'सुट',
        '??????' => 'सलवार',
    ];

    foreach ($fixedRow as $key => $value) {
        if (is_string($value)) {
            $original = $value;
            $fixedRow[$key] = str_replace(array_keys($nepaliFixes), array_values($nepaliFixes), $value);

            if ($fixedRow[$key] !== $original) {
                Log::info("🔧 Fixed Nepali text in '{$key}': '{$original}' => '{$fixedRow[$key]}'");
            }
        }
    }

    return $fixedRow;
}
    /**
     * Extract gallery images from multiple possible sources
     */
   private function extractGalleryImages(array $flattened, array $originalRow): array
{
    $galleryImages = [];
    $this->debugColumnNames($originalRow);

    // Try multiple possible column names
    $possibleImageColumns = [
        'Image URL',
        'image_url',
        'Image_URL',
        'imageurl',
        'ImageUrl',
        'Images',
        'images',
        'gallery_images',
        'Gallery'
    ];

    foreach ($possibleImageColumns as $column) {
        if (!empty($originalRow[$column])) {
            Log::info("✅ Found image column: '{$column}'", ['value' => $originalRow[$column]]);
            $images = $this->parseImageUrls($originalRow[$column]);
            $galleryImages = array_merge($galleryImages, $images);
            break; // Use the first matching column
        }
    }

    Log::info('📸 Final gallery images extracted', [
        'total' => count($galleryImages),
        'images' => $galleryImages
    ]);

    return array_filter(array_unique($galleryImages));
}


    /**
     * Parse image URLs from various formats
     */
    private function parseImageUrls($imageData): array
 {
    Log::info('🔄 parseImageUrls CALLED', [
        'input_type' => gettype($imageData),
        'input_value' => is_string($imageData) ? $imageData : 'NOT_STRING'
    ]);

    if (empty($imageData)) {
        Log::info('❌ parseImageUrls: Empty input');
        return [];
    }

    // If it's already an array, return as is
    if (is_array($imageData)) {
        Log::info('✅ parseImageUrls: Input is array', ['count' => count($imageData)]);
        return array_filter(array_map('trim', $imageData));
    }

    // Handle string data
    $imageString = (string) $imageData;
    Log::info('📝 parseImageUrls: Processing string', [
        'string_length' => strlen($imageString),
        'string_content' => $imageString
    ]);

    // Check if it's pipe-separated
    if (str_contains($imageString, '|')) {
        Log::info('🔗 parseImageUrls: Found pipe delimiter');
        $images = explode('|', $imageString);
        $images = array_filter(array_map('trim', $images));
        Log::info('📸 parseImageUrls: Pipe-separated result', [
            'count' => count($images),
            'images' => $images
        ]);
        return $images;
    }

    // Check other delimiters
    $delimiters = [',', ';', "\n"];
    foreach ($delimiters as $delimiter) {
        if (str_contains($imageString, $delimiter)) {
            Log::info("🔗 parseImageUrls: Found delimiter '{$delimiter}'");
            $images = explode($delimiter, $imageString);
            $images = array_filter(array_map('trim', $images));
            Log::info("📸 parseImageUrls: Delimiter result", [
                'count' => count($images),
                'images' => $images
            ]);
            return $images;
        }
    }

    // Single image
    Log::info('🖼️ parseImageUrls: Single image', ['image' => trim($imageString)]);
    return [trim($imageString)];
 }
    /**
     * Extract images from meta gallery data (serialized/JSON)
     */
    private function extractImagesFromMeta($galleryData): array
    {
        if (empty($galleryData)) {
            return [];
        }

        $images = [];

        try {
            // Handle serialized PHP data
            if (is_string($galleryData)) {
                // Try to unserialize
                $unserialized = @unserialize($galleryData);
                if ($unserialized !== false) {
                    $galleryData = $unserialized;
                }
                // Try JSON decode
                else {
                    $jsonDecoded = json_decode($galleryData, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $galleryData = $jsonDecoded;
                    }
                }
            }

            // Process array data
            if (is_array($galleryData)) {
                $images = $this->extractImagesFromArray($galleryData);
            }

        } catch (Throwable $e) {
            Log::warning('Failed to extract images from meta data: ' . $e->getMessage());
        }

        return $images;
    }

    /**
     * Recursively extract image URLs from array structure
     */
    private function extractImagesFromArray(array $data): array
    {
        $images = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $images = array_merge($images, $this->extractImagesFromArray($value));
            } elseif (is_string($value)) {
                // Check if it's a URL
                if (filter_var($value, FILTER_VALIDATE_URL) && $this->isImageUrl($value)) {
                    $images[] = $value;
                }
                // Check if it's a serialized/JSON string containing URLs
                elseif (preg_match('/https?:\/\/[^\s]+/i', $value)) {
                    preg_match_all('/https?:\/\/[^\s"\']+\.(jpg|jpeg|png|gif|webp)/i', $value, $matches);
                    $images = array_merge($images, $matches[0] ?? []);
                }
            }
        }

        return array_unique(array_filter($images));
    }

    /**
     * Check if URL points to an image
     */
    private function isImageUrl(string $url): bool
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (!$path) return false;

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];

        return in_array($extension, $imageExtensions);
    }

    private function convertArrayToUtf8(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            $utf8Key = $this->forceUtf8($key);

            if (is_array($value)) {
                $result[$utf8Key] = $this->convertArrayToUtf8($value);
            } else {
                $result[$utf8Key] = $this->forceUtf8($value);
            }
        }

        return $result;
    }

  private function forceUtf8(?string $text): ?string
{
    if ($text === null || $text === '') {
        return $text;
    }

    Log::info('🔄 UTF-8 CONVERSION DEBUG', [
        'input' => substr($text, 0, 50),
        'hex' => bin2hex(substr($text, 0, 20))
    ]);

    // If it's already valid UTF-8 with Nepali characters, return as is
    if (mb_check_encoding($text, 'UTF-8')) {
        Log::info('✅ Already valid UTF-8');
        return $text;
    }

    // Common encoding issues with Nepali text
    $text = $this->fixCommonNepaliEncodingIssues($text);

    // Try different encodings
    $encodings = ['UTF-8', 'ISO-8859-1', 'Windows-1252', 'CP1252', 'ASCII'];

    foreach ($encodings as $encoding) {
        if (mb_check_encoding($text, $encoding)) {
            $converted = mb_convert_encoding($text, 'UTF-8', $encoding);
            if (mb_check_encoding($converted, 'UTF-8')) {
                Log::info("✅ Converted from {$encoding} to UTF-8");
                return $converted;
            }
        }
    }

    // Last resort: use iconv with //TRANSLIT
    if (function_exists('iconv')) {
        $converted = @iconv('UTF-8', 'UTF-8//TRANSLIT', $text);
        if ($converted !== false) {
            Log::info('✅ Used iconv for conversion');
            return $converted;
        }
    }

    // Remove any remaining invalid characters
    $text = preg_replace('/[^\x{0009}\x{000A}\x{000D}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}\x{10000}-\x{10FFFF}]/u', '', $text);

    Log::info('🔧 Final converted text:', ['output' => substr($text, 0, 50)]);

    return $text;
}

private function fixCommonNepaliEncodingIssues(string $text): string
{
    // Common Nepali character fixes
    $nepaliFixes = [
        // Fix question mark replacement
        '?????' => 'शिव',
        '????????????' => 'अन्तर्राष्ट्रिय',
        '????????' => 'बोर्डिङ्ग',
        '?????????' => 'माध्यमिक',
        '?????' => 'विद्यालय',

        // Fix individual character issues
        'à¤¶' => 'श', 'à¤‡' => 'इ', 'à¤µ' => 'व',
        'à¤…' => 'अ', 'à¤¨' => 'न', 'à¤¤' => 'त',
        'à¤°' => 'र', 'à¤°à¤¾' => 'रा', 'à¤·' => 'ष',
        'à¤Ÿ' => 'ट', 'à¤°à¤¿' => 'रि', 'à¤¯' => 'य',
        'à¤¬' => 'ब', 'à¤¾' => 'ा', 'à¤°à¥' => 'रे',
        'à¤¡' => 'ड', 'à¤¿' => 'ि', 'à¤‚' => 'ं',
        'à¤—' => 'ग', 'à¤®' => 'म', 'à¤§' => 'ध',
        'à¤¹' => 'ह', 'à¤²' => 'ल', 'à¤¯' => 'य',
    ];

    $originalText = $text;
    $text = str_replace(array_keys($nepaliFixes), array_values($nepaliFixes), $text);

    if ($text !== $originalText) {
        Log::info('🔧 Applied Nepali character fixes');
    }

    return $text;
}

    private function containsValidNepali(string $text): bool
    {
        return $this->isNepaliText($text);
    }

    // private function lastResortUtf8Fix(string $text): string
    // {
    //     // Try iconv if available
    //     if (function_exists('iconv')) {
    //         $converted = @iconv('UTF-8', 'UTF-8//IGNORE', $text);
    //         if ($converted !== false) {
    //             $text = $converted;
    //         }
    //     }

    //     // Remove any remaining invalid characters but preserve Nepali
    //     $text = preg_replace('/[^\x20-\x7E\xA0-\xFF\x{0100}-\x{017F}\x{0180}-\x{024F}\x{1E00}-\x{1EFF}\x{0900}-\x{097F}]/u', '', $text);

    //     return trim($text);
    // }

    private function isValidRow(array $processedRow): bool
    {
        if (empty($processedRow['title']) || trim($processedRow['title']) === '' || $processedRow['title'] === 'Untitled') {
            return false;
        }

        // Check if title is not just whitespace or special characters
        if (preg_match('/^[\s\W]*$/', $processedRow['title'])) {
            return false;
        }

        return true;
    }

    private function createOrUpdateItem(array $data): ?Item
    {
        try {
            // Check if item already exists
            $existingItem = Item::where('slug', $data['slug'])
                              ->orWhere('title', $data['title'])
                              ->first();

            $categoryId = $this->resolveCategoryHierarchy($data['category_string']);
            $imagePath = $this->downloadAndStoreImage($data['image_url'], $data['slug']);

            $itemData = [
                'title' => $this->ensureUtf8($data['title']),
                'subtitle' => $this->ensureUtf8($data['subtitle']),
                'content' => $this->ensureUtf8($data['content']),
                'item_featured' => $data['item_featured'],
                'collection_date' => $data['collection_date'],
                'slug' => $data['slug'],
                'permalink' => $data['permalink'],
                'image' => $imagePath,
                'category_id' => $categoryId,
                'author_username' => $this->ensureUtf8($data['author_username']),
                'author_email' => $data['author_email'],
                'author_first_name' => $this->ensureUtf8($data['author_first_name']),
                'author_last_name' => $this->ensureUtf8($data['author_last_name']),
                'item_locations' => $this->ensureUtf8($data['item_locations']),
                'ait_latitude' => $data['ait_latitude'],
                'ait_longitude' => $data['ait_longitude'],
                'status' => 'active',
            ];

            // Remove null values to use database defaults
            $itemData = array_filter($itemData, function ($value) {
                return $value !== null;
            });

            if ($existingItem instanceof Item) {
                $existingItem->update($itemData);
                return $existingItem;
            } else {
                return Item::create($itemData);
            }
        } catch (Throwable $e) {
            Log::error('Failed to create/update item: ' . $e->getMessage(), [
                'data_title' => $data['title'] ?? 'No title',
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    private function createOrUpdateRelatedRecords(Item $item, array $data): void
    {
        $this->createOrUpdateContact($item, $data['contact_data']);
        $this->createOrUpdateOpeningTimes($item, $data['opening_hours_data']);
        $this->createOrUpdateSocialIcons($item, $data['social_data']);
        $this->createOrUpdateGallery($item, $data['gallery_images'], $data['slug']);
        $this->handleAdditionalPhones($item, $data['telephone_additional']);
    }

    /* ---------------------- GALLERY METHODS (FIXED) ---------------------- */

    private function createOrUpdateGallery(Item $item, array $galleryImages, string $slug): void
    {
        if (empty($galleryImages)) {
            Log::info('No gallery images to process', ['item_id' => $item->id, 'slug' => $slug]);
            return;
        }

        try {
            // Delete existing galleries
            $item->galleries()->delete();

            Log::info('Processing gallery images', [
                'item_id' => $item->id,
                'total_images' => count($galleryImages),
                'images' => $galleryImages
            ]);

            // Create individual gallery records for each image
            foreach ($galleryImages as $index => $imageUrl) {
                $cleanUrl = trim($imageUrl);

                if (empty($cleanUrl) || !filter_var($cleanUrl, FILTER_VALIDATE_URL)) {
                    Log::warning('Invalid gallery image URL skipped', [
                        'item_id' => $item->id,
                        'url' => $cleanUrl,
                        'index' => $index
                    ]);
                    continue;
                }

                // Create gallery record
                $gallery = $item->galleries()->create([
                    'image_url' => $cleanUrl,
                    'display_gallery' => true,
                    'sort_order' => $index,
                    'original_filename' => basename($cleanUrl),
                    'download_status' => 'pending',
                ]);

                // Queue background download
                DownloadGalleryImage::dispatch($gallery->id, $cleanUrl, $slug, $index)
                    ->onQueue('gallery-downloads');

                Log::info('Queued gallery image download', [
                    'gallery_id' => $gallery->id,
                    'item_id' => $item->id,
                    'url' => $cleanUrl,
                    'index' => $index
                ]);
            }

        } catch (Throwable $e) {
            Log::error("Gallery creation failed for item {$item->id}: " . $e->getMessage());
        }
    }

    /* ---------------------- CONTACT METHODS ---------------------- */

   private function createOrUpdateContact(Item $item, array $data): void
{
    $contactData = $this->extractContactData($data);

    Log::info('🔄 Creating/Updating contact for item:', ['item_id' => $item->id]);

    if (!empty(array_filter($contactData, function($value) {
        return $value !== null && $value !== '' && $value !== false;
    }))) {
        try {
            // Ensure UTF-8 encoding for text fields
            foreach (['address', 'webLinkLabel'] as $field) {
                if (isset($contactData[$field])) {
                    $contactData[$field] = $this->ensureUtf8($contactData[$field]);
                }
            }

            $existingContact = $item->contacts()->first();
            if ($existingContact instanceof Contact) {
                Log::info('📝 Updating existing contact', $contactData);
                $existingContact->update($contactData);
            } else {
                Log::info('➕ Creating new contact', $contactData);
                $item->contacts()->create($contactData);
            }

            Log::info('✅ Contact saved successfully');

        } catch (Throwable $e) {
            Log::error("❌ Contact creation failed for item {$item->id}: " . $e->getMessage());
        }
    } else {
        Log::info('ℹ️ No contact data to save for item', ['item_id' => $item->id]);
    }
}
private function extractContactData(array $data): array
{
    Log::info('🔍 COMPREHENSIVE CONTACT DATA EXTRACTION');

    // Log ALL available data for debugging
    foreach ($data as $key => $value) {
        if (is_string($value) && !empty(trim($value))) {
            Log::info("📋 AVAILABLE DATA [{$key}]: " . substr(trim($value), 0, 100));
        }
    }

    $contactData = [];

    // Extract from direct fields first
    $this->extractDirectContactFields($data, $contactData);

    // Extract from meta data
    $this->extractMetaContactFields($data, $contactData);

    Log::info('📋 FINAL EXTRACTED CONTACT DATA:', $contactData);

    return $contactData;
}

private function extractDirectContactFields(array $data, array &$contactData): void
{
    // Telephone fields
    $phoneFields = ['telephone', 'phone', 'contact_number', 'mobile', 'phone_number'];
    foreach ($phoneFields as $field) {
        if (isset($data[$field]) && !empty(trim($data[$field])) && empty($contactData['telephone'])) {
            $contactData['telephone'] = $this->ensureUtf8(trim($data[$field]));
            Log::info("✅ TELEPHONE from direct field '{$field}': {$contactData['telephone']}");
        }
    }

    // Email fields
    $emailFields = ['email', 'contact_email', 'e-mail', 'mail'];
    foreach ($emailFields as $field) {
        if (isset($data[$field]) && !empty(trim($data[$field])) && empty($contactData['email'])) {
            $contactData['email'] = $this->ensureUtf8(trim($data[$field]));
            Log::info("✅ EMAIL from direct field '{$field}': {$contactData['email']}");
        }
    }

    // Address fields
    $addressFields = ['address', 'location', 'item_locations', 'full_address'];
    foreach ($addressFields as $field) {
        if (isset($data[$field]) && !empty(trim($data[$field])) && empty($contactData['address'])) {
            $contactData['address'] = $this->ensureUtf8(trim($data[$field]));
            Log::info("✅ ADDRESS from direct field '{$field}': " . substr($contactData['address'], 0, 50));
        }
    }

    // Latitude fields
    $latitudeFields = ['ait_latitude', 'latitude', 'lat', 'map_latitude'];
    foreach ($latitudeFields as $field) {
        if (isset($data[$field]) && !empty(trim($data[$field])) && empty($contactData['latitude'])) {
            $contactData['latitude'] = $this->ensureUtf8(trim($data[$field]));
            Log::info("✅ LATITUDE from direct field '{$field}': {$contactData['latitude']}");
        }
    }

    // Longitude fields
    $longitudeFields = ['ait_longitude', 'longitude', 'lng', 'long', 'map_longitude'];
    foreach ($longitudeFields as $field) {
        if (isset($data[$field]) && !empty(trim($data[$field])) && empty($contactData['longitude'])) {
            $contactData['longitude'] = $this->ensureUtf8(trim($data[$field]));
            Log::info("✅ LONGITUDE from direct field '{$field}': {$contactData['longitude']}");
        }
    }

    // Website fields
    $websiteFields = ['web', 'website', 'url', 'website_url'];
    foreach ($websiteFields as $field) {
        if (isset($data[$field]) && !empty(trim($data[$field])) && empty($contactData['web'])) {
            $contactData['web'] = $this->ensureUtf8(trim($data[$field]));
            Log::info("✅ WEBSITE from direct field '{$field}': {$contactData['web']}");
        }
    }
}

private function extractMetaContactFields(array $data, array &$contactData): void
{
    $metaFields = ['_ait_item_item_data', 'ait_item_item_data', 'item_data'];

    foreach ($metaFields as $metaField) {
        if (!empty($data[$metaField])) {
            Log::info("🔍 Checking meta field: {$metaField}");

            $metaData = $this->decodeMeta($data[$metaField]);

            if (!empty($metaData)) {
                Log::info("📦 Meta data keys found:", array_keys($metaData));

                // Extract basic contact info
                if (empty($contactData['telephone']) && !empty($metaData['telephone'])) {
                    $contactData['telephone'] = $this->ensureUtf8(trim($metaData['telephone']));
                    Log::info("✅ TELEPHONE from meta: {$contactData['telephone']}");
                }

                if (empty($contactData['email']) && !empty($metaData['email'])) {
                    $contactData['email'] = $this->ensureUtf8(trim($metaData['email']));
                    Log::info("✅ EMAIL from meta: {$contactData['email']}");
                }

                if (empty($contactData['web']) && !empty($metaData['web'])) {
                    $contactData['web'] = $this->ensureUtf8(trim($metaData['web']));
                    Log::info("✅ WEB from meta: {$contactData['web']}");
                }

                if (empty($contactData['address']) && !empty($metaData['address'])) {
                    $contactData['address'] = $this->ensureUtf8(trim($metaData['address']));
                    Log::info("✅ ADDRESS from meta: " . substr($contactData['address'], 0, 50));
                }

                // Extract coordinates from meta data
                if (empty($contactData['latitude']) && !empty($metaData['latitude'])) {
                    $contactData['latitude'] = $this->ensureUtf8(trim($metaData['latitude']));
                    Log::info("✅ LATITUDE from meta: {$contactData['latitude']}");
                }

                if (empty($contactData['longitude']) && !empty($metaData['longitude'])) {
                    $contactData['longitude'] = $this->ensureUtf8(trim($metaData['longitude']));
                    Log::info("✅ LONGITUDE from meta: {$contactData['longitude']}");
                }

                // Also check map data in meta
                if (!empty($metaData['map']) && is_array($metaData['map'])) {
                    Log::info("🗺️ Map data found in meta:", $metaData['map']);

                    if (empty($contactData['latitude']) && !empty($metaData['map']['latitude'])) {
                        $contactData['latitude'] = $this->ensureUtf8(trim($metaData['map']['latitude']));
                        Log::info("✅ LATITUDE from map data: {$contactData['latitude']}");
                    }

                    if (empty($contactData['longitude']) && !empty($metaData['map']['longitude'])) {
                        $contactData['longitude'] = $this->ensureUtf8(trim($metaData['map']['longitude']));
                        Log::info("✅ LONGITUDE from map data: {$contactData['longitude']}");
                    }

                    if (empty($contactData['address']) && !empty($metaData['map']['address'])) {
                        $contactData['address'] = $this->ensureUtf8(trim($metaData['map']['address']));
                        Log::info("✅ ADDRESS from map data: " . substr($contactData['address'], 0, 50));
                    }
                }

                // Handle telephoneAdditional array
                if (!empty($metaData['telephoneAdditional']) && is_array($metaData['telephoneAdditional'])) {
                    Log::info('📞 Processing telephoneAdditional:', $metaData['telephoneAdditional']);

                    foreach ($metaData['telephoneAdditional'] as $index => $phoneEntry) {
                        if (isset($phoneEntry['number']) && !empty(trim($phoneEntry['number']))) {
                            $phoneNumber = $this->ensureUtf8(trim($phoneEntry['number']));
                            if ($index === 0 && empty($contactData['telephone'])) {
                                $contactData['telephone'] = $phoneNumber;
                                Log::info("✅ Primary phone from telephoneAdditional[0]: {$phoneNumber}");
                            } elseif ($index === 1 && empty($contactData['phone1'])) {
                                $contactData['phone1'] = $phoneNumber;
                                Log::info("✅ Secondary phone from telephoneAdditional[1]: {$phoneNumber}");
                            }
                        }
                    }
                }
            }
        }
    }
}
// private function extractFromSerializedData(array $data, array &$contactData): void
// {
//     // Check all possible serialized data fields
//     $serializedFields = ['_ait_item_item_data', 'ait_item_item_data', 'item_data', 'meta_data'];

//     foreach ($serializedFields as $field) {
//         if (!empty($data[$field])) {
//         Log::info("🔍 Checking serialized field: {$field}");

//             $metaData = $this->decodeMeta($data[$field]);

//             if (!empty($metaData)) {
//             Log::info("📦 Serialized data keys:", array_keys($metaData));

//                 // Extract contact info from serialized data
//                 if (empty($contactData['telephone']) && !empty($metaData['telephone'])) {
//                     $contactData['telephone'] = $this->ensureUtf8(trim($metaData['telephone']));
//                 Log::info("✅ TELEPHONE from serialized: {$contactData['telephone']}");
//                 }

//                 if (empty($contactData['email']) && !empty($metaData['email'])) {
//                     $contactData['email'] = $this->ensureUtf8(trim($metaData['email']));
//                 Log::info("✅ EMAIL from serialized: {$contactData['email']}");
//                 }

//                 // Check telephoneAdditional
//                 if (!empty($metaData['telephoneAdditional']) && is_array($metaData['telephoneAdditional'])) {
//                 Log::info('📞 telephoneAdditional found:', $metaData['telephoneAdditional']);

//                     foreach ($metaData['telephoneAdditional'] as $index => $phoneEntry) {
//                         if (isset($phoneEntry['number']) && !empty(trim($phoneEntry['number']))) {
//                             $phoneNumber = $this->ensureUtf8(trim($phoneEntry['number']));
//                             if ($index === 0 && empty($contactData['telephone'])) {
//                                 $contactData['telephone'] = $phoneNumber;
//                             Log::info("✅ Primary phone from telephoneAdditional[0]: {$phoneNumber}");
//                             } elseif ($index === 1 && empty($contactData['phone1'])) {
//                                 $contactData['phone1'] = $phoneNumber;
//                             Log::info("✅ Secondary phone from telephoneAdditional[1]: {$phoneNumber}");
//                             }
//                         }
//                     }
//                 }
//             }
//         }
//     }
// }

// // private function extractAdditionalPhonesFromMeta(array $data, array &$contactData): void
// // {
// //     // Check for telephoneAdditional in various meta fields
// //     $metaFields = ['_ait_item_item_data', 'ait_item_item_data', 'telephoneAdditional'];

// //     foreach ($metaFields as $metaField) {
// //         if (!empty($data[$metaField])) {
// //             Log::info("🔍 Checking telephoneAdditional in: {$metaField}");

// //             $metaData = $this->decodeMeta($data[$metaField]);

// //             if (isset($metaData['telephoneAdditional']) && is_array($metaData['telephoneAdditional'])) {
// //                 Log::info('📞 Found telephoneAdditional array:', $metaData['telephoneAdditional']);

// //                 $additionalPhones = [];
// //                 foreach ($metaData['telephoneAdditional'] as $index => $phoneEntry) {
// //                     if (isset($phoneEntry['number']) && !empty(trim($phoneEntry['number']))) {
// //                         $phoneNumber = $this->ensureUtf8(trim($phoneEntry['number']));
// //                         $additionalPhones[] = $phoneNumber;
// //                         Log::info("✅ Additional phone {$index}: {$phoneNumber}");
// //                     }
// //                 }

// //                 // Assign to phone1 and phone2 if not already set
// //                 if (!empty($additionalPhones)) {
// //                     if (empty($contactData['phone1']) && isset($additionalPhones[0])) {
// //                         $contactData['phone1'] = $additionalPhones[0];
// //                     }
// //                     if (empty($contactData['phone2']) && isset($additionalPhones[1])) {
// //                         $contactData['phone2'] = $additionalPhones[1];
// //                     }
// //                 }
// //             }
// //         }
// //     }
// // }

// // private function extractDirectContactFields(array $data): array
// // {
// //     $contactData = [];

// //     $fieldMappings = [
// //         'telephone' => ['telephone', 'primary_phone', 'phone', 'contact_number'],
// //         'phone1' => ['phone1', 'additional_1', 'mobile', 'phone_1'],
// //         'phone2' => ['phone2', 'additional_2', 'phone_secondary', 'phone_2'],
// //         'email' => ['email', 'mail', 'contact_email'],
// //         'web' => ['web', 'website', 'url', 'website_url'],
// //         'address' => ['address', 'location', 'map_address', 'item_locations'],
// //         'latitude' => ['latitude', 'lat', 'ait_latitude', 'map_latitude'],
// //         'longitude' => ['longitude', 'lon', 'lng', 'ait_longitude', 'map_longitude'],
// //     ];

// //     foreach ($fieldMappings as $field => $possibleNames) {
// //         foreach ($possibleNames as $name) {
// //             if (isset($data[$name]) && !empty(trim($data[$name]))) {
// //                 $contactData[$field] = trim($data[$name]);
// //                 Log::info("✅ Found direct contact field '{$name}' => '{$contactData[$field]}'");
// //                 break;
// //             }
// //         }
// //     }

// //     return $contactData;
// // }

// // private function extractMetaContactFields(array $data): array
// // {
// //     $contactData = [];

// //     // Look for meta data fields that might contain contact info
// //     $metaFields = ['_ait_item_item_data', 'ait_item_item_data', 'meta_data', 'item_data'];

// //     foreach ($metaFields as $metaField) {
// //         if (!empty($data[$metaField])) {
// //             Log::info("🔍 Checking meta field: {$metaField}");
// //             $metaData = $this->decodeMeta($data[$metaField]);

// //             // Extract contact info from meta data
// //             if (!empty($metaData)) {
// //                 $metaContact = $this->extractFromMetaData($metaData);
// //                 $contactData = array_merge($contactData, $metaContact);
// //             }
// //         }
// //     }

// //     return $contactData;
// // }

private function extractFromMetaData(array $metaData): array
{
    $contactData = [];

    // Common meta data structures for contact info
    $metaMappings = [
        'telephone' => ['telephone', 'phone', 'contact_number'],
        'email' => ['email', 'mail'],
        'web' => ['web', 'website', 'url'],
        'address' => ['address', 'location'],
        'latitude' => ['latitude', 'lat'],
        'longitude' => ['longitude', 'lng', 'long'],
    ];

    foreach ($metaMappings as $field => $possibleKeys) {
        foreach ($possibleKeys as $key) {
            if (isset($metaData[$key]) && !empty(trim($metaData[$key]))) {
                $contactData[$field] = trim($metaData[$key]);
                Log::info("✅ Found meta contact field '{$key}' => '{$contactData[$field]}'");
                break;
            }
        }
    }

    // Handle telephoneAdditional array
    if (isset($metaData['telephoneAdditional']) && is_array($metaData['telephoneAdditional'])) {
        Log::info('📞 Processing telephoneAdditional array', $metaData['telephoneAdditional']);
        $additionalPhones = [];
        foreach ($metaData['telephoneAdditional'] as $index => $phoneEntry) {
            if (isset($phoneEntry['number']) && !empty(trim($phoneEntry['number']))) {
                $additionalPhones[] = trim($phoneEntry['number']);
            }
        }

        if (!empty($additionalPhones)) {
            if (!isset($contactData['phone1']) && isset($additionalPhones[0])) {
                $contactData['phone1'] = $additionalPhones[0];
            }
            if (!isset($contactData['phone2']) && isset($additionalPhones[1])) {
                $contactData['phone2'] = $additionalPhones[1];
            }
        }
    }

    return $contactData;
}
    /* ---------------------- OPENING HOURS METHODS ---------------------- */

    private function createOrUpdateOpeningTimes(Item $item, array $data): void
    {
        $openingData = $this->extractOpeningHoursData($data);

        if (!empty(array_filter($openingData, function($value) {
            return $value !== null && $value !== '';
        }))) {
            try {
                // Ensure UTF-8 encoding for note field
                if (isset($openingData['openingHoursNote'])) {
                    $openingData['openingHoursNote'] = $this->ensureUtf8($openingData['openingHoursNote']);
                }

                $existingOpeningTime = $item->openingTimes()->first();
                if ($existingOpeningTime instanceof OpeningTime) {
                    $existingOpeningTime->update(array_merge([
                        'displayOpeningHours' => $data['displayOpeningHours'] ?? $data['display_opening_hours'] ?? true
                    ], $openingData));
                } else {
                    $item->openingTimes()->create(array_merge([
                        'displayOpeningHours' => $data['displayOpeningHours'] ?? $data['display_opening_hours'] ?? true
                    ], $openingData));
                }
            } catch (Throwable $e) {
                Log::error("OpeningTime creation failed for item {$item->id}: " . $e->getMessage());
            }
        }
    }

    private function extractOpeningHoursData(array $data): array
    {
        $hours = [];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        foreach ($days as $day) {
            foreach ($data as $key => $value) {
                if (is_string($key) && stripos($key, $day) !== false && !empty($value) && $value !== '-') {
                    $hours['openingHours' . ucfirst($day)] = $value;
                    break;
                }
            }
        }

        // Add notes if found
        foreach ($data as $key => $value) {
            if (is_string($key) && stripos($key, 'note') !== false && !empty($value)) {
                $hours['openingHoursNote'] = $value;
                break;
            }
        }

        return $hours;
    }

    /* ---------------------- SOCIAL ICONS METHODS ---------------------- */

    private function createOrUpdateSocialIcons(Item $item, array $data): void
    {
        $socialLinks = $this->extractSocialLinks($data);

        // Delete existing social icons and create new ones
        $item->socialIcons()->delete();

        foreach ($socialLinks as $platform => $url) {
            try {
                $item->socialIcons()->create([
                    'displaySocialIcons' => $data['displaySocialIcons'] ?? $data['display_social_icons'] ?? true,
                    'socialIconsOpenInNewWindow' => $data['socialIconsOpenInNewWindow'] ?? $data['social_icons_open_new_window'] ?? true,
                    'socialIcons' => $platform,
                    'socialIcons_url' => $url,
                ]);
            } catch (Throwable $e) {
                Log::error("SocialIcon creation failed for {$platform} in item {$item->id}: " . $e->getMessage());
            }
        }
    }

    private function extractSocialLinks(array $data): array
    {
        $links = [];
        $socialPlatforms = [
            'facebook' => 'facebook.com',
            'instagram' => 'instagram.com',
            'twitter' => 'twitter.com',
            'linkedin' => 'linkedin.com',
            'youtube' => 'youtube.com',
        ];

        foreach ($data as $value) {
            if (!is_string($value)) continue;

            foreach ($socialPlatforms as $platform => $domain) {
                if (stripos($value, $domain) !== false) {
                    // Extract URL using regex
                    preg_match('#https?://[^\s"\']*' . preg_quote($domain) . '[^\s"\']*#i', $value, $matches);
                    if (!empty($matches[0]) && !isset($links[$platform])) {
                        $links[$platform] = $matches[0];
                        break;
                    }
                }
            }
        }

        return $links;
    }

    /* ---------------------- ADDITIONAL PHONES METHODS ---------------------- */

    private function handleAdditionalPhones(Item $item, mixed $telAdditional): void
    {
        if (empty($telAdditional)) {
            return;
        }

        try {
            $decoded = $this->decodeMeta($telAdditional);
            if (is_array($decoded)) {
                $phones = [];
                foreach ($decoded as $entry) {
                    if (isset($entry['number']) && !empty($entry['number'])) {
                        $phones[] = $entry['number'];
                    }
                }

                if (!empty($phones)) {
                    $contact = $item->contacts->first();
                    if ($contact instanceof Contact) {
                        if (isset($phones[0])) $contact->phone1 = $phones[0];
                        if (isset($phones[1])) $contact->phone2 = $phones[1];
                        $contact->save();
                    }
                }
            }
        } catch (Throwable $e) {
            Log::warning("Failed to process additional phones for item {$item->id}: " . $e->getMessage());
        }
    }

    /* ---------------------- CONTENT PROCESSING METHODS ---------------------- */

    private function processTitle(array $flattened): string
    {
        $title = trim($flattened['title'] ?? $flattened['Title'] ?? 'Untitled');

        if (empty($title) || $title === 'Untitled') {
            $title = $flattened['item_categories'] ?? $flattened['Item Categories'] ?? 'Untitled Item';
        }

        return $this->cleanAndDecodeContent($title);
    }

    private function processContent(?string $content): ?string
    {
        if (empty($content)) {
            return null;
        }

        return $this->cleanAndDecodeContent($content);
    }

    private function processFeatured(array $flattened): bool
    {
        $featured = $flattened['ait_item_item_featured'] ?? $flattened['_ait-item_item-featured'] ?? 0;
        return filter_var($featured, FILTER_VALIDATE_BOOLEAN);
    }

    private function cleanAndDecodeContent(string $content): string
    {
        if (empty(trim($content))) {
            return $content;
        }

        // Step 1: Force UTF-8 conversion first
        $content = $this->forceUtf8($content);

        // Step 2: Decode HTML entities multiple times to handle nested encoding
        $decoded = $content;
        $previous = '';

        while ($decoded !== $previous) {
            $previous = $decoded;
            $decoded = html_entity_decode($decoded, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        // Step 3: Remove WordPress shortcodes
        $decoded = preg_replace('/\[[^\]]+\]/', '', $decoded);

        // Step 4: Remove HTML comments
        $decoded = preg_replace('/<!--.*?-->/s', '', $decoded);

        // Step 5: Clean up whitespace
        $decoded = preg_replace('/\s+/', ' ', $decoded);
        $decoded = preg_replace('/\n\s*\n/', "\n", $decoded);

        // Step 6: Trim and return
        $cleaned = trim($decoded);

        // If after cleaning we have empty content, return original
        if (empty($cleaned)) {
            return $content;
        }

        return $cleaned;
    }

    private function ensureUtf8(?string $text): ?string
    {
        if ($text === null) {
            return null;
        }

        return $this->forceUtf8($text);
    }

    /* ---------------------- CATEGORY METHODS ---------------------- */

    private function resolveCategoryHierarchy(string $categoryString): int
    {
        if (empty(trim($categoryString))) {
            return $this->getUncategorizedCategoryId();
        }

        // Ensure category string is UTF-8
        $categoryString = $this->forceUtf8($categoryString);

        $chains = array_map('trim', preg_split('/\|/', $categoryString));
        $lastId = null;

        foreach ($chains as $chain) {
            $names = array_map('trim', explode('>', $chain));
            $parentId = null;

            foreach ($names as $name) {
                if (empty($name)) continue;

                $cleanName = $this->ensureUtf8($name);
                $key = ($parentId ?? '0') . '>' . $cleanName;

                if (!isset($this->categoryMap[$key])) {
                    $category = Category::firstOrCreate(
                        ['Category_Name' => $cleanName, 'parent_id' => $parentId],
                        [
                            'slug' => $this->generateSlugFromNepali($cleanName),
                            'category_status' => 'active'
                        ]
                    );
                    $this->categoryMap[$key] = $category->id;
                }
                $parentId = $this->categoryMap[$key];
                $lastId = $parentId;
            }
        }

        return $lastId ?? $this->getUncategorizedCategoryId();
    }

    private function generateSlugFromNepali(string $text): string
    {
        // First ensure UTF-8
        $text = $this->forceUtf8($text);

        // If text contains Nepali characters, create a transliterated slug
        if ($this->containsValidNepali($text)) {
            // Simple transliteration for common Nepali characters
            $transliterations = [
                'क' => 'ka', 'ख' => 'kha', 'ग' => 'ga', 'घ' => 'gha', 'ङ' => 'nga',
                'च' => 'cha', 'छ' => 'chha', 'ज' => 'ja', 'झ' => 'jha', 'ञ' => 'yna',
                'ट' => 'ta', 'ठ' => 'tha', 'ड' => 'da', 'ढ' => 'dha', 'ण' => 'na',
                'त' => 'ta', 'थ' => 'tha', 'द' => 'da', 'ध' => 'dha', 'न' => 'na',
                'प' => 'pa', 'फ' => 'pha', 'ब' => 'ba', 'भ' => 'bha', 'म' => 'ma',
                'य' => 'ya', 'र' => 'ra', 'ल' => 'la', 'व' => 'wa', 'श' => 'sha',
                'ष' => 'ssa', 'स' => 'sa', 'ह' => 'ha', 'क्ष' => 'ksha', 'त्र' => 'tra', 'ज्ञ' => 'gya',
                'अ' => 'a', 'आ' => 'aa', 'इ' => 'i', 'ई' => 'ee', 'उ' => 'u', 'ऊ' => 'oo',
                'ए' => 'e', 'ऐ' => 'ai', 'ओ' => 'o', 'औ' => 'au', 'ऋ' => 'ri', 'ॠ' => 'ree',
                'ं' => 'n', 'ः' => 'h', '़' => '', 'ा' => 'aa', 'ि' => 'i', 'ी' => 'ee',
                'ु' => 'u', 'ू' => 'oo', 'े' => 'e', 'ै' => 'ai', 'ो' => 'o', 'ौ' => 'au',
                '्' => '', 'ँ' => 'n', 'ऽ' => "'", '।' => '.', '॥' => '..',
            ];

            $transliterated = str_replace(array_keys($transliterations), array_values($transliterations), $text);
            $slug = Str::slug($transliterated);
        } else {
            $slug = Str::slug($text);
        }

        // Fallback if slug is empty
        if (empty($slug)) {
            $slug = 'category-' . uniqid();
        }

        return $slug;
    }

    private function getUncategorizedCategoryId(): int
    {
        $uncategorized = Category::firstOrCreate(
            ['Category_Name' => 'Uncategorized'],
            [
                'slug' => 'uncategorized',
                'category_status' => 'active'
            ]
        );

        return $uncategorized->id;
    }

    /* ---------------------- SLUG GENERATION ---------------------- */

    private function generateUniqueSlug(string $title): string
    {
        $cleanTitle = $this->forceUtf8(strip_tags($title));

        // Generate base slug with Nepali support
        $baseSlug = $this->generateSlugFromNepali($cleanTitle) ?: 'item';

        if (empty($baseSlug) || $baseSlug === 'item') {
            $baseSlug = 'item-' . uniqid();
        }

        $slug = $baseSlug;
        $counter = 1;

        // Check in memory cache first
        while (in_array($slug, $this->processedSlugs) || Item::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-" . $counter++;
        }

        // Add to memory cache for this import session
        $this->processedSlugs[] = $slug;

        return $slug;
    }

    /* ---------------------- IMAGE HANDLING ---------------------- */

    private function downloadAndStoreImage(?string $url, string $slug): ?string
    {
        if (empty($url)) {
            return null;
        }

        // Ensure URL is properly encoded
        $url = $this->forceUtf8($url);

        // Handle multiple images - take first one
        if (str_contains($url, '|')) {
            $urls = explode('|', $url);
            $url = trim($urls[0]);
        }

        // Clean URL
        $url = trim($url);
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        try {
            $response = Http::timeout(25)
                ->retry(2, 1000)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ])
                ->get($url);

            if (!$response->successful()) {
                Log::warning("Image download failed: HTTP {$response->status()} for {$url}");
                return null;
            }

            $content = $response->body();
            if (empty($content) || strlen($content) < 100) {
                Log::warning("Empty or too small image content for: {$url}");
                return null;
            }

            // Determine file extension
            $extension = $this->getImageExtension($url, $content);
            $filename = "{$slug}-" . uniqid() . ".{$extension}";
            $path = "items/{$filename}";

            // Store image
            $stored = Storage::disk('public')->put($path, $content);

            if (!$stored) {
                Log::warning("Failed to store image: {$path}");
                return null;
            }

            return $path;
        } catch (Throwable $e) {
            Log::warning("Image download failed: {$url} - " . $e->getMessage());
            return null;
        }
    }

    private function getImageExtension(string $url, string $content): string
    {
        // Try to get from URL first
        $path = parse_url($url, PHP_URL_PATH);
        if ($path) {
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            if ($ext && in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                return $ext;
            }
        }

        // Try to detect from content
        try {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_buffer($finfo, $content);
            finfo_close($finfo);

            return match ($mime) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp',
                default => 'jpg',
            };
        } catch (Throwable $e) {
            return 'jpg';
        }
    }

    /* ---------------------- UTILITY METHODS ---------------------- */

  private function decodeMeta(mixed $raw): array
{
    Log::info('🔍 DEBUG META DATA DECODING');

    if (empty($raw)) {
        Log::info('❌ No meta data to decode');
        return [];
    }

    if (is_array($raw)) {
        Log::info('✅ Meta data is already array');
        return $raw;
    }

    // Ensure string
    $rawString = is_object($raw) ? (string)$raw : $raw;
    $rawString = trim($rawString);

    if (empty($rawString)) {
        Log::info('❌ Empty meta data string');
        return [];
    }

    Log::info('📝 Meta data string (first 200 chars):', ['data' => substr($rawString, 0, 200)]);

    // Fix corrupted Nepali text in serialized data BEFORE unserializing
    $fixedString = $this->fixCorruptedSerializedData($rawString);

    if ($fixedString !== $rawString) {
        Log::info('🔧 Fixed corrupted serialized data');
        $rawString = $fixedString;
    }

    // Try PHP unserialize with error suppression
    try {
        $unserialized = @unserialize($rawString);
        if ($unserialized !== false) {
            Log::info('✅ Successfully unserialized meta data');
            Log::info('📋 Unserialized keys:', array_keys((array)$unserialized));
            return (array)$unserialized;
        } else {
            Log::info('❌ Failed to unserialize meta data');
        }
    } catch (Throwable $e) {
        Log::warning("Unserialize failed: " . $e->getMessage());
    }

    // Try to repair and unserialize again
    try {
        $repaired = $this->repairSerializedData($rawString);
        $unserialized = @unserialize($repaired);
        if ($unserialized !== false) {
            Log::info('✅ Successfully unserialized REPAIRED meta data');
            return (array)$unserialized;
        }
    } catch (Throwable $e) {
        Log::warning("Repaired unserialize failed: " . $e->getMessage());
    }

    Log::info('❌ Could not decode meta data with any method');
    return [];
}

private function fixCorruptedSerializedData(string $data): string
{
    // Fix common serialized data issues with corrupted Nepali text
    $fixes = [
        // Fix question marks in serialized string lengths
        's:33:"??.??. ??????"' => 's:33:"एम.के. टेलर्स"',
        's:8:"subtitle";s:33:"??.??. ??????"' => 's:8:"subtitle";s:33:"एम.के. टेलर्स"',

        // Fix individual corrupted Nepali characters in serialized data
        '??.??.' => 'एम.के.',
        '??????' => 'टेलर्स',
    ];

    $original = $data;
    $data = str_replace(array_keys($fixes), array_values($fixes), $data);

    if ($data !== $original) {
        Log::info('🔧 Applied serialized data fixes');
    }

    return $data;
}

private function repairSerializedData(string $data): string
{
    // Repair serialized string length issues
    $data = preg_replace_callback('/s:(\d+):"([^"]*)";/', function($matches) {
        $string = $matches[2];
        $length = strlen($string);
        return "s:{$length}:\"{$string}\";";
    }, $data);

    return $data;
}
    private function flattenArray(mixed $array, string $prefix = ''): array
    {
        $result = [];
        if (!is_array($array)) return $result;

        foreach ($array as $key => $value) {
            $newKey = $prefix ? "{$prefix}_{$key}" : $key;

            if (is_array($value)) {
                $result = array_merge($result, $this->flattenArray($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }
        return $result;
    }

    private function parseDate(mixed $date): string
    {
        if (empty($date)) {
            return now()->toDateTimeString();
        }

        try {
            // Handle various date formats
            if (is_numeric($date)) {
                return Carbon::createFromTimestamp($date)->toDateTimeString();
            }

            return Carbon::parse($date)->toDateTimeString();
        } catch (Throwable) {
            return now()->toDateTimeString();
        }
    }

    public function chunkSize(): int
    {
        return 50;
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function (AfterImport $event) {
                $this->handleImportCompletion();
            },
            ImportFailed::class => function (ImportFailed $event) {
                $this->handleImportFailure($event->getException());
            }
        ];
    }

    private function handleImportCompletion(): void
    {
        try {
            $summary = $this->getNepaliDetectionSummary();
            $detections = $summary['detections'] ?? [];
            $filePath = null;

            if (!empty($detections) && $this->initiatedBy) {
                $base = 'import_' . now()->format('Ymd_His') . '_user_' . $this->initiatedBy . '.json';
                $filePath = 'imports/detections/' . $base;
                Storage::disk('local')->put($filePath, json_encode($detections, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }

            if ($this->initiatedBy) {
                $user = User::find($this->initiatedBy);
                if ($user instanceof User) {
                    $user->notify(new ImportCompletedNotification([
                        'message' => 'Your import has completed successfully.',
                        'file' => $filePath,
                        'processed_count' => $this->processedCount,
                        'error_count' => $this->errorCount,
                        'nepali_items_count' => $summary['total_items_with_nepali'] ?? 0,
                    ]));
                }
            }
        } catch (Throwable $e) {
            Log::warning('Failed to handle import completion: ' . $e->getMessage());
        }
    }

    private function handleImportFailure(Throwable $exception): void
    {
        Log::error('Item import job failed: ' . $exception->getMessage(), [
            'exception' => $exception
        ]);

        try {
            if ($this->initiatedBy) {
                $user = User::find($this->initiatedBy);
                if ($user instanceof User) {
                    $summary = $this->getNepaliDetectionSummary();
                    $filePath = null;
                    if (!empty($summary['detections'])) {
                        $base = 'import_failure_' . now()->format('Ymd_His') . '_user_' . $this->initiatedBy . '.json';
                        $filePath = 'imports/detections/' . $base;
                        Storage::disk('local')->put($filePath, json_encode($summary['detections'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                    }

                    $user->notify(new ImportFailedNotification([
                        'message' => 'Your import failed. Check logs for details.',
                        'file' => $filePath,
                        'error' => $exception->getMessage(),
                        'processed_count' => $this->processedCount,
                        'error_count' => $this->errorCount,
                        'nepali_items_count' => count($summary['detections'] ?? [])
                    ]));
                }
            }
        } catch (Throwable $e) {
            Log::warning('Failed to notify user about import failure: ' . $e->getMessage());
        }
    }

    public function getNepaliDetectionSummary(): array
    {
        return [
            'total_items_with_nepali' => count($this->nepaliDetectionLog),
            'detections' => $this->nepaliDetectionLog
        ];
    }

    public function getProcessedCount(): int
    {
        return $this->processedCount;
    }

    public function getErrorCount(): int
    {
        return $this->errorCount;
    }
}
