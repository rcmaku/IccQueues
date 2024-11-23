<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AgentStatusHistory;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function index()
    {
        $agents = User::all();
        return view('agent.index', compact('agents'));
    }

    public function create()
    {
        return view('agent.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'AgentOrder' => 'required|integer|unique:users,AgentOrder',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $agent = new User();
        $agent->first_name = $request->first_name;
        $agent->last_name = $request->last_name;
        $agent->AgentOrder = $request->AgentOrder;
        $agent->email = $request->email;
        $agent->password = bcrypt($request->password);
        $agent->save();

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
        $agent->delete();
        return redirect()->route('agent.index')->with('success', 'Agent deleted successfully.');
    }

    public function showUserStatusGrid()
    {

        $users = User::with('agentStatus')
            ->orderBy('AgentOrder', 'asc')
            ->get();

        // Return the view with the sorted users
        return view('it-queue', compact('users'));
    }


    public function updateStatus(Request $request)
    {
        $user = auth()->user();
        $unavailableStatus = \App\Models\AgentStatus::firstOrCreate(['name' => 'unavailable']);
        $availableStatus = \App\Models\AgentStatus::firstOrCreate(['name' => 'available']);

        $newStatus = $user->agentStatus->name === 'available' ? $unavailableStatus : $availableStatus;
        $user->agentStatus()->associate($newStatus);
        $user->save();

        \App\Models\AgentStatusHistory::create([
            'user_id' => $user->id,
            'agent_status_id' => $user->agentStatus->id,
            'changed_at' => now(),
        ]);

        if ($newStatus->name === 'unavailable') {
            \App\Models\Queue::create([
                'user_id' => $user->id,
                'status_call' => 1,
                'support_start' => now(),
            ]);
        }

        return redirect()->route('it-queue')->with('status', 'Your status has been updated.')
            ->with('new_status', $newStatus->name);
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

}
