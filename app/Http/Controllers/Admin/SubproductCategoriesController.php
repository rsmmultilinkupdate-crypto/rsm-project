<?php

namespace App\Http\Controllers\Admin;

use App\Pcategory;
use App\Subpcategory;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SubproductCategoriesController extends Controller
{
    /**
     * Enforce middleware.
     */
    public function __construct()
    {
        $this->middleware('role:manage_settings');
        $this->middleware('role:view_all_category', ['only' => ['index','subpcategoriesData']]);
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
        return view('admin/subproductcategories/index');
    }

    /**
     * index categories - Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function subpcategoriesData()
    {
        $categories = Subpcategory::join('users', 'subpcategories.user_id', '=', 'users.id')
                        ->select(['subpcategories.id', 'subpcategories.name AS category_name', 'subpcategories.user_id', 'users.name', 'subpcategories.created_at']);


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
                            <a class="dropdown-item" href="'.route('subproductcategories.show', $model->id).'"><i class="fas fa-eye"></i> View</a>
                            <a class="dropdown-item" href="'.route('subproductcategories.edit', $model->id).'"><i class="fas fa-edit"></i> Edit</a>
                            <a class="dropdown-item text-danger" href="#" onclick="callDeletItem(\''.$model->id.'\', \'subproductcategories\');"><i class="fas fa-trash"></i> Delete</a>
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

            $categories = Subpcategory::where('name', 'LIKE', '%' . $request->term. '%')
                                ->orderBy('name')
                                ->skip($offset)
                                ->take($resultCount)
                                ->selectRaw('id, name as text')
                                ->get();

            $count = Count(Subpcategory::where('name', 'LIKE', '%' . $request->term. '%')
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
        $categories = Pcategory::get();
        return view('admin/subproductcategories/create',['categories'=> $categories]);
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
        ]);

        $imagePath = Storage::putFile('images', $request->file('image'));

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                    ->withErrors($validator)->withInput();
        }

        // Store the item
        $category = new Subpcategory;
        $category->name = $request->name;
        $category->user_id = Auth::user()->id;
        $category->description = $request->description;
        $category->meta_title = $request->meta_title;
        $category->meta_keywords = $request->meta_keywords;
        $category->meta_description = $request->meta_description;
        $category->image = $imagePath;
        $category->pcategories_id = $request->categories;
        $category->is_active = $request->is_active;
        $category->save();

        // Back to index with success
        return redirect()->route('subproductcategories.index')->with('custom_success', 'Sub Category has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Subpcategory::findOrFail($id);
        return view('admin/subproductcategories/show', ['category' => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Subpcategory::findOrFail($id);
        $categories = Pcategory::get();
        return view('admin/subproductcategories/edit', ['category' => $category, 'categories' => $categories]);
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
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                    ->withErrors($validator)->withInput();
        }

       

        // Update the item
        $category = Subpcategory::findOrFail($id);

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
        $category->pcategories_id = $request->categories;
        $category->description = $request->description;
        $category->is_active = $request->is_active;
        $category->save();

        // Back to index with success
        return redirect()->route('subproductcategories.index')->with('custom_success', 'Sub Category has been updated successfully');
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
        $category = Subpcategory::findOrFail($id);

        // Foreign Key Error Protection
        if ($category->products()->count() > 0) {
            return back()->with('custom_errors', 'Sub Category was not deleted. It is already attached with some products.');
        }

        // permanent delete
        $status = $category->delete();

        if ($status) {
            // If success
            return back()->with('custom_success', 'Sub Category has been deleted.');
        } else {
            // If no success
            return back()->with('custom_errors', 'Sub Category was not deleted. Something went wrong.');
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
        $categories = Subpcategory::find($arrId);
        foreach ($categories as $category) {
            if ($category->products()->count() > 0) {
                return back()->with('custom_errors', '<b>'.$category->name. '</b>: It is already attached with some blogs. Categories were not deleted');
            }
        }

        // If no Foreign Key Error
        $status = Subpcategory::destroy($arrId);

        if ($status) {
            // If success
            return back()->with('custom_success', 'Bulk Delete action completed.');
        } else {
            // If no success
            return back()->with('custom_errors', 'Bulk Delete action failed. Something went wrong.');
        }
    }
}
