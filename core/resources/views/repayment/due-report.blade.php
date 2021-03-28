@extends('layouts.dashboard')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/datatables.min.css') }}">
@endsection
@section('content')

@php
    $query = DB:: select('select sum(it.amount) as total_du, it.order_instalment_id,c.* from instalment_times it
                   join order_instalments oi  on oi.id = it.order_instalment_id
                   join customers c on c.id = oi.customer_id
                   where it.status=0 group by it.order_instalment_id');
//dd( $query);
 //@dd($orders);

@endphp


    <section id="horizontal-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="horz-layout-basic">{{$page_title}}</h4>
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
                                <table class="table table-striped table-bordered table-hover zero-configuration">
                                    <thead>
                                    <tr>
                                        <th>ID#</th>
                                        <th>Customer Name</th>
                                        <th>Mobile</th>
                                        <th>Installment Due</th>
                                        <th> On Due</th>
                                        <th>Total Due</th>

                                    </tr>
                                    </thead>

                                    <tbody>

                                    @php
                                        $total_installment_due = 0;
                                        $total_on_due = 0;
                                        $total_due=0;
                                        $totaldiscount = 0;
                                     @endphp
                                    @foreach($orders as $k => $p)
                                        <tr class="{{ $p->deleted_at != null ? 'bg-warning white' : '' }}">
                                            <td>{{ ++$k }}</td>

{{--                                            <td>{{ $p->customer->name }}</td>--}}
                                            <td>{{ $p->customer_name }}</td>
                                            <td>{{ $p->customer_phone }}</td>


                                            @if($p->payment_type== 2 )
                                                        @php
                                                        $paid = getPaid($p->order_installment_id);
                                                        $totaldiscount = getTotaldiscount($p->order_installment_id);
                                                        $discount_due =($p->total-$totaldiscount)-$paid;
                                                        $due = $p->total - $paid;
                                                       if($discount_due > 0){
                                                              $total_installment_due = $total_installment_due +$discount_due;
                                                       }else{
                                                           $total_installment_due = $total_installment_due + 0 ;
                                                       }

                                                        //$total_installment_duew = $total_installment_due +$due;
                                                        @endphp


                                                @if($totaldiscount > 0)
                                                    @if($discount_due > 0)
                                                    <td>{{ $discount_due }}</td>
                                                        @else
                                                        <td>0</td>
                                                        @endif
                                                @else
                                                    <td>{{ $due }}</td>
                                                @endif

                                            @else
                                                <td>0</td>

                                                @endif


                                            @if($p->payment_type==1)
                                                <td>{{ $p->due_amount }}</td>
                                                        @php
                                                        $total_on_due = $total_on_due + $p->due_amount;

                                                        @endphp
                                            @else
                                                <td>0</td>
                                            @endif


                                            @if($totaldiscount > 0)
                                                @if($discount_due > 0)
                                                <td>{{ ($p->payment_type==2) ? $discount_due : $p->due_amount  }}</td>
                                                    @else
                                                    <td>0</td>
                                                    @endif

                                                @else
                                                <td>{{ ($p->payment_type==2) ? $due : $p->due_amount  }}</td>
                                            @endif

                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="3">Total</td>
                                        <td>{{ $total_installment_due }}</td>
                                        <td>{{ $total_on_due }}</td>
                                        <td>{{ $total_on_due + $total_installment_due }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!---ROW-->

    <div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" >
                <div class="modal-header bg-warning white">
                    <h4 class="modal-title" id="myModalLabel2"><i class='fa fa-exclamation-triangle'></i><strong> Confirmation !</strong> </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <strong>Are you sure you want to do this ?</strong>
                </div>

                <div class="modal-footer">
                    <form method="post" action="{{ route('due-repayment-delete') }}" class="form-inline">
                        {!! csrf_field() !!}
                        {{ method_field('DELETE') }}
                        <input type="hidden" name="delete_id" id="delete_id" value="0">
                        <button type="button" class="btn btn-warning font-weight-bold text-uppercase" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>&nbsp;
                        <button type="submit" class="btn btn-danger font-weight-bold text-uppercase"><i class="fa fa-check"></i> Yes Sure</button>

                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ asset('assets/admin/js/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/datatable-basic.js') }}"></script>
    <script>
        $(document).ready(function () {
            $(document).on("click", '.delete_button', function (e) {
                var id = $(this).data('id');
                $("#delete_id").val(id);
            });
        });
    </script>
@endsection
