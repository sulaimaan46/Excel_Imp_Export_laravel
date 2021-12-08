<?php

namespace App\Exports;

use App\Models\Exceldata;
use GuzzleHttp\Psr7\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelDataExport implements FromCollection, WithHeadings
{

    public function __construct(String  $header)
    {
        $this->header = $header;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Exceldata::all();
    }

    //Put Here Header Name That you want in your excel sheet
    public function headings(): array

        {
            $param = $this->header;
             if($param == 'header'){
                return [
                    'Id',
                    'First_Name',
                    'Last_Name',
                    'Age',
                    'Created_at',
                    'Updated_at'
                ];
             }else{
                return [];
             }


        }
}


