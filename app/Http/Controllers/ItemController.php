<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Gallery;
use App\Models\Item;
use App\Models\OpeningTime;
use App\Models\SocialIcon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Exports\ItemsExport;
use App\Imports\ItemImport;
use App\Jobs\ImportItemsJob;
use App\Models\Banner;
use App\Models\User;
use App\Models\Contacts;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Excel as ExcelType;
use Symfony\Component\HttpFoundation\JsonResponse;

class ItemController extends Controller
{
   public function index(Request $request)
{
    $user = Auth::user();
    $query = Item::query();

    // Apply filters
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }
    if ($request->filled('title')) {
        $query->where('title', 'like', '%' . $request->title . '%');
    }
    if ($request->filled('subtitle')) {
        $query->where('subtitle', 'like', '%' . $request->subtitle . '%');
    }
    if ($request->filled('item_featured')) {
        $query->where('item_featured', $request->item_featured);
    }
    if ($request->filled('collection_date')) {
        $query->whereDate('collection_date', $request->collection_date);
    }
    if ($request->filled('search')) {
        $searchTerm = $request->search;
        $query->where(function($q) use ($searchTerm) {
            $q->where('title', 'like', '%' . $searchTerm . '%')
              ->orWhere('subtitle', 'like', '%' . $searchTerm . '%')
              ->orWhereHas('category', function($q) use ($searchTerm) {
                  $q->where('Category_Name', 'like', '%' . $searchTerm . '%');
              })
              ->orWhereHas('contacts', function($q) use ($searchTerm) {
                  $q->where('telephone', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%');
              });
        });
    }

    // Use pagination with 15 items per page
    $items = $query->with(['category.parent', 'contacts'])
                   ->orderBy('created_at', 'asc')
                   ->paginate(15);

    $categories = Category::with('children')->whereNull('parent_id')->get();

    return view('admin.items.index', compact('user', 'items', 'categories'));
}
  public function search(Request $request)
    {
        $keyword = $request->get('keyword');
        $category = $request->get('category');
        $location = $request->get('location');

        // Start query
        $query = Item::query()->orderBy('created_at', 'desc');

        // Keyword search (title, subtitle, content)
        if (!empty($keyword)) {
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'LIKE', "%{$keyword}%")
                  ->orWhere('subtitle', 'LIKE', "%{$keyword}%")
                  ->orWhere('content', 'LIKE', "%{$keyword}%");
            });
        }

        // Category filter
        if (!empty($category)) {
            $query->where('category_id', $category);
        }

        // Location filter
        if (!empty($location)) {
            $query->where('item_locations', 'LIKE', "%{$location}%");
        }

        // Get results with pagination
        $items = $query->orderBy('item_featured', 'desc')
                      ->orderBy('created_at', 'desc')
                      ->paginate(12)
                      ->appends($request->all());

        // Get categories for filter dropdown
        $categories = Category::orderBy('created_at', 'desc')
                            ->orderBy('Category_Name')
                            ->get();

        return view('web.items.search-results', compact('items', 'categories', 'keyword', 'category', 'location'));
    }
    public function create()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return view('admin.items.add', compact('categories'));
    }

    public function store(Request $request)
{
    try {
        // 1️⃣ Validation
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'item_featured' => 'nullable|string|max:255',
            'collection_date' => 'nullable|date',

            'category_id' => 'nullable|exists:categories,id',
            'category_name' => 'nullable|string|max:255',
            'child_category_name' => 'nullable|string|max:255',

            'author_username' => 'nullable|string|max:255',
            'author_email' => 'nullable|email|max:255',
            'author_first_name' => 'nullable|string|max:255',
            'author_last_name' => 'nullable|string|max:255',

            'telephone' => 'nullable|string|max:255',
            'phone1' => 'nullable|string|max:255',
            'phone2' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'showemail' => 'sometimes|boolean',
            'web' => 'nullable|url|max:255',
            'webLinkLabel' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|string|max:20',
            'longitude' => 'nullable|string|max:20',
            'streetview' => 'nullable|string|max:255',

            'displayOpeningHours' => 'sometimes|boolean',
            'openingHoursMonday' => 'nullable|string|max:255',
            'openingHoursTuesday' => 'nullable|string|max:255',
            'openingHoursWednesday' => 'nullable|string|max:255',
            'openingHoursThursday' => 'nullable|string|max:255',
            'openingHoursFriday' => 'nullable|string|max:255',
            'openingHoursSaturday' => 'nullable|string|max:255',
            'openingHoursSunday' => 'nullable|string|max:255',
            'openingHoursNote' => 'nullable|string',

            'displaySocialIcons' => 'sometimes|boolean',
            'socialIconsOpenInNewWindow' => 'sometimes|boolean',
            'socialIcons' => 'nullable|string|max:255',
            'socialIcons_url' => 'nullable|url|max:255',

            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'displayGallery' => 'sometimes|boolean',
            'gallery' => 'nullable|array',
            'gallery.*' => 'nullable|image|mimes:png,jpg,jpeg,gif,svg|max:2048',
        ]);

        DB::beginTransaction();

        // 2️⃣ Handle category hierarchy
        $categoryId = $validated['category_id'] ?? null;

        if (!empty($validated['category_name'])) {
            $category = Category::firstOrCreate(['Category_Name' => $validated['category_name']]);
            $categoryId = $category->id;
        }

        if (!empty($validated['child_category_name'])) {
            $childCategory = Category::firstOrCreate([
                'Category_Name' => $validated['child_category_name'],
                'parent_id' => $categoryId
            ]);
            $categoryId = $childCategory->id;
        }

        // 3️⃣ Generate unique slug
        $slug = Str::slug($validated['title']);
        $baseSlug = $slug;
        $count = 1;

        while (Item::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }

        // 4️⃣ Generate permalink dynamically
        $permalink = url('/items/' . $slug);

        // 5️⃣ Handle main image
        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('items/images', 'public')
            : null;

        // 6️⃣ Create item
        $item = Item::create([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'content' => $validated['content'] ?? null,
            'item_featured' => $validated['item_featured'] ?? null,
            'collection_date' => $validated['collection_date'] ?? null,
            'slug' => $slug,
            'permalink' => $permalink,
            'image' => $imagePath,
            'category_id' => $categoryId,
            'author_username' => $validated['author_username'] ?? null,
            'author_email' => $validated['author_email'] ?? null,
            'author_first_name' => $validated['author_first_name'] ?? null,
            'author_last_name' => $validated['author_last_name'] ?? null,
        ]);

        // 7️⃣ Save Contact Info
        $item->contacts()->create([
            'telephone' => $validated['telephone'] ?? null,
            'phone1' => $validated['phone1'] ?? null,
            'phone2' => $validated['phone2'] ?? null,
            'email' => $validated['email'] ?? null,
            'showemail' => $request->boolean('showemail'),
            'web' => $validated['web'] ?? null,
            'webLinkLabel' => $validated['webLinkLabel'] ?? null,
            'address' => $validated['address'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'streetview' => $validated['streetview'] ?? null,
        ]);

        // 8️⃣ Opening Hours
        $item->openingTimes()->create([
            'display_opening_hours' => $request->boolean('displayOpeningHours'),
            'openingHoursMonday' => $validated['openingHoursMonday'] ?? null,
            'openingHoursTuesday' => $validated['openingHoursTuesday'] ?? null,
            'openingHoursWednesday' => $validated['openingHoursWednesday'] ?? null,
            'openingHoursThursday' => $validated['openingHoursThursday'] ?? null,
            'openingHoursFriday' => $validated['openingHoursFriday'] ?? null,
            'openingHoursSaturday' => $validated['openingHoursSaturday'] ?? null,
            'openingHoursSunday' => $validated['openingHoursSunday'] ?? null,
            'openingHoursNote' => $validated['openingHoursNote'] ?? null,
        ]);

        // 9️⃣ Social Icons
        if (!empty($validated['socialIcons']) && !empty($validated['socialIcons_url'])) {
            $item->socialIcons()->create([
                'displaySocialIcons' => $request->boolean('displaySocialIcons'),
                'openInNewWindow' => $request->boolean('socialIconsOpenInNewWindow'),
                'socialIcons' => $validated['socialIcons'],
                'socialIcons_url' => $validated['socialIcons_url'],
            ]);
        }

        // 🔟 Gallery
        if ($request->hasFile('gallery')) {
            $fileNames = [];
            foreach ($request->file('gallery') as $file) {
                $fileName = $slug . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('gallery', $fileName, 'public');
                $fileNames[] = 'gallery/' . $fileName;
            }

            Gallery::create([
                'item_id' => $item->id,
                'gallery' => json_encode($fileNames), // Store as JSON array
                'display_gallery' => $request->boolean('displayGallery', true),
            ]);
        }

        DB::commit();

        return redirect()->route('home')->with('success', 'Item created successfully with dynamic permalink and slug!');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('danger', 'Error: ' . $e->getMessage())->withInput();
    }
}
    public function edit($encryptedId)
{
        $id = decrypt($encryptedId); // 🔓 Decrypt the ID
    $item = Item::with(['contacts', 'openingTimes', 'socialIcons', 'galleries'])->findOrFail($id);
    $categories = Category::with('children')->whereNull('parent_id')->get();

    return view('admin.items.edit', compact('item', 'categories','id'));
}


