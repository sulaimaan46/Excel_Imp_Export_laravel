<?php

namespace App\Exports;

use App\Models\Exceldata;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExcelDataExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Exceldata::all();
    }
}
