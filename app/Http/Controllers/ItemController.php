<?php

namespace App\Http\Controllers;

use App\Exports\ItemsExport;
use App\Imports\ItemsImport;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Gallery;
use App\Models\Item;
use App\Models\Opening_Time;
use App\Models\SocialIcon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;





class ItemController extends Controller
{
    public function index(Request $request)
{
        $user = Auth::user(); // or however you're retrieving the user
    // Start with a base query to retrieve all items
    $query = Item::query();

    // Apply filters only if the request parameters are filled
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id); // Assuming 'category_id' is the foreign key in the items table
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
    if ($request->filled('author_username')) {
        $query->where('author_username', 'like', '%' . $request->author_username . '%');
    }
    if ($request->filled('author_email')) {
        $query->where('author_email', 'like', '%' . $request->author_email . '%');
    }
    if ($request->filled('author_first_name')) {
        $query->where('author_first_name', 'like', '%' . $request->author_first_name . '%');
    }
    if ($request->filled('author_last_name')) {
        $query->where('author_last_name', 'like', '%' . $request->author_last_name . '%');
    }
    if ($request->filled('slug')) {
        $query->where('slug', 'like', '%' . $request->slug . '%');
    }
    if ($request->filled('parent')) {
        $query->where('parent', 'like', '%' . $request->parent . '%');
    }
    if ($request->filled('parent_slug')) {
        $query->where('parent_slug', 'like', '%' . $request->parent_slug . '%');
    }
    if ($request->filled('telephone')) {
        $query->whereHas('contacts', function ($q) use ($request) {
            $q->where('telephone', 'like', '%' . $request->telephone . '%');
        });
    }
    if ($request->filled('address')) {
        $query->whereHas('contacts', function ($q) use ($request) {
            $q->where('address', 'like', '%' . $request->address . '%');
        });
    }
    if ($request->filled('id')) {
        $query->where('id', $request->id);
    }

    // Execute the query and paginate results
    $items = $query->paginate(10);

    // Retrieve all categories for the filter dropdown
    $categories = Category::all();

