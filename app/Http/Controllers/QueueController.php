<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Queue;
use App\Models\Status;

class QueueController extends Controller
{

    // QueueController.php


    public function updateQueue()
    {
        // Get the "Available" status ID
        $availableStatus = Status::where('name', 'Available')->first();

        if (!$availableStatus) {
            return response()->json(['message' => 'Available status not found.'], 404);
        }

        // Get all agents with "Available" status
        $availableAgents = User::whereHas('status', function($query) use ($availableStatus) {
            $query->where('status_id', $availableStatus->id);
        })->get();

        foreach ($availableAgents as $agent) {
            // Check if the agent is already in the queue
            $isInQueue = Queue::where('agent_id', $agent->id)->exists();

            if (!$isInQueue) {
                // Add the agent to the queue
                Queue::create(['agent_id' => $agent->id]);
            }
        }

        return response()->json(['message' => 'Queue updated successfully.']);
    }


/**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
