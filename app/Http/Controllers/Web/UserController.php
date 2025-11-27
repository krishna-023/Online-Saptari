<?php

namespace App\Http\Controllers\Web;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Gallery;
use App\Models\Item;
use App\Models\OpeningTime;
use App\Models\SocialIcon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Banner;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use App\Models\Visitor;
use App\Models\VisitorAction;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    // public function index(Request $request)
    // {
    //     $user = Auth::user();
    //     $query = Item::query();

    //     if ($request->filled('category_id')) {
    //         $query->where('category_id', $request->category_id);
    //     }
    //     if ($request->filled('title')) {
    //         $query->where('title', 'like', '%' . $request->title . '%');
    //     }
    //     if ($request->filled('subtitle')) {
    //         $query->where('subtitle', 'like', '%' . $request->subtitle . '%');
    //     }
    //     if ($request->filled('item_featured')) {
    //         $query->where('item_featured', $request->item_featured);
    //     }
    //     if ($request->filled('collection_date')) {
    //         $query->whereDate('collection_date', $request->collection_date);
    //     }

    //     $items = $query->with(['category.parent', 'contacts'])->paginate(10);

    //     $categories = Category::with('children')->whereNull('parent_id')->get();

    //     return view('admin.items.index', compact('user', 'items', 'categories'));
    // }

    public function create()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return view('web.items.webadd', compact('categories'));
    }

