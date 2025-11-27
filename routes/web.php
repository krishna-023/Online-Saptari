<?php

use App\Http\Controllers\Admin\AccountController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\Web\UserController;
use App\Models\Category;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

/**
 * -----------------------------
 * Public Routes (No Auth Required)
 * -----------------------------
 */
Auth::routes(['verify' => true]);

// Public routes
Route::get('/testweb', [ItemController::class, 'testweb'])->name('testweb');
Route::get('/', [ItemController::class, 'home'])->name('home');
Route::get('/login', [AdminHomeController::class, 'root'])->name('login');
Route::post('/login', [AdminHomeController::class, 'login'])->name('login.submit');
Route::get('/search', [ItemController::class, 'search'])->name('search.results');
Route::get('/lang/{locale}', [AdminHomeController::class, 'lang'])->name('lang');

// Public category routes
Route::get('/categories/all', [UserController::class, 'allCategories'])->name('all.categories');
Route::get('/category/{slug}', [UserController::class, 'categoryItems'])->name('category.items');
Route::get('/related-items', [UserController::class, 'getRelatedItems']);
Route::get('/category-items', [UserController::class, 'getItemsByCategory']);

// Public item viewing
Route::get('/item/user/view/{slug}', [UserController::class, 'userview'])->name('item.userview');

/**
 * -----------------------------
 * Authenticated User Routes (Basic Users)
 * -----------------------------
 */
Route::middleware(['auth', 'verified'])->group(function () {

    // Profile routes for all authenticated users
    Route::get('/profile', [ItemController::class, 'show'])->name('item.profile');
    Route::get('user/profile', [ItemController::class, 'userprofileshow'])->name('user.profile');
    Route::get('/profile/settings', [ItemController::class, 'profilesetting'])->name('pages-profile-settings');
    Route::post('/profile/settings/update', [AdminHomeController::class, 'updateProfile'])->name('profile.settings.update');
    Route::put('/profile/picture/{user}', [AdminHomeController::class, 'updateProfilePicture'])
         ->name('profile.picture.update');

         Route::get('/import-status', [ItemController::class, 'importStatus'])->name('import.status');
        Route::get('/import-sample', [ItemController::class, 'downloadSample'])->name('item.import.sample');
    // Account settings for all users
    Route::get('account/settings', [AccountController::class, 'accountSettings'])->name('account.settings');
    Route::post('account/address/save', [AccountController::class, 'saveAddress'])->name('account.address.save');
    Route::post('account/payment/save', [AccountController::class, 'savePayment'])->name('account.payment.save');
    Route::post('account/notifications/save', [AccountController::class, 'saveNotificationSettings'])->name('account.notifications.save');
    Route::post('account/theme/save', [AccountController::class, 'saveTheme'])->name('account.theme.save');
    Route::post('account/profile/update', [AccountController::class, 'updateProfile'])->name('account.profile.update');

    // User item management (for regular users)
    Route::middleware(['check.permission:item_create'])->group(function () {
        Route::get('users/create/item', [UserController::class, 'create'])->name('user.add');
        Route::post('users/items/store', [UserController::class, 'itemstore'])->name('usersitem.store');
    });

    // Tracking action
    Route::post('/track-action', [UserController::class, 'userstore'])->name('track.action');
});

/**
 * -----------------------------
 * Admin Routes (Admin & Super-Admin)
 * -----------------------------
 */
