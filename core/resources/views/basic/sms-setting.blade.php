@extends('layouts.dashboard')

@section('style')

    <link href="{{ asset('assets/admin/css/bootstrap-toggle.min.css') }}" rel="stylesheet">

@endsection
@section('content')
    <section id="horizontal-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                       <!--  <h4 class="card-title" id="horz-layout-basic">{{$page_title}}</h4> -->
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
                           <form method="POST" action="{{ route('send-sms') }}">
                               {{ csrf_field() }}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-10 offset-1">
                                        <div class="form-group col-lg-12">
                                            <label for="message-to">Select Payment/Type:</label>
                                            <div>
                                                <select class="col-lg-6"  name="sms_type">
                                                    <option value="1">Custom</option>
                                                    <option value="2">Due Paid</option>
                                                    <option value="3">Instalment Paid</option>
                                                    <option value="4">Late Due Paid</option>
                                                    <option value="5">Late Instalment Paid</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <label for="message-to">Sent To:</label>
                                            <div>
                                                <select class="col-lg-6"  name="is_all">
                                                    <option value="1">All Customer</option>
{{--                                                    <option value="2">Selective Customer</option>--}}
                                                </select>
                                            </div>
                                        </div>

{{--                                          <div class="col-md-1">--}}
{{--                                            <div class="form-group" id="select-checkbox">--}}
{{--                                                <label for="all-select"><input class="checked" type="checkbox" id="all-select" onclick="select_all_student()"> Select All</label>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}



                                        <div class="form-group">
                                            <label class="col-md-12"><strong style="text-transform: uppercase;">Message</strong></label>
                                            <div class="col-md-12">
                                                <textarea name="massage_body"  class="form-control" value="" ></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <button type="submit" onclick="nicEditors.findEditor('area1').saveContent();nicEditors.findEditor('area2').saveContent();" class="btn btn-primary btn-block btn-lg"><i class="fa fa-send"></i> UPDATE</button>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- row -->
                            </div>
                           </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



@endsection
@section('scripts')

    <script src="{{ asset('assets/admin/js/bootstrap-toggle.min.js') }}"></script>
    <script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script>

    <script type="text/javascript">
        bkLib.onDomLoaded(function() {
            new nicEditor({fullPanel : true}).panelInstance('area1');
            new nicEditor({fullPanel : true}).panelInstance('area2');
        });
    </script>

@endsection
