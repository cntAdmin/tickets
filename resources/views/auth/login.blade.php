@extends('layouts.app')
@section('content')
    <div class="row h-100 justify-content-start align-items-center">
            <video autoplay muted loop class="position-fixed w-100 d-none d-lg-block" style="opacity: 0.5; filter: blur(.5px) ">
                <source src="{{ asset('/storage/login_video.mp4')}}" type="video/mp4">
            </video>
            <div class="card bg-semiTransparent ml-5">
                <div class="card-header bg-orange">
                    <p class="text-center text-white">{{ __('ASISTENCIA TÉCNICA') }}</p>
                    <img src="{{ asset('/storage/logo-andel.svg')}}" />
                </div>
                <div class="card-body w-100">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input 
                                    id="username" 
                                    type="text" 
                                    class="form-control @error('username') is-invalid @enderror" 
                                    name="username" 
                                    value="{{ old('username') }}" 
                                    required 
                                    autocomplete="username" 
                                    autofocus 
                                    placeholder="Usuario"
                                >
                                @error('username')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input 
                                    id="password" 
                                    type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    name="password" 
                                    required 
                                    autocomplete="current-password" 
                                    placeholder="Contraseña"
                                >
                                @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        name="remember" 
                                        id="remember"
                                        {{ old('remember') ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label" for="remember">{{ __('Recordarme') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-orange">{{ __('Acceder') }}</button>
                                @if (Route::has('password.request'))
                                    <a class="btn btn-secondary" href="{{ route('password.request') }}">
                                        {{ __('¿Has olvidado la contraseña?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
