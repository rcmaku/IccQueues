<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function generateReport(Request $request)
    {
        // Query the 'queues' table and join with 'users' table
        $query = Queue::query()
            ->selectRaw('users.id as user_id, CONCAT(users.first_name, " ", users.last_name) as full_name, users.email')
            ->selectRaw('COUNT(queues.id) as interaction_count')
            ->selectRaw('IFNULL(AVG(TIMESTAMPDIFF(SECOND, queues.support_start, queues.support_end)), 0) as avg_handling_time')
            ->selectRaw('MIN(queues.support_start) as first_interaction_date')
            ->selectRaw('MAX(queues.support_end) as last_interaction_date')
            ->join('users', 'queues.user_id', '=', 'users.id')
            ->whereNotNull('queues.support_start')
            ->whereNotNull('queues.support_end')
            ->groupBy('users.id', 'users.first_name', 'users.last_name', 'users.email');

        // Apply filters if provided
        if ($request->has('user_id')) {
            $query->where('users.id', $request->user_id);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('queues.support_start', [$startDate, $endDate]);
        }

        // Execute the query and get the results
        $reportData = $query->get();

        // Retrieve all users for the dropdown filter
        $users = User::all();

        // Return the view with the report data and users list
        return view('report.report', compact('reportData', 'users'));
    }
}
