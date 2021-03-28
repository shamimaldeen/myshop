@extends('layouts.dashboard')
@section('import_style')
@endsection
@section('style')
    <style>
        td{
            font-weight: bold;
            font-size: 14px;
        }
        .select2-selection,.select2-results{
            font-weight: bold !important;
        }
    </style>
@endsection
@section('content')


    <section id="horizontal-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <button id="btn_add" name="btn_add" class="btn btn-primary font-weight-bold text-uppercase"><i class="fa fa-plus"></i> Add New Staff</button>
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
                                    <th>ID</th>
                                    <th>Staff Name</th>
                                    <th>Staff Email</th>
                                    <th>Staff Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="products-list" name="products-list">
                                @foreach ($staff as $product)
                                    <tr id="product{{$product->id}}">
                                        <td>{{$product->id}}</td>
                                        <td>{{$product->name}}</td>
                                        <td>{{$product->email}}</td>
                                        <td>
                                            @if($product->status == 1)
                                                Active
                                            @else
                                                Deactive
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-detail open_modal font-weight-bold text-uppercase" value="{{$product->id}}"><i class="fa fa-edit"></i> EDIT Staff</button>
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
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-lg" >
                <div class="modal-header">
                    <h4 class="modal-title font-weight-bold text-uppercase" id="myModalLabel"><i class="fa fa-indent"></i> Manage Staff</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="frmProducts" name="frmProducts" class="form-horizontal" autocomplete="off" novalidate="">
                        <div class="form-group">
                            <label for="inputName" class="col-sm-12 control-label font-weight-bold text-uppercase">Staff Name : </label>
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <input type="text" class="form-control has-error font-weight-bold " id="name" name="name" placeholder="Staff Name" value="">
                                    <span class="input-group-addon"><strong><i class="fa fa-file-text-o"></i></strong></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-12 control-label font-weight-bold text-uppercase">Staff Email : </label>
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <input type="text" class="form-control has-error font-weight-bold " id="email" name="email" placeholder="Staff Email" value="">
                                    <span class="input-group-addon"><strong><i class="fa fa-envelope-open-o"></i></strong></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-12 control-label font-weight-bold text-uppercase">Staff Password : </label>
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <input type="text" class="form-control has-error font-weight-bold " id="password" name="password" placeholder="Staff Password" autocomplete="new1-password" value="">
                                    <span class="input-group-addon"><strong><i class="fa fa-key"></i></strong></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-12 control-label font-weight-bold text-uppercase">Confirm Password : </label>
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <input type="text" class="form-control has-error font-weight-bold " id="password_confirmation" name="password_confirmation" autocomplete="new2-password" placeholder="Confirm Password" value="">
                                    <span class="input-group-addon"><strong><i class="fa fa-key"></i></strong></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-12 control-label font-weight-bold text-uppercase">Staff Status : </label>
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <select name="status" id="status" class="form-control font-weight-bold text-uppercase">
                                        <option value="1" class="font-weight-bold text-uppercase">Active</option>
                                        <option value="0" class="font-weight-bold text-uppercase">Deactive</option>
                                    </select>
                                    <span class="input-group-addon"><strong><i class="fa fa-check"></i></strong></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12 col-sm-offset-3">
                                <button type="button" class="btn btn-primary btn-block font-weight-bold text-uppercase" id="btn-save" value="add"><i class="fa fa-send"></i> Save Staff</button>
                                <input type="hidden" id="product_id" name="product_id" value="0">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <meta name="_token" content="{!! csrf_token() !!}" />
@endsection

@section('scripts')

    <script>

        var url = '{{ url('/admin/manage-staff') }}';
        //display modal form for product editing
        $(document).on('click','.open_modal',function(){
            var product_id = $(this).val();

            $.get(url + '/' + product_id, function (data) {
                //success data
                console.log(data);
                $('#product_id').val(data.id);
                $('#name').val(data.name);
                $('#email').val(data.email);
                $('#status').val(data.status);
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
                name: $('#name').val(),
                email: $('#email').val(),
                status: $('#status').val(),
                password: $('#password').val(),
                password_confirmation: $('#password_confirmation').val(),
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
                    var product = '<tr id="product' + data.id + '"><td>' + data.id + '</td><td>' + data.name + '</td><td>' + data.email + '</td><td>' + data.staffStatus + '</td>';
                    product += '<td><button class="btn btn-primary btn-detail open_modal font-weight-bold text-uppercase" value="' + data.id + '"><i class="fa fa-edit"></i> EDIT Staff</button></td></tr>';

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
                toastr.success('Successfully Staff Saved.');
            });
        });
    </script>
@endsection