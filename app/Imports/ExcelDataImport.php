<?php

namespace App\Imports;

use App\Models\Exceldata;
use Maatwebsite\Excel\Concerns\ToModel;

class ExcelDataImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Exceldata([

                'first_name'     => $row[0],
                'last_name'    => $row[1],
                'age' => $row[2],

        ]);
    }
}
