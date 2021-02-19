@include('parts.headers')
<div id="app">
    <navbar user_role="{{ auth()->user()->getRoleNames()[0] ?? null }}" user_name="{{ auth()->user()->name }}"></navbar>
        <div class="d-flex justify-content-around">
            <div class="mr-auto col-2 px-0" style="height: 90vh;">
                @unlessrole('superadmin|admin|staff')
                <sidebar></sidebar>
                @else
                <admin-sidebar user_role="{{ auth()->user()->getRoleNames()[0] ?? null }}"></admin-sidebar>
                @endhasanyrole
            </div>
            <div class="ml-auto col-10 m-4">
                <main class="p-2">
                    <App user_role="{{ auth()->user()->getRoleNames()[0] }}" :user="{{ auth()->user() }}"></App>
                </main>
            </div>
        </div>
    </div>
@include('parts.footer')
