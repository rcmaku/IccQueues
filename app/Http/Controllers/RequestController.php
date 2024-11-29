<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Request as RequestModel;
use App\Models\AgentStatusHistory;
use Illuminate\Http\Request as HttpRequest;
use Carbon\Carbon;

class RequestController extends Controller
{

    /**
     * Store the new request and assign an available user.
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

        // Retrieve the current queue from the session
        $userQueue = session('userQueue', []);

        if (empty($userQueue)) {
            return redirect()->route('it-queue')->with('error', 'No available IT Specialist users to assign this request.');
        }

        // Assign the first available user from the queue
        $nextUser = array_shift($userQueue);

        // Prepare the request data to be saved
        $requestData = [
            'title' => $validated['title'],
            'channel' => $validated['channel'],
            'request_type' => $validated['request_type'],
            'description' => $validated['description'],
            'start_time' => $validated['start_time'],
            'status' => $validated['status'],
            'user_id' => $nextUser['id'],  // Assign the user from the queue
        ];

        // Save the new request
        RequestModel::create($requestData);

        // After assigning the user, push them back to the end of the queue
        array_push($userQueue, $nextUser);

        // Update the session with the new queue
        session(['userQueue' => $userQueue]);

        return redirect()->route('it-queue')->with([
            'success' => 'Request created and assigned!',
            'upcomingUser' => $nextUser, // Pass the next user data back
            'users' => $userQueue,
        ]);
    }

    /**
     * Show the available IT specialists and pending requests.
     *
     * @return \Illuminate\View\View
     */
    public function showQueue()
    {
        // Retrieve the user queue from session or initialize if empty
        $userQueue = session('userQueue', []);

        // Filter available users and sort by their availability
        $availableUsers = collect($userQueue)
            ->filter(function ($user) {
                return isset($user['agentStatus']) && $user['agentStatus']['name'] === 'available';
            })
            ->sortBy(function ($user) {
                return $user['available_at'] ?? now();
            });

        // Get the upcoming user (first in the sorted list)
        $upcomingUser = $availableUsers->first();

        // Retrieve all pending requests
        $requests = RequestModel::where('status', 'pending')->get();

        return view('it-queue', [
            'users' => $availableUsers,
            'upcomingUser' => $upcomingUser,
            'requests' => $requests,  // Pass requests to the view
        ]);
    }
}
