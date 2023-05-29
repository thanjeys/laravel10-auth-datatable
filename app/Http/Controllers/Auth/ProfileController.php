<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function edit()
    {
        return view('auth.profile-edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048'
        ]);


        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('avatar')) {
            // Get the uploaded file from the request
            $uploadedFile = $request->file('avatar');

            // Generate a unique filename for the image
            $filename = time() . '_' . $uploadedFile->getClientOriginalName();

            // Save the uploaded file to the storage disk
            Storage::putFileAs('public/avatars', $uploadedFile, $filename);

            if ($user->avatar) {
                Storage::delete('public/avatars/' . basename($user->avatar));
            }
            // Save the file path to the user's avatar field
            $user->avatar = 'storage/avatars/' . $filename;
        }

        // Save the updated user object
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
