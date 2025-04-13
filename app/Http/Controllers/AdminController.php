<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(){
        return view('admin.dashboard');
    }

    public function show(User $user){
        // Fetch the user details
        return view('admin.user_view', compact('user'));
    }
    
    // List all students
    public function userlist(){
        $users = User::all();
        return view('admin.userlist' , compact('users'));
    }
    

    public function destroy(User $user){
    $user->delete();
    return redirect()->route('admin.userlist')->with('success', 'User deleted successfully.');
    }
}
