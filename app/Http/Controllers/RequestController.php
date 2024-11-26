<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Request; // Request model
use App\Models\AgentStatus; // Add this to access AgentStatus model
use App\Models\agentStatusHistory;
use Illuminate\Http\Request as HttpRequest;

class RequestController extends Controller
{
    public function store(HttpRequest $request)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'channel' => 'nullable|string|max:255',
            'description' => 'required|string|max:500',  // Added for description validation
            'request_type' => 'nullable|string|max:255',  // Validate request type
            'start_time' => 'required|date',
            'status' => 'required|string|in:pending',  // Validate that status is 'pending'
        ]);

        // Get the next available user
        $nextAvailableUser = User::whereHas('agentStatus', function ($query) {
            $query->where('name', 'available');
        })->first();

        // Handle case if no user is available
        if (!$nextAvailableUser) {
            return redirect()->back()->withErrors('No available users to assign this request.');
        }

        // Assign the next available user to the request
        $validated['user_id'] = $nextAvailableUser->id;

        // Create the request and assign it to the available user
        $requestCreated = Request::create($validated);

        // Update the user's status to busy
        $nextAvailableUser->agentStatus()->associate(AgentStatus::where('name', 'busy')->first());
        $nextAvailableUser->save();

        return redirect()->back()->with('success', 'Request created and assigned!');
    }

    public function showQueue()
    {
        // Fetch users with their agent status and other necessary data
        $users = User::with('agentStatus')->orderBy('AgentOrder')->get();

        // Fetch the current task that is in "pending" status
        $task = Request::where('status', 'pending')->first();  // Only tasks with 'pending' status

        // Handle case where no task is available
        if (!$task) {
            return view('it-queue', ['message' => 'No pending tasks at the moment']);
        }

        // Pass the current task and users, as well as the authenticated user
        return view('it-queue', compact('task', 'users'))->with('authUser', Auth::user());
    }

    public function complete(Request $request)
    {
        // Ensure that only the assigned user can complete the request
        if ($request->user_id !== auth()->id()) {
            return redirect()->route('it-queue')->with('error', 'You are not authorized to complete this request.');
        }

        // Update the request status to 'Completed'
        $request->update(['status' => 'Completed']);

        // Fetch the user assigned to this task
        $user = User::find($request->user_id);

        // Fetch the 'available' status and associate it with the user (if not already available)
        $availableStatus = AgentStatus::firstOrCreate(['name' => 'available']);

        // Update the user's status to 'available'
        $user->agentStatus()->associate($availableStatus);
        $user->save();

        // Update the last interaction (status change) time
        agentStatusHistory::create([
            'user_id' => $user->id,
            'agent_status_id' => $availableStatus->id,
            'changed_at' => now(),
        ]);

        return redirect()->route('it-queue')->with('success', 'Request marked as complete and status updated to available.');
    }



}
