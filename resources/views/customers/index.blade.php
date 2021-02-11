@include('parts.headers')
    <div id="app">
        <navbar user_role="{{ auth()->user()->getRoleNames()[0] ?? null }}" user_name="{{ auth()->user()->name }}"></navbar>
        <div class="d-flex justify-content-around">
            <div class="mr-auto col-2 px-0" style="height: 90vh;">
                <sidebar user_role="{{ auth()->user()->getRoleNames()[0] ?? null }}"></sidebar>
            </div>
            <div class="ml-auto col-10 m-4">
                <main class="p-2">
                    <app></app>
                </main>
            </div>
        </div>
    </div>
@include('parts.footer')
