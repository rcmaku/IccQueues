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

        // Query for the report with counts for each channel
        $query = Request::query()
            ->selectRaw('users.id as user_id, CONCAT(users.first_name, " ", users.last_name) as full_name, users.email')
            ->selectRaw('COUNT(requests.id) as interaction_count')
            ->selectRaw('COUNT(CASE WHEN requests.channel = "Slack" THEN 1 END) as slack_count')   // Slack interactions
            ->selectRaw('COUNT(CASE WHEN requests.channel = "Whatsapp" THEN 1 END) as whatsapp_count') // WhatsApp interactions
            ->selectRaw('COUNT(CASE WHEN requests.channel = "Email" THEN 1 END) as email_count')     // Email interactions
            ->selectRaw('IFNULL(AVG(TIMESTAMPDIFF(SECOND, requests.start_time, requests.end_time)), 0) as avg_handling_time')
            ->join('users', 'requests.user_id', '=', 'users.id')
            ->groupBy('users.id', 'users.first_name', 'users.last_name', 'users.email');

        // Apply filtering if start_date and end_date are provided
        if ($request->start_date) {
            $query->whereDate('requests.created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('requests.created_at', '<=', $request->end_date);
        }

        // Get the data from the query
        $reportData = $query->get();

        // Get all users for use in the view
        $users = User::all();

        // Return the report view with the data
        return view('report.report', compact('reportData', 'users'));
    }

}
