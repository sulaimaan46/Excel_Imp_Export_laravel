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
use Illuminate\Support\Facades\DB;

class ExcelDataUpdateProcess implements ShouldQueue
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

                    $value = str_ireplace(str_split('\\/:*?"<>|-,%$@!}{][`~;®™#'), ' ', $reader);

                    $data = array(
                        'marks' => $value,
                    );
                    $records = ExcelData::get();

                    $insert_data = collect($records); // Make a collection to use the chunk method

                    // it will chunk the dataset in smaller collections containing 500 values each.
                    // Play with the value to get best result
                    $chunks = $insert_data->chunk(500);

                    foreach ($chunks as $chunk)
                    {
                        return DB::table('excel_data')->where('id',$chunk->id)->update($data);
                    }

                });

            }

            if($this->fileType == "xlsx" && $this->header == 'header'){

                (new FastExcel)->import($this->fileData, function ($reader){

                    $value= array_values($reader);

                    $data = array(
                        'marks' => $value[0] ,
                    );
                    $records = ExcelData::get();

                    $insert_data = collect($records); // Make a collection to use the chunk method

                    // it will chunk the dataset in smaller collections containing 500 values each.
                    // Play with the value to get best result
                    $chunks = $insert_data->chunk(500);

                    foreach ($chunks as $chunk)
                    {
                        return DB::table('excel_data')->where('id',$chunk->id)->update($data);
                    }

                });

            }else{

                (new FastExcel)->startRow(2)->import($this->fileData, function ($reader){

                    $data= array_keys($reader);

                    $records = ExcelData::get();

                    $insert_data = collect($records); // Make a collection to use the chunk method

                    // it will chunk the dataset in smaller collections containing 500 values each.
                    // Play with the value to get best result
                    $chunks = $insert_data->chunk(500);


                    foreach ($chunks as $chunk)
                    {
                        foreach ($chunk as $chunkData)
                        {
                            return DB::table('excel_data')->where('id',$chunkData->id)->update([

                                'marks' => $reader[$data[0]],

                            ]);
                        }

                    }

                });

            }


        }else{

            Excel::import(new ExcelDataImport($this->header),$this->fileData);

        }
    }
}