    // Return the view with items and categories
    return view('items.index', compact('user','items', 'categories'));
}



    public function create()
    {
        $categories = Category::all();
        return view('items.add',compact('categories'));
    }

    public function store(Request $request)
    {

        $request->validate([
//            'category_id' => 'required',
            'reference_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'nullable|string|max:255',
            'item_featured' => 'nullable|string|max:255',
            'collection_date' => 'nullable|date',
            'permalink' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'author_username' => 'nullable|string|max:255',
            'author_email' => 'nullable|email|max:255',
            'author_first_name' => 'nullable|string|max:255',
            'author_last_name' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
            'parent' => 'nullable|string|max:255',
            'parent_slug' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:255',
            'phone1' => 'nullable|string|max:255',
            'phone2' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
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
            'displayGallery' => 'sometimes|boolean',
            'gallery' => 'nullable|url|max:255',
        ]);

        $category = null;
        if ($request->has('category_name')) {
            $category = Category::where('Category_Name', $request->category_name)->first();
        }

        if ($request->has('category_id')) {
            $category = Category::find($request->category_id);
        }

        if ($category) {
            $categoryId = $category->id;
        } else {
            $category = Category::create([
                'Category_Name' => $request->category_name,
                'reference_id' => $request->reference_id,
            ]);
            $categoryId = $category->id;
        }

        $item = Item::create([
            'category_id' => $categoryId,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'content' => $request->content,
            'item_featured' => $request->item_featured,
            'collection_date' => $request->collection_date,
            'permalink' => $request->permalink,
            'image' => $request->file('image') ? $request->file('image')->store('images', 'public') : null,
            'author_username' => $request->author_username,
            'author_email' => $request->author_email,
            'author_first_name' => $request->author_first_name,
            'author_last_name' => $request->author_last_name,
            'slug' => $request->slug,
            'parent' => $request->parent,
            'parent_slug' => $request->parent_slug,
        ]);

        if (!$item) {
            return back()->withErrors(['error' => 'Failed to create item.'])->withInput();
        }

        Contact::create([
            'item_id' => $item->id,
            'telephone' => $request->telephone,
            'phone1' => $request->phone1,
            'phone2' => $request->phone2,
            'email' => $request->email,
            'contactOwnerBtn' => $request->contactOwnerBtn,
            'web' => $request->web,
            'webLinkLabel' => $request->webLinkLabel,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'streetview' => $request->streetview,
            'swheading' => $request->swheading,
            'swpitch' => $request->swpitch,
            'swzoom' => $request->swzoom,
        ]);

        Opening_Time::create([
            'item_id' => $item->id,
            'displayOpeningHours' => $request->displayOpeningHours,
            'openingHoursMonday' => $request->openingHoursMonday,
            'openingHoursTuesday' => $request->openingHoursTuesday,
            'openingHoursWednesday' => $request->openingHoursWednesday,
            'openingHoursThursday' => $request->openingHoursThursday,
            'openingHoursFriday' => $request->openingHoursFriday,
            'openingHoursSaturday' => $request->openingHoursSaturday,
            'openingHoursSunday' => $request->openingHoursSunday,
            'openingHoursNote' => $request->openingHoursNote,
        ]);

        SocialIcon::create([
            'item_id' => $item->id,
            'displaySocialIcons' => $request->displaySocialIcons,
            'socialIconsOpenInNewWindow' => $request->socialIconsOpenInNewWindow,
            'socialIcons' => $request->socialIcons,
            'socialIcons_url' => $request->socialIcons_url,
        ]);

        Gallery::create([
            'item_id' => $item->id,
            'displayGallery' => $request->displayGallery,
            'gallery' => $request->gallery,
        ]);

        session()->flash('success', 'Item added successfully.');

        return redirect()->route('item.index');
    }


    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $categories = Category::all();
        $contacts = Contact::where('item_id', $id)->first();
        $socialIcon = SocialIcon::where('item_id', $id)->first(); // Retrieve social icon data
        $opening_time = Opening_Time::where('item_id', $id)->first();
        $gallery = Gallery::where('item_id', $id)->first();

        return view('items.edit', compact('item', 'categories', 'contacts','socialIcon', 'opening_time', 'gallery'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'reference_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'nullable|string|max:255',
            'item_featured' => 'nullable|string|max:255',
            'collection_date' => 'nullable|date',
            'permalink' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'author_username' => 'nullable|string|max:255',
            'author_email' => 'nullable|email|max:255',
            'author_first_name' => 'nullable|string|max:255',
            'author_last_name' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
            'parent' => 'nullable|string|max:255',
            'parent_slug' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:255',
            'phone1' => 'nullable|string|max:255',
            'phone2' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
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
            'displayGallery' => 'sometimes|boolean',
            'gallery' => 'nullable|url|max:255',
        ]);

        // Find the item to update
        $item = Item::findOrFail($id);

        // Update the category
        $category = null;
        if ($request->has('category_name')) {
            $category = Category::where('Category_Name', $request->category_name)->first();
        }

        if ($request->has('category_id')) {
            $category = Category::find($request->category_id);
        }

        if ($category) {
            $categoryId = $category->id;
        } else {
            $category = Category::create([
                'Category_Name' => $request->category_name,
                'reference_id' => $request->reference_id,
            ]);
            $categoryId = $category->id;
        }

        // Update the item
        $item->update([
            'reference_id' => $request->reference_id,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'content' => $request->content,
            'item_featured' => $request->item_featured,
            'collection_date' => $request->collection_date,
            'permalink' => $request->permalink,
            'image' => $request->file('image') ? $request->file('image')->store('images', 'public') : $item->image,
            'author_username' => $request->author_username,
            'author_email' => $request->author_email,
            'author_first_name' => $request->author_first_name,
            'author_last_name' => $request->author_last_name,
            'slug' => $request->slug,
            'parent' => $request->parent,
            'parent_slug' => $request->parent_slug,
        ]);


        // Update or create the contact
        $contact = Contact::updateOrCreate(
            ['item_id' => $item->id],
            [
                'telephone' => $request->telephone,
                'phone1' => $request->phone1,
                'phone2' => $request->phone2,
                'email' => $request->email,
                'contactOwnerBtn' => $request->contactOwnerBtn,
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

        // Update or create the opening time
        $openingTime = Opening_Time::updateOrCreate(
            ['item_id' => $item->id],
            [
                'displayOpeningHours' => $request->displayOpeningHours,
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

        // Update or create the social icon
        $socialIcon = SocialIcon::updateOrCreate(
            ['item_id' => $item->id],
            [
                'displaySocialIcons' => $request->displaySocialIcons,
                'socialIconsOpenInNewWindow' => $request->socialIconsOpenInNewWindow,
                'socialIcons' => $request->socialIcons,
                'socialIcons_url' => $request->socialIcons_url,
            ]
        );

        // Update or create the gallery
        $gallery = Gallery::updateOrCreate(
            ['item_id' => $item->id],
            [
                'displayGallery' => $request->displayGallery,
                'gallery' => $request->gallery,
            ]
        );

        session()->flash('success', 'Item updated successfully.');

        return redirect()->route('item.index');
    }

    public function view($id)
    {
        $itemId = decrypt($id);

        $item = Item::with(['category', 'contacts', 'opening_Time', 'socialIcons', 'galleries'])
            ->findOrFail($itemId);
        return view('items.view', compact('item'));
    }


    public function deleteSelected(Request $request)
    {
        $ids = $request->input('ids');
        if (is_array($ids)) {
            try {
                Item::whereIn('id', $ids)->delete();
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            }
        }
        return response()->json(['success' => false], 400);
    }

    public function destroy($id)
    {
        $decryptedId = decrypt($id); // Decrypt the encrypted ID
        $item = Item::findOrFail($decryptedId);

        // Perform the deletion
        $item->delete();
        session()->flash('success', 'Item deleted successfully.');

        return redirect()->route('item.index');
    }

//    public function bulkDelete(Request $request)
//    {
//        $ids = $request->input('ids', []);
//
//        if (is_array($ids) && !empty($ids)) {
//            try {
//                Item::whereIn('id', $ids)->delete();
//                return response()->json(['success' => true, 'message' => 'Selected items deleted successfully.']);
//            } catch (\Exception $e) {
//                return response()->json(['success' => false, 'message' => 'Error deleting items: ' . $e->getMessage()]);
//            }
//        } else {
//            return response()->json(['success' => false, 'message' => 'No items selected for deletion.']);
//        }
//    }
public function show()
    {
        $user = Auth::user(); // Retrieve the authenticated user
        return view('items.profile.profile', compact('user')); // Return the profile view
    }

    public function profilesetting()
    {
        $user = Auth::user(); // Retrieve the authenticated user
        return view('items.profile.settings', compact('user')); // Return the profile settings view
    }

    public function profileupdate(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user

        // Validate the request
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update user information
        $user->name = $request->username;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // Handle the profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete the old profile picture if it exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Store the new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path; // Save the path to the database
        }

        $user->save(); // Save the updated user information

        return redirect()->route('item.profile')->with('status', 'Profile updated successfully!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new ItemsImport, $request->file('file'));

        return redirect()->back()->with('success', 'Items imported successfully!');
    }
    public function export(Request $request)
{
    $selectedIds = $request->input('ids', []); // Ensure it gets selected IDs as an array
    return Excel::download(new ItemsExport($selectedIds), 'items_export.xlsx');
}


}
