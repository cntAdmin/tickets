@include('parts.headers')
    <div id="app">
        @include('parts.top_navbar')
        <div class="d-flex justify-content-around">
            <div class="mr-auto col-2 px-0" style="height: 90vh;">
                @include('parts.sidebar')
            </div>
            <div class="ml-auto col-10 m-4">
                <main class="p-2">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
@include('parts.footer')