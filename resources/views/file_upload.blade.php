<!DOCTYPE html>
<html>
<head>
    <title>Laravel Excel upload</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
</head>

    <script>

        $(document).ready(function(){
            setTimeout(() => {
                $('.alert').hide();
            }, 2000);
        })

        function uploadFile(){

            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;
            /*Checks whether the file is a valid excel file*/
            if (regex.test($("#input_file").val().toLowerCase())) {
                var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/
                if ($("#input_file").val().toLowerCase().indexOf(".xlsx") > 0) {
                    xlsxflag = true;
                }
                /*Checks whether the browser supports HTML5*/
                if (typeof (FileReader) != "undefined") {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var data = e.target.result;
                        /*Converts the excel data in to object*/
                        if (xlsxflag) {
                            var workbook = XLSX.read(data, { type: 'binary' });
                        }
                        else {
                            var workbook = XLS.read(data, { type: 'binary' });
                        }
                        /*Gets all the sheetnames of excel in to a variable*/
                        var sheet_name_list = workbook.SheetNames;

                        var cnt = 0; /*This is used for restricting the script to consider only first sheet of excel*/

                        sheet_name_list.forEach(function (y) { /*Iterate through all sheets*/
                            /*Convert the cell value to Json*/
                            if (xlsxflag) {
                                var exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[y]);
                            }
                            else {
                                var exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);
                            }
                            if (exceljson.length > 0 && cnt == 0) {
                                BindTable(exceljson, '#exceltable');
                                cnt++;
                            }

                            console.log(exceljson);

                        });

                        $('#exceltable').show();
                    }
                    if (xlsxflag) {/*If excel file is .xlsx extension than creates a Array Buffer from excel*/
                        reader.readAsArrayBuffer($("#input_file")[0].files[0]);
                    }
                    else {
                        reader.readAsBinaryString($("#input_file")[0].files[0]);
                    }
                }
                else {
                    alert("Sorry! Your browser does not support HTML5!");
                }
            }
            else {
                alert("Please upload a valid Excel file!");
            }

            sendFileUpload()
        }

        function BindTable(jsondata, tableid) {/*Function used to convert the JSON array to Html Table*/
            var columns = BindTableHeader(jsondata, tableid); /*Gets all the column headings of Excel*/
            var size = 10;
            var items = jsondata.slice(0, size);

            for (var i = 0; i < items.length; i++) {
                var row$ = $('<tr/>');
                for (var colIndex = 0; colIndex < columns.length; colIndex++) {
                    var cellValue = jsondata[i][columns[colIndex]];
                    if (cellValue == null)
                        cellValue = "";
                    row$.append($('<td/>').html(cellValue));
                }
                $(tableid).append(row$);
            }
        }
        function BindTableHeader(jsondata, tableid) {/*Function used to get all column names from JSON and bind the html table header*/
            var columnSet = [];
            var headerTr$ = $('<tr/>');
            for (var i = 0; i < jsondata.length; i++) {
                var rowHash = jsondata[i];
                for (var key in rowHash) {
                    if (rowHash.hasOwnProperty(key)) {
                        if ($.inArray(key, columnSet) == -1) {/*Adding each unique column names to a variable array*/
                            columnSet.push(key);
                            headerTr$.append($('<th/>').html(key));
                        }
                    }
                }
            }
            $(tableid).append(headerTr$);
            return columnSet;
        }

        function sendFileUpload(){

            var formValue = $('#input_file')[0].files[0];
            // console.log(formData);
            var headerValue = $('input[name="header"]:checked').val();

            if(formValue){
                var fd = new FormData();
                console.log(fd);
                // Append data
                fd.append('file',formValue);
                fd.append('header',headerValue);

                $.ajax({
                        type      : 'POST',
                        url       : '{{ url('/excel-data') }}',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: fd,

                        processData: false,
                        contentType: false,
                        cache: false,

                        success   : function(data) {
                            $('.excel-alert').remove();
                            $('.panel-body').append('<br><br><div class="alert excel-alert alert-success alert-block"><button type="button" class="close" data-dismiss="alert">×</button><strong>'+data.message+'</strong></div>');
                        },
                        error : function(err){
                            console.log(err)
                            $('.excel-alert').remove();

                            $('.panel-body').append('<br><br><div class="alert excel-alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><strong>Whoops!</strong> There were some problems with your input. <ul><li>'+err+'</li></ul></div>');

                        }
                    });
                }
        }

    </script>

<body>
    
<div class="container">

    <div class="panel panel-primary">
      <div class="panel-heading"><h2>laravel Excel data upload</h2></div>
      <div class="panel-body">

        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
        </div>
        @endif

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('file.upload.post') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">

                <div class="col-md-6">
                    <input type="file" name="file" id="input_file" class="form-control">
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="header" id="inlineRadio1" value="header" required>
                    <label class="form-check-label" for="inlineRadio1">With Header</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="header" id="inlineRadio2" value="non-header">
                    <label class="form-check-label" for="inlineRadio2">Without Header</label>
                  </div>
                </div><br>

                  <div class="row">
                    <div class="col-md-6">
                        <button type="button" id="upload_button" onclick="uploadFile()" class="btn btn-success">Upload</button>
                    </div>
                  </div>
        </form>
    <br><a href="{{ route('file_upload_new_value') }}" class="btn btn-primary text-sm text-gray-700 dark:text-gray-500 underline">Upload new value</a>

      </div>
    </div>
    <br><br>

    <div class="row">

        <div class="col-md-3">
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Xlsx
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <a href="{{ URL::to('file_read/xlsx/header') }}" class="dropdown-item">With Header</a>
                <a href="{{ URL::to('file_read/xlsx/nonheader') }}" class="dropdown-item">Non Header</a>
            </div>
          </div>
        </div>

        <div class="col-md-3">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Xls
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                    <a href="{{ URL::to('file_read/xls/header') }}" class="dropdown-item">With Header</a>
                    <a href="{{ URL::to('file_read/xls/nonheader') }}" class="dropdown-item">Non Header</a>
                </div>
              </div>
            </div>

            <div class="col-md-3">
                <div class="dropdown">
                    <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      CSV
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                        <a href="{{ URL::to('file_read/csv/header') }}" class="dropdown-item">With Header</a>
                        <a href="{{ URL::to('file_read/csv/nonheader') }}" class="dropdown-item">Non Header</a>
                    </div>
                  </div>
                </div>
                <br><br>
                <table class="table" id="exceltable">
                </table>
    </div>
    <br><br>

    <div class="row">

       <table class="table table-striped table-dark">
           <thead>
               <td>SI.No</td>
               <td>First Name</td>
               <td>Last Name</td>
               <td>Age</td>
           </thead>
           <tbody>

               @foreach ($recordData as $key => $recordDatas)
            <tr>
                <td>{{$key +1}}</td>
                <td>{{$recordDatas->first_name}}</td>
                <td>{{$recordDatas->last_name}}</td>
                <td>{{$recordDatas->age}}</td>
            </tr>
               @endforeach

           </tbody>
       </table>

       {!! $recordData->render() !!}

    </div>
</div>
</body>

</html>
