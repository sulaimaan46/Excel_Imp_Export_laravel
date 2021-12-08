<?php

namespace App\Imports;

use App\Models\Exceldata;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExcelDataImport implements ToModel,WithHeadingRow
{
    public function __construct(String  $header)
    {
        $this->header = $header;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $header = $this->header;
        if($header == "header"){

            return new Exceldata([
                'first_name'     => $row['first_name'],
                'last_name'    => $row['last_name'],
                'age' => $row['age'],

            ]);
        }else{
            return new Exceldata([

                'first_name'     => $row[0],
                'last_name'    => $row[1],
                'age' => $row[2],

            ]);
        }


    }

    public function headingRow(): int
    {
        $header = $this->header;
        if($header == "header"){
            return 1;
        }

        return true;
    }
}
