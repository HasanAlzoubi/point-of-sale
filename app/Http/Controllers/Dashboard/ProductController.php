<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $categories = Category::all();

        $category_id=$request->category_id;
        $products = Product::when($request->search, function ($q) use ($request) {

            return $q->whereTranslationLike('name', '%' . $request->search . '%');

        })->when($request->category_id, function ($q) use ($request) {

            return $q->where('category_id',$request->category_id);

        })->paginate(5);

        return view('dashboard.products.index', compact('products', 'categories','category_id'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('dashboard.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ar.*' => 'required|unique:product_translations',
            'en.*' => 'required|unique:product_translations',
            'image' => 'image',
            'purchase_price' => 'required|numeric',
            'sell_price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);
        $data = $request->all();
        if ($request->image) {
            Image::make($request->image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/product_images/' . $request->image->hashName()));

            $data['image'] = $request->image->hashName();
        }

        Product::create($data);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.products.index');
    }


    public function show()
    {
        //
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('dashboard.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {

        $request->validate([
            'ar.*' => ['required', Rule::unique('product_translations')->ignore($product->id, 'product_id')],
            'en.*' => ['required', Rule::unique('product_translations')->ignore($product->id, 'product_id')],
            'image' => 'image',
            'purchase_price' => 'required|numeric',
            'sell_price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        $data = $request->all();

        if ($request->image) {

            if ($product->image != 'default.png')
                File::delete(public_path('uploads/product_images/' . $product->image));

            Image::make($request->image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/product_images/' . $request->image->hashName()));

            $data['image'] = $request->image->hashName();
        }

        $product->update($data);

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.products.index');
    }

    public function destroy(Product $product)
    {
        if ($product->image && $product->image != 'default.png') {
            File::delete(public_path('uploads/product_images/' . $product->image));
        }
        $product->delete();

        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.users.index');
    }
}
