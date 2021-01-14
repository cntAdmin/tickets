    <nav class="navbar navbar-light bg-white shadow mt-1 h-100 col-2 flex-column overflow-auto position-fixed">
        <div class="container">
            {{-- HAMBURGER BUTTON --}}
            <button class="btn btn-block btn-link border-bottom d-block d-md-none" type="button" data-toggle="collapse" data-target="#sidebar_navbar"
                aria-controls="sidebar_navbar" aria-expanded="true" aria-label="{{ __('Toggle navigation') }}" title="Contraer/Expandir Menú">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse mt-2 d-none d-md-block" id="sidebar_navbar">
                <ul class="navbar-nav">
                    {{-- APARTADO TICKETS --}}
                    <li class="nav-item w-100">
                        <div class="d-flex align-items-center shadow-sm w-100">
                            <div class="mr-auto">
                                <span class="btn btn-toolbar text-uppercase font-weight-bold" type="button" data-toggle="collapse"
                                    data-target="#tickets_sidebar" aria-expanded="false" aria-controls="tickets_sidebar">
                                    {{ __('Tickets')}}
                                </span>
                            </div>
                            <div class="ml-auto">
                                <span class="navbar-toggler-icon"type="button" data-toggle="collapse"
                                    data-target="#tickets_sidebar" aria-expanded="false" aria-controls="tickets_sidebar">
                                </span>
                            </div>
                        </div>
                        <div class="collapse" id="tickets_sidebar">
                            <a class="btn btn-toolbar btn-block mt-2" href="{{ route('ticket.index') }}">
                                - <span class="ml-2">{{ __('Todos los Tickets') }}</span>
                            </a>
                            <a class="btn btn-toolbar btn-block" href="{{ route('ticket.index', ['status' => 1]) }}">
                                - <span class="ml-2">{{ __('Tickets Abiertos') }}</span>
                            </a>
                            <a class="btn btn-toolbar btn-block" href="{{ route('ticket.index', ['status' => 2]) }}">
                                - <span class="ml-2">{{ __('Tickets Cerrados') }}</span>
                            </a>
                            <a class="btn btn-toolbar btn-block" href="{{ route('ticket.index', ['status' => 3]) }}">
                                - <span class="ml-2">{{ __('Tickets Resueltos') }}</span>
                            </a>
                        </div>
                    </li>
                    {{-- APARTADO DEPARTAMENTOS --}}
                    <li class="nav-item mt-2">
                        <div class="shadow-sm">
                            <a class="btn btn-toolbar text-uppercase font-weight-bold" href="{{ route('department.index') }}">
                                {{ __('Departamentos')}}
                            </a>
                        </div>
                    </li>
                    {{-- APARTADO CLIENTES --}}
                    <li class="nav-item mt-2">
                        <div class="shadow-sm">
                            <a class="btn btn-toolbar text-uppercase font-weight-bold" href="{{ route('customer.index') }}">
                                {{ __('Clientes')}}
                            </a>
                        </div>
                    </li>
                    {{-- APARTADO MEDIA --}}
                    <li class="nav-item mt-2">
                        <div class="shadow-sm">
                            <a class="btn btn-toolbar text-uppercase font-weight-bold" href="{{ route('media.index') }}">
                                {{ __('Ficheros')}}
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
