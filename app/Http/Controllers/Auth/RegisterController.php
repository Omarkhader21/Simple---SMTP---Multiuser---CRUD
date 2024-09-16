<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\AuthMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'image' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',
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

            flash()->success('Link verification sent successfully to your email: '. $request->email .'');

            return redirect()->route('login');
        } catch (ValidationException $e) {

            // Get the validation errors
            $errors = $e->errors(); // Array of errors

            // Flash each error message separately
            foreach ($errors as $fieldErrors) {
                foreach ($fieldErrors as $message) {
                    flash()->error($message);
                }
            }

            // Redirect back to the previous page or form with input
            return redirect()->back()->withInput();
        }
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
