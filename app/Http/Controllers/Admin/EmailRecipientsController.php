<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\EmailRecipient;

class EmailRecipientsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:manage_settings');
    }

    public function index()
    {
        $recipients = EmailRecipient::orderBy('id', 'asc')->get();
        return view('admin.email-recipients.index', compact('recipients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:email_recipients,email',
            'name' => 'nullable|string|max:255'
        ]);

        EmailRecipient::create([
            'email' => $request->email,
            'name' => $request->name,
            'is_active' => 1
        ]);

        return redirect()->route('email-recipients.index')
                         ->with('success', 'Email recipient added successfully!');
    }

    public function update(Request $request, $id)
    {
        $recipient = EmailRecipient::findOrFail($id);
        
        $request->validate([
            'email' => 'required|email|unique:email_recipients,email,' . $id,
            'name' => 'nullable|string|max:255'
        ]);

        $recipient->update([
            'email' => $request->email,
            'name' => $request->name
        ]);

        return redirect()->route('email-recipients.index')
                         ->with('success', 'Email recipient updated successfully!');
    }

    public function toggleStatus($id)
    {
        $recipient = EmailRecipient::findOrFail($id);
        $recipient->is_active = !$recipient->is_active;
        $recipient->save();

        $status = $recipient->is_active ? 'activated' : 'deactivated';
        return redirect()->route('email-recipients.index')
                         ->with('success', "Email recipient {$status} successfully!");
    }

    public function destroy($id)
    {
        $recipient = EmailRecipient::findOrFail($id);
        $recipient->delete();

        return redirect()->route('email-recipients.index')
                         ->with('success', 'Email recipient deleted successfully!');
    }
}
