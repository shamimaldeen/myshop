@extends('layouts.dashboard')
@section('content')

    <section id="horizontal-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <button id="btn_add" name="btn_add" class="btn btn-primary font-weight-bold"><i class="fa fa-plus"></i> Add New Category</button>
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
                            <th>Company Name</th>
                            <th>Category Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody id="products-list" name="products-list">
                        @foreach($category as $key => $product)
                            <tr id="product{{$product->id}}">
                                <td>{{++$key}}</td>
                                <td> {{$product->company->name}}</td>
                                <td> {{$product->name}}</td>
                                <td>
                                    @if($product->status == 1)
                                        <label class="label label-success">Active</label>
                                    @else
                                        <label class="label label-warning">Deactive</label>
                                    @endif
                                </td>
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
                    <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-list"></i> Manage Category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="frmProducts" name="frmProducts" class="form-horizontal" novalidate="">

                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 text-right control-label font-weight-bold uppercase">Company : </label>
                            <div class="col-sm-8">
                                <select name="company_id" id="company_id" class="form-control font-weight-bold has-error" >
                                    <option value="" class="font-weight-bold">Select Company</option>
                                    @foreach($company as $c)
                                        <option value="{{ $c->id }}" class="font-weight-bold">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 text-right control-label font-weight-bold uppercase">Name : </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control has-error font-weight-bold " id="name" name="name" placeholder="Category Name" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 text-right control-label font-weight-bold uppercase">Status : </label>
                            <div class="col-sm-8">
                                <select name="status" id="status" class="form-control font-weight-bold has-error" >
                                    <option value="1" class="font-weight-bold">Active</option>
                                    <option value="0" class="font-weight-bold">Deactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-8 offset-3">
                                <button type="button" class="btn btn-primary btn-block font-weight-bold text-uppercase" id="btn-save" value="add"><i class="fa fa-send"></i> Save Category</button>
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

        var url = '{{ url('/admin/manage-category') }}';
        //display modal form for product editing
        $(document).on('click','.open_modal',function(){
            var product_id = $(this).val();

            $.get(url + '/' + product_id, function (data) {
                //success data
                console.log(data);
                $('#product_id').val(data.id);
                $('#name').val(data.name);
                $('#company_id').val(data.company_id);
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
                company_id: $('#company_id').val(),
                name: $('#name').val(),
                status: $('#status').val(),

            };
            //used to determine the http verb to use [add=POST], [update=PUT]
            var state = $('#btn-save').val();
            var type = "POST"; //for creating new resource
            var product_id = $('#product_id').val();;
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
                    var product = '<tr id="product' + data.id + '"><td>' + data.id + '</td><td>' + data.company + '</td><td>' + data.name + '</td><td>'+ '<label class="label label-'+(data.status == 1 ? 'success' :'warning')+ '">' +(data.status == 1 ? 'Active' :'Deactive') +'</label></td>';
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
                toastr.success('Successfully Category Saved.');
            });
        });
    </script>

@endsection