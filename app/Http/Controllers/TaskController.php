<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest; // Rename HTTP Request
use App\Models\Request as TaskRequest; // Rename your Request model
use App\Models\User;
use App\Models\AgentStatus;
use Illuminate\Support\Facades\Log;
use App\Models\agentStatusHistory;


class TaskController extends Controller
{
    public function markAsComplete(HttpRequest $request, $taskId)
    {
        // Retrieve the task by ID
        $task = TaskRequest::findOrFail($taskId);

        // Update the task status to 'Completed' and set the end_time to the current time
        $task->status = 'Completed';
        $task->end_time = now();  // This uses Laravel's now() helper function for the current time
        $task->save();

        // Get the user assigned to this task
        $user = $task->user;

        // Get the 'Available' status from the agent_status table
        $availableStatus = AgentStatus::where('name', 'Available')->first();

        // Ensure the 'Available' status exists
        if (!$availableStatus) {
            return redirect()->back()->withErrors('The "Available" status is missing from the system.');
        }

        // Check if the user's status is already "Available" to prevent redundant updates
        if ($user->agentStatus && $user->agentStatus->name === 'Available') {
            return redirect()->back()->with('info', 'User is already marked as Available.');
        }

        // Update the user's status to 'Available' using the logic from the updateStatus function
        try {
            // Update the user's status
            $user->agentStatus()->associate($availableStatus);
            $user->available_at = now();  // Update available_at field with the current time
            $user->save();

            // Record the status change in the agent_status_history table
            AgentStatusHistory::create([
                'user_id' => $user->id,
                'agent_status_id' => $availableStatus->id,
                'changed_at' => now(),
            ]);

            // Log the status change
            Log::info("User ID " . $user->id . " status changed to 'Available' at " . now());

        } catch (\Exception $e) {
            // Log the error if unable to update the status
            Log::error('Error updating agent status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update agent status.');
        }

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Task marked as completed and user status updated to Available.');
    }



    protected $requestController;

    public function __construct(RequestController $requestController)
    {
        $this->requestController = $requestController;
    }

    public function skip(HttpRequest $httpRequest, $requestId)
    {
        // Retrieve the task by ID
        $task = TaskRequest::findOrFail($requestId);

        // Validate ownership and ensure the task is not already completed
        if ($task->user_id === auth()->id() && $task->status !== 'Completed') {
            // Mark the task as skipped
            $task->status = 'Skipped';
            $task->save();

            // Fetch the user queue from the session
            $userQueue = session('userQueue', []);

            // If no users are available in the queue
            if (empty($userQueue)) {
                return redirect()->back()->withErrors('No available users to assign the task to.');
            }

            // Get the next user in the queue (shift the first user)
            $nextUser = array_shift($userQueue);

            // Reassign the task to the next available user
            $task->user_id = $nextUser['id'];
            $task->save();

            // Log the reassignment
            Log::info('Task reassigned to user: ' . $nextUser['id']);

            // Push the user who was just assigned the task back to the end of the queue
            array_push($userQueue, $nextUser);

            // Save the updated user queue back to the session
            session(['userQueue' => $userQueue]);

            // Optionally, update the skipping user's status to 'Available'
            $user = auth()->user();
            $availableStatus = AgentStatus::where('name', 'Available')->first();

            if ($availableStatus) {
                $user->agentStatus()->associate($availableStatus);
                $user->save();
            }

            return redirect()->back()->with('success', 'Task skipped and reassigned to the next available user.');
        }

        // If task ownership or status validation fails
        return redirect()->back()->withErrors('Unable to skip the task.');
    }

}
