<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get all products
        $products = Product::all();

        //memuat model fungsi category yang ada di model product
        $products->load('category');

        //tampilkan response json
        return response()->json([
            'status' => 'success',
            'data' => $products
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|min:3',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'category_id' => 'required',
            'is_favorite' => 'required',
            'image' => 'required|image|mimes:png,jpg,jpeg'
        ]);

        $filename = time() . '.' . $request->image->extension();

        $product = Product::create([
            'name' => $request->name, //request dari body
            'price' => (int) $request->price,
            'stock' => (int) $request->stock, 
            'category_id' => $request->category_id,
            'is_favorite' => $request->is_favorite,
            'image' => $filename
        ]);

        if ($request->hasFile('image')) {
            # code...
            $image = $request->file('image');
            $image->storeAs('public/products', $product->id . '.' . $image->getClientOriginalExtension());
            $product->image = 'storage/products/' . $product->id . '.' . $image->getClientOriginalExtension();
            $product->save();
        }

        if ($product) {
            # code... kalau berhasil add produk
            return response()->json([
                'success' => true,
                'message' => 'Product Created',
                'data' => $product
            ], 201);
        } else {
            # code...
            return response()->json([
                'success' => false,
                'message' => 'Product failed to save'
            ], 401);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //nullable boleh kosong
        $request->validate([
            'id' => 'required', //dijadikan sebagai parameter update produk maka required
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'category_id' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        //kalau enggka ada datanya langsung fail, id produk diambil dari request / 
        // dari param $id
        // $product = Product::findOrFail($id); 
        $product = Product::findOrFail($request->id); 
        $product->name = $request->name;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->stock = $request->stock;

        if ($request->hasFile('image')) {
            # code... hapus file yg sudah ada dulu
            Storage::delete('public/products/' . $product->image);
            $filename = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/products', $filename);
            $product->image = $filename;
        }

        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Product Updated',
            'data' => $product
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $product = Product::findOrFail($id);
        Storage::delete('public/products' . $product->image);
        $product->delete();

        //menampilkan json
        return response()->json([
            'success' => true,
            'message' => 'Product deleted'
        ], 200);
    }
}