public function update(Request $request, $encryptedId)
{
    $id = decrypt($encryptedId);
    $item = Item::findOrFail($id);

    try {
        // 1️⃣ Validation
        $request->validate([
            'reference_id' => 'nullable|integer',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'item_featured' => 'nullable|string|max:255',
            'collection_date' => 'nullable|date',
            'permalink' => 'nullable|url|max:255',
            'slug' => 'nullable|string|max:255',

            'category_id' => 'required|exists:categories,id',
            'category_name' => 'nullable|string|max:255',
            'child_category_name' => 'nullable|string|max:255',

            'author_username' => 'nullable|string|max:255',
            'author_email' => 'nullable|email|max:255',
            'author_first_name' => 'nullable|string|max:255',
            'author_last_name' => 'nullable|string|max:255',
            'parent' => 'nullable|string|max:255',
            'parent_slug' => 'nullable|string|max:255',

            'telephone' => 'nullable|string|max:255',
            'phone1' => 'nullable|string|max:255',
            'phone2' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'showemail' => 'sometimes|boolean',
            'contactOwnerBtn' => 'sometimes|boolean',
            'web' => 'nullable|url|max:255',
            'webLinkLabel' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|string|max:20',
            'longitude' => 'nullable|string|max:20',
            'streetview' => 'nullable|string|max:255',
            'swheading' => 'nullable|string|max:255',
            'swpitch' => 'nullable|string|max:255',
            'swzoom' => 'nullable|string|max:255',

            'displayOpeningHours' => 'sometimes|boolean',
            'openingHoursMonday' => 'nullable|string|max:255',
            'openingHoursTuesday' => 'nullable|string|max:255',
            'openingHoursWednesday' => 'nullable|string|max:255',
            'openingHoursThursday' => 'nullable|string|max:255',
            'openingHoursFriday' => 'nullable|string|max:255',
            'openingHoursSaturday' => 'nullable|string|max:255',
            'openingHoursSunday' => 'nullable|string|max:255',
            'openingHoursNote' => 'nullable|string',

            'displaySocialIcons' => 'sometimes|boolean',
            'socialIconsOpenInNewWindow' => 'sometimes|boolean',
            'socialIcons' => 'nullable|string|max:255',
            'socialIcons_url' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'displayGallery' => 'sometimes|boolean',
            'gallery' => 'nullable|array',
            'gallery.*' => 'nullable|image|mimes:png,jpg,jpeg,gif,svg|max:2048',
        ]);

        // 2️⃣ Handle category selection/creation
        $categoryId = $request->category_id;
        if ($request->filled('category_name')) {
            $category = Category::firstOrCreate(['Category_Name' => $request->category_name]);
            $categoryId = $category->id;
        }
        if ($request->filled('child_category_name')) {
            $childCategory = Category::firstOrCreate([
                'Category_Name' => $request->child_category_name,
                'parent_id' => $categoryId
            ]);
            $categoryId = $childCategory->id;
        }

        // 3️⃣ Handle slug & permalink
        $slug = $request->slug ?: Str::slug($request->title);
        $permalink = $request->permalink ?: url('/items/' . $slug);

        // 4️⃣ Update main image
        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $item->image = $request->file('image')->store('items/images', 'public');
        }

        // 5️⃣ Update main item
        $item->update([
            'reference_id' => $request->reference_id,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'content' => $request->input('content'),
            'item_featured' => $request->item_featured,
            'collection_date' => $request->collection_date,
            'slug' => $slug,
            'permalink' => $permalink,
            'category_id' => $categoryId,
            'author_username' => $request->author_username,
            'author_email' => $request->author_email,
            'author_first_name' => $request->author_first_name,
            'author_last_name' => $request->author_last_name,
            'parent' => $request->parent,
            'parent_slug' => $request->parent_slug,
        ]);

        // 6️⃣ Update or create contacts
        $item->contacts()->updateOrCreate(
            ['item_id' => $item->id],
            [
                'telephone' => $request->telephone,
                'phone1' => $request->phone1,
                'phone2' => $request->phone2,
                'email' => $request->email,
                'showemail' => $request->boolean('showemail'),
                'contactOwnerBtn' => $request->boolean('contactOwnerBtn'),
                'web' => $request->web,
                'webLinkLabel' => $request->webLinkLabel,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'streetview' => $request->streetview,
                'swheading' => $request->swheading,
                'swpitch' => $request->swpitch,
                'swzoom' => $request->swzoom,
            ]
        );

        // 7️⃣ Update or create opening hours
        $item->openingTimes()->updateOrCreate(
            ['item_id' => $item->id],
            [
                'display_opening_hours' => $request->boolean('displayOpeningHours'),
                'openingHoursMonday' => $request->openingHoursMonday,
                'openingHoursTuesday' => $request->openingHoursTuesday,
                'openingHoursWednesday' => $request->openingHoursWednesday,
                'openingHoursThursday' => $request->openingHoursThursday,
                'openingHoursFriday' => $request->openingHoursFriday,
                'openingHoursSaturday' => $request->openingHoursSaturday,
                'openingHoursSunday' => $request->openingHoursSunday,
                'openingHoursNote' => $request->openingHoursNote,
            ]
        );

        // 8️⃣ Update social icons
        if ($request->filled('socialIcons') && $request->filled('socialIcons_url')) {
            $item->socialIcons()->updateOrCreate(
                ['item_id' => $item->id],
                [
                    'displaySocialIcons' => $request->displaySocialIcons ?? 1,
                    'openInNewWindow' => $request->socialIconsOpenInNewWindow ?? 1,
                    'socialIcons' => $request->socialIcons,
                    'socialIcons_url' => $request->socialIcons_url,
                ]
            );
        }

        // 9️⃣ Update gallery
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $fileName = Str::slug($request->title) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('gallery', $fileName, 'public');

                Gallery::create([
                    'item_id' => $item->id,
                    'gallery' => $fileName,
                    'display_gallery' => $request->boolean('displayGallery', true),
                ]);
            }
        }

        return redirect()->route('home')->with('success', 'Item updated successfully!');

    } catch (\Exception $e) {
        return redirect()->back()->with('danger', 'Error: ' . $e->getMessage());
    }
}

    public function view($encryptedId)
{
    try {
        $id = decrypt($encryptedId);

        $item = Item::with([
            'category.parent',
            'contacts',
            'openingTimes',
            'socialIcons',
            'galleries'
        ])->findOrFail($id);

        // Prepare statistics for the view
        $stats = [
            'total_gallery_images' => $item->galleries->sum(function($gallery) {
                return is_array($gallery->gallery) ? count($gallery->gallery) : 1;
            }),
            'social_links_count' => $item->socialIcons->count(),
            'has_opening_hours' => $item->openingTimes ? true : false,
            'has_contact_info' => $item->contacts ? true : false,
            'is_featured' => $item->item_featured,
        ];

        return view('admin.items.view', compact('item', 'id', 'stats'));

    } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
        abort(404, 'Item not found');
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        abort(404, 'Item not found');
    }
}

    public function destroy($encryptedId)
{
    $id = decrypt($encryptedId); // Decrypt the ID

    $item = Item::with(['contacts', 'openingTimes', 'socialIcons', 'galleries'])->findOrFail($id);

    // Delete main image
    if ($item->image) {
        Storage::disk('public')->delete($item->image);
    }

    // Delete gallery images
    // if ($item->galleries) {
    //     foreach ($item->galleries as $gallery) {
    //         $images = is_array($gallery->gallery) ? $gallery->gallery : json_decode($gallery->gallery, true);
    //         if ($images) {
    //             foreach ($images as $image) {
    //                 Storage::disk('public')->delete($image);
    //             }
    //         }
    //     }
    // }

    // Delete item and its relations
    $item->delete();

    return redirect()->route('item.index')
        ->with('success', 'Item deleted successfully!');
}


   public function deleteSelected(Request $request)
{
    $ids = $request->input('ids');

    if (!empty($ids)) {
        $items = Item::with('galleries')->whereIn('id', $ids)->get();

        foreach ($items as $item) {
            // Delete main image
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }

            // Delete gallery images
            if ($item->galleries) {
                foreach ($item->galleries as $gallery) {
                    $images = is_array($gallery->gallery) ? $gallery->gallery : json_decode($gallery->gallery, true);
                    if ($images) {
                        foreach ($images as $image) {
                            Storage::disk('public')->delete($image);
                        }
                    }
                }
            }
        }

        Item::whereIn('id', $ids)->delete();

        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false], 400);
}

    public function show()
    {
        $user = Auth::user();
        return view('admin.items.profile.profile', compact('user'));
    }

