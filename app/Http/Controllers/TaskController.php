<?php

namespace App\Http\Controllers;

use App\Models\Request;  // Assuming you are using Request as the model
use Illuminate\Http\Request as HttpRequest;
use App\Models\User;
use Illuminate\Support\Facades\Log; // Add this to log information



class TaskController extends Controller
{
    public function markAsComplete(Request $request, $taskId)
    {
        // Find the task by ID
        $task = Request::findOrFail($taskId);

        // Mark the task as complete
        $task->status = 'Completed';

        // Set the end_time to the current time
        $task->end_time = now();

        // Save the updated task
        $task->save();

        // Redirect with success message
        return redirect()->route('it-queue')->with('success', 'Task marked as complete.');
    }

    public function skipTask($taskId)
    {
        // Find the task by ID
        $task = Request::findOrFail($taskId);

        // Log the current task status
        Log::info('Task ID: ' . $task->id . ' - Current Status: ' . $task->status);

        // Check if the task is already completed
        if ($task->status === 'Completed') {
            return redirect()->route('it-queue')->with('error', 'This task is already completed.');
        }

        // Find all users with 'available' status
        $availableUsers = User::whereHas('agentStatus', function ($query) {
            $query->where('name', 'available'); // Only users with 'available' status
        })->get();

        // Log the available users
        Log::info('Available Users: ' . $availableUsers->pluck('id')->implode(', '));

        // If no available user is found, try cycling through the list of all users
        if ($availableUsers->isEmpty()) {
            $allUsers = User::orderBy('AgentOrder')->get(); // Get all users in order of 'AgentOrder'
            $nextUser = null;

            // Cycle through all users in order
            foreach ($allUsers as $user) {
                if ($user->agentStatus && $user->agentStatus->name === 'available') {
                    $nextUser = $user;
                    break;
                }
            }

            // If still no available user, return error
            if (!$nextUser) {
                return redirect()->route('it-queue')->with('error', 'No other agents available.');
            }
        } else {
            // If there are available users, pick the next one based on ID (greater than current user ID)
            $nextUser = $availableUsers->where('id', '>', auth()->id())->first();

            // If no next user is found, cycle through the available users from the beginning
            if (!$nextUser) {
                $nextUser = $availableUsers->first();
            }
        }

        // Log the next available user to check if one is selected
        Log::info('Next Available User: ' . ($nextUser ? $nextUser->id : 'None'));

        // If a next available user is found, reassign the task
        if ($nextUser) {
            // Reassign the task to the next available user
            $task->user_id = $nextUser->id;
            $task->status = 'Assigned';
            $task->save();

            return redirect()->route('it-queue')->with('success', 'Task has been reassigned to the next available user.');
        }

        return redirect()->route('it-queue')->with('error', 'No available users to reassign the task.');
    }


}
