
@extends('layouts.dashboard')
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/invoice.css') }}">
@endsection
@section('content')
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

                            <section class="card" id="printAble">
                                <div id="invoice-template" class="card-body">
                                    <!-- Invoice Company Details -->
                                    <div id="invoice-company-details" class="row">
                                        <div class="col-md-4 col-sm-12 text-center text-md-left">
                                            <img src="{{ asset('assets/images/logo.png') }}" alt="company logo" style="width: 40%" class=""/>
                                            <div class="details text-center" style="width: 44%">
                                                <h5 class="mt-1">{{ $basic->title }}</h5>
                                                <h6>{{ $basic->address }}</h6>
                                                <h6>{{ $basic->phone }}</h6>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-sm-12 text-center text-md-right">
                                            <h2>INVOICE</h2>
                                            <p class="pb-3">#{{ $sell->custom }}</p>
                                        </div>
                                    </div>
                                    <!--/ Invoice Company Details -->
                                    <!-- Invoice Customer Details -->
                                    <div id="invoice-customer-details" class="row pt-2">
                                        <div class="col-sm-12 text-center text-md-left">
                                            <p class="font-weight-bold">Bill To</p>
                                        </div>
                                        <div class="col-md-6 col-sm-12 text-center text-md-left">
                                            @if($sell->payment_type == 2)
                                                @php $sellInstalment2 = \App\OrderInstalment::whereOrder_id($sell->id)->first() @endphp
                                                <img src="{{ asset('assets/images/customer/'.$sellInstalment2->customer_image) }}"  style="width: 80px;height: 80px;" class=""/>
                                            @else
                                            @endif
                                            <ul class="px-0 list-unstyled">
                                                <li class="text-bold-800">{{ $sell->customer->name }}</li>
                                                <li>{{ $sell->customer->phone }}, {{ $sell->customer->phone2 }}</li>
                                                <li>{{ $sell->customer->address }}.</li>
                                            </ul>
                                        </div>

                                    </div>
                                    <!--/ Invoice Customer Details -->
                                    <!-- Invoice Items Details -->
                                    <div id="invoice-items-details" class="pt-2">
                                        <div class="row">
                                            <div class="table-responsive col-sm-12">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>#SL</th>
                                                        <th>Item Category</th>
                                                        <th>Item & Description</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($sellItem as $key => $sl)
                                                        <tr>
                                                            <td scope="row">{{ ++$key }}</td>
                                                            <td>{{ $sl->product->category->name }}</td>
                                                            <td>
                                                                <p>{{ $sl->product->name }} - ({{$sl->code}})</p>
                                                            </td>
{{--                                                            <td class="text-right">{{ $sl->store->sell_price }} - {{ $basic->currency }}</td>--}}
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Invoice Footer -->
                                    <div id="invoice-footer">
                                        <div class="row">
                                            <div class="col-md-5 col-sm-12 offset-7 text-center">
                                                <div class="row">
                                                    <div class="col-md-6"></div>
                                                    <div class="col-md-6">
                                                        <a href="{{ route('print-chalan',$sell->custom) }}" target="_blank" class="btn btn-primary btn-block btn-lg text-uppercase my-1"><i class="fa fa-print"></i> Print Invoice</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/ Invoice Footer -->
                                </div>
                            </section>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!---ROW-->
@endsection
@section('scripts')

@endsection