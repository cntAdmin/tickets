@extends('layouts.app')

@section('content')
<div class="d-flex flex-column justify-content-center align-items-center align-items-lg-start justify-content-lg-start h-100">
    <video autoplay muted loop class="position-fixed w-100 d-none d-lg-block" style="opacity: 0.5; filter: blur(.5px) ">
        <source src="{{ asset('/storage/login_video.mp4')}}" type="video/mp4">
    </video>

    <div class="mt-5 mt-lg-0 ml-lg-0 my-lg-auto col-12 col-lg-4 align-middle">
        <div class="d-flex justify-content-start">
            <div class="col-md-10">
                <div class="card shadow-lg">
                    {{-- <div class="card-header bg-white"> --}}
                    <div class="card-header" style="background-color: #FA5002;">
                        <div class="d-flex justify-content-center align-items-center">
                            {{-- <img src="{{ asset('/storage/aap_logo.jpeg')}}" class="d-inline-block align-middle w-25" alt=""> --}}
                            <img src="{{ asset('/storage/logo-andel.svg')}}" class="d-inline-block align-middle" alt="" width="200px">
                            <h5 class="text-uppercase text-center text-white mx-5" style="margin-bottom: 0px !important;">Asistencia Técnica</h5>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group row justify-content-center">
                                <div class="input-group col-md-12">
                                    <div class="d-flex w-100">
                                        <div class="col-6 col-lg-4 mr-0 pr-0">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text text-uppercase w-100">{{ __('Usuario') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-8 ml-0 pl-0">

                                            <input id="username" type="text"
                                                class="form-control @error('username') is-invalid @enderror"
                                                name="username" value="{{ old('username') }}" required
                                                autocomplete="username" autofocus>

                                            @error('username')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row justify-content-center">
                                <div class="input-group col-md-12">
                                    <div class="d-flex w-100">
                                        <div class="col-6 col-lg-4 mr-0 pr-0">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text text-uppercase w-100">
                                                    {{ __('Contraseña') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-8 ml-0 pl-0">
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" required autocomplete="current-password">

                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group d-flex justify-content-center mt-2">
                                <div class="col-md-12">
                                    <div class="form-check text-center">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Recordarme') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row justify-content-center">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        {{ __('Acceder') }}
                                    </button>
                                </div>

                                @if (Route::has('password.request'))
                                <div class="col-md-8 text-center">
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('¿Has olvidado tu contraseña?') }}
                                    </a>
                                </div>
                                @endif
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection