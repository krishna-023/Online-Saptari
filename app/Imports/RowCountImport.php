<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class RowCountImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // This class is only used to count rows
        return $rows;
    }
}
