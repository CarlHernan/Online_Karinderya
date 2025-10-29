<x-layout>
    <div class="min-h-screen w-full overflow-x-hidden flex flex-col" style="background-color: #dddbd9;">
        <!-- Header -->
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 shadow-lg">
            <div class="max-w-4xl mx-auto px-4 py-8">
                <h1 class="text-4xl font-bold text-white font-merriweather">My Profile</h1>
                <p class="text-emerald-100 font-poppins mt-2 text-lg">Manage your account information and preferences</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto px-4 py-8">
            <div class="space-y-8">
                <!-- Profile Picture Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-emerald-900 font-merriweather mb-4">Profile Picture</h2>
                    
                    <div class="flex items-center space-x-6">
                        <!-- Current Profile Picture -->
                        <div class="flex-shrink-0">
                            <img id="current-profile-picture" 
                                 @if($user->profile_picture && !empty(trim($user->profile_picture)))
                                     src="{{ asset('storage/' . $user->profile_picture) }}" 
                                 @else
                                     style="display: none;"
                                 @endif
                                 alt="Profile Picture" 
                                 class="h-24 w-24 rounded-full object-cover border-4 border-gray-200 shadow-md">
                            
                             <div id="initials-placeholder" 
                                  @if($user->profile_picture && !empty(trim($user->profile_picture)))
                                      style="display: none;"
                                  @endif
                                  class="h-24 w-24 rounded-full bg-emerald-900 flex items-center justify-center border-4 border-gray-200 shadow-md">
                                 <span class="text-white font-bold text-2xl">{{ $user->getInitials() }}</span>
                             </div>
                        </div>
                        
                        <!-- Upload Form -->
                        <div class="flex-1">
                            <form method="post" action="{{ route('user.profile.picture.update') }}" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                @method('patch')
                                
                                <div>
                                    <label for="profile_picture" class="block text-sm font-medium text-emerald-700 font-poppins mb-2">
                                        Upload New Picture
                                    </label>
                                    <div class="flex items-center space-x-3">
                                        <input type="file" name="profile_picture" id="profile_picture" 
                                               accept="image/jpeg,image/png,image/gif,image/webp"
                                               onchange="previewProfilePicture(this)"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                        
                                         <button type="submit" 
                                                 class="inline-flex items-center px-4 py-2 bg-emerald-900 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-emerald-800 focus:bg-emerald-800 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                             Upload
                                         </button>
                                    </div>
                                    
                                    @error('profile_picture')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    
                                    @if($user->profile_picture && !empty(trim($user->profile_picture)))
                                        <div class="mt-3">
                                            <button type="button" onclick="removeProfilePicture()" 
                                                    class="text-sm text-red-600 hover:text-red-800 underline font-poppins">
                                                Remove current picture
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </form>
                            
                            <!-- Remove Picture Form -->
                            @if($user->profile_picture && !empty(trim($user->profile_picture)))
                                <form method="post" action="{{ route('user.profile.picture.remove') }}" id="remove-picture-form" class="hidden">
                                    @csrf
                                    @method('delete')
                                </form>
                            @endif
                        </div>
                    </div>
                    
                    @if (session('status') === 'profile-picture-updated')
                        <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md">
                            Profile picture updated successfully.
                        </div>
                    @elseif (session('status') === 'profile-picture-removed')
                        <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md">
                            Profile picture removed successfully.
                        </div>
                    @endif
                </div>

                <!-- Profile Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-emerald-900 font-merriweather mb-4">Profile Information</h2>
                    <p class="text-sm text-gray-600 font-poppins mb-6">Update your account's profile information and email address.</p>
                    
                    <form method="post" action="{{ route('user.profile.update') }}" class="space-y-6">
                        @csrf
                        @method('patch')

                        <div>
                            <label for="name" class="block text-sm font-medium text-emerald-700 font-poppins">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                   required autofocus autocomplete="name">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-emerald-700 font-poppins">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                   required autocomplete="username">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="mt-2">
                                    <p class="text-sm text-gray-800 font-poppins">
                                        Your email address is unverified.
                                        <button form="send-verification" class="underline text-sm text-emerald-600 hover:text-emerald-500">
                                            Click here to re-send the verification email.
                                        </button>
                                    </p>
                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 font-medium text-sm text-green-600 font-poppins">
                                            A new verification link has been sent to your email address.
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-4">
                             <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-900 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-emerald-800 focus:bg-emerald-800 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                 Save Changes
                             </button>

                            @if (session('status') === 'profile-updated')
                                <p class="text-sm text-green-600 font-poppins">Profile updated successfully.</p>
                            @endif
                        </div>
                    </form>

                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>
                </div>

                <!-- Update Password -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-emerald-900 font-merriweather mb-4">Update Password</h2>
                    <p class="text-sm text-gray-600 font-poppins mb-6">Ensure your account is using a long, random password to stay secure.</p>
                    
                    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf
                        @method('put')

                        <div>
                            <label for="current_password" class="block text-sm font-medium text-emerald-700 font-poppins">Current Password</label>
                            <input type="password" name="current_password" id="current_password"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                   autocomplete="current-password">
                            @error('current_password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-emerald-700 font-poppins">New Password</label>
                            <input type="password" name="password" id="password"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                   autocomplete="new-password">
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-emerald-700 font-poppins">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                   autocomplete="new-password">
                            @error('password_confirmation')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-4">
                             <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-900 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-emerald-800 focus:bg-emerald-800 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                 Update Password
                             </button>

                            @if (session('status') === 'password-updated')
                                <p class="text-sm text-green-600 font-poppins">Password updated successfully.</p>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Account Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-emerald-900 font-merriweather mb-4">Account Actions</h2>
                    
                    <!-- Delete Account -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-emerald-900 font-merriweather mb-2">Delete Account</h3>
                        <p class="text-sm text-gray-600 font-poppins mb-4">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
                        
                        <button type="button" onclick="confirmDelete()" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Delete Account
                        </button>

                        <!-- Delete Account Form -->
                        <form method="post" action="{{ route('user.profile.destroy') }}" id="delete-form" class="hidden mt-4">
                            @csrf
                            @method('delete')
                            <div class="space-y-4">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-emerald-700 font-poppins">Password</label>
                                    <input type="password" name="password" id="password"
                                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                           placeholder="Enter your password to confirm">
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex gap-4">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Delete Account
                                    </button>
                                    <button type="button" onclick="cancelDelete()" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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
</x-layout>
