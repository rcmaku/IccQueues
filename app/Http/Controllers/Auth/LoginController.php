<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AgentStatus;
use App\Models\AgentStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
/**
* Show the login form.
*
* @return \Illuminate\View\View
*/
public function showLoginForm()
{
return view('auth.login');  // The view you provided
}

/**
* Handle login logic.
*
* @param \Illuminate\Http\Request $request
* @return \Illuminate\Http\RedirectResponse
*/
public function login(Request $request)
{
// Validate the incoming data
$validated = $request->validate([
'email' => 'required|email',
'password' => 'required|string',
]);

// Attempt to authenticate the user
if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember-me'))) {
// After successful login, get the authenticated user
$user = Auth::user();

// Fetch or create the 'available' status
$availableStatus = AgentStatus::firstOrCreate(['name' => 'available']);

// Update the user's status to "available"
$user->agentStatus()->associate($availableStatus);
$user->save();

// Log the status change in the AgentStatusHistory
AgentStatusHistory::create([
'user_id' => $user->id,
'agent_status_id' => $availableStatus->id,
'changed_at' => now(),
]);

// Redirect to the intended URL or a default page
return redirect()->intended('/support'); // Replace with the route you want to redirect after successful login
}

// If authentication fails, redirect back with error
return Redirect::back()->withErrors([
'email' => 'The provided credentials do not match our records.',
]);
}
}
