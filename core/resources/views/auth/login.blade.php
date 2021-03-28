@extends('layouts.frontEnd')

@section('content')

    <div class="banner-innerpage" style="background-image:url('{{ asset('assets/images') }}/{{$basic->breadcrumb}}')">
        <div class="container">
            <!-- Row  -->
            <div class="row justify-content-center ">
                <!-- Column -->
                <div class="col-md-6 align-self-center text-center" data-aos="fade-down" data-aos-duration="1200">
                    <h1 class="title">{{$page_title}}</h1>
                </div>
                <!-- Column -->
            </div>
        </div>
    </div>


    <div class="spacer form2">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-lg-6">
                    <div class="text-box">
                        <h1 class="font-light">{{ $section->login_title }}</h1>
                        <p>{{$section->login_description}}</p>
                        <form class="m-t-20" action="{{ route('login') }}" autocomplete="off" method="post" data-aos="fade-up" data-aos-duration="1200">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-lg-12">
                                    @if (session()->has('message'))
                                        <div class="alert alert-warning alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            {{ session()->get('message') }}
                                        </div>
                                    @endif
                                    @if($errors->any())
                                        @foreach ($errors->all() as $error)
                                            <div class="alert alert-danger alert-dismissable">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                {!!  $error !!}
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input class="form-control" name="email" type="email" placeholder="Email address" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input class="form-control" name="password" type="password" placeholder="Password" autocomplete="rrr" required>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
                                            <input type="checkbox" class="custom-control-input" id="remember-me" {{ old('remember') ? 'checked' : '' }}>
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Remember Me</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group text-right">
                                        <a href="{{ route('password.request') }}" class="card-link">Forgot Password?</a>
                                    </div>
                                </div>

                                @if($basic->captcha_status == 1)
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {!! Captcha::display() !!}
                                        </div>
                                    </div>
                                @endif
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-md btn-block btn-info-gradiant btn-arrow"><span> LogIn Now <i class="ti-arrow-right"></i></span></button>
                                    <!--  -->
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-lg-12 text-center m-t-30">
                                <div class="have-ac ml-auto align-self-center">Create an account? <a href="{{ route('register') }}" class="text-danger">Register</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
