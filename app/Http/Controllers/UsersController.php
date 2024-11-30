<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Status; // Assuming the Status model exists
use App\Models\AgentStatus; // Correctly import AgentStatus model
use Illuminate\Http\Request;
use App\Models\Request as TaskRequest; // Correctly importing the Request model as TaskRequest
use App\Models\AgentStatusHistory; // Import the AgentStatusHistory model


class UsersController extends Controller
{
    // Show all agents
    public function index()
    {
        // Get all agents from the database
        $agents = User::all();
        // Return the view with the agents data
        return view('agent.index', compact('agents'));
    }

    // Show form to create a new agent
    public function create()
    {
        // Get all used AgentOrders
        $usedOrders = User::pluck('AgentOrder')->toArray();

        // Create an array of available orders
        $allOrders = range(1, 100);
        $availableOrders = array_diff($allOrders, $usedOrders);

        // Fetch available statuses
        $availableStatuses = agentStatus::all();  // Assuming you have a Status model

        return view('agent.create', compact('availableOrders', 'availableStatuses'));
    }

    // Store the new agent data in the database
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
                'confirmed'
            ],
        ], [
            'first_name.regex' => 'The first name may only contain letters and spaces.',
            'last_name.regex' => 'The last name may only contain letters and spaces.',
            'email_handle.regex' => 'The email handle may only contain letters, numbers, and special characters like . _ % + -. ',
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

    // Show the edit form for the agent
    public function edit(string $id)
    {
        $agent = User::findOrFail($id);
        return view('agent.edit', compact('agent'));
    }

    // Update agent data in the database
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

    // Show agent details
    public function show(User $agent)
    {
        return view('agent.show', compact('agent'));
    }

    // Delete the agent from the database
    public function destroy(User $agent)
    {
        // Delete the user
        $agent->delete();
        return redirect()->route('agent.index')->with('success', 'Agent deleted successfully.');
    }

    // Show the grid with available IT specialists
    public function showUserStatusGrid()
    {
        // Fetch all agent statuses
        $availableStatuses = AgentStatus::all();  // Fetch all statuses

        // Fetch users with their agent statuses and histories
        $users = User::with(['agentStatusHistory', 'agentStatus'])->get();

        // Fetch all requests (or filter by user if needed)
        $requests = TaskRequest::all();  // This fetches all requests, adjust as needed

        // Find the user who has been available the longest (earliest available_at)
        $upcomingUser = $users->filter(function($user) {
            return $user->agentStatus && $user->agentStatus->name === 'available';
        })->sortBy('available_at')->first();  // Sort by available_at to get the one who's been available the longest

        // Pass the variables to the view
        return view('it-queue', compact('users', 'availableStatuses', 'requests', 'upcomingUser'));
    }


    // Add user to the queue
    public function addToQueue(User $user)
    {
        // Initialize or get the existing queue from session
        $userQueue = session('userQueue', []);

        // Add user to the queue with necessary info, ensuring available_at is included
        $userQueue[] = [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'AgentOrder' => $user->AgentOrder,
            'email' => $user->email,
            'status' => $user->agentStatus->name ?? 'unavailable',
            'available_at' => $user->available_at ? $user->available_at->toDateTimeString() : null, // Ensure available_at is included
        ];

        // Sort the queue in descending order of available_at
        usort($userQueue, function ($a, $b) {
            // Check for valid 'available_at' before attempting strtotime
            $availableAtA = isset($a['available_at']) ? strtotime($a['available_at']) : 0;
            $availableAtB = isset($b['available_at']) ? strtotime($b['available_at']) : 0;

            return $availableAtB - $availableAtA;
        });

        // Reindex the array to avoid gaps in keys
        $userQueue = array_values($userQueue);

        // Save the updated queue back to session
        session(['userQueue' => $userQueue]);

        return redirect()->route('agent.show', $user);
    }
    // Mark user as available and set the available_at timestamp
    public function markAvailable()
    {
        $user = auth()->user();

        // First, check if the user has any ongoing queue entry that needs to be ended
        $queueEntry = \App\Models\Queue::where('user_id', $user->id)
            ->whereNull('support_end')
            ->first();

        if ($queueEntry) {
            $queueEntry->update([
                'support_end' => now(),
                'status_call' => 0, // Marking as no longer active
            ]);
        }

        // Set the user's status to 'available'
        $availableStatus = \App\Models\AgentStatus::firstOrCreate(['name' => 'available']);
        $user->agentStatus()->associate($availableStatus);

        // Update the available_at field with the current time
        $user->available_at = now();

        // Save the user to persist the available_at field
        $user->save();

        // Record the status change in history
        \App\Models\AgentStatusHistory::create([
            'user_id' => $user->id,
            'agent_status_id' => $availableStatus->id,
            'changed_at' => now(),
        ]);

        // Now, push the user back into the session queue, ensuring available_at is included
        $this->addToQueue($user);

        // Provide success message and redirect back to the queue page
        return redirect()->route('it-queue')->with('success', 'Task completed and status updated to available.');
    }





    // Remove user from the queue
    private function removeFromQueue($user)
    {
        $userQueue = session('userQueue', []);

        // Find and remove the user from the queue
        foreach ($userQueue as $index => $queuedUser) {
            if ($queuedUser['id'] === $user->id) {
                unset($userQueue[$index]);
                break;
            }
        }

        // Reindex the array to avoid gaps
        $userQueue = array_values($userQueue);

        // Save the updated queue back to the session
        session(['userQueue' => $userQueue]);
    }


    // Update the status of a user (e.g., for IT Check, etc.)
    // Update the status of a user (e.g., for IT Check, etc.)
    public function updateStatus(Request $request)
    {
        $user = auth()->user();

        // Validate incoming status
        $request->validate([
            'status' => 'required|exists:agent_status,name', // Validates the status name
        ]);

        // Find the status by name
        $status = AgentStatus::where('name', $request->status)->first();

        if (!$status) {
            return redirect()->back()->withErrors('Status not found!');
        }

        // Check if the status is already the same
        if ($user->agentStatus && $user->agentStatus->name === $status->name) {
            return redirect()->back()->with('info', 'Your status is already set to this.');
        }

        // Update user's status
        $user->agentStatus()->associate($status);
        $user->save();

        // Record status history
        AgentStatusHistory::create([
            'user_id' => $user->id,
            'agent_status_id' => $status->id,
            'changed_at' => now(),
        ]);

        // If status changes to anything that takes the user out of the queue (e.g., not available),
        // remove them from the session queue
        if ($status->name !== 'available') {
            $this->removeFromQueue($user);
        }

        // Provide success message
        return redirect()->back()->with('success', 'Status updated successfully!');
    }


}
