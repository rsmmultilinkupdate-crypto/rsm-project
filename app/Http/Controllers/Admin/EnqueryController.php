<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Enquery;
use Illuminate\Support\Facades\Storage;
use DB;
use Mail;
use Response;
class EnqueryController extends Controller
{
	    /**
     * Enforce middleware.
     */
   protected $user;

    public function __construct()
    {

		$this->middleware('role:manage_settings');
        $this->middleware('role:view_all_blog', ['only' => ['getenquery']]);
       
    }
	public function index()
    {
		try {
            $enquiries = Enquery::orderBy('id', 'desc')->get();
            
            // Get active email recipients for display
            $activeEmails = [];
            try {
                $activeEmails = \App\EmailRecipient::where('is_active', 1)->pluck('email')->toArray();
            } catch (\Exception $e) {
                \Log::warning('Could not fetch email recipients: ' . $e->getMessage());
            }
            
            if (empty($activeEmails)) {
                $activeEmails = ['rsmmultilinkenquiry@gmail.com', 'kumarshivam827@gmail.com'];
            }
            
		    return view('admin/enquiries/index', ['data' => $enquiries, 'activeEmails' => $activeEmails]);
        } catch (\Exception $e) {
            \Log::error('Enquiry index error: ' . $e->getMessage());
            return view('admin/enquiries/index', ['data' => collect(), 'activeEmails' => ['rsmmultilinkenquiry@gmail.com', 'kumarshivam827@gmail.com']]);
        }
    }

    public function show($id)
    {
        // Handle the logic for displaying a single resource
    }

    public function edit($id)
    {
        // Handle the logic for showing the edit form
    }

    public function update(Request $request, $id)
    {
        // Handle the logic for updating a resource
    }

    public function destroy($id)
    {
        // Handle the logic for deleting a resource
    }

	
}

