<?php

namespace App\Http\Controllers;

use App\Models\ExcelData;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExcelDataExport;
use App\Imports\ExcelDataImport;
use App\Http\Requests\ExcelDataRequest;


class ExcelDataController extends Controller
{
     /**
     * Constructor for instance model.
     */

    public function __construct(ExcelData $excelData)
    {
        $this->exceldata = $excelData;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recordData = $this->exceldata->orderBy('created_at', 'DESC')->paginate(15);

        return view('home',compact('recordData'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fileUploadPost(ExcelDataRequest $request)
    {
        ini_set('max_execution_time', 1000);

        $fileType= $request->file->extension();

        $fileName = time().'.'.$fileType;

        $request->file->move(public_path('uploads'), $fileName);

        $fileData = public_path('uploads/').$fileName;

        if($fileType == "xlsx" || $fileType == "csv"){

            if($fileType == "csv"){

                (new FastExcel)->configureCsv(';', '#', 'gbk')->import($fileData);

            }

            (new FastExcel)->import($fileData, function ($reader) {

                return $this->exceldata->create([
                    'first_name' => $reader['First_Name'],
                    'last_name' => $reader['Last_Name'],
                    'age' => $reader['Age'],
                ]);
            });

        }else{

            Excel::import(new ExcelDataImport,$fileData);

        }

        return back()->with('success','You have successfully upload file.');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExcelData  $excelData
     * @return \Illuminate\Http\Response
     */
    public function showXLSX($type,ExcelData $excelData)
    {

        ini_set('max_execution_time', 1000);

        if($type == 'xlsx'){
            return (new FastExcel($excelData::all()))->download('file.xlsx');
        }
        if($type == 'xls'){
            return Excel::download(new ExcelDataExport, 'file.xls');
        }
        if($type == 'csv'){
            $data = $this->exceldata->orderBy('created_at', 'DESC')->get();
            (new FastExcel($data))->download('file.csv');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExcelData  $excelData
     * @return \Illuminate\Http\Response
     */
    public function edit(ExcelData $excelData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExcelData  $excelData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExcelData $excelData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExcelData  $excelData
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExcelData $excelData)
    {
        //
    }
}
