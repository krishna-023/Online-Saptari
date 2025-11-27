<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;
use App\Models\Item;
use App\Models\Category;

class CacheInvalidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that home cache is cleared when an item is created
     */
    public function test_home_cache_cleared_on_item_creation()
    {
        // Prime the cache
        Cache::put('home_view_cache_guest', '<html>Cached Home</html>', 3600);
        $this->assertTrue(Cache::has('home_view_cache_guest'));

        // Create a new item (should trigger ItemObserver)
        Item::factory()->create(['title' => 'New Item']);

        // Cache should be cleared
        $this->assertFalse(Cache::has('home_view_cache_guest'));
    }

    /**
     * Test that home cache is cleared when an item is updated
     */
    public function test_home_cache_cleared_on_item_update()
    {
        $item = Item::factory()->create(['title' => 'Original Title']);

        // Prime the cache
        Cache::put('home_view_cache_guest', '<html>Cached Home</html>', 3600);
        $this->assertTrue(Cache::has('home_view_cache_guest'));

        // Update the item
        $item->update(['title' => 'Updated Title']);

        // Cache should be cleared
        $this->assertFalse(Cache::has('home_view_cache_guest'));
    }

    /**
     * Test that home cache is cleared when a category is created
     */
    public function test_home_cache_cleared_on_category_creation()
    {
        // Prime the cache
        Cache::put('home_view_cache_guest', '<html>Cached Home</html>', 3600);
        $this->assertTrue(Cache::has('home_view_cache_guest'));

        // Create a category
        Category::factory()->create(['Category_Name' => 'New Category']);

        // Cache should be cleared
        $this->assertFalse(Cache::has('home_view_cache_guest'));
    }

    /**
     * Test that home cache is cleared when an item is deleted
     */
    public function test_home_cache_cleared_on_item_deletion()
    {
        $item = Item::factory()->create(['title' => 'Item to Delete']);

        // Prime the cache
        Cache::put('home_view_cache_guest', '<html>Cached Home</html>', 3600);
        $this->assertTrue(Cache::has('home_view_cache_guest'));

        // Delete the item
        $item->delete();

        // Cache should be cleared
        $this->assertFalse(Cache::has('home_view_cache_guest'));
    }
}
