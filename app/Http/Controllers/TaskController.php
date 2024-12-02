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

    public function assignTask(HttpRequest $request, $taskId)
    {
        // Retrieve the task by ID
        $task = TaskRequest::findOrFail($taskId);

        // Validate the user being assigned to the task
        $userId = $request->input('user_id');
        $user = User::findOrFail($userId);

        // Check if the user is already assigned to the task
        if ($task->user_id === $user->id) {
            return redirect()->back()->with('info', 'This task is already assigned to the selected user.');
        }

        // Assign the task to the selected user
        $task->user_id = $user->id;
        $task->status = 'Assigned'; // Mark the task as "Assigned"
        $task->start_time = now(); // Optionally set the start time to now
        $task->save();

        // Get the 'Assigned' status from the agent_status table (or create if not exists)
        $assignedStatus = AgentStatus::where('name', 'Assigned')->first();

        // If 'Assigned' status is not found, create it or log an error
        if (!$assignedStatus) {
            $assignedStatus = AgentStatus::create([
                'name' => 'Assigned',
                'description' => 'User is assigned to a task',
            ]);
        }

        // Update the user's status to 'Assigned' and set their availability time to now
        try {
            $user->agentStatus()->associate($assignedStatus);
            $user->available_at = now();  // Update availability time
            $user->save();

            // Record the status change in the agent_status_history table
            AgentStatusHistory::create([
                'user_id' => $user->id,
                'agent_status_id' => $assignedStatus->id,
                'changed_at' => now(),
            ]);

            // Log the task assignment
            Log::info("User ID " . $user->id . " assigned task ID " . $task->id . " at " . now());

        } catch (\Exception $e) {
            Log::error('Error updating agent status during task assignment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update agent status during task assignment.');
        }

        // Now, update the status of the current user (the one doing the assigning) to 'Available'
        $currentUser = auth()->user(); // Get the current logged-in user

        // Get the 'Available' status from the agent_status table
        $availableStatus = AgentStatus::where('name', 'Available')->first();

        // Ensure the 'Available' status exists
        if (!$availableStatus) {
            return redirect()->back()->withErrors('The "Available" status is missing from the system.');
        }

        // Check if the current user's status is not already "Available"
        if ($currentUser->agentStatus && $currentUser->agentStatus->name !== 'Available') {
            // Update the current user's status to 'Available'
            try {
                $currentUser->agentStatus()->associate($availableStatus);
                $currentUser->available_at = now();  // Update available_at field with the current time
                $currentUser->save();

                // Record the status change in the agent_status_history table
                AgentStatusHistory::create([
                    'user_id' => $currentUser->id,
                    'agent_status_id' => $availableStatus->id,
                    'changed_at' => now(),
                ]);

                // Log the status change to 'Available'
                Log::info("Current user ID " . $currentUser->id . " status changed to 'Available' after assigning task ID " . $task->id . " at " . now());

            } catch (\Exception $e) {
                Log::error('Error updating current user agent status: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Failed to update current user agent status.');
            }
        }

        // Redirect back with success message
        return redirect()->back()->with('success', 'Task successfully assigned and your status updated to Available.');
    }

}
