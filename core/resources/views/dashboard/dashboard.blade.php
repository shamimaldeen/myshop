@extends('layouts.dashboard')

@section('content')


    <section id="horizontal-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="horz-layout-basic">Account Statistic</h4>
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
                            <div class="row">

                                <div class="col-xl-3 col-lg-6 col-12">
                                    <div class="card bg-gradient-directional-primary">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="media d-flex">
                                                    <div class="media-body white text-left">
                                                        <h3>{{ $basic->symbol }}{{ $basic->balance }}</h3>
                                                        <span class="font-weight-bold text-uppercase">Current Cash</span>
                                                    </div>
                                                    <div class="align-self-center">
                                                        <i class="ft ft-credit-card white font-large-2 float-right"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-6 col-12">
                                    <div class="card bg-gradient-directional-success">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="media d-flex">
                                                    <div class="media-body white text-left">
                                                        <h3>{{ $basic->symbol }}{{ $total_deposit }}</h3>
                                                        <span class="font-weight-bold text-uppercase">Total Deposit</span>
                                                    </div>
                                                    <div class="align-self-center">
                                                        <i class="ft ft-download-cloud white font-large-2 float-right"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-6 col-12">
                                    <div class="card bg-gradient-directional-warning">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="media d-flex">
                                                    <div class="media-body white text-left">
                                                        <h3>{{ $basic->symbol }}{{ $total_withdraw }}</h3>
                                                        <span class="font-weight-bold text-uppercase">Total Withdraw</span>
                                                    </div>
                                                    <div class="align-self-center">
                                                        <i class="ft ft-upload-cloud white font-large-2 float-right"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-6 col-12">
                                    <div class="card bg-gradient-directional-danger">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="media d-flex">
                                                    <div class="media-body white text-left">
                                                        <h3>{{ $basic->symbol }}{{ $total_expense }}</h3>
                                                        <span class="font-weight-bold text-uppercase">Total Expense</span>
                                                    </div>
                                                    <div class="align-self-center">
                                                        <i class="ft ft-cloud-lightning white font-large-2 float-right"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!---ROW-->

    <section id="horizontal-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="horz-layout-basic">Store Statistic</h4>
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
                            <div class="row">

                                <div class="col-xl-3 col-lg-6 col-12">
                                    <div class="card bg-gradient-directional-blue-grey">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="media d-flex">
                                                    <div class="media-body white text-left">
                                                        <h3>{{ $categoryCount }}+</h3>
                                                        <span class="font-weight-bold text-uppercase">Total Category</span>
                                                    </div>
                                                    <div class="align-self-center">
                                                        <i class="ft ft-grid white font-large-2 float-right"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-6 col-12">
                                    <div class="card bg-gradient-directional-grey-blue">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="media d-flex">
                                                    <div class="media-body white text-left">
                                                        <h3>{{ $productCount }}+</h3>
                                                        <span class="font-weight-bold text-uppercase">Total Product</span>
                                                    </div>
                                                    <div class="align-self-center">
                                                        <i class="ft ft-box white font-large-2 float-right"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-6 col-12">
                                    <div class="card bg-gradient-directional-purple">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="media d-flex">
                                                    <div class="media-body white text-left">
                                                        <h3>{{ $stockCount }} items</h3>
                                                        <span class="font-weight-bold text-uppercase">Stock Product</span>
                                                    </div>
                                                    <div class="align-self-center">
                                                        <i class="ft ft-layers white font-large-2 float-right"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-6 col-12">
                                    <div class="card bg-gradient-directional-pink">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="media d-flex">
                                                    <div class="media-body white text-left">
                                                        <h3>{{ $basic->symbol }}{{ $stock_amount }}</h3>
                                                        <span class="font-weight-bold text-uppercase">Product Price</span>
                                                    </div>
                                                    <div class="align-self-center">
                                                        <i class="ft ft-credit-card white font-large-2 float-right"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!---ROW-->

    <section id="horizontal-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="horz-layout-basic">Sell Statistic</h4>
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
                            <div class="row">

                                <div class="col-xl-3 col-lg-6 col-12">
                                    <div class="card bg-gradient-directional-grey-blue">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="media d-flex">
                                                    <div class="media-body white text-left">
                                                        <h3>{{ $total_sell }}+</h3>
                                                        <span class="font-weight-bold text-uppercase">Total Sell</span>
                                                    </div>
                                                    <div class="align-self-center">
                                                        <i class="ft ft-bar-chart-2 white font-large-2 float-right"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-6 col-12">
                                    <div class="card bg-gradient-directional-primary">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="media d-flex">
                                                    <div class="media-body white text-left">
                                                        <h3>{{ $basic->symbol }}{{ $sell_amount }}</h3>
                                                        <span class="font-weight-bold text-uppercase">Sell Price</span>
                                                    </div>
                                                    <div class="align-self-center">
                                                        <i class="ft ft-credit-card white font-large-2 float-right"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-6 col-12">
                                    <div class="card bg-gradient-directional-pink">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="media d-flex">
                                                    <div class="media-body white text-left">
                                                        <h3>{{ $basic->symbol }}{{ $due_on }}</h3>
                                                        <span class="font-weight-bold text-uppercase">Total Due</span>
                                                    </div>
                                                    <div class="align-self-center">
                                                        <i class="ft ft-copy white font-large-2 float-right"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-6 col-12">
                                    <div class="card bg-gradient-directional-purple">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="media d-flex">
                                                    <div class="media-body white text-left">
                                                        <h3>{{ $basic->symbol }}{{ $instalment_on }}</h3>
                                                        <span class="font-weight-bold text-uppercase">Total Instalment</span>
                                                    </div>
                                                    <div class="align-self-center">
                                                        <i class="ft ft-sliders white font-large-2 float-right"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!---ROW-->

    <section id="horizontal-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="horz-layout-basic">Company Statistic</h4>
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
                            <div class="row">

                                <div class="col-xl-3 col-lg-6 col-12">
                                    <div class="card bg-gradient-directional-success">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="media d-flex">
                                                    <div class="media-body white text-left">
                                                        <h3>{{ $companyCount }}+</h3>
                                                        <span class="font-weight-bold text-uppercase">Total Company</span>
                                                    </div>
                                                    <div class="align-self-center">
                                                        <i class="ft ft-briefcase white font-large-2 float-right"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-6 col-12">
                                    <div class="card bg-gradient-directional-blue">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="media d-flex">
                                                    <div class="media-body white text-left">
                                                        <h3>{{ $basic->symbol }}{{ $company_send }}</h3>
                                                        <span class="font-weight-bold text-uppercase">Company Send</span>
                                                    </div>
                                                    <div class="align-self-center">
                                                        <i class="ft ft-credit-card white font-large-2 float-right"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-6 col-12">
                                    <div class="card bg-gradient-directional-primary">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="media d-flex">
                                                    <div class="media-body white text-left">
                                                        <h3>{{ $basic->symbol }}{{ $company_pay }}</h3>
                                                        <span class="font-weight-bold text-uppercase">Total Pay</span>
                                                    </div>
                                                    <div class="align-self-center">
                                                        <i class="ft ft-credit-card white font-large-2 float-right"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-6 col-12">
                                    <div class="card bg-gradient-directional-warning">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <div class="media d-flex">
                                                    <div class="media-body white text-left">
                                                        <h3>{{ $basic->symbol }}{{ $company_send - $company_pay }}</h3>
                                                        <span class="font-weight-bold text-uppercase">Total Due</span>
                                                    </div>
                                                    <div class="align-self-center">
                                                        <i class="ft ft-credit-card white font-large-2 float-right"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!---ROW-->

@stop
@section('scripts')

    <script type="text/javascript" src="{{ asset('assets/admin/js/Chart.min.js') }}"></script>

    {{--<script language="JavaScript">
        displayLineChart();
        function displayLineChart() {
            var data = {
                labels: [
                    @foreach($dL as $l)
                            '{{ $l }}',
                    @endforeach
                ],
                datasets: [
                    {
                        label: "Prime and Fibonacci",
                        fillColor: "#3dbcff",
                        strokeColor: "#0099ff",
                        pointColor: "rgba(220,220,220,1)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: [
                            @foreach($dV as $d)
                                    '{{ $d }}',
                            @endforeach
                        ]
                    }
                ]
            };
            var ctx = document.getElementById("lineChart").getContext("2d");
            var options = {
                responsive: true
            };
            var lineChart = new Chart(ctx).Line(data, options);
        }
    </script>--}}
@endsection