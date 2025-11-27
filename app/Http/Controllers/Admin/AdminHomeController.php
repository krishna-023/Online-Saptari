<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class AdminHomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified'])
            ->except(['root', 'login', 'register', 'lang']);
    }

    public function index()
    {
        return view('admin.items.index');
    }

    public function root()
    {
        $categories = Category::withCount('items')->get();
        $chartData = [
            'categories' => $categories->pluck('Category_Name')->toArray(),
            'itemCounts' => $categories->pluck('items_count')->toArray(),
        ];

        return view('auth.login', compact('chartData'));
    }

    /** Users Index */
    public function userIndex(Request $request)
    {
        $query = User::query();

        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /** 🟢 Bulk Email & SMS */
    public function bulkEmailSms(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'action' => 'required|in:email,sms',
            'message' => 'required|string|max:1000',
        ]);

        $users = User::whereIn('id', $request->user_ids)->get();

        $successCount = 0;
        $failCount = 0;

        foreach ($users as $user) {
            try {
                if ($request->action === 'email') {
                    // send email
                    Mail::raw($request->message, function ($msg) use ($user) {
                        $msg->to($user->email)
                            ->subject('Notification from Admin');
                    });
                } elseif ($request->action === 'sms' && $user->phone) {
                    // send sms using any API (dummy example)
                    Http::post('https://api.smsprovider.com/send', [
                        'to' => $user->phone,
                        'message' => $request->message,
                    ]);
                }
                $successCount++;
            } catch (\Exception $e) {
                $failCount++;
            }
        }

        return redirect()->back()->with('success', "✅ $successCount messages sent successfully. ($failCount failed)");
    }

    /** Delete User */
    public function userDestroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->role === 'super-admin' && !Auth::user()->isSuperAdmin()) {
            return back()->with('error', 'Only super administrators can delete other super admins.');
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }


    /** Login */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            return match (Auth::user()->role) {
                'super-admin' => redirect()->route('dashboard'),
                'admin'       => redirect()->route('item.index'),
                'user'        => redirect()->route('home'),
                default       => back()->withErrors(['email' => 'You are not authorized.']),
            };
        }

        return back()->withErrors(['email' => 'The provided credentials are incorrect.'])
                     ->onlyInput('email');
    }

    /** Logout */
    public function logout(Request $request)
    {
        // Delete API tokens if using Sanctum
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }

        // Logout via the auth facade, not via $request->user()
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', '✅ Logged out successfully!');
    }


    /** Create User Form */
    public function create()
    {
        $authUser = Auth::user();
        $roles = [
            'super-admin' => 'Super Administrator',
            'admin' => 'Administrator',
            'user' => 'Regular User',
        ];

        // Remove super-admin option if current user is not super-admin
        if (!$authUser->isSuperAdmin()) {
            unset($roles['super-admin']);
        }

        $permissionGroups = config('role_permissions.permission_groups', []);

        return view('auth.usersadd', compact('permissionGroups', 'authUser', 'roles'));
    }

    /** Store User */
    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|string|min:6|confirmed',
            'role'            => 'required|in:super-admin,admin,user',
            'permissions'     => 'nullable|array',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $authUser = Auth::user();

        // Authorization checks
        if ($request->role === 'super-admin' && !$authUser->isSuperAdmin()) {
            return back()->withErrors(['role' => 'Only super administrators can create super admin users.']);
        }

        $data = $request->only(['name', 'email', 'role']);
        $data['password'] = Hash::make($request->password);

        // Set permissions based on role and selected permissions
        $defaultPermissions = data_get(config('role_permissions.default_permissions', []), $request->role, []);

        if ($request->has('permissions') && $authUser->role !== 'user') {
            // Use selected permissions if provided
            $data['permissions'] = $request->permissions;
        } else {
            // Use default permissions for the role
            $data['permissions'] = $defaultPermissions === 'all' ?
                array_keys(collect(config('role_permissions.permission_groups'))->pluck('permissions')->flatten(1)->toArray()) :
                $defaultPermissions;
        }

        // Handle profile picture
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = strtolower(str_replace(' ', '_', $request->name)) . '_profile.' . $file->getClientOriginalExtension();
            $file->storeAs('profile_pictures', $filename, 'public');
            $data['profile_picture'] = 'profile_pictures/' . $filename;
        }

        User::create($data);

        return redirect()->route('user.index')->with('success', '✅ User added successfully!');
    }

    /** Edit User Form */
    public function edit(User $user)
    {
        $authUser = Auth::user();

        // Authorization checks
        if ($user->isSuperAdmin() && !$authUser->isSuperAdmin()) {
            return redirect()->route('user.index')->with('error', 'You cannot edit super administrator accounts.');
        }

        $roles = [
            'super-admin' => 'Super Administrator',
            'admin' => 'Administrator',
            'user' => 'Regular User',
        ];

        // Remove super-admin option if current user is not super-admin
        if (!$authUser->isSuperAdmin()) {
            unset($roles['super-admin']);
        }

        $permissionGroups = config('role_permissions.permission_groups', []);

        return view('admin.users.edit', compact('user', 'permissionGroups', 'authUser', 'roles'));
    }

    /** Update User */
    public function update(Request $request, User $user)
    {
        $authUser = Auth::user();

        // Authorization checks
        if ($user->isSuperAdmin() && !$authUser->isSuperAdmin()) {
            return back()->withErrors(['role' => 'You cannot modify super administrator accounts.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:super-admin,admin,user',
            'permissions' => 'nullable|array',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Only allow role change if authorized
        if ($authUser->isSuperAdmin() || ($authUser->isAdmin() && !$user->isSuperAdmin())) {
            $user->role = $request->role;
        }

        // Update permissions
        if ($authUser->role !== 'user') {
            if ($request->has('permissions')) {
                $user->permissions = $request->permissions;
            } else {
                // Set default permissions for the role
                $defaultPermissions = data_get(config('role_permissions.default_permissions', []), $request->role, []);
                $user->permissions = $defaultPermissions === 'all' ?
                    array_keys(collect(config('role_permissions.permission_groups'))->pluck('permissions')->flatten(1)->toArray()) :
                    $defaultPermissions;
            }
        }

        // Handle profile picture update
        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $file = $request->file('profile_picture');
            $filename = strtolower(str_replace(' ', '_', $request->name)) . '_profile.' . $file->getClientOriginalExtension();
            $file->storeAs('profile_pictures', $filename, 'public');
            $user->profile_picture = 'profile_pictures/' . $filename;
        }

        $user->save();

        return redirect()->route('user.index')->with('success', '✅ User updated successfully!');
    }

    /** Bulk Actions for Users */
   public function bulkAction(Request $request)
{
    $request->validate([
        'action_type' => 'required|in:email,sms',
        'user_ids' => 'required|string',
        'message' => 'required|string',
        'subject' => 'nullable|string'
    ]);

    $userIds = explode(',', $request->user_ids);
    $users = User::whereIn('id', $userIds)->get();

    $action = $request->action_type;
    $subject = $request->subject ?? 'Notification from Admin';
    $message = $request->message;

    $count = 0;

    foreach ($users as $user) {
        if ($action === 'email' && $user->email) {
            try {
                Mail::raw($message, function ($mail) use ($user, $subject) {
                    $mail->to($user->email)
                         ->subject($subject);
                });
                $count++;
            } catch (\Throwable $e) {
                Log::error("Email send failed for {$user->email}: {$e->getMessage()}");
            }
        } elseif ($action === 'sms' && $user->phone) {
            try {
                // Example placeholder for SMS API integration
                // Replace this section with your preferred SMS gateway
                // e.g., Twilio::message($user->phone, $message);
                Log::info("SMS to {$user->phone}: {$message}");
                $count++;
            } catch (\Throwable $e) {
                Log::error("SMS send failed for {$user->phone}: {$e->getMessage()}");
            }
        }
    }

    return redirect()
        ->back()
        ->with('success', ucfirst($action) . " sent successfully to {$count} users.");
}

    /** Update Profile (for individual users) */
    public function updateProfile(Request $request, User $user)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email,' . $user->id,
            'password'        => 'nullable|string|min:6|confirmed',
            'role'            => 'sometimes|in:user,admin,super-admin',
            'permissions'     => 'nullable|array',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $authUser = Auth::user();

        $user->name = $request->name;
        $user->email = $request->email;

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Only admin/super-admin can update role
        if ($authUser->role !== 'user' && $request->has('role')) {
            // Additional authorization check
            if ($user->isSuperAdmin() && !$authUser->isSuperAdmin()) {
                return back()->withErrors(['role' => 'You cannot change the role of a super administrator.']);
            }
            $user->role = $request->role;
        }

        // Only admin/super-admin can update permissions
        if ($authUser->role !== 'user') {
            $user->permissions = $request->permissions ?: [];
        }

        // Handle profile picture
        if ($request->hasFile('profile_picture')) {
            // Delete old picture
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $file = $request->file('profile_picture');
            $filename = strtolower(str_replace(' ', '_', $user->name)) . '_profile.' . $file->getClientOriginalExtension();
            $file->storeAs('profile_pictures', $filename, 'public');
            $user->profile_picture = 'profile_pictures/' . $filename;
        }

        $user->save();

        return redirect()->back()->with('success', '✅ User updated successfully!');
    }

    public function updateProfilePicture(Request $request, User $user)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Delete old picture if exists
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $path = $request->file('profile_picture')->store('profiles', 'public');
        $user->profile_picture = $path;
        $user->save();

        return back()->with('success', '✅ Profile picture updated successfully!');
    }

    /** Update Password */
    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:6|confirmed',
        ]);

        $user = User::findOrFail($id);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', '✅ Password updated successfully!');
    }

    /** Switch Language */
    public function lang($locale)
    {
        App::setLocale($locale);
        Session::put('lang', $locale);
        return redirect()->back()->with('locale', $locale);
    }

    /** Get Authenticated User (API) */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    /** Dashboard Data */
    public function dashboardindex()
    {
        $usersCount      = User::count();
        $categoriesCount = Category::count();
        $itemsCount      = Item::count();
        $contactsCount   = Contact::count();

        $usersPerMonth = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $itemsAdded    = Item::where('created_at', '>=', now()->subMonth())->count();
        $itemsModified = Item::where('updated_at', '>=', now()->subMonth())->count();

        $categoriesChartData = Category::withCount('items')
            ->get()
            ->map(fn($c) => [
                'category'    => $c->Category_Name,
                'items_count' => $c->items_count,
            ]);

        $latestUsers = User::latest()->take(5)->get();
        $latestItems = Item::latest()->take(5)->with('category.parent')->get();

        return view('admin.items.dashboard', compact(
            'usersCount',
            'categoriesCount',
            'itemsCount',
            'contactsCount',
            'usersPerMonth',
            'itemsAdded',
            'itemsModified',
            'categoriesChartData',
            'latestUsers',
            'latestItems'
        ));
    }

    /** Delete Item */
    public function deleteItem($id)
    {
        $item = Item::find($id);

        if ($item) {
            $item->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    /** API JSON Data */
    public function data()
    {
        return response()->json([
            'usersCount'         => User::count(),
            'categoriesCount'    => Category::count(),
            'itemsCount'         => Item::count(),
            'contactsCount'      => Contact::count(),
            'usersPerMonth'      => User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                                        ->groupBy('month')
                                        ->pluck('count', 'month')
                                        ->toArray(),
            'itemsAdded'         => Item::where('created_at', '>=', now()->subMonth())->count(),
            'itemsModified'      => Item::where('updated_at', '>=', now()->subMonth())->count(),
            'categoriesChartData'=> Category::withCount('items')->get()->map(fn($c) => [
                'category'    => $c->Category_Name,
                'items_count' => $c->items_count,
            ]),
            'latestUsers'        => User::latest()->take(5)->get(),
            'latestItems'        => Item::latest()->take(5)->with('category.parent')->get(),
        ]);
    }
}
