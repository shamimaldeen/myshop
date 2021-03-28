@extends('layouts.dashboard')
@section('content')
    <section id="horizontal-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <button id="btn_add" name="btn_add" class="btn btn-primary font-weight-bold text-uppercase"><i class="fa fa-plus"></i> Add New Instalment</button>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                <li><a data-action="close"><i class="ft-x"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collpase show">
                        <div class="card-body">

                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Duration</th>
                                    <th>Time</th>
                                    <th>Charge</th>
                                    <th>Difference</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="products-list" name="products-list">
                                @foreach($instalment as $key => $product)
                                    <tr id="product{{$product->id}}">
                                        <td>{{++$key}}</td>
                                        <td> {{$product->name}}</td>
                                        @if($product->duration_type == 0)
                                            <td> {{$product->duration}} - Month</td>
                                        @elseif($product->duration_type == 1)
                                            <td> {{$product->duration}} - Weeks</td>
                                        @else
                                            <td> {{$product->duration}} - Days</td>
                                        @endif

                                        <td> {{$product->time}} - Time</td>
                                        <td> {{$product->charge}}%</td>
                                        <td> {{$product->difference}} - Days</td>
                                        <td>
                                            <button class="btn btn-primary btn-detail open_modal font-weight-bold uppercase" value="{{$product->id}}"><i class="fa fa-edit"></i> EDIT</button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!---ROW-->

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" >
                <div class="modal-header bg-gradient-radial-primary white">
                    <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-list"></i> Manage Installment</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="frmProducts" name="frmProducts" class="form-horizontal" novalidate="">

                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 text-right control-label font-weight-bold uppercase">Instalment Name : </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input name="name" id="name" class="form-control bold" value="" placeholder="Instalment Name"/>
                                    <span class="input-group-addon"><strong><i class="fa fa-file-text-o"></i></strong></span>
                                </div>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 text-right control-label font-weight-bold uppercase">Duration Type : </label>
                            <div class="col-md-8">
                                <select name="duration_type" id="duration_type"class="form-control select2">
                                    <option value="">Select Duration Type</option>
                                    <option value="0" class="">Month</option>
                                    <option value="1" class="">Week</option>
                                    <option value="2" class="">Day</option>
                                </select>
                            </div>
                        </div>


                        <div id="month">
                            <div class="form-group row">
                                <label for="inputName" class="col-sm-3 text-right control-label font-weight-bold uppercase">Total Duration : </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input name="duration" id="duration" type="number" class="form-control bold" value="" placeholder="Total Duration"/>
                                        <span class="input-group-addon"><strong>Month/Day/Week</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>

