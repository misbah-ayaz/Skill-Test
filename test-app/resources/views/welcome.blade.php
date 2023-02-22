<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="content">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Test Case</title>
    <style type="text/css">
        body{
            background-color: #d2d2d2 !important;
        }
        .dropdown{
            background-color: #fff;
        }
        .sub{
            width:auto !important;
            background: mediumpurple !important;
            color: white !important;
        }
    </style>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5 justify-content-center">
        <div class="row">
            <div class="col-md-12 dropdown rounded p-5 mt-5">
                <div class="row">
                    <div class="col-md-8 mb-3">
                       <h4>Laravel Test Case</h4>
                    </div> 
                    
                </div>
                <form action="/welcome2" method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="form-group mb-3">
                        <label class="form-control-label" for="supplier">supplier</label>
                        <select  id="supplier-dropdown" name="supplier" class="form-control">
                            <option value="">-- Select Supplier --</option>
                            @foreach ($suppliers as $data)
                                <option value="{{$data->id}}">
                                    {{$data->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-control-label" for="seller">Seller</label>
                        <select id="seller-dropdown" name="seller" class="form-control">
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="steering">Steering</label>
                        <select id="steering-dropdown" name="steering" class="form-control">
                        </select>
                    </div><br>
                    <button class="form-control sub"  type="submit" >Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>
  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {

        /*------------------------------------------
        --------------------------------------------
        Supplier Dropdown Change Event
        --------------------------------------------
        --------------------------------------------*/
        $('#supplier-dropdown').on('change', function () {
            var idSupplier = this.value;
            console.log(idSupplier);
            $("#seller-dropdown").html('');
        
            $.ajax({
                url: "{{url('api/fetch-sellers')}}",
                type: "GET",
                data: {
                    // supplier_id: idSupplier,
                    // _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (result) {
                    $('#seller-dropdown').html('<option value="">-- Select Seller --</option>');
                    $.each(result.sellers, function (key, value) {
                        // console.log(result.sellers);
                        $("#seller-dropdown").append('<option value="' + value
                            .id + '">' + value.seller_type + '</option>');
                    });
                    $('#steering-dropdown').html('<option value="">-- Select Steering Type --</option>');
                }
            });
        });

        /*------------------------------------------
        --------------------------------------------
        Seller Dropdown Change Event
        --------------------------------------------
        --------------------------------------------*/
        $('#seller-dropdown').on('change', function () {
            var idSeller = this.value;
            $("#steering-dropdown").html('');
            $.ajax({
                url: "{{url('api/fetch-steerings')}}",
                type: "GET",
                data: {
                    // seller_id: idSeller,
                    // _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (res) {
                    $('#steering-dropdown').html('<option value="">-- Select Steering Type --</option>');
                    $.each(res.steerings, function (key, value) {
                        $("#steering-dropdown").append('<option value="' + value
                            .id + '">' + value.type + '</option>');
                    });
                }
            });
        });
    });
</script>
</html>