<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Products::paginate(5);

        return response()->json($products);
    }

    public function show($id)
    {
        $products = Products::findOrFail($id);
        $cacheKey = 'product_' . $products->id;
        $cacheDuration = 60;

        return Cache::remember($cacheKey, $cacheDuration, function () use ($products) {
            return response()->json($products);
        });
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_name' => 'required|max:255',
            'product_description' => 'required',
            'product_price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        $product = Products::create($validatedData);

        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = Products::findOrFail($id);

        $validatedData = $request->validate([
            'product_name' => 'required|max:255',
            'product_description' => 'required',
            'product_price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        $product->update($validatedData);

        return response()->json($product);
    }

    public function delete($id)
    {
        $product = Products::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