{{--                        <div id="week" style="display: none">--}}
{{--                            <div class="form-group row">--}}
{{--                                <label for="inputName" class="col-sm-3 text-right control-label font-weight-bold uppercase">Total Duration : </label>--}}
{{--                                <div class="col-sm-8">--}}
{{--                                    <div class="input-group">--}}
{{--                                        <input name="duration" id="duration" type="number" class="form-control bold" value="" placeholder="Total Duration"/>--}}
{{--                                        <span class="input-group-addon"><strong>Week</strong></span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div id="day" style="display: none">--}}
{{--                            <div class="form-group row">--}}
{{--                                <label for="inputName" class="col-sm-3 text-right control-label font-weight-bold uppercase">Total Duration : </label>--}}
{{--                                <div class="col-sm-8">--}}
{{--                                    <div class="input-group">--}}
{{--                                        <input name="duration" id="duration" type="number" class="form-control bold" value="" placeholder="Total Duration"/>--}}
{{--                                        <span class="input-group-addon"><strong>Day</strong></span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}



                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 text-right control-label font-weight-bold uppercase">Installment Time : </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input name="time" id="time" type="number" class="form-control bold" value="" placeholder="Installment Time"/>
                                    <span class="input-group-addon"><strong>Time</strong></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 text-right control-label font-weight-bold uppercase">Installment Charge : </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input name="charge" id="charge" type="text" class="form-control bold" value="" placeholder="Installment Charge"/>
                                    <span class="input-group-addon"><strong><i class="fa fa-percent"></i></strong></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-8 offset-3">
                                <button type="button" class="btn btn-primary btn-block font-weight-bold text-uppercase" id="btn-save" value="add"><i class="fa fa-send"></i> Save Instalment</button>
                                <input type="hidden" id="product_id" name="product_id" value="0">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <meta name="_token" content="{!! csrf_token() !!}" />
    <!-- Modal for DELETE -->

@endsection
@section('scripts')

    <script>

        // $('#duration_type').on('change',function (e) {
        //     var duration_type = e.target.value;
        //     if(duration_type == 0){
        //         document.getElementById("month").style.display = 'block';
        //         document.getElementById("week").style.display = 'none';
        //         document.getElementById("day").style.display = 'none';
        //     }else if (duration_type == 1){
        //         document.getElementById("month").style.display = 'none';
        //         document.getElementById("week").style.display = 'block';
        //         document.getElementById("day").style.display = 'none';
        //     }else {
        //         document.getElementById("month").style.display = 'none';
        //         document.getElementById("week").style.display = 'none';
        //         document.getElementById("day").style.display = 'block';
        //     }
        // });

        var url = '{{ url('/admin/manage-instalment') }}';
        //display modal form for product editing
        $(document).on('click','.open_modal',function(){
            var product_id = $(this).val();

            $.get(url + '/' + product_id, function (data) {
                console.log(data);
                $('#product_id').val(data.id);
                $('#name').val(data.name);
                $('#duration_type').val(data.duration_type);
                $('#duration').val(data.duration);
                $('#time').val(data.time);
                $('#charge').val(data.charge);
                $('#btn-save').val("update");
                $('#myModal').modal('show');
            })
        });
        //display modal form for creating new product
        $('#btn_add').click(function(){
            $('#btn-save').val("add");
            $('#frmProducts').trigger("reset");
            $('#myModal').modal('show');
        });
        //create new product / update existing product
        $("#btn-save").click(function (e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            e.preventDefault();
            var formData = {
                company_id: $('#company_id').val(),
                name: $('#name').val(),
                duration_type: $('#duration_type').val(),
                duration: $('#duration').val(),
                time: $('#time').val(),
                charge: $('#charge').val()
            };
            //used to determine the http verb to use [add=POST], [update=PUT]
            var state = $('#btn-save').val();
            var type = "POST"; //for creating new resource
            var product_id = $('#product_id').val();
            var my_url = url;
            if (state == "update"){
                type = "PUT"; //for updating existing resource
                my_url += '/' + product_id;
            }
            console.log(formData);
            $.ajax({
                type: type,
                url: my_url,
                data: formData,
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    var product = '<tr id="product' + data.id + '"><td>' + data.id + '</td><td>' + data.name + '</td><td>' + data.duration + ' - Month</td><td>' + data.time + ' - Times</td><td>' + data.charge + '%</td><td>' + data.difference + ' - Days</td>';
                    product += '<td><button class="btn btn-primary btn-detail open_modal font-weight-bold uppercase" value="' + data.id + '"><i class="fa fa-edit"></i> EDIT</button> </td></tr>';
                    if (state == "add"){ //if user added a new record
                        $('#products-list').append(product);
                    }else{ //if user updated an existing record
                        $("#product" + product_id).replaceWith( product );
                    }
                    $('#frmProducts').trigger("reset");
                    $('#myModal').modal('hide')
                },
                error: function(data)
                {
                    $.each( data.responseJSON.errors, function( key, value ) {
                        toastr.error( value);
                    });
                }

            }).done(function() {
                toastr.success('Successfully Instalment Saved.');
                location.reload();
            });
        });
    </script>

@endsection