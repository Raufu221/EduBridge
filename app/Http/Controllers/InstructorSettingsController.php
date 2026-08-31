<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class InstructorSettingsController extends Controller
{
    /**
     * Show the settings page.
     */
    public function show()
    {
        return view('instructor.settings', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update profile information (name, bio, avatar, social links).
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'         => 'required|string|max:100',
            'phone'        => 'nullable|string|regex:/^[0-9]{11}$/',
            'about_me'     => 'nullable|string|max:1000',
            'social_links' => 'nullable|string|max:500',
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = [
            'name'         => $request->name,
            'phone'        => $request->phone,
            'about_me'     => $request->about_me,
            'social_links' => $request->social_links,
        ];

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if it's stored locally
            if ($user->profile_pic && Storage::disk('public')->exists($user->profile_pic)) {
                Storage::disk('public')->delete($user->profile_pic);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['profile_pic'] = $path;
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the instructor's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Your current password is incorrect.'])->withInput();
        }

        $user->update(['password' => Hash::make($request->password)]);


        return back()->with('password_success', 'Password changed successfully!');
    }

    /**
     * Update notification preferences.
     */
    public function updateNotifications(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        Auth::user()->update([
            'notification_settings' => $request->settings
        ]);

        return response()->json(['message' => 'Notification settings saved!']);
    }
}
