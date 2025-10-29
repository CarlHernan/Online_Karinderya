@extends('layouts.master')

@section('hide_sidebar', true)
@section('header_title', 'Manage Profile')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
            ← Back to Dashboard
        </a>
    </div>
    <!-- Profile Information -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Profile Information</h3>
            <p class="mt-1 text-sm text-gray-600">Update your account's profile information and email address.</p>
        </div>
        <div class="p-6">
            <!-- Profile Picture Section -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                <div class="flex items-center space-x-4">
                    <!-- Current Profile Picture -->
                    <div class="flex-shrink-0">
                        <img id="current-profile-picture" 
                             @if($user->profile_picture && !empty(trim($user->profile_picture)))
                                 src="{{ asset('storage/' . $user->profile_picture) }}" 
                             @else
                                 style="display: none;"
                             @endif
                             alt="Profile Picture" 
                             class="h-20 w-20 rounded-full object-cover border-2 border-gray-300">
                        
                        <div id="initials-placeholder" 
                             @if($user->profile_picture && !empty(trim($user->profile_picture)))
                                 style="display: none;"
                             @endif
                             class="h-20 w-20 rounded-full bg-gray-300 flex items-center justify-center border-2 border-gray-300">
                            <span class="text-gray-600 font-semibold text-lg">{{ $user->getInitials() }}</span>
                        </div>
                    </div>
                    
                    <!-- Upload Form -->
                    <div class="flex-1">
                        <form method="post" action="{{ request()->routeIs('user.profile.*') ? route('user.profile.picture.update') : route('profile.picture.update') }}" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            @method('patch')
                            
                            <div class="flex items-center space-x-3">
                                <input type="file" name="profile_picture" id="profile_picture" 
                                       accept="image/jpeg,image/png,image/gif,image/webp"
                                       onchange="previewProfilePicture(this)"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Upload
                                </button>
                            </div>
                            
                            @error('profile_picture')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            @if($user->profile_picture)
                                <div class="mt-2">
                                    <button type="button" onclick="removeProfilePicture()" 
                                            class="text-sm text-red-600 hover:text-red-800 underline">
                                        Remove current picture
                                    </button>
                                </div>
                            @endif
                        </form>
                        
                        <!-- Remove Picture Form -->
                        @if($user->profile_picture)
                            <form method="post" action="{{ request()->routeIs('user.profile.*') ? route('user.profile.picture.remove') : route('profile.picture.remove') }}" id="remove-picture-form" class="hidden">
                                @csrf
                                @method('delete')
                            </form>
                        @endif
                    </div>
                </div>
                
                @if (session('status') === 'profile-picture-updated')
                    <p class="mt-2 text-sm text-green-600">Profile picture updated successfully.</p>
                @elseif (session('status') === 'profile-picture-removed')
                    <p class="mt-2 text-sm text-green-600">Profile picture removed successfully.</p>
                @endif
            </div>

            <form method="post" action="{{ request()->routeIs('user.profile.*') ? route('user.profile.update') : route('profile.update') }}" class="space-y-6">
                @csrf
                @method('patch')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           required autofocus autocomplete="name">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           required autocomplete="username">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2">
                            <p class="text-sm text-gray-800">
                                Your email address is unverified.
                                <button form="send-verification" class="underline text-sm text-blue-600 hover:text-blue-500">
                                    Click here to re-send the verification email.
                                </button>
                            </p>
                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600">
                                    A new verification link has been sent to your email address.
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Save
                    </button>

                    @if (session('status') === 'profile-updated')
                        <p class="text-sm text-green-600">Saved.</p>
                    @endif
                </div>
            </form>

            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                @csrf
            </form>
        </div>
    </div>

    <!-- Update Password -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Update Password</h3>
            <p class="mt-1 text-sm text-gray-600">Ensure your account is using a long, random password to stay secure.</p>
        </div>
        <div class="p-6">
            <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                @method('put')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                    <input type="password" name="current_password" id="current_password"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           autocomplete="current-password">
                    @error('current_password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                    <input type="password" name="password" id="password"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           autocomplete="new-password">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           autocomplete="new-password">
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Save
                    </button>

                    @if (session('status') === 'password-updated')
                        <p class="text-sm text-green-600">Password updated.</p>
                    @endif
                </div>
            </form>
                </div>
            </div>

    <!-- Delete Account -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Delete Account</h3>
            <p class="mt-1 text-sm text-gray-600">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
        </div>
        <div class="p-6">
            <button type="button" onclick="confirmDelete()" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Delete Account
            </button>

            <!-- Delete Account Form -->
            <form method="post" action="{{ request()->routeIs('user.profile.*') ? route('user.profile.destroy') : route('profile.destroy') }}" id="delete-form" class="hidden">
                @csrf
                @method('delete')
                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
                           placeholder="Password">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-4 flex gap-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Delete Account
                    </button>
                    <button type="button" onclick="cancelDelete()" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Cancel
                    </button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    document.getElementById('delete-form').classList.remove('hidden');
}

function cancelDelete() {
    document.getElementById('delete-form').classList.add('hidden');
}

function removeProfilePicture() {
    if (confirm('Are you sure you want to remove your profile picture?')) {
        document.getElementById('remove-picture-form').submit();
    }
}

function previewProfilePicture(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            // Hide initials placeholder
            document.getElementById('initials-placeholder').style.display = 'none';
            
            // Show and update the image preview
            const img = document.getElementById('current-profile-picture');
            img.src = e.target.result;
            img.style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
