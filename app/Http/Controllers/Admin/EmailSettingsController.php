<?php

namespace App\Http\Controllers\Admin;

use App\EmailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;

class EmailSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:manage_settings');
    }

    /**
     * Show all email settings
     */
    public function index()
    {
        try {
            $emails = EmailSetting::orderBy('id', 'asc')->get();
            return view('admin/email-settings/index', ['emails' => $emails]);
        } catch (\Exception $e) {
            \Log::error('EmailSettings index error: ' . $e->getMessage());
            return view('admin/email-settings/index', ['emails' => collect()]);
        }
    }

    /**
     * Store new email
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:email_settings,email',
            'label' => 'required|string|max:100',
            'type'  => 'required|in:enquiry,otp,both',
        ]);

        EmailSetting::create([
            'email'     => $request->email,
            'label'     => $request->label,
            'type'      => $request->type,
            'is_active' => 1,
        ]);

        return redirect()->back()->with('custom_success', 'Email added successfully.');
    }

    /**
     * Toggle active/inactive status
     */
    public function toggleStatus($id)
    {
        $email = EmailSetting::findOrFail($id);
        $email->is_active = !$email->is_active;
        $email->save();

        $status = $email->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('custom_success', "Email {$status} successfully.");
    }

    /**
     * Update email record
     */
    public function update(Request $request, $id)
    {
        $email = EmailSetting::findOrFail($id);

        $request->validate([
            'email' => 'required|email|unique:email_settings,email,' . $id,
            'label' => 'required|string|max:100',
            'type'  => 'required|in:enquiry,otp,both',
        ]);

        $email->update([
            'email' => $request->email,
            'label' => $request->label,
            'type'  => $request->type,
        ]);

        return redirect()->back()->with('custom_success', 'Email updated successfully.');
    }

    /**
     * Delete email record
     */
    public function destroy($id)
    {
        EmailSetting::findOrFail($id)->delete();
        return redirect()->back()->with('custom_success', 'Email deleted successfully.');
    }

    /**
     * Send test mail to verify SMTP is working
     */
    public function sendTestMail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            Mail::raw('This is a test email from RSM Admin Panel. Your mail configuration is working correctly.', function ($message) use ($request) {
                $message->to($request->test_email)
                        ->subject('RSM Admin - Test Email');
            });

            return redirect()->back()->with('custom_success', 'Test email sent successfully to ' . $request->test_email);
        } catch (\Exception $e) {
            return redirect()->back()->with('custom_error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
}
