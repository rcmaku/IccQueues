<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Request;
use App\Models\User;
use Illuminate\Http\Request as HttpRequest;

class ReportController extends Controller
{
    public function generateReport(HttpRequest $request)
    {
        // Validate the request parameters
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'end_date.after_or_equal' => 'The end date must be equal to or later than the start date.',
        ]);

        // Simplified query for testing purposes
        $query = Request::query()
            ->selectRaw('users.id as user_id, CONCAT(users.first_name, " ", users.last_name) as full_name, users.email')
            ->selectRaw('COUNT(requests.id) as interaction_count')
            ->selectRaw('IFNULL(AVG(TIMESTAMPDIFF(SECOND, requests.start_time, requests.end_time)), 0) as avg_handling_time')
            ->selectRaw('MIN(requests.start_time) as first_interaction_date')
            ->selectRaw('MAX(requests.end_time) as last_interaction_date')
            ->join('users', 'requests.user_id', '=', 'users.id')
            // Remove whereNotNull temporarily to check if data exists
            ->groupBy('users.id', 'users.first_name', 'users.last_name', 'users.email');

        // Debugging: Log the SQL query to check the logic
        \Log::info('SQL Query:', ['query' => $query->toSql(), 'bindings' => $query->getBindings()]);

        // Get the data from the query
        $reportData = $query->get();

        // Debugging: Log the results to check if data is being fetched
        \Log::info('Report Data:', ['data' => $reportData]);

        // Get all users for use in the view
        $users = User::all();

        // Return the report view with the data
        return view('report.report', compact('reportData', 'users'));
    }
}
