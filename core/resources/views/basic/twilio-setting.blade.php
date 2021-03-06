@extends('layouts.dashboard')
@section('style')

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


                            <div class="row">


                                <div class="col-md-12">
                                    <!-- BEGIN SAMPLE FORM PORTLET-->
                                    <div class="card">
                                        <div class="card-content collpase show">
                                            <div class="card-body">

                                                <form class="form-horizontal" action="{{ route('twilio-setting') }}" method="post" role="form">
                                                    {!! csrf_field() !!}
                                                    <div class="form-body">

                                                        <div class="form-group">
                                                            <label class="col-md-12"><strong>Twilio API</strong><br></label>
                                                            <div class="col-md-12">
                                                                <input class="form-control" name="tw_api" value="{{$basic->tw_api}}" placeholder="Twilio API">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn btn-primary btn-block btn-lg"><i class="fa fa-send"></i> UPDATE</button>
                                                        </div>

                                                    </div>
                                                </form>
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


@endsection
