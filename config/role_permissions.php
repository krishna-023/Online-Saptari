<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Role → Permissions Mapping
    |--------------------------------------------------------------------------
    */
    'roles' => [
        'super-admin' => [
            'all', // Super-admin has all permissions
        ],
        'admin' => [
            // Dashboard
            'dashboard_access',
            'view_analytics',

            // Category Management
            'category_view',
            'category_create',
            'category_edit',
            'category_delete',

            // Item Management
            'item_view',
            'item_create',
            'item_edit',
            'item_delete',
            'item_import_export',
            'item_bulk_actions',

            // Banner Management
            'banner_view',
            'banner_create',
            'banner_edit',
            'banner_delete',

            // User Management (limited)
            'user_view',

            // Profile
            'profile_view',
            'profile_edit',
            'settings_access',
        ],
        'user' => [
            // Basic Access
            'home_access',

            // Item Access
            'item_view_public',
            'item_create_own',
            'item_edit_own',
            'item_delete_own',

            // Profile
            'profile_view',
            'profile_edit',
            'settings_access',

            // Banner (if users can create banners)
            'banner_create_own',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Permission to Route Mapping
    |--------------------------------------------------------------------------
    */
    'route_permissions' => [
        // Dashboard
        'dashboard' => 'dashboard_access',
        'dashboard.data' => 'view_analytics',

        // Categories
        'categories.index' => 'category_view',
        'categories.create' => 'category_create',
        'categories.store' => 'category_create',
        'categories.show' => 'category_view',
        'categories.edit' => 'category_edit',
        'categories.update' => 'category_edit',
        'categories.destroy' => 'category_delete',

        // Items (Admin)
        'item.index' => 'item_view',
        'item.add' => 'item_create',
        'item.store' => 'item_create',
        'item.view' => 'item_view',
        'item.edit' => 'item_edit',
        'item.update' => 'item_edit',
        'item.destroy' => 'item_delete',
        'item.bulkDelete' => 'item_bulk_actions',
        'item.deleteSelected' => 'item_bulk_actions',
        'item.import' => 'item_import_export',
        'item.export' => 'item_import_export',

        // Items (User)
        'user.add' => 'item_create_own',
        'usersitem.store' => 'item_create_own',
        'item.userview' => 'item_view_public',

        // Banners
        'banners.index' => 'banner_view',
        'banners.create' => 'banner_create',
        'banners.store' => 'banner_create',
        'banners.show' => 'banner_view',
        'banners.edit' => 'banner_edit',
        'banners.update' => 'banner_edit',
        'banners.destroy' => 'banner_delete',

        // Profile & Settings
        'item.profile' => 'profile_view',
        'user.profile' => 'profile_view',
        'pages-profile-settings' => 'settings_access',
        'profile.settings.update' => 'profile_edit',
        'account.settings' => 'settings_access',
        'account.address.save' => 'profile_edit',
        'account.payment.save' => 'profile_edit',
        'account.notifications.save' => 'profile_edit',
        'account.theme.save' => 'profile_edit',
        'account.profile.update' => 'profile_edit',

        // Public routes
        'home' => 'home_access',
        'category.items' => 'home_access',
        'all.categories' => 'home_access',
        'search.results' => 'home_access',
        'track.action' => 'home_access',
    ],

    /*
    |--------------------------------------------------------------------------
    | Permission Categories (for UI display)
    |--------------------------------------------------------------------------
    */
    'permission_groups' => [
        'dashboard' => [
            'name' => 'Dashboard',
            'permissions' => [
                'dashboard_access' => 'Access Dashboard',
                'view_analytics' => 'View Analytics',
            ],
        ],
        'category_management' => [
            'name' => 'Category Management',
            'permissions' => [
                'category_view' => 'View Categories',
                'category_create' => 'Create Categories',
                'category_edit' => 'Edit Categories',
                'category_delete' => 'Delete Categories',
            ],
        ],
        'item_management' => [
            'name' => 'Item Management',
            'permissions' => [
                'item_view' => 'View All Items',
                'item_create' => 'Create Items',
                'item_edit' => 'Edit Items',
                'item_delete' => 'Delete Items',
                'item_import_export' => 'Import/Export Items',
                'item_bulk_actions' => 'Bulk Actions',
            ],
        ],
        'user_items' => [
            'name' => 'Personal Items',
            'permissions' => [
                'item_view_public' => 'View Public Items',
                'item_create_own' => 'Create Own Items',
                'item_edit_own' => 'Edit Own Items',
                'item_delete_own' => 'Delete Own Items',
            ],
        ],
        'banner_management' => [
            'name' => 'Banner Management',
            'permissions' => [
                'banner_view' => 'View Banners',
                'banner_create' => 'Create Banners',
                'banner_edit' => 'Edit Banners',
                'banner_delete' => 'Delete Banners',
                'banner_create_own' => 'Create Own Banners',
            ],
        ],
        'profile' => [
            'name' => 'Profile & Settings',
            'permissions' => [
                'profile_view' => 'View Profile',
                'profile_edit' => 'Edit Profile',
                'settings_access' => 'Access Settings',
            ],
        ],
        'general' => [
            'name' => 'General Access',
            'permissions' => [
                'home_access' => 'Access Homepage',
                'user_view' => 'View Users',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Structure (linked with permissions)
    |--------------------------------------------------------------------------
    */
    'menu' => [
        [
            'title' => 'Home',
            'icon' => 'ri-home-4-line',
            'route' => 'home',
            'permission' => 'home_access',
        ],
        [
            'title' => 'Dashboard',
            'icon' => 'ph-gauge',
            'route' => 'dashboard',
            'permission' => 'dashboard_access',
        ],
        [
            'title' => 'Categories',
            'icon' => 'ri-folders-line',
            'permission' => 'category_view',
            'children' => [
                [
                    'title' => 'All Categories',
                    'icon' => 'ri-list-check',
                    'route' => 'categories.index',
                    'permission' => 'category_view',
                ],
                [
                    'title' => 'Add Category',
                    'icon' => 'ri-add-circle-line',
                    'route' => 'categories.create',
                    'permission' => 'category_create',
                ],
            ],
        ],
        [
            'title' => 'Items',
            'icon' => 'ri-archive-line',
            'permission' => 'item_view',
            'children' => [
                [
                    'title' => 'All Items',
                    'icon' => 'ri-file-list-line',
                    'route' => 'item.index',
                    'permission' => 'item_view',
                ],
                [
                    'title' => 'Add Item',
                    'icon' => 'ri-add-line',
                    'route' => 'item.add',
                    'permission' => 'item_create',
                ],
                [
                    'title' => 'My Items',
                    'icon' => 'ri-user-line',
                    'route' => 'user.add',
                    'permission' => 'item_create_own',
                ],
            ],
        ],
        [
            'title' => 'Banners',
            'icon' => 'ri-image-line',
            'permission' => 'banner_view',
            'children' => [
                [
                    'title' => 'All Banners',
                    'icon' => 'ri-file-list-line',
                    'route' => 'banners.index',
                    'permission' => 'banner_view',
                ],
                [
                    'title' => 'Add Banner',
                    'icon' => 'ri-add-line',
                    'route' => 'banners.create',
                    'permission' => 'banner_create',
                ],
            ],
        ],
        [
            'title' => 'Profile',
            'icon' => 'ri-user-3-line',
            'route' => 'item.profile',
            'permission' => 'profile_view',
        ],
        [
            'title' => 'Settings',
            'icon' => 'ri-settings-3-line',
            'route' => 'account.settings',
            'permission' => 'settings_access',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Permissions for New Users
    |--------------------------------------------------------------------------
    */
    'default_permissions' => [
        'super-admin' => 'all',
        'admin' => [
            'dashboard_access',
            'view_analytics',
            'category_view',
            'category_create',
            'category_edit',
            'category_delete',
            'item_view',
            'item_create',
            'item_edit',
            'item_delete',
            'item_import_export',
            'item_bulk_actions',
            'banner_view',
            'banner_create',
            'banner_edit',
            'banner_delete',
            'user_view',
            'profile_view',
            'profile_edit',
            'settings_access',
            'home_access',
        ],
        'user' => [
            'home_access',
            'item_view_public',
            'item_create_own',
            'item_edit_own',
            'item_delete_own',
            'banner_create_own',
            'profile_view',
            'profile_edit',
            'settings_access',
        ],
    ],
];
