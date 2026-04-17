<?php

namespace App\Http\Controllers\Admin;
use App\Testimonial;
use App\Blog;
use App\User;
use App\Category;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TestimonialPost;
use App\Http\Requests\UpdateTestimonial;
use App\Http\Requests\UpdateBlogPost;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    /**
     * Enforce middleware.
     */
    public function __construct()
    {
        $this->middleware('role:manage_settings');
        $this->middleware('role:view_all_blog', ['only' => ['index', 'testimonialsData']]);
        $this->middleware('role:view_blog', ['only' => ['show']]);

        $this->middleware('role:add_blog', ['only' => ['create','store']]);

        $this->middleware('role:edit_blog', ['only' => ['update', 'edit', 'updateActiveStatus']]);

        $this->middleware('role:delet_blog', ['only' => ['destroy', 'restore', 'permanentDelet', 'emptyTrash']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trashed_items = Testimonial::onlyTrashed()->count();
        return view('admin/testimonial/index', ['trashed_items_count' => $trashed_items]);
    }

    /**
     * index blogs - Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testimonialsData()
    {
        $blogs = Testimonial::join('users', 'testimonials.user_id', '=', 'users.id')
                        ->select(['testimonials.id', 'testimonials.title', 'testimonials.user_id', 'testimonials.is_active', 'users.name', 'testimonials.created_at']);

        return Datatables::of($blogs)
                ->editColumn('created_at', function ($model) {
                    return "<abbr title='".$model->created_at->format('F d, Y @ h:i A')."'>".$model->created_at->format('F d, Y')."</abbr>";
                })
                ->editColumn('is_active', function ($model) {
                    if ($model->is_active == 0) {
                        return '<div class="text-danger">No <span class="badge badge-light"><i class="fas fa-times"></i></span></div>';
                    } else {
                        return '<div class="text-success">Yes <span class="badge badge-light"><i class="fas fa-check"></i></span></div>';
                    }
                })
                ->editColumn('users.name', function ($model) {
                    return '<a href="'.route('users.show', $model->user_id).'" class="link">'.$model->name.' <i class="fas fa-external-link-alt"></i></a>';
                })
                ->addColumn('bulkAction', '<input type="checkbox" name="selected_ids[]" id="bulk_ids" value="{{ $id }}">')
                ->addColumn('actions', function ($model) {
                    if ($model->is_active == 0) {
                        $publish_action = '<a class="dropdown-item" href="'.route('blogs.publishStatus', $model->id).'" onclick="return confirm(\'Are you sure?\')"><i class="fas fa-check"></i> Publish</a>';
                    } else {
                        $publish_action = '<a class="dropdown-item" href="'.route('blogs.publishStatus', $model->id).'" onclick="return confirm(\'Are you sure?\')"><i class="fas fa-times"></i> Unpublish</a>';
                    }
                    return '
                     <div class="dropdown float-right">
                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-cog"></i> Action
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="'.route('testimonial.show', $model->id).'"><i class="fas fa-eye"></i> View</a>
                            <a class="dropdown-item" href="'.route('testimonial.edit', $model->id).'"><i class="fas fa-edit"></i> Edit</a>
                            '.$publish_action.'
                            <a class="dropdown-item text-danger" href="#" onclick="callDeletItem(\''.$model->id.'\', \'testimonial\');"><i class="fas fa-trash"></i> Trash</a>
                        </div>
                    </div>';
                })
                ->rawColumns(['actions','users.name','is_active','bulkAction','created_at'])
                ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $authors = User::active()->whereHas('roles', function ($query) {
            $query->where('role', '=', 'add_blog');
        })->get();

        return view('admin/testimonial/create', ['authors' => $authors]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TestimonialPost $request)
    {
        // Pre Validations are done in StoreBlogPost Request
        // Store the item

        // Store File & Get Path
        $imagePath = Storage::putFile('images', $request->file('image'));


        // Step 1 - Init item Obj
        $blog = new Testimonial;

        // Set item fields
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->image = $imagePath;
        $blog->user_id = $request->user_id;
        $blog->is_active = $request->is_active;

        // Step 2 - Save Item
        $blog->save();

        // Back to index with success
        return redirect()->route('testimonial.index')->with('custom_success', 'Testimonial has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin/blogs/show', ['blog' => $blog]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Blog Details
        $blog = Testimonial::findOrFail($id);
        // Authors List
        $authors = User::active()->whereHas('roles', function ($query) {
            $query->where('role', '=', 'add_blog');
        })->get();

        return view('admin/testimonial/edit', ['blog' => $blog, 'authors' => $authors]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTestimonial $request, $id)
    {
        // Pre Validations are done in UpdateBlogPost Request
        // Update the item

        // Get the item to update
        $blog = Testimonial::findOrFail($id);

        // Store File & Get Path
        if ($request->hasFile('image')) {
            $imagePath = Storage::putFile('images', $request->file('image'));
            // Delet Old Image
            Storage::delete($blog->image);
        } else {
            $imagePath = $blog->image;
        }


        // Step 1 - Set item fields
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->image = $imagePath;
        $blog->user_id = $request->user_id;
        $blog->is_active = $request->is_active;

        // Step 2 - Save Item
        $blog->save();

        // Back to index with success
        return back()->with('custom_success', 'Testimonial has been updated successfully');
    }

    /**
     * Update the is active status of specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateActiveStatus($id)
    {
        // get all trashed blogs and permanent Delet the blogs
        $blog = Testimonial::findOrFail($id);

        if ($blog->is_active == 0) {
            $blog->is_active = 1;
        } else {
            $blog->is_active = 0;
        }
        $status = $blog->save();

        if ($status) {
            // If success
            return back()->with('custom_success', 'Testimonial publish status updated.');
        } else {
            // If no success
            return back()->with('custom_errors', 'Failed to change publish status. Something went wrong.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the blog by $id
        $blog = Testimonial::findOrFail($id);

        // Soft Delet the blog and transfer to Trash Items
        $blog->delete();

        if ($blog->trashed()) {
            // If success
            return back()->with('custom_success', 'Testimonial has been deleted and transfered to trash items.');
        } else {
            // If no success
            return back()->with('custom_errors', 'Testimonial was not deleted. Something went wrong.');
        }
    }

    /**
     * Bulk trash items in the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bulkTrash(Request $request)
    {
        $arrId = explode(",", $request->ids);
        $status = Testimonial::destroy($arrId);

        if ($status) {
            // If success
            return back()->with('custom_success', 'Testimonial Trash action completed.');
        } else {
            // If no success
            return back()->with('custom_errors', 'Bulk Trash action failed. Something went wrong.');
        }
    }

    /**
     * Restore the specified resource from trashed storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        // Find the blog by $id
        $blog = Testimonial::onlyTrashed()->findOrFail($id);

        // Restore the blog
        $blog->restore();

        if (!$blog->trashed()) {
            // If success
            return back()->with('custom_success', 'Testimonial has been restored.');
        } else {
            // If no success
            return back()->with('custom_errors', 'Testimonial was not able to restore. Something went wrong.');
        }
    }

    /**
     * Bulk Restore items in the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bulkRestore(Request $request)
    {
        $arrId = explode(",", $request->ids);
        $status = Testimonial::onlyTrashed()->restore($arrId);

        if ($status) {
            // If success
            return back()->with('custom_success', 'Bulk Restore action completed.');
        } else {
            // If no success
            return back()->with('custom_errors', 'Bulk Restore action failed. Something went wrong.');
        }
    }

    /**
     * Permanent Delet the specified resource from trashed storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function permanentDelet($id)
    {
        // Find the blog by $id
        $blog = Testimonial::onlyTrashed()->findOrFail($id);


        // Delete Image
        Storage::delete($blog->image);

        // Permanent Delet the blog
        $status = $blog->forceDelete();

        if ($status) {
            // If success
            return back()->with('custom_success', 'Testimonial has been deleted permanently.');
        } else {
            // If no success
            return back()->with('custom_errors', 'Testimonial was not able to deleted permanently. Something went wrong.');
        }
    }

    /**
     * permanent delet all trashed items in the specified resource from trashed storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function emptyTrash()
    {
        // get all trashed blogs and permanent Delet the blogs
        $blogs = Testimonial::onlyTrashed()->get();

        foreach ($blogs as $blog) {

            // Delete Image
            Storage::delete($blog->image);
            // Delete Blog
            $blog->forceDelete();
        }

        return back()->with('custom_success', 'Trash has been emptied.');
    }
}
