<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\agentStatusHistory;
use App\Models\AgentStatus; // Make sure this is correctly imported
use App\Models\Request as RequestModel;
use Illuminate\Http\Request as HttpRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\View; // Make sure this is at the top of your controller
use Illuminate\Support\Facades\Log; // For logging errors or debugging
use App\Notifications\TicketAssignedNotification;  // <-- Import the notification class



class RequestController extends Controller
{
    /**
     * Store the new request and assign it to the most available IT Specialist.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(HttpRequest $request)
    {
        // Validate incoming data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'channel' => 'required|string|max:255',
            'request_type' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'start_time' => 'required|date',
            'status' => 'required|string|max:50',
        ]);

        // Retrieve the most available IT Specialist
        $upcomingUser = User::select('users.*')
            ->join('agent_roles', 'users.id', '=', 'agent_roles.user_id')
            ->join('roles', 'agent_roles.role_id', '=', 'roles.id')
            ->join('agent_status', 'users.agent_status_id', '=', 'agent_status.id')
            ->where('roles.roleName', 'IT Specialist')
            ->where('agent_status.name', 'available')
            ->whereNotNull('users.available_at')
            ->orderBy('users.available_at', 'asc')
            ->latest('users.updated_at')
            ->first();

        // If no available IT Specialist is found, redirect with an error
        if (!$upcomingUser) {
            return redirect()->route('it-queue')->with('error', 'No available IT Specialist users to assign this request.');
        }

        // Prepare the request data
        $requestData = [
            'title' => $validated['title'],
            'channel' => $validated['channel'],
            'request_type' => $validated['request_type'],
            'description' => $validated['description'],
            'start_time' => $validated['start_time'],
            'status' => $validated['status'],
            'user_id' => $upcomingUser->id, // Assign the upcoming user
        ];

        // Save the new request
        $newRequest = RequestModel::create($requestData);

        // Log the creation of the new request
        Log::info("New request created and assigned to user ID: " . $upcomingUser->id);

        // Send the notification to the assigned user
        $upcomingUser->notify(new TicketAssignedNotification($validated['title']));

        // Get the "busy" status
        $busyStatus = AgentStatus::where('name', 'busy')->first();

        if (!$busyStatus) {
            Log::error('No "busy" status found in the agent_status table.');
            return redirect()->route('it-queue')->with('error', 'The "busy" status is missing from the system.');
        }

        // Update the user's status to busy
        try {
            if ($upcomingUser->agentStatus && $upcomingUser->agentStatus->name === 'busy') {
                return redirect()->route('it-queue')->with('info', 'User is already set to "busy".');
            }

            $upcomingUser->agentStatus()->associate($busyStatus);
            $upcomingUser->save();

            // Record status history
            AgentStatusHistory::create([
                'user_id' => $upcomingUser->id,
                'agent_status_id' => $busyStatus->id,
                'changed_at' => now(),
            ]);

            // Log the status change
            Log::info("User ID " . $upcomingUser->id . " status changed to 'busy' at " . now());

        } catch (\Exception $e) {
            Log::error('Error updating agent status: ' . $e->getMessage());
            return redirect()->route('it-queue')->with('error', 'Failed to update agent status.');
        }

        // Return to the queue page with success message
        return redirect()->route('it-queue')->with([
            'success' => 'Request created and assigned!',
            'upcomingUser' => $upcomingUser, // Pass the upcoming user data back
        ]);
    }




    /**
     * Show the available IT specialists and pending requests.
     *
     * @return \Illuminate\View\View
     */
    public function showQueue()
    {
        // Retrieve available users and upcoming user as before
        $availableUsers = User::select('users.*')
            ->join('agent_roles', 'users.id', '=', 'agent_roles.user_id')
            ->join('roles', 'agent_roles.role_id', '=', 'roles.id')
            ->join('agent_status_history', 'users.id', '=', 'agent_status_history.user_id')
            ->join('agent_status', 'agent_status_history.agent_status_id', '=', 'agent_status.id')
            ->where('roles.roleName', 'IT Specialist')
            ->where('agent_status.name', 'available')
            ->orderBy('agent_status_history.changed_at', 'asc')
            ->get();

        $upcomingUser = $availableUsers->first(); // The upcoming user
        $requests = RequestModel::where('status', 'pending')->get(); // Pending requests

        // Pass data to both layout and child views
        return view('it-queue', [
            'users' => $availableUsers,
            'upcomingUser' => $upcomingUser, // Pass the upcoming user to the view
            'requests' => $requests
        ]);
    }



}
