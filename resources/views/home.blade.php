<!DOCTYPE html>
<html>
<head>
    <title>Laravel Excel upload</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script></head>

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
                    <input type="file" name="file" class="form-control">
                </div>

                <div class="col-md-6">
                    <button type="submit" class="btn btn-success">Upload</button>
                </div>

            </div>
        </form>

      </div>
    </div>
    <br><br>
    <div class="row">

        <div class="col-md-3">
            <a href="{{ URL::to('file_read/xlsx') }}" class="btn btn-success">Download Xlsx</a>
        </div>

        <div class="col-md-3">
            <a href="{{ URL::to('file_read/xls') }}" class="btn btn-primary">Download Xls</a>
        </div>

        <div class="col-md-3">
            <a href="{{ URL::to('file_read/csv') }}" class="btn btn-info">Download Csv</a>
        </div>

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

               @foreach ($recordData as $key=> $recordDatas)
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
