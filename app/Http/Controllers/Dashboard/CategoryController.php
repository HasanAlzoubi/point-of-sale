<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:categories_create')->only(['create','store']);
        $this->middleware('permission:categories_read')->only('index');
        $this->middleware('permission:categories_update')->only(['edit', 'update']);
        $this->middleware('permission:categories_delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $categories = Category::when($request->search, function ($q) use ($request) {

            return $q->whereTranslationLike('name', '%' . $request->search . '%');

        })->paginate(5);
        return view('dashboard.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('dashboard.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ar.*' => 'required|unique:category_translations',
            'en.*' => 'required|unique:category_translations',
        ]);

        Category::create($request->all());

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    public function edit(Category $category)
    {
        return view('dashboard.categories.edit',compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'ar.*' => ['required', Rule::unique('category_translations', 'name')->ignore($category->id, 'category_id')],
            'en.*' => ['required', Rule::unique('category_translations', 'name')->ignore($category->id, 'category_id')]
        ]);

        $category->update($request->all());

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.categories.index');
    }


    public function destroy(Category $category)
    {
        $category->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.categories.index');
    }
}
