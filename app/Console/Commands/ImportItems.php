<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ItemImport;

class ImportItems extends Command
{
    protected $signature = 'items:import';
    protected $description = 'Import items from CSV file';

    public function handle()
    {
        $path = storage_path('app/imports/final_decoded_production_ready.csv');

        if (!file_exists($path)) {
            $this->error('CSV file not found: ' . $path);
            return;
        }

        $this->info('🚀 Starting item import...');
        Excel::import(new ItemImport, $path);
        $this->info('✅ Import complete!');
    }
}
