<?php

namespace App\Http\Controllers;

use App\Models\ExcelData;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExcelDataExport;
use App\Imports\ExcelDataImport;
use App\Http\Requests\ExcelDataRequest;
use App\Jobs\ExcelDataProcess;
use App\Jobs\ExcelDataUpdateProcess;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

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

        return view('file_upload',compact('recordData'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fileUploadPost(ExcelDataRequest $request)
    {
        ini_set('max_execution_time', 1000);

        if($request->header){
            $header = $request->header;
        }else{
            $header = null;
        }

        $fileType= $request->file->getClientOriginalExtension();

        $fileName = time().'.'.$fileType;

        $request->file->move(public_path('uploads'), $fileName);

        $fileData = public_path('uploads/').$fileName;


        $batch = Bus::batch([

            new ExcelDataProcess($fileType,$fileData,$header),

        ])->dispatch();

        // ExcelDataProcess::dispatch();


        return back()->with('success','You have successfully upload file.');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return view('new_value_upload');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExcelData  $excelData
     * @return \Illuminate\Http\Response
     */
    public function fileExport($type,$header,ExcelData $excelData)
    {

        ini_set('max_execution_time', 1000);

        if($type == 'xlsx'){

            if($header == 'header'){
                return (new FastExcel($excelData::all()))->download('file.xlsx');
            }else{
                return Excel::download(new ExcelDataExport($header), 'file.xlsx');
            }
        }
        if($type == 'xls'){

            return Excel::download(new ExcelDataExport($header), 'file.xls');
        }
        if($type == 'csv'){

            $data = $this->exceldata->orderBy('created_at', 'DESC')->get();

            return (new FastExcel($data))->download('file.csv');
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
    public function update(ExcelDataRequest $request)
    {
        ini_set('max_execution_time', 1000);

        if($request->header){
            $header = $request->header;

        }else{
            $header = null;
        }

        $fileType= $request->file->getClientOriginalExtension();

        $fileName = time().'.'.$fileType;

        $request->file->move(public_path('uploads'), $fileName);

        $fileData = public_path('uploads/').$fileName;

        $batch = Bus::batch([

            new ExcelDataUpdateProcess($fileType,$fileData,$header),

        ])->dispatch();
        
        return back()->with('success','You have successfully upload file.');


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
