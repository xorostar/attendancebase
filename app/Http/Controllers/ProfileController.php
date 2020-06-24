<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();
        return view("profile.edit", compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'profile_picture' => ['nullable', 'image'],
        ]);
        if (array_key_exists("profile_picture", $data)) {
            if ($user->profile_picture) {
                Storage::delete('public/' . $user->profile_picture);
            }
            $image = $request->profile_picture;
            $image_name = Str::random(15) . '.' . $image->getClientOriginalExtension();
            Storage::putFileAs('public', $image, $image_name);
            $data["profile_picture"] = $image_name;
        } else {
            $data["profile_picture"] = $user->profile_picture;
        }
        $user->name = $data["name"];
        $user->profile_picture = $data["profile_picture"];
        $user->save();
        $request->session()->flash('success', 'Profile updated successfully.');
        return redirect(route("profile.edit"));
    }
}
