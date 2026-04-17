<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SitemapController extends Controller
{
	public function __construct()
	{
		$this->middleware('role:manage_settings');
        $this->middleware('role:view_all_blog', ['only' => ['getenquery']]);
	}
    public function upload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'sitemap' => 'required|file|mimes:xml,jpg,jpeg,png,pdf,doc,docx,txt'
        ]);
	    // Store the file in the public root
       
        $path = $request->file('sitemap')->move(public_path(), 'sitemap.xml');
		
        return back()->with('success', 'Sitemap uploaded successfully!');
        
       
    }
}
