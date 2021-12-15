<!DOCTYPE html>
<html>
<head>
    <title>Laravel Excel upload</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script></head>

    <script>

        $(document).ready(function(){
            setTimeout(() => {
                $('.alert').hide();
            }, 2000);
        })

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
                    <input type="file" name="file" class="form-control">
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
                        <button type="submit" class="btn btn-success">Upload</button>
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
