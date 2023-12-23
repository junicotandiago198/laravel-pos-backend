<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = DB::table('products')
                ->when($request->input('name'), function ($query, $name) {
                    return $query->where('name', 'like', '%'.$name.'%');
                 })
                 ->orderBy('created_at', 'desc')
                ->paginate(10);
        return view('pages.products.index', compact('products'));
    }

    public function create()
    {
        return view('pages.products.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        Product::create($data);
        return redirect()->route('product.index')->with('success', 'Product successfully created');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('pages.products.edit', compact('product'));
    }

    public function update(Product $product)
    {
        // Pastikan $product adalah instance dari model Eloquent Product
        if (!($product instanceof \App\Models\Product)) {
            return redirect()->route('product.index')->with('error', 'Invalid product instance');
        }
    
        $data = request()->all();
        $product->update($data);
    
        return redirect()->route('product.index')->with('success', 'Product successfully updated');
    }
}
