<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Pcategory;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductCategoriesController extends Controller
{
    /**
     * Enforce middleware.
     */
    public function __construct()
    {
        $this->middleware('role:manage_settings');
        $this->middleware('role:view_all_category', ['only' => ['index','pcategoriesData']]);
        $this->middleware('role:view_category', ['only' => ['show']]);

        $this->middleware('role:add_category', ['only' => ['create', 'store']]);

        $this->middleware('role:edit_category', ['only' => ['update', 'edit']]);

        $this->middleware('role:delet_category', ['only' => ['destroy', 'bulkDelete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin/productcategories/index');
    }

    /**
     * index categories - Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pcategoriesData()
    {
        $categories = Pcategory::join('users', 'pcategories.user_id', '=', 'users.id')
                        ->select(['pcategories.id', 'pcategories.name AS category_name', 'pcategories.user_id', 'users.name', 'pcategories.created_at']);


        return Datatables::of($categories)
                ->editColumn('created_at', function ($model) {
                    return "<abbr title='".$model->created_at->format('F d, Y @ h:i A')."'>".$model->created_at->format('F d, Y')."</abbr>";
                })
                ->editColumn('users.name', function ($model) {
                    return '<a href="'.route('users.show', $model->user_id).'" class="link">'.$model->name.' <i class="fas fa-external-link-alt"></i></a>';
                })
                ->addColumn('bulkAction', '<input type="checkbox" name="selected_ids[]" id="bulk_ids" value="{{ $id }}">')
                ->addColumn('actions', function ($model) {
                    return '
                     <div class="dropdown float-right">
                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-cog"></i> Action
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="'.route('productcategories.show', $model->id).'"><i class="fas fa-eye"></i> View</a>
                            <a class="dropdown-item" href="'.route('productcategories.edit', $model->id).'"><i class="fas fa-edit"></i> Edit</a>
                            <a class="dropdown-item text-danger" href="#" onclick="callDeletItem(\''.$model->id.'\', \'productcategories\');"><i class="fas fa-trash"></i> Delete</a>
                        </div>
                    </div>';
                })
                ->rawColumns(['actions','users.name','bulkAction','created_at'])
                ->make(true);
    }

    /**
     *  Select2 categories - Process select2 ajax request.
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function categoriesAjaxSelectData(Request $request)
    {
        if ($request->ajax()) {
            $page = $request->page;
            $resultCount = 10;

            $offset = ($page - 1) * $resultCount;

            $categories = Pcategory::where('name', 'LIKE', '%' . $request->term. '%')
                                ->orderBy('name')
                                ->skip($offset)
                                ->take($resultCount)
                                ->selectRaw('id, name as text')
                                ->get();

            $count = Count(Pcategory::where('name', 'LIKE', '%' . $request->term. '%')
                                ->orderBy('name')
                                ->selectRaw('id, name as text')
                                ->get());

            $endCount = $offset + $resultCount;
            $morePages = $count > $endCount;

            $results = array(
              "results" => $categories,
              "pagination" => array(
                  "more" => $morePages
                  )
              );
            return response()->json($results);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/productcategories/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validations
        $validatedData = $request->validate([
            'name' => 'required|unique:categories|max:150',
            'image' => 'required',
            'type' => 'required',
            'display_order' => 'required',
        ]);

        $imagePath = Storage::putFile('images', $request->file('image'));

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                    ->withErrors($validator)->withInput();
        }

        // Store the item
        $category = new Pcategory;
        $category->name = $request->name;
        $category->user_id = Auth::user()->id;
        $category->description = $request->description;
        $category->meta_title = $request->meta_title;
        $category->meta_keywords = $request->meta_keywords;
        $category->meta_description = $request->meta_description;
        $category->image = $imagePath;
        $category->type = $request->type;
        $category->is_active = $request->is_active;
        $category->display_order = $request->display_order;
        $category->save();

        // Back to index with success
        return redirect()->route('productcategories.index')->with('custom_success', 'Product Category has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Pcategory::findOrFail($id);
        return view('admin/productcategories/show', ['category' => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Pcategory::findOrFail($id);
        return view('admin/productcategories/edit', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validations
        $validatedData = $request->validate([
            'name' => 'required|max:150',
            'type' => 'required|max:150',
            'display_order' => 'required|max:150',
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                    ->withErrors($validator)->withInput();
        }

       

        // Update the item
        $category = Pcategory::findOrFail($id);

         if ($request->hasFile('image')) {
            $imagePath = Storage::putFile('images', $request->file('image'));
            // Delet Old Image
            Storage::delete($category->image);
        } else {
            $imagePath = $category->image;
        }

        $category->name = $request->name;
        $category->image = $imagePath;
        $category->meta_title = $request->meta_title;
        $category->meta_keywords = $request->meta_keywords;
        $category->meta_description = $request->meta_description;
        $category->type = $request->type;
        $category->description = $request->description;
        $category->is_active = $request->is_active;
        $category->display_order = $request->display_order;
        $category->save();

        // Back to index with success
        return redirect()->route('productcategories.index')->with('custom_success', 'Product Category has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the category by $id
        $category = Pcategory::findOrFail($id);


        //echo $id;
        //die;
        // Foreign Key Error Protection
        if ($category->products()->count() > 0) {
            return back()->with('custom_errors', 'Product Category was not deleted. It is already attached with some products.');
        }
        DB::table('pcategory_product')->where('pcategory_id', '=', $id)->delete();
        // permanent delete
        $status = $category->delete();
        //echo $status; die;
        if ($status) {
            // If success
            return back()->with('custom_success', 'Product Category has been deleted.');
        } else {
            // If no success
            return back()->with('custom_errors', 'Product Category was not deleted. Something went wrong.');
        }
    }

    /**
     * Bulk delete items in the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bulkDelete(Request $request)
    {
        $arrId = explode(",", $request->ids);

        // Foreign Key Error Protection
        $categories = Pcategory::find($arrId);
        foreach ($categories as $category) {
            if ($category->products()->count() > 0) {
                return back()->with('custom_errors', '<b>'.$category->name. '</b>: It is already attached with some blogs. Categories were not deleted');
            }
        }

        // If no Foreign Key Error
        $status = Pcategory::destroy($arrId);

        if ($status) {
            // If success
            return back()->with('custom_success', 'Bulk Delete action completed.');
        } else {
            // If no success
            return back()->with('custom_errors', 'Bulk Delete action failed. Something went wrong.');
        }
    }
}
