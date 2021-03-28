{{--<!DOCTYPE html>--}}
{{--<html>--}}
{{--<head>--}}
{{--    <meta charset="utf-8">--}}
{{--    <title></title>--}}
{{--</head>--}}
{{--<body>--}}
{{--<div class="table-responsive">--}}
{{--    <table class="table table-striped table-bordered table-hover zero-configuration">--}}
{{--        <thead>--}}
{{--        <tr>--}}
{{--            <th>SL#</th>--}}
{{--            <th>Repayment Date</th>--}}
{{--            <th>Invoice ID</th>--}}
{{--            <th>Ins Amount</th>--}}
{{--            <th>Pay Amount</th>--}}
{{--            <th>Adjust Amount</th>--}}
{{--            <th>Status</th>--}}
{{--        </tr>--}}
{{--        </thead>--}}

{{--        <tbody>--}}
{{--        @foreach($data as $k => $p)--}}
{{--            <tr class="{{ $p->deleted_at != null ? 'bg-warning white' : '' }}">--}}
{{--                <td>{{ ++$k }}</td>--}}
{{--                <td>{{ \Carbon\Carbon::parse($p->pay_date)->format('dS-F-y') }}</td>--}}
{{--                <td>{{ $p->instalment->custom }}</td>--}}
{{--                <td>{{ $p->amount }} {{ $basic->currency }}</td>--}}
{{--                <td>--}}
{{--                    @if($p->status == 0)--}}
{{--                        <div class="badge badge-warning text-uppercase font-weight-bold"><i class="fa fa-times"></i> Not Yet</div>--}}
{{--                    @else--}}
{{--                        {{ $p->pay_amount }} {{ $basic->currency }}--}}
{{--                    @endif--}}
{{--                </td>--}}
{{--                <td>{{ ($p->amount - $p->ins_discount) -$p->pay_amount }} {{ $basic->currency }}</td>--}}
{{--                <td>--}}
{{--                    @if($p->status == 0)--}}
{{--                        <div class="badge badge-warning text-uppercase font-weight-bold"><i class="fa fa-spinner"></i> Pending</div>--}}
{{--                    @else--}}
{{--                        <div class="badge badge-success text-uppercase font-weight-bold"><i class="fa fa-check"></i> Complete</div>--}}
{{--                    @endif--}}
{{--                </td>--}}
{{--            </tr>--}}
{{--        @endforeach--}}
{{--        </tbody>--}}
{{--    </table>--}}
{{--</div>--}}

{{--</body>--}}
{{--</html>--}}

<!DOCTYPE html>
<html>
<head>
    <style>
        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td, #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even){background-color: #f2f2f2;}

        #customers tr:hover {background-color: #ddd;}

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>

<table id="customers">
    <tr>
        <th>SL#</th>
        <th>Repayment Date</th>
        <th>Invoice ID</th>
        <th>Ins Amount</th>
        <th>Pay Amount</th>
        <th>Adjust Amount</th>
        <th>Status</th>
    </tr>

    @foreach($data as $k => $p)
    <tr class="{{ $p->deleted_at != null ? 'bg-warning white' : '' }}">
        <td>{{ ++$k }}</td>
        <td>{{ \Carbon\Carbon::parse($p->pay_date)->format('dS-F-y') }}</td>
        <td>{{ $p->instalment->custom }}</td>
        <td>{{ $p->amount }} {{ $basic->currency }}</td>
        <td>
            @if($p->status == 0)
                <div class="badge badge-warning text-uppercase font-weight-bold"><i class="fa fa-times"></i> Not Yet</div>
            @else
                {{ $p->pay_amount }} {{ $basic->currency }}
            @endif
        </td>
        <td>{{ ($p->amount - $p->ins_discount) -$p->pay_amount }} {{ $basic->currency }}</td>
        <td>
            @if($p->status == 0)
                <div class="badge badge-warning text-uppercase font-weight-bold"><i class="fa fa-spinner"></i> Pending</div>
            @else
                <div class="badge badge-success text-uppercase font-weight-bold"><i class="fa fa-check"></i> Complete</div>
            @endif
        </td>
    </tr>
    @endforeach

</table>

</body>
</html>