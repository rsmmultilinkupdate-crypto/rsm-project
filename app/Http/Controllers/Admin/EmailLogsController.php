<?php

namespace App\Http\Controllers\Admin;

use App\EmailLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmailLogsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:manage_settings');
    }

    /**
     * Show all email logs
     */
    public function index()
    {
        $logs = EmailLog::orderBy('created_at', 'desc')->paginate(50);
        return view('admin/email-logs/index', ['logs' => $logs]);
    }

    /**
     * Clear old logs
     */
    public function clear(Request $request)
    {
        $days = $request->input('days', 30);
        $deleted = EmailLog::where('created_at', '<', now()->subDays($days))->delete();
        
        return redirect()->back()->with('custom_success', "Deleted $deleted old email logs.");
    }
}