public function itemstore(Request $request)
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
        $item->openingTime()->create([
            'displayOpeningHours' => $request->boolean('displayOpeningHours'),
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
            foreach ($request->file('gallery') as $file) {
                $fileName = $slug . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('gallery', $fileName, 'public');

                Gallery::create([
                    'item_id' => $item->id,
                    'gallery' => $fileName,
                    'display_gallery' => $request->boolean('displayGallery', true),
                ]);
            }
        }

        DB::commit();

        return redirect()->route('home')->with('success', 'Item created successfully with dynamic permalink and slug!');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('danger', 'Error: ' . $e->getMessage())->withInput();
    }
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
        $item->openingTime()->updateOrCreate(
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

    public function destroy($encryptedId)
{
    $id = decrypt($encryptedId); // Decrypt the ID

    $item = Item::with(['contacts', 'openingTime', 'socialIcons', 'galleries'])->findOrFail($id);

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
        $user = Auth::user();
        return view('admin.items.profile.settings', compact('user'));
    }

    public function profileupdate(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'username'=>'required|string|max:255',
            'email'=>'required|string|email|max:255|unique:users,email,'.$user->id,
            'password'=>'nullable|string|min:8|confirmed',
            'profile_picture'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->username;
        $user->email = $request->email;

        if($request->filled('password')){
            $user->password = bcrypt($request->password);
        }

        if($request->hasFile('profile_picture')){
            if($user->profile_picture){
                Storage::disk('public')->delete($user->profile_picture);
            }
            $user->profile_picture = $request->file('profile_picture')->store('profile_pictures','public');
        }

        //$user->save();
        return redirect()->route('item.profile')->with('status','Profile updated successfully!');
    }
   public function userview($slug)
{
    // Load item by slug (not ID)
    $item = Item::with([
        'contacts',     // Contacts
        'galleries',    // Gallery images
        'socialIcons',  // Social links
        'openingTimes',  // Opening hours
        'category'      // Category info
    ])->where('slug', $slug)->firstOrFail();

    // Increment view count
    $item->increment('views');

    $decodedTitle = html_entity_decode($item->title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $decodedSubtitle = $item->subtitle ? html_entity_decode($item->subtitle, ENT_QUOTES | ENT_HTML5, 'UTF-8') : null;
    $decodedContent = $item->content ? html_entity_decode($item->content, ENT_QUOTES | ENT_HTML5, 'UTF-8') : null;

    // Process gallery images - handle BOTH storage methods
    $allGalleryImages = $this->processGalleryImages($item->galleries);

    // Similar items: same category, exclude current
    $similarItems = Item::where('category_id', $item->category_id)
                        ->where('id', '!=', $item->id)
                        ->take(6)
                        ->get();

    // Related items: same parent category, exclude current
    $relatedItems = Item::whereHas('category', function($query) use ($item) {
                            if ($item->category && $item->category->parent_id) {
                                $query->where('parent_id', $item->category->parent_id);
                            }
                        })
                        ->where('id', '!=', $item->id)
                        ->take(6)
                        ->get();

    return view('web.items.userview', compact(
        'item',
        'allGalleryImages',
        'similarItems',
        'relatedItems',
        'decodedTitle',
        'decodedSubtitle',
        'decodedContent'
    ));
}

/**
 * Process gallery images from BOTH storage methods:
 * 1. Individual records with image_url field
 * 2. Array storage in gallery field as JSON
 */
private function processGalleryImages($galleries)
{
    $allImages = [];

    if (!$galleries || $galleries->isEmpty()) {
        return $allImages;
    }

    foreach ($galleries as $gallery) {
        // Method 1: Check if it's individual record with image_url
        if (!empty($gallery->image_url)) {
            $imageUrl = $this->processImagePath($gallery->image_url);
            if ($imageUrl) {
                $allImages[] = [
                    'url' => $imageUrl,
                    'type' => 'individual',
                    'gallery_id' => $gallery->id,
                    'original_url' => $gallery->image_url
                ];
            }
        }

        // Method 2: Check if it's array storage in gallery field
        if (!empty($gallery->gallery)) {
            $arrayImages = $this->extractImagesFromGalleryField($gallery->gallery);
            foreach ($arrayImages as $imageUrl) {
                if ($imageUrl) {
                    $allImages[] = [
                        'url' => $imageUrl,
                        'type' => 'array',
                        'gallery_id' => $gallery->id
                    ];
                }
            }
        }

        // Method 3: Check if there's a local_path (downloaded images)
        if (!empty($gallery->local_path)) {
            $localImageUrl = $this->processImagePath($gallery->local_path);
            if ($localImageUrl && !in_array($localImageUrl, array_column($allImages, 'url'))) {
                $allImages[] = [
                    'url' => $localImageUrl,
                    'type' => 'local',
                    'gallery_id' => $gallery->id
                ];
            }
        }
    }

    return $allImages;
}

/**
 * Extract images from gallery field (JSON array or serialized)
 */
private function extractImagesFromGalleryField($galleryData)
{
    $images = [];

    if (empty($galleryData)) {
        return $images;
    }

    // Handle JSON string format
    if (is_string($galleryData)) {
        $decoded = json_decode($galleryData, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $images = $decoded;
        } else {
            // Handle pipe-separated string format
            $potentialImages = array_filter(array_map('trim', explode('|', $galleryData)));
            foreach ($potentialImages as $img) {
                if (!empty($img) && $this->isValidImagePath($img)) {
                    $images[] = $img;
                }
            }

            // If no pipe separation, try comma separation
            if (empty($images)) {
                $potentialImages = array_filter(array_map('trim', explode(',', $galleryData)));
                foreach ($potentialImages as $img) {
                    if (!empty($img) && $this->isValidImagePath($img)) {
                        $images[] = $img;
                    }
                }
            }
        }
    }
    // Handle array format
    elseif (is_array($galleryData)) {
        $images = $galleryData;
    }

    // Process image URLs
    $processedImages = [];
    foreach ($images as $imagePath) {
        $cleanPath = trim($imagePath);
        if (!empty($cleanPath)) {
            $processedUrl = $this->processImagePath($cleanPath);
            if ($processedUrl) {
                $processedImages[] = $processedUrl;
            }
        }
    }

    return $processedImages;
}

/**
 * Process image path and convert to proper URL
 */
private function processImagePath($imagePath)
{
    $cleanPath = trim($imagePath);

    if (empty($cleanPath)) {
        return null;
    }

    // If it's already a full URL, return as is
    if (Str::startsWith($cleanPath, ['http://', 'https://'])) {
        return $cleanPath;
    }

    // Handle storage paths
    if (Str::startsWith($cleanPath, 'storage/')) {
        return asset($cleanPath);
    }

    if (Str::startsWith($cleanPath, '/storage/')) {
        return asset($cleanPath);
    }

    // Handle gallery directory paths
    if (Str::startsWith($cleanPath, 'gallery/')) {
        return asset('storage/' . $cleanPath);
    }

    // Default: assume it's in storage
    return asset('storage/' . ltrim($cleanPath, '/'));
}

/**
 * Check if the path looks like a valid image
 */
private function isValidImagePath($path)
{
    $cleanPath = strtolower(trim($path));
    $imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp', '.svg'];

    foreach ($imageExtensions as $ext) {
        if (str_contains($cleanPath, $ext)) {
            return true;
        }
    }

    return false;
}
public function allCategories()
    {
        try {
            $categories = Category::with(['children', 'items'])
                ->whereNull('parent_id')
                ->orderBy('Category_Name')
                ->get();

            $categoriesWithStats = $categories->map(function($category) {
                $category->items_count = $category->items->count();
                $category->views_count = $category->items->sum('views') ?? 0;
                return $category;
            });

            return view('web.category.all', compact('categoriesWithStats'));

        } catch (\Exception $e) {
            Log::error('HomeController allCategories error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Unable to load categories.');
        }
    }

public function getRelatedItems(Request $request)
{
    $categoryId = $request->get('category_id');
    $page = $request->get('page', 1);
    $exclude = $request->get('exclude');
    $perPage = 8;

    $query = Item::with(['category', 'contacts'])
        ->where('id', '!=', $exclude)
        ->where('status', 'active');

    if ($categoryId) {
        $query->where('category_id', $categoryId);
    }

    $items = $query->orderBy('created_at', 'desc')
        ->paginate($perPage, ['*'], 'page', $page);

    // Return JSON response for API
    return response()->json([
        'items' => $items->items(),
        'hasMore' => $items->hasMorePages(),
        'currentPage' => $items->currentPage(),
        'total' => $items->total()
    ]);
}

// Add this method for category-based items listing
public function getItemsByCategory(Request $request)
{
    $categorySlug = $request->get('category');
    $page = $request->get('page', 1);
    $perPage = 12;

    $query = Item::with(['category', 'contacts']);

    if ($categorySlug) {
        $query->whereHas('category', function($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }

    $items = $query->orderBy('created_at', 'desc')
        ->paginate($perPage, ['*'], 'page', $page);

    return response()->json([
        'items' => $items->items(),
        'hasMore' => $items->hasMorePages(),
        'currentPage' => $items->currentPage(),
        'total' => $items->total()
    ]);
}


  public function categoryItems(Request $request, $slug)
{
    $page = $request->get('page', 1);
    $perPage = 12;

    // Get the category based on the slug from route parameter
    $category = Category::where('slug', $slug)->firstOrFail();

    $query = Item::with(['category', 'contacts'])
        ->where('category_id', $category->id); // Use the category ID from the slug

    $items = $query->orderBy('created_at', 'desc')
        ->paginate($perPage, ['*'], 'page', $page);

    // Remove the JSON response if you're returning a view
    return view('web.category.items', compact('items', 'category'));
}
// Show form
    public function bannercreate()
    {
        return view('web.bannercreate');
    }

    // Store banner
    public function bannerstore(Request $request)
    {
    $request->validate([
        'title' => 'nullable|string|max:255',
        'image' => 'required|image|max:2048', // 2MB limit
        'link' => 'nullable|url|max:255',
    ]);

    $path = $request->file('image')->store('banners', 'public');

    Banner::create([
        'user_id'    => Auth::id(),
        'title'      => $request->title,
        'image'      => $path,
        'link'       => $request->link,
        'created_by' => Auth::id(),
        'updated_by' => Auth::id(),
    ]);

    return redirect()->route('home')->with('success', 'Banner posted successfully!');
}

    // Admin Index: Show all banners (active + inactive)
public function bannerIndex()
{
    $banners = Banner::with(['user', 'creator', 'updater'])
        ->latest()
        ->paginate(10);

    return view('admin.banners.index', compact('banners'));
}

 public function bannerShow($id)
    {
        $banner = Banner::with(['user', 'creator', 'updater'])->findOrFail($id);
        return view('admin.banners.show', compact('banner'));
    }

    // ✅ Edit form
    public function bannerEdit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banners.edit', compact('banner'));
    }

    // ✅ Update banner
    public function bannerUpdate(Request $request, $id)
{
    $banner = Banner::findOrFail($id);

    $request->validate([
        'title' => 'nullable|string|max:255',
        'image' => 'nullable|image|max:2048',
        'link'  => 'nullable|url|max:255',
        'is_active' => 'boolean',
    ]);

    $data = $request->only(['title', 'link', 'is_active']);
    $data['updated_by'] = Auth::id();

    if ($request->hasFile('image')) {
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }
        $data['image'] = $request->file('image')->store('banners', 'public');
    }

    $banner->update($data);

    return redirect()->route('banners.index')->with('success', '✅ Banner updated successfully!');
}

    // ✅ Delete banner
    public function bannerDestroy($id)
    {
        $banner = Banner::findOrFail($id);

        // Delete image
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()->route('banners.index')->with('success', '🗑️ Banner deleted successfully!');
    }

    public function userstore(Request $request)
     {
        $visitorId = $request->track['visitor_id'] ?? null;
        $action = $request->track['action'] ?? $request->method().' '.$request->path();
        $url = $request->track['url'] ?? $request->fullUrl();
        $details = $request->track['details'] ?? null;

        VisitorAction::create([
            'visitor_id' => $visitorId,
            'action' => $action,
            'url' => $url,
            'details' => $details ? json_encode($details) : null,
        ]);

        return response()->json(['status' => 'success']);
    }
}
