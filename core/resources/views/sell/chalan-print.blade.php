<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $site_title }} | Invoice</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap3.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/admin/fonts/font-awesome/css/font-awesome.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/AdminLTE.css') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.png') }}">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .invoice {
            margin: 0px !important;
        }
        .invoice .invoice-logo {
            margin-bottom: 0px;
        }
        .invoice .invoice-logo-space {
            margin-bottom: 0px;
        }
        .invoice .invoice-logo p {
            padding: 5px 0;
            font-size: 14px;
            line-height: 28px;
            margin-top: 50px;
        }
        .invoice-extra-p{
            font-size: 13px;
        }
        .extra-h{
            margin-top: 0;
        }
        .extra-well{
            margin-bottom: 0;
        }
        .extra-table{
            font-size: 13px;
        }
        .extra-table2{
            font-size: 13px;
        }
        .paymentStatus{
            padding: 0px 5px;
            border: 1px solid;
            border-radius: 3px;
            font-weight: bold;
        }
        @media print {

        }
        .font-size-14{
            font-size: 13px !important;
        }

    </style>

    <!-- Google Font -->
    {{--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    --}}
    <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700,700i" rel="stylesheet">
</head>
<body onload="window.print();">
<div class="wrapper">
    <!-- Main content -->
    <section class="invoice">
        <!-- title row -->
        <div style="text-align:center;border-color: #1B2942">
            <h2 class="btn btn-primary btn-sm font-weight-bold text-uppercase">Chalan</h2>
        </div>
        <div class="row invoice-logo">
            <div class="col-xs-6 invoice-logo-space">
                <img src="{{ asset('assets/images/logo.png') }}" class="center-block" alt="" />
                <div class="text-center">
                    <h4 style="font-size: 15px;">{{ $basic->title }}</h4>
                    <h5 style="font-size: 13px;">{{ $basic->email }}, {{ $basic->phone }}</h5>
                    <h5 style="font-size: 13px;">{{ $basic->address }}</h5>
                </div>
            </div>
            <div class="col-xs-6">
                <p class="text-center"> Invoice : {{ $sell->custom }} <br>
                    <span class="invoice-extra-p">{{ \Carbon\Carbon::parse($sell->created_at)->format('dS M, Y') }}</span>
                </p>
            </div>
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-6 col-xs-6">
                <div class="well extra-well extra-table2" style="padding: 10px;">
                    <h4 class="extra-h" style="margin-bottom: 3px;font-size: 14px;text-align: center;">Customer Details</h4>
                    <ul class="list-unstyled" style="margin-bottom: 0;">
                        <li class="font-size-14">Name : {{ $sell->customer->name }}</li>
                        <li class="font-size-14">Mobile : {{ $sell->customer->phone }}</li>
                        @if($sell->customer->phone != null)
                            <li class="font-size-14">Alternative Mobile : {{ $sell->customer->phone2 }}</li>
                        @endif
                        <li class="font-size-14"> Address : {{ $sell->customer->address }}</li>
                    </ul>
                </div>

            </div>
        </div>
    <!-- /.row -->
        <hr style="margin-top: 10px;margin-bottom: 0px;">
        <!-- Table row -->
        <br>
        <br>
        <br>
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped extra-table2" style="margin-bottom: 0;">
                    <thead>
                    <tr>
                        <th>Category</th>
                        <th>Serial #</th>
                        <th>Product Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sellItem as $key => $sl)
                        <tr>
                            <td>{{ $sl->product->category->name }}</td>
                            <td>{{$sl->code}}</td>
                            <td>{{ $sl->product->name }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="row">
            <br>
            <br>
            <br>
            <br>
            <div style="float: left;margin-left: 20px;">
                <span>-------------------</span>
                <p> Received by</p>
            </div>
            <div style="float: right;">
                <span>-------------------</span>
                <p>Authorized by</p>
            </div>
            <br>
            <br>

            <div class="col-sm-12" style="text-align: center;">
                <p class="font-size-14 text-align-center">{!! $basic->admin_text  !!}</p>
            </div>
        </div>

    </section>

    <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>
