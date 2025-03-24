<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // bikin database (migrations) -> model -> controller ->view
        $categories = Category::paginate(10); //utk dibuRKn pagination
        return view('pages.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource., utk menampilkan form tambah
     */
    public function create()
    {
        //
        return view('pages.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required',
            'image' => 'required|image|mimes:png,jpg,jpeg,gif,svg|max:2048',
        ]);

        // simpan request
        $category = new Category;
        $category->name = $request->name;
        $category->description = $request->description;

        // if ($request->hasFile('image')) {
        //     # code... php artisan storage:link
        //     $image = $request->file('image');
        //     $image->storeAs('public/categories', $category->id . '.' . $image->getClientOriginalExtension());
        //     $category->image = 'storage/categories/' . $category->id . '.' . $image->getClientOriginalExtension();
        //     $category->save();
        // }

           //save image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension(); // Nama unik waktu + id
            $image->storeAs('public/categories', $imageName);
            $category->image = 'storage/categories/' . $imageName;
            $category->save();
        }

        return redirect()->route('categories.index')->with('success', 'Category Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        return view('pages.categories.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $category = Category::find($id);

        return view('pages.categories.edit', compact('category'));
    }

    

    /**
     * Update the specified resource in storage.
     */
 
     public function update(Request $request, $id)
     {
         // Validasi input, update produk enggak butuh image lagi, yang diupdate namenya saja
        //  description tetap bisa masuk
         $request->validate([
             'name' => 'required',
            //  'image' => 'nullable|image|mimes:png,jpg,jpeg,gif,svg|max:2048', // Jika gambar bisa kosong
         ]);
     
         // Cek apakah kategori ditemukan
         $category = Category::find($id);
         if (!$category) {
             return redirect()->route('categories.index')->with('error', 'Category not found');
         }
     
         // Update data kategori dari inputan
         $category->name = $request->name;
         $category->description = $request->description;

        if($request->hasFile('image')){        
            $path = public_path().'/storage/categories/';
  
            //code for remove old file
            if($category->image != ''  && $category->image != null){
                 $file_old = $path.$category->image;
                 unlink($file_old);
            }
  
            //upload new file
            $file = $request->file;
            $filename = $file->getClientOriginalName();
            $file->move($path, $filename);
  
            //for update in table
            $category->update(['image' => $filename]);
       }
     
         // Simpan semua perubahan
         $category->update(
            [
                'name' => $request->name,
                'description' => $request->description
            ]
         );
     
         return redirect()->route('categories.index')->with('success', 'Category updated successfully');
     }
     

     //update, ini kode enggak bisa update
    //  public function update(Request $request, $id)
    //  {
    //      //validate the request...
    //      $request->validate([
    //          'name' => 'required',
    //      ]);
 
    //      //update the request...
    //      $category = Category::find($id);
    //      $category->name = $request->name;
    //      $category->description = $request->description;
 
    //      //save image
    //      if ($request->hasFile('image')) {
    //          $image = $request->file('image');
    //          $image->storeAs('public/categories', $category->id . '.' . $image->getClientOriginalExtension());
    //          $category->image = 'storage/categories/' . $category->id . '.' . $image->getClientOriginalExtension();
    //          $category->save();
    //      }
 
    //      return redirect()->route('categories.index')->with('success', 'Category updated successfully');
    //  }
 

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //hapus reqyest
        $category = Category::find($id);
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category Deleted Successfully');
    }
}
