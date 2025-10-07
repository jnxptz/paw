<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::orderByDesc('id')->get();
        return view('admin.products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2',
            'description' => 'required|min:10',
            'price' => 'required|numeric|min:0.01',
            'image' => 'required|string',
            'category' => 'required|string',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image_path' => 'img/' . $request->image,
            'category' => $request->category,
            'stock_quantity' => $request->stock_quantity,
            'is_active' => 1,
        ]);

        return redirect()->route('admin.products')->with('success', 'Product added successfully!');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|min:2',
            'description' => 'required|min:10',
            'price' => 'required|numeric|min:0.01',
            'image' => 'required|string',
            'category' => 'required|string',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image_path' => 'img/' . $request->image,
            'category' => $request->category,
            'stock_quantity' => $request->stock_quantity,
        ]);

        return redirect()->route('admin.products')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Product deleted successfully!');
    }
}
