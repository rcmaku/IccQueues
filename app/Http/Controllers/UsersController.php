<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\AgentStatusHistory;
use Illuminate\Support\Facades\DB;
use App\Models\AgentStatus;  // Add this line at the top


class UsersController extends Controller
{
    // In your Controller Method
    public function index()
    {
        // Fetch tasks that are 'pending' (not 'Completed')
        $requests = Request::where('status', 'pending')->get();

        // You can also fetch the users and other required data
        $users = User::all();
        $availableStatuses = Status::all();  // Or your actual statuses data

        return view('your-view', compact('requests', 'users', 'availableStatuses'));
    }

    public function create()
    {
        $usedOrders = User::pluck('AgentOrder')->toArray();

        $allOrders = range(1, 100);

        $availableOrders = array_diff($allOrders, $usedOrders);

        return view('agent.create', compact('availableOrders'));
    }
    public function store(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'first_name' => [
                'required',
                'regex:/^[a-zA-Z\s]+$/',
                'max:255'
            ],
            'last_name' => [
                'required',
                'regex:/^[a-zA-Z\s]+$/',
                'max:255'
            ],
            'AgentOrder' => [
                'required',
                'integer',
                'unique:users,AgentOrder'
            ],
            'email_handle' => [
                'required',
                'regex:/^[a-zA-Z0-9._%+-]+$/',
                'max:64',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed' // Ensures the password_confirmation field is validated
            ],
        ], [
            'first_name.regex' => 'The first name may only contain letters and spaces.',
            'last_name.regex' => 'The last name may only contain letters and spaces.',
            'email_handle.regex' => 'The email handle may only contain letters, numbers, and special characters like . _ % + -.',
            'password.confirmed' => 'The password confirmation does not match.',
        ]);

        // Combine the email handle with the domain to form the full email
        $email = $validated['email_handle'] . '@iccbpo.com';

        // Check if the email already exists in the database
        if (User::where('email', $email)->exists()) {
            return back()->withInput($validated)->withErrors(['email_handle' => 'This email address is already taken. Please choose a different one.']);
        }

        // Create the agent record
        $agent = new User();
        $agent->first_name = $request->first_name;
        $agent->last_name = $request->last_name;
        $agent->AgentOrder = $request->AgentOrder;
        $agent->email = $email;
        $agent->password = bcrypt($request->password);
        $agent->save();

        // Redirect to agent index with success message
        return redirect()->route('agent.index')->with('success', 'Agent created successfully!');
    }

    public function edit(string $id)
    {
        $agent = User::findOrFail($id);
        return view('agent.edit', compact('agent'));
    }

    public function update(Request $request, string $id)
    {
        $agent = User::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:60',
            'last_name' => 'required|string|max:60',
            'email' => 'required|email|unique:users,email,' . $agent->id,
            'password' => 'nullable|string|min:6',
            'AgentOrder' => 'nullable|integer|unique:users,AgentOrder,' . $agent->id,
        ]);

        $agent->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $agent->password,
            'AgentOrder' => $request->AgentOrder,
        ]);

        return redirect()->route('agent.index')->with('success', 'Agent updated successfully.');
    }


    public function show(User $agent)
    {
        return view('agent.show', compact('agent'));
    }

    public function destroy(User $agent)
    {
        // Delete the user
        $agent->delete();
        return redirect()->route('agent.index')->with('success', 'Agent deleted successfully.');
    }


    public function showUserStatusGrid()
    {
        // Fetch users with their statuses and history
        $users = User::with([
            'agentStatus',
            'agentStatusHistory' => function ($query) {
                $query->latest('changed_at');
            },
            'requests',  // Load the user's requests
        ])->orderBy('AgentOrder', 'asc')->get();

        // Fetch all available statuses
        $availableStatuses = AgentStatus::all();

        // Find the most recent status change excluding changes to "Available"
        $lastStatusChange = AgentStatusHistory::whereHas('agentStatus', function ($query) {
            $query->where('name', '!=', 'Available');
        })->latest('changed_at')->first();

        // Determine the next user based on AgentOrder, circularly if needed
        $nextUser = null;
        if ($lastStatusChange) {
            $lastUser = $lastStatusChange->user;
            $nextUser = $users->filter(function ($user) use ($lastUser) {
                return $user->AgentOrder > $lastUser->AgentOrder;
            })->first();

            // If no user is found with a higher AgentOrder, get the first in line
            if (!$nextUser) {
                $nextUser = $users->first();
            }
        }

        // Find the next available user
        $nextAvailableUser = $users->first(function ($user) {
            return $user->agentStatus && $user->agentStatus->name === 'available' && !$user->isBusy();
        });

        // Fetch all requests (or filter by user if needed)
        $requests = \App\Models\Request::all();
        // You can modify this query if needed

        return view('it-queue', [
            'users' => $users,
            'lastStatusChange' => $lastStatusChange,
            'nextUser' => $nextUser,
            'taskDescription' => 'This is your next task description here.',
            'availableStatuses' => $availableStatuses,
            'nextAvailableUser' => $nextAvailableUser,
            'requests' => $requests,  // Pass the requests variable
        ]);
    }



    public function passToNextUser()
    {
        // Fetch all users sorted by AgentOrder
        $users = User::orderBy('AgentOrder', 'asc')->get();

        // Ensure there are users in the list
        if ($users->isEmpty()) {
            return redirect()->route('it-queue')->withErrors('No users found in the queue.');
        }

        // Get the currently logged-in user
        $currentUser = auth()->user();

        // If there's no logged-in user, redirect with an error
        if (!$currentUser) {
            return redirect()->route('it-queue')->withErrors('No current user found.');
        }

        // Find the current user's position in the ordered list
        $currentIndex = $users->search(function ($user) use ($currentUser) {
            return $user->id === $currentUser->id;
        });

        // If the current user is not in the queue, start from the first user
        if ($currentIndex === false) {
            $nextUser = $users->first();
        } else {
            // Determine the next user's index, wrapping around if needed
            $nextIndex = ($currentIndex + 1) % $users->count();
            $nextUser = $users[$nextIndex];
        }

        // Simulate updating the queue to pass to the next user
        // (e.g., you could add logic here to update a `current_user_id` in the database if needed)
        $message = 'Passed to the next user: ' . $nextUser->first_name . ' ' . $nextUser->last_name;

        // Redirect back to the IT queue view with a success message
        return redirect()->route('it-queue')->with('success', $message);
    }

    public function updateStatus(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'status' => 'required|exists:agent_status,name', // Validates the status name
        ]);

        // Find the status by name
        $status = AgentStatus::where('name', $request->status)->first();

        // Update user's status
        $user->agentStatus()->associate($status);
        $user->save();

        // Record status history
        AgentStatusHistory::create([
            'user_id' => $user->id,
            'agent_status_id' => $status->id,
            'changed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Status updated successfully!');
    }



    public function fetchUpdatedStatus()
    {
        $users = User::with(['agentStatus', 'agentStatusHistory' => function ($query) {
            $query->latest('changed_at');
        }])->get();

        return response()->json($users);
    }

    public function completeTask()
    {
        $user = auth()->user();
        $queueEntry = \App\Models\Queue::where('user_id', $user->id)
            ->whereNull('support_end')
            ->first();

        if ($queueEntry) {
            $queueEntry->update([
                'support_end' => now(),
                'status_call' => 0,
            ]);
        }

        $availableStatus = \App\Models\AgentStatus::firstOrCreate(['name' => 'available']);
        $user->agentStatus()->associate($availableStatus);
        $user->save();

        \App\Models\AgentStatusHistory::create([
            'user_id' => $user->id,
            'agent_status_id' => $availableStatus->id,
            'changed_at' => now(),
        ]);

        return redirect()->route('it-queue')->with('success', 'Task completed and status updated to available.');
    }

    public function notifyAvailableUsers(Request $request)
    {
        $availableUsers = User::where('status', 'available')->get();

        // Broadcast an event
        broadcast(new NewRequestNotification($request->title, $request->description, $availableUsers));

        return response()->json(['success' => true, 'message' => 'Notification sent successfully!']);
    }

}
