<x-guest-layout>
    <div class="auth-wrapper d-flex no-block justify-content-center align-items-center"
         style="background:url('{{ asset('assets/images/big/auth-bg.jpg') }}') no-repeat center center;">
        <div class="auth-box">
            <div class="logo text-center">
                <span class="db">
                    <img src="{{ asset('images/logo.png') }}" alt="logo" class="w-10 h-auto mx-auto block"/>
                </span>
                <h5 class="font-bold text-lg text-gray-800 mb-3 mt-1">Sign In to Admin</h5>
            </div>

            <form method="POST" action="{{ url('admin/login') }}" class="form-horizontal m-t-20">
                @csrf

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                    </div>
                    <input id="email" type="email" name="email"
                           class="form-control form-control-lg @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required autofocus autocomplete="username"
                           placeholder="Email">
                </div>
                <x-input-error :messages="$errors->get('email')" class="text-danger mb-2" />

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon2"><i class="ti-pencil"></i></span>
                    </div>
                    <input id="password" type="password" name="password"
                           class="form-control form-control-lg @error('password') is-invalid @enderror"
                           required autocomplete="current-password" placeholder="Password">
                    <div class="input-group-append">
                        <button type="button" class="input-group-text bg-white border-left-0" onclick="togglePassword('password', this)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('password')" class="text-danger mb-2" />

                <div class="form-group text-center">
                    <div class="col-xs-12 p-b-20">
                        <button class="btn btn-block btn-lg btn-info" type="submit">Log In</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
<script>
function togglePassword(id, el) {
    const input = document.getElementById(id);
    const isPwd = input.type === 'password';
    input.type = isPwd ? 'text' : 'password';
    el.classList.toggle('text-primary', isPwd);
}
</script>

