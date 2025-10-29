<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // Check which guard is being used by looking at the route name
        $routeName = $request->route()->getName();
        
        // Debug: Log which route is being accessed
        \Log::info('Profile edit accessed', [
            'route_name' => $routeName,
            'user_id' => $request->user()->id,
            'user_name' => $request->user()->name
        ]);
        
        if ($routeName === 'user.profile.edit') {
            // Customer profile - use the new customer layout
            return view('profile.customer-edit', [
                'user' => $request->user(),
            ]);
        } else {
            // Admin profile - use the existing admin layout
            return view('profile.edit', [
                'user' => $request->user(),
            ]);
        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        // mag redirect to the correct profile route depending on guard wfawdfsaff dcsa va hofsdf kapoy nako sir kakalabas ko lang hospital HAHAHHA
        if (Auth::guard('admin')->check()) {
            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        }

        return Redirect::route('user.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update the user's profile picture.
     */
    public function updatePicture(Request $request): RedirectResponse
    {
        try {
            // Debug: Log the request details
            \Log::info('Profile picture upload started', [
                'user_id' => $request->user()->id,
                'has_file' => $request->hasFile('profile_picture'),
                'file_size' => $request->hasFile('profile_picture') ? $request->file('profile_picture')->getSize() : null,
                'file_mime' => $request->hasFile('profile_picture') ? $request->file('profile_picture')->getMimeType() : null,
            ]);

            $request->validate([
                'profile_picture' => 'required|image|mimes:jpeg,png,gif,webp|max:2048', // 2MB max
            ]);

            $user = $request->user();

            // Delete old profile picture if exists
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Store new profile picture
            $file = $request->file('profile_picture');
            if (!$file || !$file->isValid()) {
                \Log::error('Invalid file uploaded', [
                    'user_id' => $user->id,
                    'file_valid' => $file ? $file->isValid() : false,
                    'file_error' => $file ? $file->getError() : 'No file'
                ]);
                return back()->withErrors(['profile_picture' => 'Invalid file uploaded.']);
            }

            // Store new profile picture using the same method as product images
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/profile-pictures'), $filename);
            $path = 'profile-pictures/' . $filename;
            
            \Log::info('File stored successfully', [
                'user_id' => $user->id,
                'path' => $path,
                'file_exists' => Storage::disk('public')->exists($path)
            ]);
            
            // Check if path is generated correctly
            if (empty($path)) {
                \Log::error('Empty path generated', ['user_id' => $user->id]);
                return back()->withErrors(['profile_picture' => 'Failed to store the image. Please try again.']);
            }
            
            $user->profile_picture = $path;
            $user->save();
            
            // Refresh the user model to ensure updated data
            $user->refresh();

            \Log::info('Profile picture updated successfully', [
                'user_id' => $user->id,
                'profile_picture' => $user->profile_picture
            ]);

            // Redirect to the correct profile route based on the route name
            $routeName = $request->route()->getName();
            
            \Log::info('Redirecting after profile picture upload', [
                'route_name' => $routeName,
                'user_id' => $user->id
            ]);
            
            if ($routeName === 'user.profile.picture.update') {
                return Redirect::route('user.profile.edit')->with('status', 'profile-picture-updated');
            } else {
                return Redirect::route('profile.edit')->with('status', 'profile-picture-updated');
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in profile picture upload', [
                'errors' => $e->errors(),
                'user_id' => $request->user()->id
            ]);
            return back()->withErrors($e->errors());
            
        } catch (\Exception $e) {
            \Log::error('Profile picture upload error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $request->user()->id
            ]);
            
            return back()->withErrors(['profile_picture' => 'An error occurred while uploading the image. Please try again.']);
        }
    }

    /**
     * Remove the user's profile picture.
     */
    public function removePicture(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Delete profile picture file if exists
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Clear profile picture from database
        $user->profile_picture = null;
        $user->save();

        // Redirect to the correct profile route based on the route name
        $routeName = $request->route()->getName();
        
        \Log::info('Redirecting after profile picture removal', [
            'route_name' => $routeName,
            'user_id' => $user->id
        ]);
        
        if ($routeName === 'user.profile.picture.remove') {
            return Redirect::route('user.profile.edit')->with('status', 'profile-picture-removed');
        } else {
            return Redirect::route('profile.edit')->with('status', 'profile-picture-removed');
        }
    }
}
