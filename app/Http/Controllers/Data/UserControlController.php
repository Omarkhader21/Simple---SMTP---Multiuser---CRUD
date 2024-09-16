<?php

namespace App\Http\Controllers\Data;

use Exception;
use App\Models\User;
use App\Mail\AuthMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserControlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::query()->get();
        return view('user-controller.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user-controller.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:4',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Handle image upload
        $imageName = null;
        if ($request->hasFile('image')) {
            // Get the original filename or generate a new name
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();

            // Store the image in the desired folder, like 'profile_images'
            $request->file('image')->storeAs('profile_images', $imageName, 'public');
        }

        // Generate a verification key
        $verifyKey = Str::random(32); // Generate a random key or your preferred method

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'image' => $imageName,
            'verify_key' => $verifyKey
        ]);

        $details = [
            'name' => $request->name,
            'role' => 'user',
            'datetime' => date('Y-m-d H:i:s'),
            'website' => 'Laravel 11 - registration via SMTP + Multiuser + CRUD + SweetAlert',
            'url' => 'http://' . request()->getHttpHost() . "/" . "verify/" . $verifyKey
        ];

        Mail::to($request->email)->send(new AuthMail($details));

        flash()->success('User registered successfully!');

        flash()->success('Link verification sent successfully to email: ' . $request->email . '');

        return redirect()->route('user-control.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        try {
            $user = User::findOrFail($id);
            return view('user-controller.edit', compact('user'));
        } catch (Exception $e) {
            flash()->error($e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'name' => 'required|min:4',
            'email' => 'required|email|unique:users,email,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image validation rules
        ]);

        // Handle image upload
        $imageName = null;
        if ($request->hasFile('image')) {
            // Get the original filename or generate a new name
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();

            // Store the image in the desired folder, like 'profile_images'
            $request->file('image')->storeAs('profile_images', $imageName, 'public');
        }

        $user = User::findOrFail($id);

        $user->update([
            'name' => $validate['name'],
            'email' => $validate['email'],
            'image' => $imageName
        ]);

        flash()->success('User updated successfully!');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Use findOrFail to handle non-existent records more gracefully
            $student = User::findOrFail($id);

            $student->delete();

            flash()->success('User deleted successfully!');

            return redirect()->back();
        } catch (Exception $e) {
            // Handle the exception and provide an error message
            flash()->error($e->getMessage());
            // Optionally, log the exception for debugging
            Log::error('Error deleting student data: ' . $e->getMessage());

            return redirect()->back();
        }
    }
}
