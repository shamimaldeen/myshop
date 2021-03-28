@extends('layouts.dashboard')
@section('content')

    <section id="horizontal-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <button id="btn_add" name="btn_add" class="btn btn-primary font-weight-bold"><i class="fa fa-plus"></i> Add Company</button>
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

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Company Name</th>
                                        <th>Company Email</th>
                                        <th>Company Address</th>
                                        <th>Total Send</th>
                                        <th>Total Pay</th>
                                        <th>Total Due</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody id="products-list" name="products-list">
                                    @foreach($company as $product)
                                        <tr id="product{{$product->id}}">
                                            <td>{{ $product->id }}</td>
                                            <td> {{ $product->name }}</td>
                                            <td> {{ $product->email }} <br>{{ $product->phone }}</td>
                                            <td> {{ $product->address }}</td>
                                            <td> {{ $product->total_send }} - {{ $basic->currency }}</td>
                                            <td> {{ $product->total_pay }} - {{ $basic->currency }}</td>
                                            <td> {{ $product->total_send - $product->total_pay }} - {{ $basic->currency }}</td>
                                            <td>
                                                <button class="btn btn-primary btn-detail open_modal font-weight-bold uppercase" value="{{$product->id}}"><i class="fa fa-edit"></i></button>
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
        </div>
    </section><!---ROW-->

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" >
                <div class="modal-header bg-primary white">
                    <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-bank"></i> Manage Company</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="frmProducts" name="frmProducts" class="form-horizontal" novalidate="">
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 text-right control-label font-weight-bold uppercase">Company Name : </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control has-error font-weight-bold " id="name" name="name" placeholder="Company Name" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 text-right control-label font-weight-bold uppercase">Company Email : </label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control has-error font-weight-bold " id="email" name="email" placeholder="Company Email" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 text-right control-label font-weight-bold uppercase">Company Phone : </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control has-error font-weight-bold" id="phone" name="phone" placeholder="Company Phone" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 text-right control-label font-weight-bold uppercase">Company Address : </label>
                            <div class="col-sm-8">
                                <textarea class="form-control has-error font-weight-bold" id="address" rows="3" name="address" placeholder="Company Address"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-8 offset-3">
                                <button type="button" class="btn btn-primary btn-block font-weight-bold uppercase" id="btn-save" value="add"><i class="fa fa-send"></i> Save Company</button>
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

        var url = '{{ url('/admin/manage-company') }}';
        //display modal form for product editing
        $(document).on('click','.open_modal',function(){
            var product_id = $(this).val();
            $.get(url + '/' + product_id, function (data) {
                //success data
                console.log(data);
                $('#product_id').val(data.id);
                $('#name').val(data.name);
                $('#email').val(data.email);
                $('#phone').val(data.phone);
                $('#address').val(data.address);
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
                phone: $('#phone').val(),
                address: $('#address').val()
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
                    var due = data.total_send - data.total_pay;
                    var product = '<tr id="product' + data.id + '"><td>' + data.id + '</td><td>' + data.name + '</td><td>' + data.email + ' </br>' + data.phone + '</td><td>' + data.address + '</td><td>' + data.total_send + ' - {{ $basic->currency }}</td><td>' + data.total_pay + ' - {{ $basic->currency }}</td><td>' + due + ' - {{ $basic->currency }}</td>';
                    product += '<td><button class="btn btn-primary btn-detail open_modal font-weight-bold uppercase" value="' + data.id + '"><i class="fa fa-edit"></i></button></td></tr>';

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
                toastr.success('Successfully Company Saved.');
            });
        });
    </script>

@endsection