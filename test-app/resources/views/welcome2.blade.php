<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="content">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Dynamic Dropdown</title>
    <style type="text/css">
        body{
            background-color: #d2d2d2 !important;
        }
        .dropdown{
            background-color: #fff;
        }
        .wd{
            width:127px;
        }
        .btnAdd{
            width:130px !important;
            margin-top:1rem !important;
            margin-bottom:1rem !important;
            border-radius:5px !important;
            border: 1px solid grey !important;
            margin-left: 11px !important;
            background: mediumpurple !important;
            color: white !important;
        }
        .hidee{
            display:none;
        }
        .table input{
            border:none;
        }
        .filterbtn{
            width:auto !important;
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
                       <h4>Laravel Dynamic</h4>
                    </div> 
                    <div class="col-md-3 mb-3 text-end">
                       
                    </div>
                </div>
                <form>
                @csrf
                    <div class="form-group mb-3">
                        <label class="form-control-label" for="model">Model</label>
                        <select  id="model-dropdown" class="form-control">
                            <option value="">-- Select Model --</option>
                            @foreach ($models as $data)
                                <option value="{{$data->id}}">
                                    {{$data->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-control-label" for="sfx">SFX</label>
                        <select id="sfx-dropdown" class="form-control">
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="variant">Variant</label>
                        <select id="variant-dropdown" class="form-control">
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="color">Color</label>
                        <select id="color-dropdown" class="form-control">
                        </select>
                    </div>
                </form>
                <div class="row" style="margin-top:2rem;" id="clickbox">
                    <div class="col-lg-10">
                        <form id="user_form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <table id="table" class="table table-bordered table-striped update">
                            <thead>
                            </thead>
                            <tbody id="exampleid">
                               
                                </tr>
                            </tbody>
                            <input type="hidden" id="hiddenrow" name="hiddenrow" value=""/>
                        </table>
                        <button id='submit' type="submit" class='btnAdd hidee'>Submit</button>
                        </form>
                    </div>
                    <div><button id='add' class='btnAdd hidee'>Add new row</button></div><br><br>
                </div>
                <div class="filters">
                    <div class="filter">
                    <label class="form-control-label" for="month">Search</label>
                        <input name="month" placeholder="Enter Month i.e January">
                    </div>
                    <br>
                    <button class="do-filter form-control filterbtn">Filter</button>
                    <br><br>
                </div>
                <table id="filtered-data" class="table table-bordered table-striped">
                    <tbody id="quantity">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
       jQuery('.do-filter').click(function (e) {
        e.preventDefault();
        console.log(jQuery('input[name="month"]').val());
        var month = jQuery('input[name="month"]').val();
        $.ajax({
            url:"{{url('api/filter')}}",
            type:'post',
            data: {
                    month: month,
                    _token: '{{csrf_token()}}'
                },
            success:function(response){
                console.log("hj");
                $('#quantity').append("<tr>\
                <th>Supplier</th>\
                <th>Quantity</th>\
                <th>Month</th>\
                <th>Year</th>\
                </tr>"
                )
                $.each(response.collection, function (key, value) {
                    console.log(response.collection);
                    $('#quantity').append("<tr style='height:3rem'>\
                    <td><input type='hidden' value="+ value.id +" name='id[]' id='id' class='id' /><input class='wd supplier' value="+ value.supplier +" name='supplier[]' id='supplier' /></td>\
                    <td><input class='wd' value="+ value.quantity +" name='quantity[]' id='quantity'/></td><td><input class='wd' value="+ value.month +" name='month[]' id='month'/></td><td><input class='wd' value="+ value.year +" name='year[]' id='year'/></td>"
                    );
                })
            }
        });
        // jQuery.post('api/filter', {
        //     _token: window.csrf_token,
        //     month: jQuery('input[name="month"]').val(),
        // }, function (data) {
        //     var $tableBody = jQuery('#filtered-data tbody');
        //     $tableBody.html('');
            
        //     jQuery.each(data, function (i) {
        //         $tableBody.append(
        //             '<tr>' +
        //                 '<td>' + data[i].id + '</td>' +
        //                 '<td>' + data[i].gender + '</td>' +
        //                 '<td>' + data[i].status + '</td>' +
        //                 '<td>' + data[i].age + '</td>' +
        //             '</tr>'
        //         ); 
        //     });
        // }, 'json');
    })

        /*------------------------------------------
        --------------------------------------------
        Model Dropdown Change Event
        --------------------------------------------
        --------------------------------------------*/
        $('#model-dropdown').on('change', function () {
            var idModel = this.value;
            console.log(idModel);
            $("#sfx-dropdown").html('');
            $.ajax({
                url: "{{url('api/fetch-sfxs')}}",
                type: "POST",
                data: {
                    model_id: idModel,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (result) {
                    $('#sfx-dropdown').html('<option value="">-- Select SFX --</option>');
                    $.each(result.sfxs, function (key, value) {
                        $("#sfx-dropdown").append('<option value="' + value.sfx_id + '">' + value.sfx + '</option>');
                    });
                    $('#variant-dropdown').html('<option value="">-- Select Variant --</option>');
                    $('#color-dropdown').html('<option value="">-- Select Color --</option>');
                }
            });
        });

        /*------------------------------------------
        --------------------------------------------
        SFX Dropdown Change Event
        --------------------------------------------
        --------------------------------------------*/
        $('#sfx-dropdown').on('change', function () {
            var idSFX = this.value;
            $("#variant-dropdown").html('');
            $.ajax({
                url: "{{url('api/fetch-variants')}}",
                type: "POST",
                data: {
                    sfx_id: idSFX,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (res) {
                    $('#variant-dropdown').html('<option value="">-- Select Variant --</option>');
                    $.each(res.variants, function (key, value) {
                        $("#variant-dropdown").append('<option value="' + value.variant_id + '">' + value.variant + '</option>');
                    });
                    $('#color-dropdown').html('<option value="">-- Select Color --</option>');
                }
            });
        });
         /*------------------------------------------
        --------------------------------------------
        Variant Dropdown Change Event
        --------------------------------------------
        --------------------------------------------*/
        $('#variant-dropdown').on('change', function () {
            var idVariant = this.value;
            
            $("#color-dropdown").html('');
            $.ajax({
                url: "{{url('api/fetch-colors')}}",
                type: "POST",
                data: {
                    variant_id: idVariant,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (res) {
                    $('#color-dropdown').html('<option value="">-- Select Color --</option>');
                    console.log(res);
                    $.each(res.colors, function (key, value) {
                        
                        $("#color-dropdown").append('<option value="' + value.color_id + '">' + value.color + '</option>');
                    });
            
                }
            });
        });
        $('#color-dropdown').on('change', function () {
            var idColor = this.value;
            
            // $("#color-dropdown").html('');
            $.ajax({
                url: "{{url('api/save-data')}}",
                type: "POST",
                data: {
                    color_id: idColor,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (res) {
                    
                    console.log(res);
                    $('#exampleid').append("<tr>\
                            <th>Supplier</th>\
                            <th>Seller</th>\
                            <th>Steering Type</th>\
                            <th>Model</th>\
		                    <th>SFX</th>\
                            <th>Variant</th>\
                            <th>Color</th>\
                            </tr>"
                            )
                    $.each(res.all_data, function (key, value) {
                        
                        $('#exampleid').append("<tr style='height:3rem'>\
                        <td><input type='hidden' value="+ value.id +" name='id[]' id='id' class='id' /><input class='wd supplier' value="+ value.supplier +" name='supplier[]' id='supplier' /></td>\
                        <td><input class='wd' value="+ value.seller +" name='seller[]' id='seller'/></td>\
                        <td><input class='wd' value="+ value.steering +" name='steering[]' id='steering' /></td>\
                        <td><input class='wd' value="+ value.model +" name='model[]' id='model' /></td>\
                        <td><input class='wd' value="+ value.sfx +" name='sfx[]' id='sfx' /></td>\
                        <td><input class='wd' value="+ value.variant +" name='variant[]' id='variant' /></td>\
                        <td><input class='wd' value="+ value.color +" name='color[]' id='color'/></td>"
                        );
                    })
                    $('#add').css('display', 'block');
                    $('#submit').css('display', 'block');
                    window.addEventListener('click', function(e){
                        if (document.getElementById('add').contains(e.target)){
                            $("#table tbody tr:last").clone().appendTo('#table tbody').find("input").val("");
                            e.preventDefault();
                        } 
                    })
                    window.addEventListener('click', function(e){
                        if (document.getElementById('submit').contains(e.target)){
                            // var supplier = $("#supplier").val();
                            // var seller = $("#seller").val();
                            // var steering = $("#steering").val();
                            // var sfx = $("#sfx").val();
                            // var variant = $("#variant").val();
                            // var model = $("#model").val();
                            // var color = $("#color").val();
                            // var row = "<tr><td>" + supplier + "</td><td>" + seller + "</td><td>" + steering + "</td><td>" + model + "</td><td>" + sfx + "</td><td>" + variant + "</td><td>" + color + "</td><td>";
                            // $("#table tbody").append(row);
                            e.preventDefault();
                            var i = $(document).find('.supplier').length;
                            console.log($(document).find('.supplier').length);
                            if(i){
                                // console.log(i);
                                document.getElementById('hiddenrow').value=i;
                        
                            }
                            
                            var data=$('#user_form').serialize();
                            console.log($("#user_form").serialize());
                            
                            $.ajax({
                                url:"{{url('api/save_data')}}",
                                type:'post',
                                data:data,
                                success:function(response){
                                var countt = response.count;
                                document.getElementById('hiddenrow').value=countt;
                                 console.log(response);
                                 $('#exampleid').empty();
                                 $('#hiddenrow').val("");
                                 $('#exampleid').append("<tr>\
                                    <th>Supplier</th>\
                                    <th>Seller</th>\
                                    <th>Steering Type</th>\
                                    <th>Model</th>\
                                    <th>SFX</th>\
                                    <th>Variant</th>\
                                    <th>Color</th>\
                                    </tr>"
                                    )
                                 $.each(response.all_data2, function (key, value) {
                               
                                $('#exampleid').append("<tr style='height:3rem'>\
                                <td><input type='hidden' value="+ value.id +" name='id[]' id='id' class='id' /><input class='wd supplier' value="+ value.supplier +" name='supplier[]' id='supplier' /></td>\
                                <td><input class='wd' value="+ value.seller +" name='seller[]' id='seller'/></td>\
                                <td><input class='wd' value="+ value.steering +" name='steering[]' id='steering' /></td>\
                                <td><input class='wd' value="+ value.model +" name='model[]' id='model' /></td>\
                                <td><input class='wd' value="+ value.sfx +" name='sfx[]' id='sfx' /></td>\
                                <td><input class='wd' value="+ value.variant +" name='variant[]' id='variant' /></td>\
                                <td><input class='wd' value="+ value.color +" name='color[]' id='color'/></td>"
                                );
                            })
                                }
                            });
                        } 
                    })

                   
                }
            });
        });
    });
</script>

</html>