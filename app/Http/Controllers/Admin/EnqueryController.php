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
		
        $enquiries = Enquery::orderBy('id', 'desc')->get();
		return view('admin/enquiries/index',['data'=>$enquiries]);
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

