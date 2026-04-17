<?php

namespace App\Http\Controllers\Admin;

use App\Product;
use App\Blog;
use App\MultiImages;
use App\User;
use DB;
use App\Category;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreBlogPost;
use App\Http\Requests\UpdateBlogPost;
use App\Http\Controllers\Controller;
use App\Subpcategory;
use App\Pcategory;
use Illuminate\Support\Facades\Storage;
use Image;

class ProductsController extends Controller
{
    /**
     * Enforce middleware.
     */
    public function __construct()
    {
        $this->middleware('role:manage_settings');
        $this->middleware('role:view_all_blog', ['only' => ['index', 'productsData', 'trashed', 'productsAjaxTrashedData']]);
        $this->middleware('role:view_blog', ['only' => ['show']]);

        $this->middleware('role:add_blog', ['only' => ['create', 'store']]);

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
        $trashed_items = Product::onlyTrashed()->count();
        return view('admin/products/index', ['trashed_items_count' => $trashed_items]);
    }

    /**
     * index blogs - Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function productsData()
    {
        $products = Product::join('users', 'products.user_id', '=', 'users.id')
            ->select(['products.id', 'products.title', 'products.user_id', 'products.is_active', 'users.name', 'products.created_at']);

        return Datatables::of($products)
            ->editColumn('created_at', function ($model) {
                return "<abbr title='" . $model->created_at->format('F d, Y @ h:i A') . "'>" . $model->created_at->format('F d, Y') . "</abbr>";
            })
            ->editColumn('is_active', function ($model) {
                if ($model->is_active == 0) {
                    return '<div class="text-danger">No <span class="badge badge-light"><i class="fas fa-times"></i></span></div>';
                } else {
                    return '<div class="text-success">Yes <span class="badge badge-light"><i class="fas fa-check"></i></span></div>';
                }
            })
            ->editColumn('users.name', function ($model) {
                return '<a href="' . route('users.show', $model->user_id) . '" class="link">' . $model->name . ' <i class="fas fa-external-link-alt"></i></a>';
            })
            ->addColumn('bulkAction', '<input type="checkbox" name="selected_ids[]" id="bulk_ids" value="{{ $id }}">')
            ->addColumn('actions', function ($model) {
                if ($model->is_active == 0) {
                    $publish_action = '<a class="dropdown-item" href="' . route('products.publishStatus', $model->id) . '" onclick="return confirm(\'Are you sure?\')"><i class="fas fa-check"></i> Publish</a>';
                } else {
                    $publish_action = '<a class="dropdown-item" href="' . route('products.publishStatus', $model->id) . '" onclick="return confirm(\'Are you sure?\')"><i class="fas fa-times"></i> Unpublish</a>';
                }
                return '
                     <div class="dropdown float-right">
                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-cog"></i> Action
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="' . route('products.show', $model->id) . '"><i class="fas fa-eye"></i> View</a>
                            <a class="dropdown-item" href="' . route('products.edit', $model->id) . '"><i class="fas fa-edit"></i> Edit</a>
                            ' . $publish_action . '
                            <a class="dropdown-item text-danger" href="#" onclick="callDeletItem(\'' . $model->id . '\', \'products\');"><i class="fas fa-trash"></i> Trash</a>
                        </div>
                    </div>';
            })
            ->rawColumns(['actions', 'users.name', 'is_active', 'bulkAction', 'created_at'])
            ->make(true);
    }



    /**
     * Display a listing of the trashed resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function trashed()
    {
        $trashed_items = Product::onlyTrashed()->count();
        return view('admin/products/trashed-index', ['trashed_items_count' => $trashed_items]);
    }

    /**
     * trashed blogs - Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function productsAjaxTrashedData()
    {
        $blogs = Product::join('users', 'products.user_id', '=', 'users.id')
            ->select(['products.id', 'products.title', 'products.user_id', 'users.name', 'products.deleted_at'])
            ->onlyTrashed();

        return Datatables::of($blogs)
            ->editColumn('trashed_at', function ($model) {
                return $model->deleted_at->format('F d, Y h:i A');
            })
            ->editColumn('users.name', function ($model) {
                return '<a href="' . route('users.show', $model->user_id) . '" class="link">' . $model->name . ' <i class="fas fa-external-link-alt"></i></a>';
            })
            ->addColumn('bulkAction', '<input type="checkbox" name="selected_ids[]" id="bulk_ids" value="{{ $id }}">')
            ->addColumn('actions', function ($model) {
                return '
                     <div class="dropdown float-right">
                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-cog"></i> Action
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="' . route('products.restore', $model->id) . '" onclick="return confirm(\'Are you sure?\')"><i class="fas fa-history"></i> Restore</a>
                            <a class="dropdown-item text-danger" href="' . route('products.permanentDelet', $model->id) . '" onclick="return confirm(\'Are you sure?\')"><i class="fas fa-trash"></i> Permanent Delet</a>
                        </div>
                    </div>';
            })
            ->rawColumns(['actions', 'users.name', 'bulkAction'])
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

        return view('admin/products/create', ['authors' => $authors]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBlogPost $request)
    {
        
        
        // Pre Validations are done in StoreBlogPost Request
        // Store the item
        if ($request->hasFile('image')) {
            // Store File & Get Path
            $path   = $request->file('image');
            
            $imagess = Image::make($path)->fit(450);
            $mime = $path->getClientOriginalExtension();
            // $originalName = $path->getClientOriginalName();
           
            // $name = explode(".",$originalName);
            // $OnlyName = $name[0];
            
            $imageName = time() . "." . $mime;
            

            $imagePath = Storage::putFile('images', $request->file('image'));
           
            $getImageName = explode("/",$imagePath);
            $nameWithExtension = $getImageName[1];
            $getOnlyName = explode(".",$nameWithExtension);
          
            Storage::disk('public')->put("thumbnail/" . $imageName, (string) $imagess->encode());
            // $OriginalImage = Image::make($imagePath);
           // Save the image in WebP format with the same name
        //    $test =  $OriginalImage->encode('webp')->save($getOnlyName[0] . '.webp');
        } else {
            $imagePath = "";
            $imageName = "";
        }
        // Store & Get Categories
        $categoryArr = array();
        foreach ($request->categories as $category) {
            if (is_numeric($category)) {
                // Store in array
                $categoryArr[] = $category;
            } else {
                // if the item not numeric that means that its new item and we should create it
                // User Id will automatically set by mutator in Category model
                $newCategory = Pcategory::create(['name' => $category, 'user_id' => Auth::user()->id]);
                // include the new item to array
                $categoryArr[] = $newCategory->id;
            }
        }
        if (isset($request->subcategories)) {

            // Store & Get Categories
            $subcategoryArr = array();
            foreach ($request->subcategories as $category) {
                if (is_numeric($category)) {
                    // Store in array
                    $subcategoryArr[] = $category;
                } else {
                    // if the item not numeric that means that its new item and we should create it
                    // User Id will automatically set by mutator in Category model
                    $newCategory = Subpcategory::create(['name' => $category, 'user_id' => Auth::user()->id]);
                    // include the new item to array
                    $subcategoryArr[] = $newCategory->id;
                }
            }
        }

        // Step 1 - Init item Obj
        $blog = new Product;

        // Set item fields
        $blog->alt_img = $nameWithExtension;
        $blog->title = $request->title;
        $blog->excerpt = $request->excerpt;
        $blog->description = $request->description;
        $blog->image = $imagePath;
        $blog->user_id = $request->user_id;
        $blog->is_active = $request->is_active;
        $blog->allow_comments = $request->allow_comments;
        $blog->thumbnail = 'thumbnail/' . $imageName;
        $blog->meta_title = $request->meta_title;
        $blog->meta_keywords = $request->meta_keywords;
        $blog->meta_description = $request->meta_description;
        $blog->new_launches = $request->new_launches;
        $blog->hot_offers = $request->hot_offers;
        $blog->tags = $request->tags;
        // Step 2 - Save Item
        $blog->save();


        // Store File & Get Path
        if ($request->hasFile('multipleimage')) {

            $path1   = $request->file('multipleimage');
            foreach ($request->file('multipleimage') as $img) {

                # code...
                $imagess1 = Image::make($img)->fit(450);
                $mime = $img->getClientOriginalExtension();
                $imageName = time() . "." . $mime;

                $imagePath1 = Storage::putFile('images', $img);
                Storage::disk('public')->put("thumbnail/" . $imageName, (string) $imagess1->encode());

                // Step 1 - Init item Obj
                $multi = new MultiImages;

                // Set item fields
                $multi->product_id = $blog->id;
                $multi->image = $imagePath1;
                $multi->thumbnail = 'thumbnail/' . $imageName;
                // Step 2 - Save Item
                $multi->save();
            }
        }

        // Step 3 - Attach/Sync Related Items
        $blog->pcategories()->sync($categoryArr);
        if (isset($request->subcategories)) {
            $blog->subpcategories()->sync($subcategoryArr);
        } else {
            DB::table('product_subpcategory')->where('product_id', '=', $blog->id)->delete();
        }
        // Back to index with success
        return redirect()->route('products.index')->with('custom_success', 'Product has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $blog = Product::findOrFail($id);
        return view('admin/products/show', ['blog' => $blog]);
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
        $blog = Product::findOrFail($id);

        $multi_images = DB::table('multi_images')->where('product_id', '=', $id)->get();
        // Authors List
        $authors = User::active()->whereHas('roles', function ($query) {
            $query->where('role', '=', 'add_blog');
        })->get();

        return view('admin/products/edit', ['blog' => $blog, 'authors' => $authors, 'multi_images' => $multi_images]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletemulti($id)
    {


        $multi_images = DB::table('multi_images')->where('id', '=', $id)->delete();
        return back()->with('custom_success', 'Product images been delete successfully');
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBlogPost $request, $id)
    {

        
        // Store File & Get Path
        if ($request->hasFile('multipleimage')) {

            $path1   = $request->file('multipleimage');
            foreach ($request->file('multipleimage') as $img) {

                # code...
                $imagess1 = Image::make($img)->fit(450);
                $mime = $img->getClientOriginalExtension();
                $imageName = time() . "." . $mime;

                $imagePath1 = Storage::putFile('images', $img);
                Storage::disk('public')->put("thumbnail/" . $imageName, (string) $imagess1->encode());

                $getImageName = explode("/",$imagePath1);
                $nameWithExtension = $getImageName[1];
                $getOnlyName = explode(".",$nameWithExtension);

                // Step 1 - Init item Obj
                $multi = new MultiImages;

                // Set item fields
                $multi->product_id = $id;
                $multi->image = $imagePath1;
                $multi->thumbnail = 'thumbnail/' . $imageName;
                // Step 2 - Save Item
                $multi->save();
            }
        }

        // Pre Validations are done in UpdateBlogPost Request
        // Update the item

        // Get the item to update
        $blog = Product::findOrFail($id);

        // Store File & Get Path
        if ($request->hasFile('image')) {

            $path   = $request->file('image');
            $imagess = Image::make($path)->fit(450);
            $mime = $path->getClientOriginalExtension();
            $imageName = time() . "." . $mime;

            $imagePath = Storage::putFile('images', $request->file('image'));
            Storage::disk('public')->put("thumbnail/" . $imageName, (string) $imagess->encode());
            $imagethumb = 'thumbnail/' . $imageName;
            // Delet Old Image

            // echo $imagePath;
            // die;

            Storage::delete($blog->image);
        } else {
            $imagePath = $blog->image;
            $imagethumb = '';
        }



        // Store & Get Categories
        $categoryArr = array();
        foreach ($request->categories as $category) {
            if (is_numeric($category)) {
                // Store in array
                $categoryArr[] = $category;
            } else {
                // if the item not numeric that means that its new item and we should create it
                // User Id will automatically set by mutator in Category model
                $newCategory = Pcategory::create(['name' => $category, 'user_id' => Auth::user()->id]);
                // include the new item to array
                $categoryArr[] = $newCategory->id;
            }
        }

        if (isset($request->subcategories)) {
            // Store & Get Categories
            $subcategoryArr = array();
            foreach ($request->subcategories as $category) {
                if (is_numeric($category)) {
                    // Store in array
                    $subcategoryArr[] = $category;
                } else {
                    // if the item not numeric that means that its new item and we should create it
                    // User Id will automatically set by mutator in Category model
                    $newCategory = Subpcategory::create(['name' => $category, 'user_id' => Auth::user()->id]);
                    // include the new item to array
                    $subcategoryArr[] = $newCategory->id;
                }
            }
        }


        // Step 1 - Set item fields
        $blog->alt_img = $nameWithExtension??'--';
        $blog->title = $request->title;
        $blog->excerpt = $request->excerpt;
        $blog->description = $request->description;
        $blog->image = $imagePath;
        $blog->user_id = $request->user_id;
        $blog->is_active = $request->is_active;
        $blog->allow_comments = $request->allow_comments;
        $blog->thumbnail = $imagethumb;
        $blog->meta_title = $request->meta_title;
        $blog->meta_keywords = $request->meta_keywords;
        $blog->meta_description = $request->meta_description;
        $blog->new_launches = $request->new_launches;
        $blog->hot_offers = $request->hot_offers;
        $blog->tags = $request->tags;
        // Step 2 - Save Item
        $blog->save();

        // Step 3 - Attach/Sync Related Items
        $blog->pcategories()->sync($categoryArr);

        if (isset($request->subcategories)) {
            $blog->subpcategories()->sync($subcategoryArr);
        } else {
            DB::table('product_subpcategory')->where('product_id', '=', $id)->delete();
        }
        // Back to index with success
        return back()->with('custom_success', 'Product has been updated successfully');
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
        $blog = Product::findOrFail($id);

        if ($blog->is_active == 0) {
            $blog->is_active = 1;
        } else {
            $blog->is_active = 0;
        }
        $status = $blog->save();

        if ($status) {
            // If success
            return back()->with('custom_success', 'Product publish status updated.');
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
        $blog = Product::findOrFail($id);

        // Soft Delet the blog and transfer to Trash Items
        $blog->delete();

        if ($blog->trashed()) {
            // If success
            return back()->with('custom_success', 'Product has been deleted and transfered to trash items.');
        } else {
            // If no success
            return back()->with('custom_errors', 'Product was not deleted. Something went wrong.');
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
        $status = Blog::destroy($arrId);

        if ($status) {
            // If success
            return back()->with('custom_success', 'Bulk Trash action completed.');
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
        $blog = Product::onlyTrashed()->findOrFail($id);

        // Restore the blog
        $blog->restore();

        if (!$blog->trashed()) {
            // If success
            return back()->with('custom_success', 'Product has been restored.');
        } else {
            // If no success
            return back()->with('custom_errors', 'Product was not able to restore. Something went wrong.');
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
        $status = Product::onlyTrashed()->restore($arrId);

        if ($status) {
            // If success
            return back()->with('custom_success', 'Product Restore action completed.');
        } else {
            // If no success
            return back()->with('custom_errors', 'Product Restore action failed. Something went wrong.');
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
        $blog = Product::onlyTrashed()->findOrFail($id);

        // Delete Related Items First
        $blog->pcategories()->detach();
        $blog->subpcategories()->detach();
        //$blog->comments()->delete();
        // Delete Image
        Storage::delete($blog->image);

        // Permanent Delet the blog
        $status = $blog->forceDelete();

        if ($status) {
            // If success
            return back()->with('custom_success', 'Product has been deleted permanently.');
        } else {
            // If no success
            return back()->with('custom_errors', 'Product was not able to deleted permanently. Something went wrong.');
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
        $blogs = Product::onlyTrashed()->get();

        foreach ($blogs as $blog) {
            // Delete Related Items First
            $blog->pcategories()->detach();
            //$blog->comments()->delete();
            // Delete Image
            Storage::delete($blog->image);
            // Delete Blog
            $blog->forceDelete();
        }

        return back()->with('custom_success', 'Trash has been emptied.');
    }
}
