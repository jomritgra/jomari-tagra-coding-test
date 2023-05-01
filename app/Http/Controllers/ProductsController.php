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

        $products = Products::create($validatedData);

        return response()->json($products);
    }

    public function update(Request $request, $id)
    {
        $products = Products::findOrFail($id);

        $validatedData = $request->validate([
            'product_name' => 'required|max:255',
            'product_description' => 'required',
            'product_price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        $products->update($validatedData);

        Cache::forget('product_' . $products->id);

        return response()->json($products);
    }

    public function delete($id)
    {
        $products = Products::findOrFail($id);
        $products->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
