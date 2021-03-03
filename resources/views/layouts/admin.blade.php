@include('parts.headers')
    <div id="app">
        <App user_role="{{ auth()->user()->getRoleNames()[0] }}" :user="{{ auth()->user() }}"></App>
    </div>
@include('parts.footer')