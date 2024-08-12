<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['approveUser', 'listUsers']);
        $this->middleware('admin')->only(['approveUser', 'listUsers']);
    }

    /**
     * Register a new user to the system.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'=> 'required|string|max:255',
            'email'=> 'required|string|email|max:255|unique:users',
            'phone_number'=> 'required|string|max:10',
            'password'=> Password::min(8)
            ->mixedCase()
            ->letters()
            ->numbers()
            ->symbols(),
        ]);
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone_number'=>$request->phone_number,
            'password'=>bcrypt($request->password),
            'is_approved'=>false,
            'is_admin'=>false,
        ]);
        return redirect('/login')->with('success', 'Registration successful! Awaiting admin approval.');
    }

    /**
     * Function to approve user to the system
     */
    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->is_approved= true;
        $user->save;

        //send an email notification to the approved user
        Mail::to($user->email)->send(new \App\Mail\UserApproved($user));

        return redirect()->back()->with('success', 'User approved successfully.');
    }

    /**
     * function that lists all users to the admin.
     */
    public function listUsers()
    {
        $users = User::where('is_admin', false)->get();
        return view ('admin.users', compact ('users'));
    }
}
