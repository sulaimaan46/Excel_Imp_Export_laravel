<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Rap2hpoutre\FastExcel\FastExcel;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ExcelDataImport;
use App\Models\ExcelData;
use Illuminate\Bus\Batchable;

class ExcelDataProcess implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $fileType;
    public $fileData;
    public $header;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fileType,$fileData,$header)
    {
        $this->fileType = $fileType;
        $this->fileData = $fileData;
        $this->header = $header;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->fileType == "xlsx" || $this->fileType == "csv"){


            if($this->fileType == "csv"){

                (new FastExcel)->import($this->fileData, function ($reader) {

                    // $data= array_map('str_getcsv',file($fileData));

                    $data=str_ireplace(str_split('\\/:*?"<>|-,%$@!}{][`~;®™#'), ' ', $reader);

                    return ExcelData::insert($data);
                });

            }

            if($this->fileType == "xlsx" && $this->header == 'header'){

                (new FastExcel)->import($this->fileData, function ($reader){

                    return ExcelData::insert($reader);

                });
            }else{

                (new FastExcel)->startRow(2)->import($this->fileData, function ($reader){

                    $data= array_keys($reader);

                    return ExcelData::insert([
                        'first_name' => $reader[$data[0]],
                        'last_name' => $reader[$data[1]],
                        'age' => $reader[$data[2]],
                    ]);

                });

            }


        }else{

            Excel::import(new ExcelDataImport($this->header),$this->fileData);

        }
    }
}
