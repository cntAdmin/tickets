@include('parts.headers')
    <div id="app"
        class="pb-5 pb-xl-2 mb-5"
        >
        <App :user_role="{{ auth()->user()->roles[0]->id }}" :user="{{ auth()->user() }}"></App>
    </div>
@include('parts.footer')