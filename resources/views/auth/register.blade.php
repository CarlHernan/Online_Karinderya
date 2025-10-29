<x-guest-layout>
    <div class="auth-wrapper d-flex no-block justify-content-center align-items-center"
         style="background:url('{{ asset('assets/images/big/auth-bg.jpg') }}') no-repeat center center;">
        <div class="auth-box">
            <div>
                <div class="logo text-center">
                    <span class="db">
                        <img src="{{ asset('images/logo.png') }}" alt="logo" class="w-16 h-auto mx-auto block" />
                    </span>
                    <h5 class="font-medium mb-6 text-gray-600 text-lg">Create your account</h5>
                </div>

                <!-- Laravel Breeze Register Form -->
                <form method="POST" action="{{ route('register') }}" class="form-horizontal mt-4">
                    @csrf

                    <!-- Name -->
                    <div class="form-group mb-3">
                        <input id="name" type="text" name="name"
                               class="form-control form-control-lg @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required autofocus autocomplete="name"
                               placeholder="Name">
                        <x-input-error :messages="$errors->get('name')" class="text-danger mt-2" />
                    </div>

                    <!-- Email -->
                    <div class="form-group mb-3">
                        <input id="email" type="email" name="email"
                               class="form-control form-control-lg @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required autocomplete="username"
                               placeholder="Email">
                        <x-input-error :messages="$errors->get('email')" class="text-danger mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="form-group mb-3">
                        <div class="input-group">
                            <input id="password" type="password" name="password"
                                   class="form-control form-control-lg @error('password') is-invalid @enderror"
                                   required autocomplete="new-password"
                                   placeholder="Password">
                            <div class="input-group-append">
                                <button type="button" class="input-group-text bg-white border-left-0" onclick="togglePassword('password', this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="text-danger mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group mb-3">
                        <div class="input-group">
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                   class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                                   required autocomplete="new-password"
                                   placeholder="Confirm Password">
                            <div class="input-group-append">
                                <button type="button" class="input-group-text bg-white border-left-0" onclick="togglePassword('password_confirmation', this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="text-danger mt-2" />
                    </div>

                    <!-- Submit -->
                    <div class="form-group text-center mb-3">
                        <button type="submit" class="btn btn-block btn-lg btn-info">
                            SIGN UP
                        </button>
                    </div>

                    <!-- Already Registered -->
                    <div class="form-group mt-3 mb-0">
                        <div class="col-sm-12 text-center">
                            Already have an account?
                            <a href="{{ route('login') }}" class="text-info ml-2"><b>Sign In</b></a>
                        </div>
                    </div>
                </form>
            </div>
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
