@include('parts.headers')
    <div id="app"
        class="pb-5 pb-xl-2 mb-5"
        >
        <App user_role="{{ auth()->user()->getRoleNames()[0] }}" :user="{{ auth()->user() }}"></App>
    </div>
@include('parts.footer')