public function profilesetting()
{
    // Use authenticated user instead of undefined $id
    $user = Auth::user();

    // Safely load permissions and roles from config with a fallback to empty arrays
    // (If you store these in DB, replace with appropriate queries)
    $permissions = config('role_permissions.permissions', []);
    $roles = config('role_permissions.roles', []);
    $userPermissions = $user->permissions ?? [];

    return view('admin.items.profile.settings', compact('user', 'permissions', 'userPermissions', 'roles'));
}
public function userprofileshow()
{
    $user = Auth::user();
    return view('web.items.profile.profile', compact('user'));
}
public function import(Request $request)
{
    Log::info('=== IMPORT METHOD CALLED ===');
    Log::info('Request received:', $request->all());

    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
    ]);

    Log::info('File validation passed');

    try {
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();

        Log::info('Starting import process', [
            'file_name' => $originalName,
            'file_size' => $file->getSize(),
            'file_mime' => $file->getMimeType(),
            'user_id' => Auth::id()
        ]);

        // Test file reading
        $filePath = $file->getRealPath();
        Log::info('File real path: ' . $filePath);
        Log::info('File exists: ' . (file_exists($filePath) ? 'YES' : 'NO'));

        // Test if we can read the file
        if (file_exists($filePath)) {
            $fileSize = filesize($filePath);
            Log::info('File size: ' . $fileSize . ' bytes');
        }

        // Import with queue
        Log::info('Calling Excel::import...');
        Excel::import(new ItemImport(Auth::id()), $file);

        Log::info('=== EXCEL IMPORT COMPLETED - DATA SHOULD BE PROCESSING ===');

        return redirect()->route('item.index')
            ->with('success', 'Import started successfully! Items are being processed in the background.');

    } catch (\Exception $e) {
        Log::error('IMPORT FAILED WITH EXCEPTION:', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->route('item.index')
            ->with('error', 'Import failed: ' . $e->getMessage());
    }
}

   public function downloadSample()
    {
        $samplePath = storage_path('app/samples/items_import_sample.csv');

        if (!file_exists($samplePath)) {
            $this->createSampleFile($samplePath);
        }

        return response()->download($samplePath, 'items_import_sample.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function createSampleFile(string $path): void
    {
        // Create directory if it doesn't exist
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $sampleData = [
            [
                'id', 'Item Categories', 'Title', 'Subtitle', 'Content',
                'Item Locations', 'ait-latitude', 'ait-longitude',
                '_ait_item_item_data', '_ait-item_item-featured', 'Date',
                'Permalink', 'Image URL', 'Author Username', 'Author Email',
                'Author First Name', 'Author Last Name', 'Slug'
            ],
            [
                '5023',
                'Education>Government School|Education>Private School',
                'Shiva International Boarding Secondary School',
                'शिव अन्तर्राष्ट्रिय बोर्डिङ्ग माध्यमिक विद्यालय',
                'Shiva International Boarding Secondary School is the Best School of Saptari. We Provide Quality Education and Dynamic Career.',
                'Rajbiraj',
                '26.5269988',
                '86.7478174',
                'a:29:{s:8:"subtitle";s:96:"शिव अन्तर्राष्ट्रिय बोर्डिङ्ग माध्यमिक विद्यालय";s:12:"featuredItem";s:1:"0";s:10:"headerType";s:3:"map";s:11:"headerImage";s:0:"";s:12:"headerHeight";s:0:"";s:3:"map";a:7:{s:7:"address";s:55:"Shiva International Boarding Secondary School, Rajbiraj";s:8:"latitude";s:10:"26.5269988";s:9:"longitude";s:10:"86.7478174";s:10:"streetview";s:1:"0";s:9:"swheading";s:2:"90";s:7:"swpitch";s:1:"5";s:6:"swzoom";s:1:"1";}s:9:"telephone";s:0:"";s:19:"telephoneAdditional";a:2:{i:0;a:1:{s:6:"number";s:14:"+977 31-521139";}i:1;a:1:{s:6:"number";s:10:"9852821139";}}s:5:"email";s:17:"info@sibssrjb.com";s:9:"showEmail";s:1:"1";s:15:"contactOwnerBtn";s:1:"0";s:3:"web";s:24:"http://sibssrajbiraj.com";s:12:"webLinkLabel";s:18:"Visit Our Web Site";s:19:"displayOpeningHours";s:1:"1";s:18:"openingHoursMonday";s:7:"9AM-5PM";s:19:"openingHoursTuesday";s:7:"9AM-5PM";s:21:"openingHoursWednesday";s:7:"9AM-5PM";s:20:"openingHoursThursday";s:7:"9AM-5PM";s:18:"openingHoursFriday";s:7:"9AM-5PM";s:20:"openingHoursSaturday";s:1:"-";s:18:"openingHoursSunday";s:7:"9AM-5PM";s:16:"openingHoursNote";s:0:"";s:18:"displaySocialIcons";s:1:"1";s:26:"socialIconsOpenInNewWindow";s:1:"1";s:11:"socialIcons";s:0:"";s:14:"displayGallery";s:1:"0";s:7:"gallery";s:0:"";s:15:"displayFeatures";s:1:"0";s:8:"features";s:0:"";}',
                '0',
                '10-04-2017',
                'https://onlinesaptari.com/item/shiva-international-boarding-secondary-school',
                'http://onlinesaptari.com/wp-content/uploads/Sibs-Rajbiraj.jpg|http://onlinesaptari.com/wp-content/uploads/Online-Saptari-Logo.png|http://onlinesaptari.com/wp-content/uploads/Sanoj-Bishwash.jpg',
                'freelancerumesh',
                'nepalcomputercare@gmail.com',
                'Online',
                'Saptari',
                'shiva-international-boarding-secondary-school'
            ]
        ];

        $file = fopen($path, 'w');
        // Add UTF-8 BOM for Excel compatibility
        fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
        foreach ($sampleData as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
    }
public function home(Request $request)
{
    // --- Detect filters ---
    $keyword  = $request->keyword;
    $category = $request->category;
    $location = $request->location;

    $hasFilters = $keyword || $category || $location;

    // --- Base item query ---
    $query = Item::select('id', 'title', 'subtitle', 'image', 'slug', 'item_featured', 'created_at', 'views', 'category_id')
                ->with(['category:id,Category_Name,slug'])
                ->orderByDesc('item_featured')
                ->orderByDesc('created_at');

    // --- Apply filters ---
    if ($keyword) {
        $query->where(function($q) use ($keyword) {
            $q->where('title', 'LIKE', "%{$keyword}%")
              ->orWhere('subtitle', 'LIKE', "%{$keyword}%");
        });
    }

    if ($category) {
        $query->where('category_id', $category);
    }

    if ($location) {
        $query->where('item_locations', 'LIKE', "%{$location}%");
    }

    // --- Paginate items ---
    $items = $query->paginate(12)->appends($request->all());

    // --- Featured items strip ---
    $featuredItems = Item::select('id', 'title', 'image', 'slug', 'created_at')
                         ->where('item_featured', 1)
                         ->latest()
                         ->limit(10)
                         ->get();

    // --- Latest categories ---
    $latestCategories = Category::select('id', 'Category_Name', 'slug')
                                ->latest()
                                ->limit(8)
                                ->get();

    // --- Most searched items ---
    $currentPageIds = $items->pluck('id')->toArray();

    $mostSearchedItems = Item::select('id', 'title', 'image', 'slug', 'views')
                             ->whereNotIn('id', $currentPageIds)
                             ->orderByDesc('views')
                             ->limit(8)
                             ->get();

    // --- Active banners ---
    $banners = Banner::select('id', 'image', 'title', 'link', 'is_active')
                     ->where('is_active', true)
                     ->latest()
                     ->limit(5)
                     ->get();

    // --- Parent categories ---
    $parentCategories = Category::select('id', 'Category_Name', 'slug')
                                ->whereNull('parent_id')
                                ->limit(20)
                                ->get();

    // --- Total counts ---
    $totalItems = Item::count();
    $totalCategories = Category::count();

    return view('home', compact(
        'items',
        'latestCategories',
        'mostSearchedItems',
        'banners',
        'totalItems',
        'totalCategories',
        'parentCategories',
        'featuredItems',
        'hasFilters'
    ));
}

public function testweb()
{
    return view('web.items.index');
}

}