Route::middleware(['auth', 'verified', 'check.permission:admin_access'])->group(function () {

    // Admin dashboard
    Route::get('/dashboard', [AdminHomeController::class, 'dashboardindex'])->name('dashboard');
    Route::get('/dashboard/data', [AdminHomeController::class, 'data'])->name('dashboard.data');
    Route::delete('/dashboard/items/{slug}', [AdminHomeController::class, 'deleteItem']);

    // Category management
    Route::middleware(['check.permission:category_management'])->group(function () {
        Route::get('/category', [CategoryController::class, 'catindex'])->name('categories.index');
        Route::get('/category/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/category/store', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/category/{category}/view', [CategoryController::class, 'show'])->name('categories.show');
        Route::get('/category/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/category/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    // Item management (admin level)
    Route::middleware(['check.permission:item_management'])->prefix('items')->name('item.')->group(function () {
        Route::get('/index', [ItemController::class, 'index'])->name('index');
        Route::get('/create', [ItemController::class, 'create'])->name('add');
        Route::post('/store', [ItemController::class, 'store'])->name('store');
        Route::delete('/bulk-delete', [ItemController::class, 'bulkDelete'])->name('bulkDelete');
        Route::post('/delete-selected', [ItemController::class, 'deleteSelected'])->name('deleteSelected');
        Route::post('/export', [ItemController::class, 'export'])->name('export');
        Route::post('/import-items', [ItemController::class, 'import'])->name('import');
        Route::post('/admin/users/bulk-email-sms', [AdminHomeController::class, 'bulkEmailSms'])->name('admin.users.bulkEmailSms');

        Route::get('/{slug}/view', [ItemController::class, 'view'])->name('view');
        Route::get('/{slug}/edit', [ItemController::class, 'edit'])->name('edit');
        Route::put('/{slug}/update', [ItemController::class, 'update'])->name('update');
        Route::delete('/{slug}/delete', [ItemController::class, 'destroy'])->name('destroy');
    });

    // Banner management
    Route::middleware(['check.permission:content_management'])->group(function () {
        Route::get('/banners', [UserController::class, 'bannercreate'])->name('banners.create');
        Route::post('/banners/store', [UserController::class, 'bannerstore'])->name('banners.store');
        Route::get('/banners/index', [UserController::class, 'bannerIndex'])->name('banners.index');
        Route::get('banners/{id}', [UserController::class, 'bannerShow'])->name('banners.show');
        Route::get('banners/{id}/edit', [UserController::class, 'bannerEdit'])->name('banners.edit');
        Route::put('banners/{id}', [UserController::class, 'bannerUpdate'])->name('banners.update');
        Route::delete('banners/{id}', [UserController::class, 'bannerDestroy'])->name('banners.destroy');
    });
});

/**
 * -----------------------------
 * Super-Admin Only Routes
 * -----------------------------
 */
Route::middleware(['auth', 'verified', 'check.permission:user_management'])->group(function () {

    // User management (super-admin only)
    Route::get('/admin/users/create', [AdminHomeController::class, 'create'])->name('user.create');
    Route::get('/admin/users', [AdminHomeController::class, 'userIndex'])->name('user.index');
    Route::delete('/admin/users/{id}', [AdminHomeController::class, 'destroy'])->name('user.destroy');
    Route::post('users/store', [AdminHomeController::class, 'store'])->name('user.store');

    // User edit and update routes
    Route::get('/admin/users/{user}/edit', [AdminHomeController::class, 'edit'])->name('user.edit');
    Route::put('/admin/users/{user}', [AdminHomeController::class, 'update'])->name('user.update');
    Route::post('/admin/users/bulk-action', [AdminHomeController::class, 'bulkAction'])->name('user.bulkAction');
});

/**
 * -----------------------------
 * Utility Routes
 * -----------------------------
 */
// Category slug generation (one-time use)
Route::get('/generate-category-slugs', function() {
    if (!Schema::hasColumn('categories', 'slug')) {
        return "Error: 'slug' column does not exist!";
    }

    $categories = \App\Models\Category::all();
    foreach ($categories as $category) {
        if (!$category->slug) {
            $category->slug = \Illuminate\Support\Str::slug($category->Category_Name);
            $category->save();
        }
    }
    return "Slugs generated successfully!";
});

// Categories API endpoint
Route::get('/items/categories', function () {
    return response()->json([
        'success' => true,
        'data' => \App\Models\Category::all(),
        'message' => 'Categories retrieved successfully'
    ]);
})->name('categories');

// Logout (accessible to all authenticated users)
Route::post('/logout', [AdminHomeController::class, 'logout'])->name('logout');

// Test email route
Route::get('/test-mail', function () {
    Mail::raw('This is a test email from Laravel using Gmail SMTP.', function ($message) {
        $message->to('mandalkrish47@gmail.com')->subject('SMTP Test');
    });
    return 'Mail sent!';
});

/**
 * -----------------------------
 * API Routes
 * -----------------------------
 */
Route::prefix('api')->group(function () {
    require base_path('routes/api.php');
});
