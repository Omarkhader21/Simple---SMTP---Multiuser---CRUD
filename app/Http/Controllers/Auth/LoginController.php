<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
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
        return view('auth.login');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email|max:255',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'max:255'
                ]
            ]);

            // Check the credentials and attempt to log in
            if (Auth::attempt($request->only('email', 'password'))) {
                // Check if the user's email is verified
                if (Auth::user()->hasVerifiedEmail()) {
                    if (Auth::user()->role === 'admin') {

                        flash()->success('Hallo admin, you have successfully logged in');
                        // Redirect to the dashboard or home page
                        return redirect()->route('admin.index');
                    } elseif (Auth::user()->role === 'user') {

                        flash()->success('Hallo ' . auth()->user()->name . ', you have successfully logged in');
                        // Redirect to the dashboard or home page
                        return redirect()->route('user.index');
                    }
                } else {
                    // Logout and redirect back with a message if the email isn't verified
                    Auth::logout();
                    flash()->warning('Your email address is not verified.');
                    return redirect()->back();
                }
            }
        } catch (ValidationException $e) {
            // Get the validation errors
            $errors = $e->errors(); // Array of errors

            // Flash each error message separately
            foreach ($errors as $fieldErrors) {
                foreach ($fieldErrors as $message) {
                    flash()->error($message);
                }
            }

            return redirect()->back()->withInput();
        }
    }

    /**
     *  Verify Account
     */

    public function verify($verify_key)
    {
        $keyCheck = User::select('verify_key')->where('verify_key', $verify_key)->exists();

        if ($keyCheck) {

            $user = User::where('verify_key', $verify_key)->update(['email_verified_at' => date('Y-m-d H:i:s')]);
            flash()->success('Verification successful, your account is now active.');
            return redirect()->route('login');
        } else {
            flash()->warning('keys are invalid. make sure you have registered');
            return redirect()->route('login')->withInput();
        }
    }

    /**
     *  Logout
     */

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
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
