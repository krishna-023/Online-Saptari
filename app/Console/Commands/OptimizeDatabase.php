<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class OptimizeDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:optimize-indexes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create essential indexes for home page performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating performance indexes...');

        try {
            // Index for items table
            DB::statement('CREATE INDEX IF NOT EXISTS idx_items_featured_created ON items(item_featured, created_at DESC)');
            DB::statement('CREATE INDEX IF NOT EXISTS idx_items_views ON items(views DESC)');
            DB::statement('CREATE INDEX IF NOT EXISTS idx_items_status ON items(status)');
            DB::statement('CREATE INDEX IF NOT EXISTS idx_items_category ON items(category_id)');

            // Index for categories table
            DB::statement('CREATE INDEX IF NOT EXISTS idx_categories_status ON categories(status)');
            DB::statement('CREATE INDEX IF NOT EXISTS idx_categories_created ON categories(created_at DESC)');

            // Index for banners table
            DB::statement('CREATE INDEX IF NOT EXISTS idx_banners_active ON banners(is_active)');

            $this->info('✓ Indexes created successfully!');
        } catch (\Exception $e) {
            $this->error('Error creating indexes: ' . $e->getMessage());
        }
    }
}
